"use strict";

require( '../lib/Prototype.js' );
var HttpRequest = require( '../lib/HttpRequest.js' );
var Wd = require( 'wd' );
var Util = require( 'util' );
var Fs = require( 'fs' );
var Uuid = require( 'node-uuid' );
/*@DEBUG*/
var Debug = require( '../lib/CliDebug.js' );
/*DEBUG@*/

var SeleniumHeader = Fs.readFileSync( './tests/selenium_header.js', 'UTF-8' );
var SeleniumFooter = Fs.readFileSync( './tests/selenium_footer.js', 'UTF-8' );

/**
	Used to compose variants for all required tests and distribute them to different hosts and browsers.
	@def class TestRunner
*/

var testUsers = [
	{ user: 'test.user1@example.com', pass: 'lFDvlksDF' },
	{ user: 'test.user2@example.com', pass: 'lkfdEFdxcXV' }
];

var defaultTestUser = testUsers[0];

// private
function _makeUserPassArgs ( login ) {
	return { qu_user: login.user, qu_pass: login.pass };
}

// private
function _buildTest ( host, testSpec ) {

	var test = {};

	if ( testSpec.selenium ) {
		test.selenium = './tests/' + testSpec.selenium;
	}

	if ( testSpec.path ) {
		
		var url = host;
		
		var firstDelimiter = testSpec.path.indexOf( '?' ) > 0 ? '&' : '?';
		url += testSpec.path + firstDelimiter + 'qunit_tests=true';

		if ( testSpec.login === undefined ) {
			url += '&' + HttpRequest.urlEncode( _makeUserPassArgs( defaultTestUser ) );
		}
		else if ( testSpec.login instanceof Object ) {
			url += '&' + HttpRequest.urlEncode( _makeUserPassArgs( testSpec.login ) );
		}

		if ( testSpec.args ) {
			url += '&' + HttpRequest.urlEncode( testSpec.args );
		}

		test.url = url;

	}

	return test;
}


/**
	@def function TestRunner ( testSuite, platform, browsers )
	@param object WebDriver platform implementation, one of platforms/*.js.
	@param string fandrop hostname where to run the tests
	@param object JSON object as defined in tests/README.md.
	@param object[]|undefined Browsers to test against. The format of the object
	is the one that should be passed to require( 'wd' ).remote().init(), in other words
	this is 'desiredCapabilities' object.
*/
function TestRunner ( platform, host, testSuite, browsers ) {
	this._platform = platform;
	this._browsers = browsers;

	// parse the tests in the json list and build full urls
	var tests = [];
	tests.length = testSuite.length;
	for ( var i = 0, iend = testSuite.length; i < iend; ++i ) {
		tests[i] = _buildTest( host, testSuite[i] );
		/*@DEBUG*/
		//Debug.tests( 'queue', test );
		/*DEBUG@*/
	}
	this._tests = tests;

	// possibly for webdriver reuse
	this._webdrivers = {};

	/*@DEBUG*/
	this._debug = { TotalTests: this._browsers.length * this._tests.length, TestsPerformed: 0, ActiveWebDrivers: 0 };
	/*DEBUG@*/
	this._errors = [];
}

