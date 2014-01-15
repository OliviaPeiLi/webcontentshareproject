/**

	Copyright, disclaimer, blah blah.

	This file was (originally) authored by me, Borislav Peev <borislav.asdf@gmail.com>,
	
	It is not authored as part of my work for Fantoon
	thus this file remains my own property. The time used
	to develop this file was spent outside my work for Fantoon
	and I'm not charging Fantoon for this work so it is not subject
	to my contract with Fantoon. I'm donating this work to Fantoon
	for use, free of charge, in anyway they see fit,
	but I'm not transferring exclusive ownership of this work to Fantoon
	or any of its employees or associates. I retain complete rights over
	the original version of this file and I may share it with whoever I please.
	Further development of this file is subject to my contract with
	Fantoon and I do not claim any rights over (future) contributions
	to this file by other Fantoon employees.

	This notice must remain here to ensure Fantoon may not
	claim exclusive rights to this work as it is my original
	work, not sponsored by Fantoon and is already in use by
	others before Fantoon, thus Fantoon may not claim exclusive
	ownership of this work.

	In simple words since I already wrote this I'm reusing it here (without charging Fantoon for it)
	but others are using it too so I don't want Fantoon to find this
	out some day and start suing these people or myself claiming
	they have rights over this work because of my contract.

*/

"use strict";

/**
	@private
	@author Borislav Peev <borislav.asdf@gmail.com>
*/
function encodePrimitive( val ) {
	if ( typeof val == 'number' || val instanceof Number ) {
		val = String( val );
	}
	return encodeURIComponent( val );
}

/**
	@private
	@author Borislav Peev <borislav.asdf@gmail.com>
*/
function encodeArray ( array, namePrefix ) {
	var ret = '';
	for ( var i = 0, iend = array.length; i < iend; ++i ) {
		var name = namePrefix + '[' + i + ']';
		var val = array[i];
		if ( val instanceof Array ) {
			val = encodeArray( val, name );
			ret += ( ret.length > 0 ? '&' : '' ) + val;
		}
		else if ( val instanceof Object ) {
			val = encodeObject( val, name );
			ret += ( ret.length > 0 ? '&' : '' ) + val;
		}
		else {
			val = encodePrimitive( val );
			ret += ( ret.length > 0 ? '&' : '' ) + name + '=' + val;
		}
	}
	return ret;
}

/**
	@private
	@author Borislav Peev <borislav.asdf@gmail.com>
*/
function encodeObject ( object, namePrefix ) {
	var ret = '';
	for ( var i in object ) {
		var name = namePrefix ? namePrefix + '[' + encodeURIComponent( i ) + ']' : encodeURIComponent( i );
		var val = object[i];
		if ( val instanceof Array ) {
			val = encodeArray( val, name );
			ret += ( ret.length > 0 ? '&' : '' ) + val;
		}
		else if ( val instanceof Object ) {
			val = encodeObject( val, name );
			ret += ( ret.length > 0 ? '&' : '' ) + val;
		}
		else {
			val = encodePrimitive( val );
			ret += ( ret.length > 0 ? '&' : '' ) + name + '=' + val;
		}
		
	}
	return ret;
}

var HttpRequest = {};

/**
 * @def static function HttpRequest.urlEncode ( object )
 * @param object
 * @return string|null
 * @author Borislav Peev <borislav.asdf@gmail.com>
 */
HttpRequest.urlEncode = function ( object ) {
	if ( !(object instanceof Object) ) {
		return null;
	}
	return encodeObject( object );	
}

/*@UNITESTS*/
// Unitest( 'HttpRequest.urlEncode()', function () {
// 	test( HttpRequest.urlEncode( 5 ) === null );
// 	test( HttpRequest.urlEncode( { a: 1, b: 2 } ) === 'a=1&b=2' );
// 	test( HttpRequest.urlEncode( { c: 'asd', d: 'q&a' } ) === 'c=asd&d=' + encodeURIComponent( 'q&a' ) );
// 	test( HttpRequest.urlEncode( { a: { aa: 1, bb: 2 }, b: 2 } ) === 'a[aa]=1&a[bb]=2&b=2' );
// 	test( HttpRequest.urlEncode( { a: [ 1, 2 ], b: 2 } ) === 'a[0]=1&a[1]=2&b=2' );
// 	test( HttpRequest.urlEncode( { a: [ { aa: 1 }, {bb: [ { cc: { dd: 2 } } ]} ], b: 2 } ) === 'a[0][aa]=1&a[1][bb][0][cc][dd]=2&b=2' );
// } );
/*UNITESTS@*/

module.exports = HttpRequest;