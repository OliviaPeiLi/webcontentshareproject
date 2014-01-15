/* 
 * Test cases for account page (account_options)
 * @package    account
 * @author     Quang, BP
 * @link       links to the site where the code is used
 * @uses       qunit
 */

QUnit.Utils._export( this );

//BP: this was original Quang's test
/*var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'account' ) {
	testModule( 'account setting', function () {
		var account_basic = '#account_basic';
		var basic_form = account_basic + ' form';
		var username = basic_form + ' input[name="uri_name"]';
		var username_err = '1234';		// error username
		var submit_btn = basic_form + ' input[name="submit"]';

		var account_err_flash = account_basic + ' #username_alert';
		var account_err = basic_form + ' #account_options_err';

		// check form username/submit button are visible
		testVisible('username is visible', username);
		testVisible('submit_btn is visible', submit_btn);

		// test validation error due to less than 5 characters
		testEvent('change username', basic_form, 'success', function(){
			visible( account_err );
			hidden(account_err_flash);
		})
		.trigger( function(){
			$(username).val(username_err);
			$(submit_btn).click();
		});

	});
}*/


//this will test each prefined bad value for each input field while putting good values in the rest of the inputs
//in other words will test many combinations of inputs for all input fields in the form
function formTests ( qForm, data ) {
	var $submit = $( qForm + ' ' + data.submit );
	var qError = data.error instanceof Function ? data.error : ( data.error ? qForm + ' ' + data.error : null );
	var qSuccess = data.success ? qForm + ' ' + data.success : null;
	
	test("Basic form state", function() {
		ok ($(qForm).length, "Form not found: "+qForm);
		ok ($submit.length, "Submit button not found "+qForm + ' ' + data.submit);
	});

	//iterate inputs
	if ( data.inputs ) {
		for ( var i = 0; i < data.inputs.length; ++i ) {

			var badInput = data.inputs[i];
			
			/*test("Basic field check", function() {
				ok($(qForm + badInput.selector ).length, "Field: "+qForm+' '+badInput.selector+' not found');
			})*/

			if ( badInput.badValue === undefined ) {
				continue;
			}

			//iterate bad values for this input
			for ( var ii = 0; ii < badInput.badValue.length; ++ii ) {
				var badValue = badInput.badValue[ii];
				
				//generate test where this bad value is tested and should diplay error
				(function ( badInput, badValue, i ) {
					var qBad = qForm + ' ' + badInput.selector;
					var testName = 'Bad value "' + badValue + '" in field ' + qBad;
					
					function fillValues () {
						for ( var j = 0; j < data.inputs.length; ++j ) {
							if ( j == i ) {
								continue;
							}
							var input = data.inputs[j];
							console.info('>>> set val ', input.selector, input.originalValue);
							$( qForm + ' ' + input.selector ).val( input.originalValue );
						}
						console.info('>>> set val ', qBad, badValue);
						$( qBad ).val( badValue );
					}

					if ( badInput.validate ) {

						//test js validation, not submit
						var validation = badInput.validate;
						function validationTrigger () {
							fillValues();
							//window.validate( $( qBad ) );
							$submit.click();
						}

						if ( validation instanceof Function ) {
							testCondition( 'Validation error ' + testName, function () { return validation( qForm ); } )
							.trigger( validationTrigger );
						} else {
							testVisible( 'Validation error ' + testName, validation )
							.trigger( validationTrigger );
						}
					} else {
						//test for form error
						testEvent( testName, qForm, 'success' )
						.trigger( validationTrigger );
					}

					//test error message is shown
					if ( qError ) {
						if ( qError instanceof Function ) {
							testCondition( 'Error after ' + testName, function () { return qError( qForm ); } );
						}
						else {
							testVisible( 'Error shown after ' + testName, qError );
						}
					}

					//test success message is not shown
					if ( qSuccess ) {
						testInvisible( 'Success not shown after ' + testName, qSuccess );
					}
				
				})( badInput, badValue, i );
			}
		}
	}

	//finally test successful submit
	var testName = 'Successful submit of ' + qForm;
	
	var testName = 'Successful submit of ' + qForm;
	testEvent( testName, qForm, 'success' )
	.trigger( function () {
		if ( data.inputs ) {
			for ( var j = 0; j < data.inputs.length; ++j ) {
				var input = data.inputs[j];
				$( qForm + ' ' + input.selector ).val( input.originalValue );
			}
		}
		$submit.click();
	} );

	//test error is not shown
	if ( qError ) {
		if ( qError instanceof Function ) {
			testCondition( 'No error after ' + testName, function () { return !qError( qForm ); } );
		}
		else {
			testInvisible( 'Error not shown after ' + testName, qError );
		}
	}

	//test success message is shown
	if ( qSuccess ) {
		testVisible( 'Success shown after ' + testName, qSuccess );
	}
}

