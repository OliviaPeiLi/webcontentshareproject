"use strict";

require( '../../lib/Prototype.js' );
var ChildProcess = require( 'child_process' );
var Os = require( 'os' );
var Events = require( 'events' );
var Util = require( 'util' );
/*@DEBUG*/
var Debug = require( '../../lib/CliDebug.js' );
/*DEBUG@*/

var os = Os.type();
if ( os.substr( 0, 3 ) == 'Win' ) {
	os = 'windows';
}
else if ( os == 'Darwin' ) {
	os = 'mac';
}
else {
	os = 'linux';
}

/**
	Represents a PhantomJS instance.
	@def class Ghost
*/
function Ghost ( platform, port, ready, argv ) {

	function onChildProcessEnd ( err, stdout, stderr ) {
		/*DEBUG@*/
		Debug.platform( 'end GHOST' );
		/*DEBUG@*/
		if ( --Ghost._debug.GhostDrivers == 0 ) {
			platform.emit( 'allFinished' );
		}
	}

	/*@DEBUG*/
	Debug.platform( 'start GHOST' , '127.0.0.1', port );
	/*DEBUG@*/

	++Ghost._debug.GhostDrivers;

	var args = [ '--webdriver=127.0.0.1:' + port ];
	if ( argv.debugmore ) {
		args.push( '--webdriver-loglevel=DEBUG' );
	}

	this._port = port;
	this._ready = false;
	this._handle = ChildProcess.execFile( 
		'./os/' + os + '/phantomjs',
		args,
		null,
		onChildProcessEnd
	);

	if ( this._handle.exitCode === null ) {

		var strStarted = 'GhostDriver - Main - running on port ' + port + '\n';
		var that = this;
		// we need to wait until the ghostdriver is ready to accept connections
		this._handle.stdout.on( 'data', function ( data ) {
			var iserr = data.substr( 0, 6 ) == '[ERROR';
			if ( argv.debug ) {
				Debug[ iserr ? 'ghostErr' : 'ghost' ]( data );
			}
			if ( that._ready === false ) {
				if ( iserr ) {
					platform.emit( 'initializationError' );
					throw new Error( data );
				}
				else if ( data.length > strStarted.length && data.substr( data.length - strStarted.length ) == strStarted ) {
					/*@DEBUG*/
					Debug.platform( 'ready GHOST' , '127.0.0.1', port );
					/*DEBUG@*/
					that._ready = true;
					process.nextTick( function () {
						ready( that );
					} );
				}
			}

		} );

	}
}

/*@DEBUG*/
Ghost._debug = { GhostDrivers: 0 };
process.on( 'exit', function () {
	console.log();
	Debug.platform( '=== DEBUG', Ghost._debug );
	console.log();
}.bind( this ) );
/*DEBUG@*/

/**
	PhantomJS based WebDriver host.
	@def class GhostDriver
*/

/**
	Starts one or more PhantomJS instances in WebDriver mode.
	@def function GhostDriver
	@param int|undefined Prepare the platform to be able to run this number of tests in parallel
	@param function
	@param object
*/
function GhostDriver ( concurrency, ready, argv ) {

	Events.EventEmitter.call( this );

	this._ghostDrivers = [];

	this.on( 'initializationError', function () {
		this.finalize();
	} );
	
	var instances = Math.min( argv.maxConcurrency, concurrency > 0 ? concurrency : Os.cpus().length );
	var port = 8910;
	var that = this;
	var listnening = 0;
	for ( var i = 0; i < instances; ++i ) {
		var ghost = new Ghost( this, port, function ( ghost ) {
			if ( ++listnening == instances ) {
				ready( that );
			}
		}, argv );
		that._ghostDrivers.push( ghost );
		++port;
	}
}

Util.inherits( GhostDriver, Events.EventEmitter );

GhostDriver.define( {

	/**
		Does clean up when this platform instance is not needed anymore.
		@def GhostDriver.finalize ()
	*/
	finalize: function () {
		for ( var i = 0, iend = this._ghostDrivers.length; i < iend; ++i ) {
			var ghost = this._ghostDrivers[i];
			if ( ghost._handle.exitCode === null ) {
				ghost._handle.kill();
			}
		}
	},

	/**
		Retrieves all available WebDriver remote hosts on this platform.
		@def function GhostDriver.getRemotes ()
		@return object[] { host: "", port: int }
	*/
	getRemotes: function () {
		var remotes = [];
		for ( var i = 0, iend = this._ghostDrivers.length; i < iend; ++i ) {
			remotes.push( { host: '127.0.0.1', port: this._ghostDrivers[i]._port } );
		}
		return remotes;
	},

	/**
		Retrieves any platform specific browser capabilities for the webdriver initialization.
		@def function GhostDriver.getBrowserOptions ()
		@return object
	*/
	getBrowserOptions: function () {
		return {};
	},

	/**
		@def function GhostDriver.reportResults ( sessionId, results, callback )
		@param string
		@param results
		@param function|undefined
	*/
	reportResults: function ( sessionId, results, callback ) {
		/*@DEBUG*/
		Debug.platform( 'qunit', results );
		/*DEBUG@*/
	}
} );

/**
	Retrieves the default browser capabilities for this platform.
	@def static function GhostDriver.getDefaultBrowser ()
	@return object
*/
GhostDriver.getDefaultBrowser = function () {
	return { browserName: 'phantomjs' };
};

module.exports = GhostDriver;