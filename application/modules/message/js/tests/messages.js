/**
 *  QUnit tests for list of messages page.
 */

QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

var test_message = 'quint message!';

//bobef: there is something wrong with test user 1 and 2. They don't show in the autocomplete list.
//       I did my tests with 'Radil Radenkov' and it works fine
//var test_recepient = 'Radil Radenkov';
var test_recepient = 'Test User 2';

if (!qunit_module || qunit_module == 'list') {

	testModule( 'New message', function () {

		testEvent( 'Form shown', '#private_msg_form', 'shown' )
		.trigger( function () {
			$( 'a#new_message' ).click();
		} );

		// testVisible( 'Validation receiver', '.err_msg_receiver' )
		// .trigger( function () {
		// 	$( '#send_msg_message_form' ).submit();
		// } );

		testVisible( 'Validation body', '.js_error_msg' );

		var autocompleteSelector = '.token-input-dropdown-google > ul > li.token-input-selected-dropdown-item-google'; //  

		testExists( 'Autocomplete create', autocompleteSelector )
		.trigger( function () {
			$( '#token-input-private_msg_name' )
				.trigger("focus")
				.val( test_recepient.substr( 0, 2 ) )
				.trigger( 'keydown' );
		});
/*
		testCondition( 'Autocomplete shown', function () {
			$autocompleteitem = $( autocompleteSelector );
			return $autocompleteitem.length == 1 && $autocompleteitem.text() == test_recepient;
		} );
*/

		testInvisible( 'Auto complete item selected', '.err_msg_receiver' )
		.trigger( function () {
			//simulate press enter to select the autocomplete item
			var key = jQuery.Event( 'keydown' );
			key.keyCode = 13;
			$( '#send_msg_message_form #token-input-private_msg_name' ).trigger( key );
			//click submit
			$( '#send_msg_message_form' ).submit();
		} );


		testEvent( 'Submit / form hidden', '#private_msg_form', 'hidden' )
		.trigger( function () {
			$( '#msg_body' ).val( test_message );
			$( '#send_msg_message_form' ).submit();
		} );

		testCondition( 'Sent message shown', function () {
			return $( '#inbox_messages .msg_entry:first .content_message' ).text() == test_message;
		} );

	} );
}

if ( ! qunit_module || qunit_module == 'delete') {

	testModule( 'Delete message', function () {

		var message_selector = '#inbox_messages .msg_entry:first';
		var $msg;
		
		test('Message received', function() {
			$msg = $(message_selector + ' .content_message').attr('data-_qunit_test', 'true');
			equal($msg.text(), test_message);
			equal($msg.text(), test_message);
		});
		
		testNotExists( 'Message delete', '.msg_entry [data-_qunit_test]' )
		.trigger( function () {
			$( message_selector + ' a.delete_float' ).click();
		} );

	} );


}