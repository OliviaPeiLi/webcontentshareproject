"use strict";

var CliClr = require( 'cli-color' );
var Util = require( 'util' );

function logWithColor () {
	arguments[0] = this( arguments[0] );
	console.log.apply( console, arguments );
}

function formatWithColor () {
	arguments[0] = this( arguments[0] );
	return Util.format.apply( console, arguments );
}

var Debug = {
	platform: logWithColor.bind( CliClr.cyanBright ),
	test: formatWithColor.bind( CliClr.white ),
	testErr: formatWithColor.bind( CliClr.redBright ),
	ghost: logWithColor.bind( CliClr.blackBright ),
	ghostErr: logWithColor.bind( CliClr.red ),
	connect: logWithColor.bind( CliClr.blackBright ),
	connectErr: logWithColor.bind( CliClr.red ),
	log: logWithColor.bind( CliClr.bgYellowBright.blueBright ),
};

module.exports = Debug;