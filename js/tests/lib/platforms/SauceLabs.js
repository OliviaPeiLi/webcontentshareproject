"use strict";

var _credentials = {
	user: 'alexi_ned',
	key: 'e1480914-04d3-409f-89c2-3b6113449bd2'
};

require( '../../lib/Prototype.js' );
var SauceAccount = require( 'saucelabs' );
var ChildProcess = require( 'child_process' );
var Events = require( 'events' );
var Uuid = require( 'node-uuid' );
var Util = require( 'util' );
var Fs = require( 'fs' );
/*@DEBUG*/
var Debug = require( '../../lib/CliDebug.js' );
/*DEBUG@*/

/**
	Represents a Sauce Connect instance.
	@def class Connect
*/
function Connect ( platform, ready, argv ) {

	var port = Uuid.v1();

	function onChildProcessEnd ( err, stdout, stderr ) {
		/*DEBUG@*/
		Debug.platform( 'end CONNECT' );
		/*DEBUG@*/
		platform.emit( 'allFinished' );
	}

	/*@DEBUG*/
	Debug.platform( 'start CONNECT', port );
	/*DEBUG@*/

	// remove previous connect log, ignore errors
	try {
		Fs.unlinkSync( './tmp/sauce_connect.log' );
	}
	catch( e ) {}

	var args = [
		'-jar',
		'./os/Sauce-Connect-latest/Sauce-Connect.jar',
		_credentials.user,
		_credentials.key,
		'--tunnel-identifier',
		port,
		'--logfile',
		'./tmp/sauce_connect.log'
	];
	if ( argv.debugmore ) {
		args.push( '--debug' );
	}

	this._port = port;
	this._ready = false;
	this._handle = ChildProcess.execFile( 
		'java',
		args,
		null,
		onChildProcessEnd
	);

	if ( this._handle.exitCode === null ) {

		var strStarted = 'Connected! You may start your tests.\n';
		var that = this;
		// we need to wait until the connect is ready to accept connections
		this._handle.stdout.on( 'data', function ( data ) {
			//todo: dont know what the connect things says when it fails
			var iserr = false;
			if ( argv.debug ) {
				Debug[ iserr ? 'connectErr' : 'connect' ]( data );
			}
			if ( that._ready === false ) {
				if ( iserr ) {
					platform.emit( 'initializationError' );
					throw new Error( data );
				}
				else if ( data.length > strStarted.length && data.substr( data.length - strStarted.length ) == strStarted ) {
					/*@DEBUG*/
					Debug.platform( 'ready CONNECT', port );
					/*DEBUG@*/
					that._ready = true;
					ready( that );
				}
			}

		} );

	}
}

/**
	Sauce Labs based WebDriver host.
	@def class SauceLabs
*/

/**
	Starts one or more PhantomJS instances in WebDriver mode.
	@def function SauceLabs
	@param int|undefined Prepare the platform to be able to run this number of tests in parallel
	@param function
	@param object
*/
function SauceLabs ( concurrency, ready, argv ) {

	Events.EventEmitter.call( this );

	this.on( 'initializationError', function () {
		this.finalize();
	} );
	
	var that = this;
	var callbacks = 0;
	var tryReady = function () {
		if ( ++callbacks == 2 ) {
			// cause the callback to be called in another loop
			// so exceptions in the callback wont end up in the callers
			// of the current callbacks
			process.nextTick( function () {
				ready( that );
			} );
		}
	};

	if ( argv['sauce-noconnect'] ) {
		this._connect = null;
		tryReady();
	}
	else {
		this._connect = new Connect( this, tryReady, argv );
	}

	this._extraOptions = {};

	if ( argv['sauce-novideo'] ) {
		this._extraOptions[ 'record-video' ] = false;
	}

	if ( argv['sauce-novideo-pass'] ) {
		this._extraOptions[ 'video-upload-on-pass' ] = false;
	}

	if ( argv['sauce-noshots'] ) {
		this._extraOptions[ 'record-screenshots' ] = false;
	}

	if ( argv['sauce-capture-html'] ) {
		this._extraOptions[ 'capture-html' ] = true;
	}

	/*@DEBUG*/
	Debug.platform( 'check ACCOUNT', _credentials.user );
	/*DEBUG@*/
	this._account = new SauceAccount( {
		username: _credentials.user,
		password: _credentials.key
	} );

	this._account.getAccountLimits( function ( err, res ) {
		if ( err ) {
			that.emit( 'initializationError' );
			throw err;
		}
		else {
			that._concurrency = Math.min( argv.maxConcurrency, concurrency > 0 ? concurrency : res.concurrency );
			/*@DEBUG*/
			Debug.platform( 'ready ACCOUNT', res );
			/*DEBUG@*/
			tryReady();
		}
	} )
}

Util.inherits( SauceLabs, Events.EventEmitter );

SauceLabs.define( {

	/**
		Does clean up when this platform instance is not needed anymore.
		@def SauceLabs.finalize ()
	*/
	finalize: function () {
		if ( this._connect ) {
			if ( this._connect._handle.exitCode === null ) {
				this._connect._handle.kill();
			}
		}
		else {
			this.emit( 'allFinished' );
		}
	},

	/**
		Retrieves all available WebDriver remote hosts on this platform.
		@def function SauceLabs.getRemotes ()
		@return object[] { host: "", port: int, user: "", key: "" }
	*/
	getRemotes: function () {
		var remotes = [];
		remotes.length = this._concurrency;
		for ( var i = 0, iend = this._concurrency; i < iend; ++i ) {
			remotes[i] = { host: 'ondemand.saucelabs.com', port: 80, user: _credentials.user, key: _credentials.key };
		}
		return remotes;
	},

	/**
		Retrieves any platform specific browser capabilities for the webdriver initialization.
		@def function SauceLabs.getBrowserOptions ()
		@return object
	*/
	getBrowserOptions: function () {
		if ( this._connect ) {
			return { 'tunnel-identifier': this._connect._port }.merge( this._extraOptions );
		}
		else {
			return {}.merge( this._extraOptions );
		}
	},

	/**
		@def function SauceLabs.reportResults ( sessionId, results, callback )
		@param string
		@param results
		@param function|undefined
	*/
	reportResults: function ( sessionId, results, callback ) {
		/*@DEBUG*/
		Debug.platform( 'qunit', results );
		/*DEBUG@*/
		this._account.updateJob( sessionId, { custom: { qunit: results } }, callback );
	}
} );

/**
	Retrieves the default browser capabilities for this platform.
	@def static function SauceLabs.getDefaultBrowser ()
	@return object
*/
SauceLabs.getDefaultBrowser = function () {
	return { browserName: 'chrome', platform: 'Windows 2008' };
};

module.exports = SauceLabs;