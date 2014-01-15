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

	The legal bullshit is longer than the code itself. This is ridiculous.

*/

"use strict";

/**
 * Copies all properties from another object to this.
 * @def function Object.merge ( object )
 * @param object
 * @return this
 * @author Borislav Peev <borislav.asdf@gmail.com>
 */
Object.defineProperty( Object.prototype, 'merge', {
	value: function ( object ) {
		for ( var i in object ) {
			this[i] = object[i];
		}
		return this;
	}
} );

/*@UNITESTS*/
// Unitest( 'Object.merge()', function () {

// 	var a = { a: 2, b: 3 }.merge( { a: 3, c: 4 } );
// 	test( a.a === 3 );
// 	test( a.b === 3 );
// 	test( a.c === 4 );

// } );
/*UNITESTS@*/

/**
 * Defines properties in the prototype of the function.
 * Each property will be added using Object.definePrototype.
 * @def function Function.define ( properties )
 * @param object Collection of properties.
 * @return this;
 * @author Borislav Peev <borislav.asdf@gmail.com>
 */
Object.defineProperty( Function.prototype, 'define', { 
	value: function ( prototype ) {
		var proto = this.prototype;
		for ( var i in prototype ) {
			Object.defineProperty( proto, i, { value: prototype[i], writable: true } );
		}
		return this;
	}
} );

/*@UNITESTS*/
// Unitest( 'Function.define()', function () {

// 	var A = function () {};
// 	A.define( { test: function () { return this.qwe; }, qwe: 5 } );
// 	var a = new A();
// 	test( a.test() === 5 );

// } );
/*UNITESTS@*/