var user = {
	firstName: 'Test',
	lastName: 'User 1',
	uriName: 'test_user1',
	password: 'lFDvlksDF',
	email: 'test.user1@example.com'
};

/* Disabled for now - undefined problem with window call Phantom. It doesn't upload any image. Strange bug */
/*
testModule( 'Image Upload', function () {

	testVisible( 'Upload image popup', '#upload_profilepic_dlg')
	.trigger(function()	{
		$('#link_to_edit_photo').click();
		console.info(window.callPhantom);
	});

	// disable this test function for now
	testVisible('Show dialog window','#profile_pic').trigger(function(){

	}).trigger(function(){
		$('#select_file_btn_hidden').click();
	});

	if ( window.callPhantom !== undefined ) {

		var _image;
		var _main_image;

		testCondition( 'Upload image test', function () {
			console.info( $('#preview img').attr('src'));
			return ( $('#preview img').attr('src') !== _image );
		},null,null, 20000 )
		.trigger( function () {
			_image = $('#preview img').attr('src');
			_main_image = $('#imgupload_preview img').attr('src');
			window.callPhantom('uploadFile', ['#select_file_btn_hidden']);
		});

	}
});
*/

testModule( 'Name', function () {
	var data = {
		inputs: [
			{
				selector: 'input[name="first_name"]',
				badValue: [ '', '$%', '1234.hg' ],
				originalValue: user.firstName,
				validate: '.error'
			},
			{
				selector: 'input[name="last_name"]',
				//badValue: [ '&^*"\'', '', '$%', '1234.hg' ],
				originalValue: user.lastName
			},
			{
				selector: 'input[name="uri_name"]',
				badValue: [ '', '123', '1234', 'asd asd', 'qwe"qwe', 'io<div>sio' ],
				originalValue: user.uriName,
				validate: '.error'
			}
		],
		submit: 'input[type="submit"]',
		error: '.error',
		success: '.account_ok'
	};
	formTests( 'form#account_basic', data );
} );



testModule( 'Update your password', function () {
	var data = {
		inputs: [
			{
				selector: 'input[name="old_pass"]',
				badValue: [ '', user.password + '123' ],
				validate: '.error',
				originalValue: user.password
			},
			{
				selector: 'input[name="new_pass"]',
				badValue: [ '', '12345' ],
				validate: '.error',
				originalValue: user.password
			}
		],
		submit: 'button',
		error: function ( qForm ) {
			var ret = $( qForm + ' .error' ).is( ':visible' ) || $( qForm + ' .error' ).is( ':visible' );
			return ret;
		},
		success: '.account_ok'
	};

	formTests( 'form#change_password', data );
} );




testModule( 'Email', function () {
	var data = {
		inputs: [
			{
				selector: 'input[name="email"]',
				badValue: [ '', 'ffff' ],
				originalValue: user.email,
				validate: '.error'
			}
		],
		submit: 'button',
		error: '.error',
		success: '.account_ok'
	};

	formTests( 'form#email_change', data );
} );



testModule( 'Basic information', function () {
	var data = {
		submit: 'input[type="submit"]',
		error: '.error',
		success: '.account_ok'
	};

	formTests( 'form#account_profile_basic', data );
} );




testModule( 'Email settings', function () {
	var data = {
		submit: 'button',
		error: '.error',
		success: '.account_ok'
	};

	formTests( 'form#save_email_setting', data );
} );




/* RR - Education is disabled for now */
/*
testModule( 'Edication', function () {
	var data = {
		inputs: [
			{
				selector: 'input[name="old_pass"]',
				badValue: [ '', user.password + '123' ],
				originalValue: user.password
			},
			{
				selector: 'input[name="new_pass"]',
				badValue: [ '', '12345' ],
				validate: '.error',
				originalValue: user.password
			}
		],
		submit: 'button',
		error: function ( qForm ) {
			var ret = $( qForm + ' .error' ).is( ':visible' ) || $( qForm + ' .error' ).is( ':visible' );
			return ret;
		},
		success: '.account_ok'
	};

	formTests( 'form#school_entry_form', data );
} );
*/