TestRunner.define( {

	/**
		Runs the tests and reports the results to the specified host platform.
		The platform is uninitialized after all tests finish.
		@def function TestRunner.run ()
	*/
	run: function () {
		var r = this._platform.getRemotes();

		if ( r.length == 0 ) {
			throw new Error( 'Nothing to test' );
		}

		var tests = this._tests;
		var browsers = this._browsers;

		var alltests = [];
		alltests.length = tests.length * browsers.length;

		if ( alltests.length == 0 ) {
			throw new Error( 'Nothing to tests' );
		}

		// prepare remote webdrivers

		var remotes = [];
		remotes.length = r.length;

		for ( var i = 0, iend = remotes.length; i < iend; ++i ) {
			remotes[i] = {}.merge( r[i] );
			remotes[i]._remoteId = i;
			this._webdrivers[i] = null;
		}

		// render all combinations of tests

		for ( var i = 0, iend = browsers.length; i < iend; ++i ) {
			var ibase = i * tests.length;
			for ( var ii = 0, iiend = tests.length; ii < iiend; ++ii ) {
				var test = {}.merge( tests[ii] );
				test._browser = {}.merge( browsers[i] );
				test._browserId = i;
				alltests[ ibase + ii ] = test;
			}
		}

		var that = this;
		function quitWebDrivers () {

			var finished = 0;
			function wdEnd ( ) {
				/*@DEBUG*/
				--that._debug.ActiveWebDrivers;
				console.log( Debug.test( 'end WD' ) );
				/*DEBUG@*/
				if ( ++finished == remotes.length ) {
					/*@DEBUG*/
					console.log( '\n' + Debug.test( '=== DEBUG', that._debug ) + '\n' );
					/*DEBUG@*/
					console.log( Debug.testErr( 'Errors: ', that._errors.length ) );
					console.log( Debug.test( 'Jobs', that._jobs ) );
					that._webdrivers = {};
					that._platform.finalize();
				}
			}

			for ( var i in that._webdrivers ) {
				var wd = that._webdrivers[i];
				wd.onSessionFinished = wdEnd;
				wd.handle.quit();
			}
		}

		
		var notfinished = alltests.length;
		function onTestFinished ( test, remote ) {
			/*@DEBUG*/
			++that._debug.TestsPerformed;
			/*DEBUG@*/

			// the remote is now free
			remotes.push( remote );

			if ( --notfinished == 0 ) {
				quitWebDrivers();
			}
			else {
				that._runNextTest( alltests, remotes, onTestFinished );
			}
		}


		this._name = Uuid.v1();
		this._jobId = 0;
		this._jobs = [];
		console.log( Debug.test( 'running job', this._name ) );

		// start running tests on each remote
		while ( this._runNextTest( alltests, remotes, onTestFinished ) ) {}
	},

	// private
	_runNextTest: function ( tests, remotes, onTestFinished ) {

		if ( tests.length == 0 || remotes.length == 0 ) {
			return false;
		}

		var that = this;
		var test = tests.shift();
		var remote = remotes.shift();

		// use previous webdriver session (i.e. saucelabs vm)
		// use one long webdriver session for all tests that run on the same browser
		// this way we wont create new saucelabs vm for each tests which in theory will make things faster
		var webdriver = this._webdrivers[ remote._remoteId ];

		// create a webdriver for the test if one is not already created
		// or if the browser in the session is different create a new one
		if ( webdriver === null || webdriver.browserId != test._browserId ) {

			// if we have webdriver already then we need different browser, quit this webdriver to create new one
			if ( webdriver !== null ) {
				/*@DEBUG*/
				--this._debug.ActiveWebDrivers;
				/*DEBUG@*/
				webdriver.handle.quit();
			}
			
			this._webdrivers[ remote._remoteId ] = {
				// the webdriver itself
				handle: null,

				// the promise create by .init() .then() etc, keep it for the next test that runs on this webdriver
				promise: null,

				// buffer the output instead of printing it to console directly because parallel tests' output would mix otherwise
				outBuf: '',

				// notify someone when the webdriver session has ended
				onSessionFinished: null,

				// keep track of which browser is running on this webdriver so we can change it (i.e. replace the driver on this remote)
				//   if the next test neeads a different browser
				browserId: test._browserId,

				// job id associated with this webdriver
				job: this._name + '-' + ( ++this._jobId )
			};
			webdriver = this._webdrivers[ remote._remoteId ];

			/*@DEBUG*/
			console.log( Debug.test( 'start WD' , remote.host, remote.port, JSON.stringify( test._browser ), 'job', webdriver.job ) );
			++this._debug.ActiveWebDrivers;
			/*DEBUG@*/
			this._jobs.push( webdriver.job );

			webdriver.handle = Wd.promiseRemote( remote.host, remote.port, remote.user, remote.key );
			
			var strQuit = '\nEnding your web drivage..\n';

			webdriver.handle.on( 'status', function ( info ) {
				var iinfo = Util.format( '\x1b[36m%s\x1b[0m', info );
				if ( info == strQuit ) {
					console.log( iinfo );
					if ( webdriver.onSessionFinished instanceof Function ) {
						webdriver.onSessionFinished();
					}
				}
				else {
					webdriver.outBuf += '\n' + iinfo;
				}
			} );

			webdriver.handle.on( 'command', function ( meth, path, data ){
				webdriver.outBuf += '\n' + Util.format( ' > \x1b[33m%s\x1b[0m: %s', meth, path, data );
			});

			var options = this._platform.getBrowserOptions().merge( test._browser || {} ).merge( { name: webdriver.job } );
			webdriver.promise = webdriver.handle.init( options );
		}

		// for claritity:
		//   webdriver.promise = webdriver.handle.init( test._browser );
		//   webdriver.promise = webdriver.promise.then
		// is the same as:
		//   webdriver.handle.init().then()
		// but we are storing the promise so we can chain more .then()-s
		// so we don't have to create new webdriver for each test
		// which would create a new VM on saucelabs

		// delete cookies so the test can start anew
		webdriver.promise = webdriver.promise.then( function () {
			/*@DEBUG*/
			var testDescription = Util.format( '%s on %s:%d / %s', test.selenium || test.url, remote.host, remote.port, JSON.stringify( test._browser ) )
			console.log();
			console.log( Debug.test( 'running', testDescription ) );
			webdriver.outBuf += '\n' + Debug.test( 'results', testDescription + '\n' );
			/*DEBUG@*/
			return webdriver.handle.deleteAllCookies();
		} );

		if ( test.url ) {
			// if we have a fandrop url to navigate to do it now
			webdriver.promise = webdriver.promise.then( function () {
				return webdriver.handle.get( test.url );
			} );
		}
		
		if ( test.selenium ) {
			// read the selenium script file exported by Selenium Builder, but strip the part
			// where the web driver is initialized because we already have it
			// then compile the thing into a function and execute it here
			var src = Fs.readFileSync( test.selenium, 'UTF-8' );
			src = src.slice( SeleniumHeader.length, src.length - SeleniumFooter.length );
			/*@DEBUG*/
			GLOBAL.require = require;
			/*DEBUG@*/
			var fn = new Function( 'promise', 'b', 'return promise' + src );
			webdriver.promise = fn( webdriver.promise, webdriver.handle );
		}

		// if we are using qunit poll when qunit is done and report results to the platform
		else {
			webdriver.promise = webdriver.promise
			.then( function () {
				// wait one minute max for qunit
				// BP: don't know if we need this, providing this alone without the timeout below does not suffice
				return webdriver.handle.setAsyncScriptTimeout( 60 * 1000 );
			} )
			.then( function () {
				// wait for qunit to be done, timeout in one minute
				// poll every 0.5s because we don't want to eval 10 times a second (for performance reasons)
				return webdriver.handle.waitForConditionInBrowser( 'window.qunitDone', 60 * 1000, 500 );
			} )
			.then( function () {
				// get qunit results
				return webdriver.handle.eval( 'window.qunitResults' );
			} )
			.then( function ( results ) {
				// send results to saucelabs (or to console)
				that._platform.reportResults( webdriver.handle.sessionID, results );
			} );
		}

		function finish () {
			console.log( webdriver.outBuf );
			webdriver.outBuf = '';
			process.nextTick( function () {
				onTestFinished( test, remote );
			} );
		}

		// flush the buffer for the current test once it is done
		webdriver.promise = webdriver.promise.then( finish );

		webdriver.promise = webdriver.promise.fail( function ( err ) {
			var msg = err.cause.value.message.split( '\n' )[0];
			that._errors.push( { message: msg } )
			webdriver.outBuf += '\n > ' + Debug.testErr( msg );
			webdriver.outBuf += '\n > ' + Debug.testErr( 'Job', webdriver.job );
			finish();
		} );

		return true;
	}

} );

module.exports = TestRunner;