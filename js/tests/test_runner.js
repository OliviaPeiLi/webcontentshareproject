"use strict";

require( './lib/Prototype.js' );
var Fs = require( 'fs' );
var TestRunner = require( './lib/TestRunner.js' );

function parseArgv ( args ) {
	var argv = {};
	for ( var i = 2, end = args.length; i < end; ++i ) {
		var arg = args[i];
		var p = arg.indexOf( '=' );
		var name = null;
		var value = null;
		if ( p >= 0 ) {
			var start = arg.charAt( 0 ) == '-' ? 1 : 0;
			name = arg.substr( start, p  - 1 );
			value = arg.substr( p + 1, arg.length - p );
		}
		else {
			if ( arg.charAt( 0 ) == '-' ) {
				name = arg.substr( 1 );
				value = true;
			}
			else {
				name = i - 2;
				value = arg;
			}
		}

		var prevval = argv[name];
		if ( prevval !== undefined ) {
			if ( prevval instanceof Array ) {
				prevval.push( value );
			}
			else {
				argv[name] = [ argv[name], value ];
			}
		}
		else {
			argv[name] = value;
		}
	}
	return argv;
}

var argv = parseArgv( process.argv );

if ( argv.config ) {
	argv.merge( JSON.parse( Fs.readFileSync( argv.config, 'UTF-8' ) ) );
}

// parse input arguments
var platform = argv.platform || 'GhostDriver';
var host = argv.host || ( Fs.existsSync( 'fandrop.host' ) ? Fs.readFileSync( 'fandrop.host', 'UTF-8' ) : 'http://localhost' );
var suite = argv.suite || './tests/tests.json';
if ( !(suite instanceof Array) ) {
	suite = [ suite ];
}
var browsers = argv.browsers || null;
var concurrency = parseInt( argv.concurrency ) || 0;
argv.concurrency = concurrency;

// load the test suites
var testSuite = [];
suite.forEach( function ( file ) {
	testSuite = testSuite.concat( JSON.parse( Fs.readFileSync( file, 'UTF-8' ) ) );
} );


// initialize the proper platform
var Platform = require( './lib/platforms/' + platform + '.js' );

// load browsers, but not if the browsers come from config.json and they are already an object/arrays
if ( !( browsers instanceof Object ) ) {
	if ( Fs.existsSync( browsers ) ) {
		browsers = JSON.parse( Fs.readFileSync( browsers, 'UTF-8' ) );
	}
	else {
		browsers = [ Platform.getDefaultBrowser() ];
	}
}
else {
	if ( !( browsers instanceof Array ) ) {
		browsers = [ browsers ];
	}
}

// no need to have more hosts than the number of tests
argv.maxConcurrency = testSuite.length * browsers.length;
concurrency = Math.min( concurrency, argv.maxConcurrency );


new Platform( concurrency, function ( platform ) {

	// make sure we don't leave processes running
	function onUncaughtException ( err ) {
		process.removeListener( 'uncaughtException', onUncaughtException );
		platform.on( 'allFinished', function () {
			// throw after all is finished (e.g. all child processes are killed) to get the debug output properly
			throw err;
		} )
		platform.finalize();
	}

	process.on( 'uncaughtException', onUncaughtException );
	
	// run the tests
	var tests = new TestRunner( platform, host, testSuite, browsers );
	tests.run();
}, argv );