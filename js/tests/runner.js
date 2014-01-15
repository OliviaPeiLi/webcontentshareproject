/*
 * Qt+WebKit powered headless test runner using Phantomjs
 *
 * Phantomjs installation: http://code.google.com/p/phantomjs/wiki/BuildInstructions
 *
 * Run with:
 *  phantomjs runner.js [url-of-your-qunit-testsuite]
 *
 * E.g.
 *      phantomjs runner.js http://localhost/qunit/test
 */

/*jshint latedef:false */
/*global phantom:true require:true console:true */
var url = phantom.args[0],
	page = require('webpage').create();

// Route "console.log()" calls from within the Page context to the main Phantom context (i.e. current "this")
page.onConsoleMessage = function(msg) {
	console.log(msg);
};

page.onInitialized = function() {
	var host = page.url.split('?')[0];
	if (host.indexOf('fantoon.com') > -1 || host.indexOf('fandrop.com') > -1 || host.indexOf('ft') > -1 || host.indexOf('fantoon.local') > -1) {
		console.info('Inner Url', host)
		page.evaluate(function(_addLogging) {
			window.document.addEventListener( "DOMContentLoaded", function() {
				_addLogging();
			}, false );
		}, _addLogging);
	} else {
		var parsed_url = page.url.split('?')[1].split('&');
		var params = {};
		for (var i in parsed_url) {
			params[parsed_url[i].split('=')[0]] = parsed_url[i].split('=')[1];
		}
		if (params.bookmarklet) {
			page.evaluate(function(_bookmarklet_base) {
				document.addEventListener('DOMContentLoaded', function() {
					console.info('DOMContentLoaded');
					window.callPhantom('DOMContentLoaded', [{'bookmarklet': _bookmarklet_base}]);
					window.onload = function() {
						console.info('window loaded');
						window.callPhantom('window_loaded', [{'bookmarklet': _bookmarklet_base}]);					  
					};
				}, false);
			}, params.bookmarklet);
		}
	}
	
};

var callbackExports = {
	uploadFile: function ( selector, file ) {
		file = file || 'js/tests/testimg.png';
		console.info('>>>>> Uploading file', selector, file);
		page.uploadFile ( selector, file );
	},
	DOMContentLoaded: function (params) {
		if (params.bookmarklet) {
			console.info('injecting tests');
			page.includeJs(params.bookmarklet+'/js/tests/qunit.js', function() {
				//page.includeJs(params.bookmarklet+'/js/tests/quint.utils.js', function() {
					page.includeJs(params.bookmarklet+'/js/modules/tests/bookmarklet.js', function() {
						console.info('tests injected');
						page.evaluate(_addLogging);
					})
				//})
			});
		}
	},
	window_loaded: function(params) {
		if (params.bookmarklet) {
			console.info('injecting bookmarklet');
			page.includeJs(params.bookmarklet+'/load_web_scraper/bookmarklet.js', function() {
				console.info('Bookmarklet injected');
			});
		}
	}
};

page.onCallback = function ( data, params ) {
	if (typeof data == 'string') {
		return callbackExports[data].apply( this, params );
	} else if ( data.fn == 'callExport' ) {
		var fn = callbackExports[data.params[0]];
		if ( fn instanceof Function ) {
			return fn.apply( this, data.params.slice( 1 ) );
		}
	}
};

page.onPageCreated = function(newPage) {
    console.log('>>>Popup window opened<<<');
    
    newPage.onConsoleMessage = function(msg) {
    	console.log('[popup window]', msg);
    };
    /*
    page.onNavigationRequested = function(url, type, willNavigate, main) {
        console.log('Trying to navigate to: ' + url);
        console.log('Caused by: ' + type);
        console.log('Will actually navigate: ' + willNavigate);
        console.log('Sent from the pagea6ad781389c4b07f566efdc15e629fc4363793f8#39;s main frame: ' + main);
    }
    */
    
    newPage.onLoadFinished = function(status) {
        console.log('>>>Popup window loaded<<<');
		var send_data = page.evaluate(function(url) {
			return window.popup_initialize(url); //Get the login data
		}, newPage.url); 
		
		if (send_data) {
	    	newPage.evaluate(function(send_data) {
	    		console.info( JSON.stringify(send_data) );
	    		if (send_data.form) {
		    		var form = document.querySelector(send_data.form);
		    		//console.info('Form', form);
		    		for (var i in send_data.data) {
		    			//console.info('Field ', form.querySelector(i));
		    			form.querySelector(i).value = send_data.data[i];
		    		}
		    		form.submit();
	    		} else if (send_data.button) {
	    			window.setTimeout(function() {
		    			document.querySelector(send_data.button).click();
	    			},1000);
	    		}
	    	}, send_data);
		}
    	
    };
    
    // Decorate
    newPage.onClosing = function(closingPage) {
        console.log('>>>popup window closing<<<');
        
        page.evaluate(function() {
			return window.popup_close(); //Google account approved
		}); 
    };
};

page.open(url, function(status){
	if (false && status !== "success") { //youtube.com returns fail but still works
		console.log("Unable to access network: " + status);
		phantom.exit(1);
	} else {
		// page.evaluate(addLogging);
		var interval = setInterval(function() {
			if (finished()) {
				clearInterval(interval);
				onfinishedTests();
			}
		}, 500);
	}
});

function finished() {
	return page.evaluate(function(){
		return !!window.qunitDone;
	});
}

function onfinishedTests() {
	var output = page.evaluate(function() {
			return JSON.stringify(window.qunitDone);
	});
	phantom.exit(JSON.parse(output).failed > 0 ? 1 : 0);
}

function _addLogging() {
	var current_test_assertions = [];
	QUnit.testDone(function(result) {
		var i, name = result.module + ': ' + result.name;
		if (result.failed) {
			console.log('Assertion Failed: ' + name);
			for (i = 0; i < current_test_assertions.length; i++) {
				console.log('    ' + current_test_assertions[i]);
			}
		}
		current_test_assertions = [];
	});

	QUnit.log(function(details) {
		var response;
		if (details.result) {
			return;
		}
		response = details.message || '';
		if (typeof details.expected !== 'undefined') {
			if (response) {
				response += ', ';
			}
			response += 'expected: ' + details.expected + ', but was: ' + details.actual;
		}
		current_test_assertions.push('Failed assertion: ' + response);
	});

	QUnit.done(function(result){
		console.log('Took ' + result.runtime +  'ms to run ' + result.total + ' tests. ' + result.passed + ' passed, ' + result.failed + ' failed.');
		window.qunitDone = result;
	});
}