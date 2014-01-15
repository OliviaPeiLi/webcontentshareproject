/**
 *  QUnit tests for thread view page.
 */

//copy the utils functions in the current scope
QUnit.Utils._export( this );

testModule( 'Thread page', function () {


	//wait for error to appear
	testVisible( 'Validation', 'div.error.blank_body')
	.trigger( function () {
		//click submit
		$( 'input[type="submit"]' ).click();
	} );

	var msgbody = 'thread message: ' + (new Date()).valueOf();
		
	//wait for message to appear
	testCondition( 'Submit message', function () { //condition
		$bodies = $( '.msg_thread_entry.msg_entry .body p.message_body' );
		for ( var i = $bodies.length - 1; i >= 0; i-- ) {
			$body = $( $bodies[i] );
			if ( $body.text() == msgbody ) {
				$body.attr( 'data-_qunit_test', 'true' );
				return true;
			}
		}
	} )
	.trigger( function () {
		//fill in message
		$( '#private_msg_body' ).val( msgbody );
		
		//click submit
		$( 'input[type="submit"]' ).click();
	} );

	//wait for message to disappear
	testNotExists( 'Delete message', '.msg_thread_entry.msg_entry .body p.message_body[data-_qunit_test="true"]' )
	.trigger( function () {
		//click delete
		$( '.msg_thread_entry.msg_entry .body p.message_body[data-_qunit_test="true"]' )
			.closest( '.msg_thread_entry.msg_entry' )
				.find( '.delete_float' )
					.click();
	} );


});