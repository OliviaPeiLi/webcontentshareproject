
//bobef:
//A set of utility functions for common QUnit tests tasks.
//Most functions are wrappers around wait().


QUnit.Utils = {

	/// Last wait object id, used for debugging.
	_lastId: 0,

	/// Default wait timeout.
	_defaultTimeout: 10 * 1000,

	/// Default wait repeat.
	_defaultRepeat: 100,

	//So we can disable all output of the tests if we want.
	_console: window.console,

	/**
	 * Converts a query string to object with key/value pairs.
	 * @param string Optional query string, otherwise location.search will be used.
	 * @return object
	 */
	queryParams: function (str) {
		if(typeof str != 'string' || str.length == 0) str = location.search;
		if(str.charAt(0) == '?') str = str.substr(1);
		var params = str.split('&');
		var ret = {};
		for(var p in params) {
			var param = params[p];
			var kv = param.split('=');
			var key = kv[0];
			var value = kv[1] !== undefined ? decodeURIComponent(kv[1]) : null;
			ret[key] = value;
		}
		return ret;
	},

	/**
	 * Repeatedly checks if given condition is met.
	 * Callbacks will be called with the same this as wait().
	 * @param any Parameter to pass to the callbacks
	 * @param function Condtion callback. Should true if the condition is met.
	 * @param function Callback to be excuted when condion is met.
	 * @param function Callback to be executed when timeout occurs.
	 * @param int Timeout period in milliseconds. Defaults to 1 seconds.
	 * @param int Repeat interval in milliseconds. Defaults to 100 milliseconds.
	 * @return jQuery.Deferred. When the function returns the wait is not started. One can call .start() to start waiting/checking or .stop() to clear the current timeout. .stop() function won't resolve nor reject the object, but will post progress with status 'stop'.
	 */
	wait: function ( param, condition, success, error, timeout, repeat ) {
		if ( timeout === undefined ) {
			timeout = QUnit.Utils._defaultTimeout;
		}
		if ( repeat === undefined ) {
			repeat = QUnit.Utils._defaultRepeat;
		}
		
		var waitObj = new jQuery.Deferred();
		
		waitObj._maxwait = timeout;
		waitObj._id = ++QUnit.Utils._lastId;
		
		waitObj.stop = function () {
			if ( waitObj._timeout ) {
				clearTimeout( waitObj._timeout );
				waitObj._timeout = null;
				return waitObj.notify( 'stop' );
			}
			else {
				return waitObj;
			}
		};
		
		waitObj.start = function () {
			waitObj._expire = (new Date()).valueOf() + waitObj._maxwait;
			return waitObj.notify( 'start' ).tick();
		};
		
		waitObj.tick = function () {
			return waitObj.notify( 'tick' );
		};
		
		var done = waitObj.done;
		waitObj.done = function ( fn ) {
			if ( !(fn instanceof Function) && fn instanceof Object && fn.start instanceof Function ) {
				var _fn = fn;
				fn = function () {
					_fn.start();
				};
			}
			return done.call( waitObj, fn );
		};
		
		var fail = waitObj.fail;
		waitObj.fail = function ( fn ) {
			if ( !(fn instanceof Function) && fn instanceof Object && fn.start instanceof Function ) {
				var _fn = fn;
				fn = function () {
					_fn.start();
				};
			}
			return fail.call( waitObj, fn );
		};
		
		waitObj.success = waitObj.done;
		waitObj.success( success );
		waitObj.fail( error );
		
		waitObj.progressex = function ( status, fn ) {
			return waitObj.progress( function ( statusa ) {
				if ( status == statusa ) {
					fn();
				}
			} );
		};

		waitObj.onstart = function ( selector, event ) {
			if (typeof selector == 'function') { //trigger complex function
				fn = selector;
			} else if (typeof selector == 'object' && event) { //trigger jquery element
				test("Find triggerer elements", function() {
					ok(selector.length, "Triggerer element not found: "+selector);
				});
				fn = function() {
					selector.trigger(event);
				}
			} else if (event) { //create jquery element from selector and triggers
				/*test("Find triggerer elements", function() {
					ok($(selector).length, "Triggerer element not found: "+selector);
				});*/
				fn = function() {
					console.info('Trigger', selector, event);
					$(selector).trigger(event);
				}
			} else {
				throw "Could not recognize trigger function";
			}
			return waitObj.progressex( 'start', fn );
		};

		waitObj.trigger = waitObj.onstart;

		waitObj.ontick = function ( fn ) {
			return waitObj.progressex( 'tick', fn );
		};

		waitObj.onstop = function ( fn ) {
			return waitObj.progressex( 'stop', fn );
		};

		waitObj.timeout = function ( timeout ) {
			waitObj._maxwait = timeout;
			return waitObj;
		};

		waitObj.ontick( function ( status ) {
			if ( waitObj.state() != 'pending' ) {
				return;
			}
			if ( condition( param ) ) {
				waitObj._timeout = null;
				waitObj.resolve( param );
			}
			else {
				waitObj._timeout = setTimeout( function() {
					waitObj._timeout = null;
					if ( (new Date()).valueOf() >= waitObj._expire ) {
						QUnit.Utils._console.log( '=== Wait timeout ===>', param );
						waitObj.reject( param );
					}
					else {
						waitObj.tick();
					}
				}, repeat);
			}
		} );

		return waitObj;
	},
	/**
	 * Similar to wait but it waits for an event
	 * @param $element - the element to which the event will be attached
	 * @param event -  the event whihc is expected
	 * @param success (optional) - called on success 
	 * @param error (optional) - called on timeout
	 * @param timeout (optional) - how much time to wait maximum for the event to trigger
	 * 
	 * @property trigger - can be a function, jquery object or a selector *the last tow requre a second parameter 'event'*
	 */
	event: function(selector, event, success, error, timeout) {		
		//Used in the async test in 'run()' and should became a function after trigger() is called;
		this.triggerer = null; 
		this.selector = selector;
		this.event = event;
		this.success = success;
		//this.$element = $element;
		
		this.run = function() {
			var eventObj = this;
			
			QUnit.test("Get trigerer", function() {
				console.info('get trigerer');
				ok(eventObj.triggerer, "Could not find trigerer function");
			});
			
			QUnit.asyncTest(this.selector + " - Event", 1, function() {			
				console.info('----------Attach event', eventObj.selector+':'+eventObj.event);
				//Timeout
				eventObj.checkTimeout = window.setTimeout(function() {
					ok(false, eventObj.selector+':'+eventObj.event+" timed out");
					QUnit.Utils._console.log( '=== Wait timeout ===>', eventObj.selector+':'+eventObj.event );
					QUnit.Utils._console.log( '=== Unitest failed ===>', eventObj.selector+':'+eventObj.event );
					//error.call(eventObj, eventObj.selector+':'+eventObj.event+" timed out");
					start();
				}, timeout || QUnit.Utils._defaultTimeout);
				
				$(eventObj.selector).one(eventObj.event, function(e) {
					if (eventObj.event_success) {
						console.info(">>>> Warning the same event is trying to execute twice: ", eventObj.event);
						return;
					}
					window.clearTimeout(eventObj.checkTimeout);
					console.info('>>> START ', eventObj.event)
					eventObj.event_success = e;
					ok(true);
					start();
				});
				
				eventObj.triggerer.call(eventObj);
				
			});
			
			if (eventObj.success) {
				QUnit.test(this.selector +"-success", function() {
					eventObj.success(eventObj.event_success)
				});
			}
		}
		
		this.trigger = function(selector, event) {
			if (typeof selector == 'function') { //trigger complex function
				this.triggerer = selector;
			} else if (typeof selector == 'object' && event) { //trigger jquery element
				test("Find triggerer elements", function() {
					ok(selector.length, "Triggerer element not found: " + selector);
				});
				this.triggerer = function() {
					selector.trigger(event);
				}
			} else if (event) { //create jquery element from selector and triggers
				test("Find triggerer elements", function() {
					ok($(selector).length, "Triggerer element not found: " + selector);
				});
				this.triggerer = function() {
					console.info('-----Trigger event', event);
					$(selector).trigger(event);
				}
			} else {
				throw "Could not recognize trigger function";
			}
			this.run();
		};

		this.timeout = function ( t ) {
			timeout = t;
			return this;
		};

		return this;
	},

	/**
	 * Repeatedly checks if a jQuery selector returns more than zero elements.
	 * The first parameter is a string with jQuery selector, the rest of the parameters are the same as wait().
	 * The return value is the same as wait() but the wait be .start()-ed.
	 */
	waitExists: function ( selector, success, error, timeout, repeat ) {
		var ret = QUnit.Utils.wait( selector, function ( selector ) {
			return $( selector ).length > 0;
		}, success, error, timeout, repeat );
		return ( success || error ) ? ret.start() : ret;
	},

	/**
	 * Repeatedly checks if a jQuery selector returns exactly zero elements.
	 * The first parameter is a string with jQuery selector, the rest of the parameters are the same as wait().
	 * The return value is the same as wait() but the wait be .start()-ed.
	 */
	waitNotExists: function ( selector, success, error, timeout, repeat ) {
		var ret = QUnit.Utils.wait( selector, function ( selector ) {
			return $( selector ).length == 0;
		}, success, error, timeout, repeat );
		return ( success || error ) ? ret.start() : ret;
	},

	
	/**
	 * Repeatedly checks if a jQuery selector returns more than zero elements and they are :visible.
	 * The first parameter is a string with jQuery selector, the rest of the parameters are the same as wait().
	 * The return value is the same as wait() but the wait be .start()-ed.
	 */
	waitVisible: function ( selector, success, error, timeout, repeat ) {
		var ret = QUnit.Utils.wait( selector, function ( selector ) {
			return $( selector ).is( ':visible' );
		}, success, error, timeout, repeat );
		return ( success || error ) ? ret.start() : ret;
	},
	/**
	 * Simple extension to QUnit equal to check if element is visible
	 */
	visible: function(selector, error) {
		error = error || selector+" should be visible";
		var $element = typeof selector == 'string' ? $(selector) : selector;
		ok($element.is(':visible') , error);
	},
	/**
	 * Simple extension to QUnit equal to check if element is hidden
	 */
	hidden: function(selector, error) {
		error = error || selector+" should be hidden";
		var $element = typeof selector == 'string' ? $(selector) : selector;
		ok($element.is(':hidden'), error);
	},
	/**
	 * Simple extension to QUnit equal to check if element exists
	 */
	exists: function(selector, error) {
		error = error || selector+" should exists";
		var $element = typeof selector == 'string' ? $(selector) : selector;
		ok($element.length, error);
	},

	
	/**
	 * Repeatedly checks if a jQuery selector returns more than zero elements and they are not :visible.
	 * The first parameter is a string with jQuery selector, the rest of the parameters are the same as wait().
	 * The return value is the same as wait() but the wait be .start()-ed.
	 */
	waitInvisible: function ( selector, success, error, repeat, timeout ) {
		var ret = QUnit.Utils.wait( selector, function ( selector ) {
			return !$( selector ).is( ':visible' );
		}, success, error, repeat, timeout ).start();
		return ( success || error ) ? ret.start() : ret;
	},


	/**
	 * Stands for async ok.
	 * A shortcut for ok( condition, text ); start();
	 * @return void
	 */
	oka: function ( condition, text ) {
		QUnit.ok( condition, text );
		QUnit.start();
	},

	/**
	 * Performs async test if a condition is met.
	 * This function wraps wait() in a QUnit.asyncTest that expects one test and calls ok()/start() automatically.
	 * If the condition becomes true the test will be marked as ok( true ), otherwise it will be marked as ok( false );
	 * @param string Name of the test
	 * @param function Condition callback. Should return true if the condition is met.
	 * @param function Optional callback to perform on success
	 * @param function Optional callback to perform on error
	 * @param int Timeout period in milliseconds. The error callback will be called if condition is not met withing this period.
	 * @param int Repeat interval in milliseconds. Repeat condition check on this interval.
	 * @return deffered wait object. See wait() for description.
	 */
	testCondition: function ( testName, condition, success, error, timeout, repeat ) {
		return QUnit.Utils.testConditionEx( testName, null, condition, success, error, timeout, repeat );
	},


	/**
	 * The same as the vairant without *Ex except that this one accepts extra parameter 'param', which is passed to wait().
	 * See testCondition for paramaters and return value.
	 */
	testConditionEx: function ( testName, param, condition, success, error, timeout, repeat ) {
		
		var k = true;

		function end ( good ) {
			if ( !good ) {
				QUnit.Utils._console.log( '=== Unitest failed ===>', testName );
			}
			QUnit.ok( good, testName );
			QUnit.start();
			var fn = good ? success : error
			if (fn instanceof Function ) {
				fn();
			}
		}

		var wait = QUnit.Utils.wait( param, condition, function () {
			end( k );
		}, function () {
			end( !k );
		}, timeout, repeat );

		wait.reverseok = function () {
			k = !k;
			return wait;
		};
		
		QUnit.asyncTest( testName, 1, function () {
			wait.start();
		} );

		return wait;
	},
	
	/**
	 * Extends QUnit asyncTest to wait for a specific event
	 */
	testEvent: function (testName, selector, event, success, error) {
		var $element = typeof selector == 'string' ? $(selector) : selector;
		var success_test;
		
		//The element may be loaded via ajax so no need of that
		//ok($element.length, selector+' not found');
		
		if (!error) {
			error = function(msg) {
				ok(false, msg);
			}
		}
		
		return new QUnit.Utils.event(selector, event, success, error);
	},

	/**
	 * test if a class is added to the selector
	 * for using it, it is mandatory to have 1 ok(true) at the place of
	 * 'success' or 'error' function
	 */
	testHasClass: function (testName, selector, theclass, success, error, timeout, repeat) {
		var $element = typeof selector == 'string' ? $(selector) : selector;
		var success_test;

		return QUnit.Utils.testConditionEx( testName, selector, function () {
			return $element.hasClass(theclass);
		}, success, error, timeout, repeat );
	},

	testNotHasClass: function (testName, selector, theclass, success, error, timeout, repeat) {
		var $element = typeof selector == 'string' ? $(selector) : selector;
		var success_test;

		return QUnit.Utils.testConditionEx( testName, selector, function () {
			return !$element.hasClass(theclass);
		}, success, error, timeout, repeat );
	},

	/**
	 * Performs async test if a jQuery selector will find more than zero elements.
	 * The second parameter is jQuery selector string, the other parameters are the same as testCondition().
	 * See testCondition for paramaters and return value.
	 */
	testExists: function ( testName, selector, success, error, timeout, repeat ) {
		return QUnit.Utils.testConditionEx( testName, selector, function () {
			if (typeof selector == 'string') {
				return $( selector ).length > 0;
			} else {
				return selector.length > 0;
			}
		}, success, error, timeout, repeat );
	},

	/**
	 * Performs async test if a jQuery selector will find zero elements.
	 * The second parameter is jQuery selector string, the other parameters are the same as testCondition().
	 * See testCondition for paramaters and return value.
	 */
	testNotExists: function ( testName, selector, success, error, timeout, repeat ) {
		return QUnit.Utils.testConditionEx( testName, selector, function () {
			return $( selector ).length == 0;
		}, success, error, timeout, repeat );
	},

	/**
	 * Performs async test if a jQuery selector will become visible.
	 * The second parameter is jQuery selector string, the other parameters are the same as testCondition().
	 * See testCondition for paramaters and return value.
	 */
	testVisible: function ( testName, selector, success, error, timeout, repeat ) {
		return QUnit.Utils.testConditionEx( testName, selector, function () {
			if (typeof selector == 'string') {
				return $( selector ).is( ':visible' );
			} else {
				return selector.is( ':visible' );
			}
		}, success, error, timeout, repeat );
	},

	/**
	 * Performs async test if a jQuery selector will become invisible.
	 * The second parameter is jQuery selector string, the other parameters are the same as testCondition().
	 * See testCondition for paramaters and return value.
	 */
	testInvisible: function ( testName, selector, success, error, timeout, repeat ) {
		return QUnit.Utils.testConditionEx( testName, selector, function () {
			return !$( selector ).is( ':visible' );
		}, success, error, timeout, repeat );
	},

	//just for consitency
	testNotVisible: function () {
		return QUnit.Utils.testInvisible.apply( this, arguments );
	},

	/**
	 * Creates a chain of deferred wait objects.
	 * They are chained using their .done() method.
	 * If one object fails the whole chain fails.
	 * Chain can be started with .start().
	 */
	/*Chain: function () {
		
		var first = null;
		var last = null;
		var once = true;

		var ret = new jQuery.Deferred();
		
		ret.start = function() {
			if ( last && once ) {
				last.done( function () {
					ret.resolve();
				} );
				once = false;
			}
			first.start();
		};

		ret.add = function ( obj ) {
			if ( last !== null ) {
				last.done( function () {
					obj.start();
				} );
			}
			else {
				first = obj;
			}
			last = obj;
			last.fail( function () {
				ret.reject();
			} );
		}
		
		for ( var i = 0; i < arguments.length; ++i ) {
			ret.add( arguments[i] );
		}
		return ret;
	},*/

	/**
	 * Calls QUnit.module and wrapps fn in QUnit.test, so it will be called at the appropriate time (i.e. when everything is loaded).
	 * This function is useful together with the test* functions.
	 */
	testModule: function ( moduleName, fn ) {
		QUnit.module( moduleName );
		QUnit.test( moduleName, function () {
			fn.call();
			ok( true, moduleName );
		});
	},

	/**
	 * Copies the utility functions to another object.
	 * So one will not need to write QUnit.Utils. on every call.
	 */
	_export: function ( target ) {
		for ( var i in QUnit.Utils ) {
			if ( i.charAt(0) != '_' ) {
				var it = QUnit.Utils[i];
				if ( it instanceof Function ) {
					target[i] = it;
				}
			}
		}
	}

};