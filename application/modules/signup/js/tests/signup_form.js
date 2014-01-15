/**
 * Signup step1 test
 */

QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'step1' ) {
	testModule( 'signup/form test', function () {

		var form = '#create_user';

		// selector
		var username   = form + ' input[name="uri_name"]';
		var email      = form + ' input[name="email"]';
		var password   = form + ' input[name="password"]';
		var firstname  = form + ' input[name="first_name"]';
		var lastname   = form + ' input[name="last_name"]';
		var submit_btn = form + ' #submit_signup';

		// testing pattern
		var username_bad   = 'test_user1';             var username_good  = 'agoodusername';
		var username_error_from_ajax = 'char@df';
		var password_bad   = ' ';                      var password_good  = 'AgoodPassword';
		var email_bad      = 'test.user1@example.com'; var email_good     = 'aemail@fantoon.com';
		var firstname_bad  = ' ';                      var firstname_good = 'tester';
		var lastname_bad   = '@';                      var lastname_good  = 'tester';

		test('form is disable in default', function(){
			ok($(form).hasClass('error'), 'form should be disable in default');
		});

		// submit button should be disable
		//testTimeoutEvent('submit button should be disable', form+' [data-validate]', 'validate', null, null, 1000)
		//.trigger( submit_btn, 'click' );
		testEvent("Prevent form submit", form, 'submit', function(e) {
			//we are checking for return false;
			ok(! e.originalEvent.returnValue, "The form shouldnt submit");
		})
		.trigger( submit_btn, 'click' );

		// ------- test basic validation
		testVisible('password is bad', $(password).closest('.form_row').find('.error'), function(){ })
		.trigger(function(){
			$(password).val(password_bad);
			$(password).trigger('keyup');
		});

		testVisible('password is valid', $(password).closest('.form_row').find('.valid'), function(){ })
		.trigger(function(){
			$(password).val(password_good);
			$(password).trigger('keyup');
		});

		testVisible('email is bad', $(email).closest('.form_row').find('.error'), function(){ })
		.trigger(function(){
			$(email).val(email_bad);
			$(email).trigger('keyup');
		});

		testVisible('email is valid', $(email).closest('.form_row').find('.valid'), function(){ })
		.trigger(function(){
			$(email).val(email_good);
			$(email).trigger('keyup');
		});

		testVisible('firstname is bad', $(firstname).closest('.form_row').find('.error'), function(){ })
		.trigger(function(){
			$(firstname).val(firstname_bad);
			$(firstname).trigger('keyup');
		});

		testVisible('firstname is valid', $(firstname).closest('.form_row').find('.valid'), function(){ })
		.trigger(function(){
			$(firstname).val(firstname_good);
			$(firstname).trigger('keyup');
		});

		testVisible('lastname is bad', $(lastname).closest('.form_row').find('.error'), function(){ })
		.trigger(function(){
			$(lastname).val(lastname_bad);
			$(lastname).trigger('keyup');
		});

		testVisible('lastname is valid', $(lastname).closest('.form_row').find('.valid'), function(){ })
		.trigger(function(){
			$(lastname).val(lastname_good);
			$(lastname).trigger('keyup');
		});

		// ------- username test (AJAX)
		testVisible('username is bad', $(username).closest('.form_row').find('.error'), function(){ })
		.trigger(function(){
			$(username).val(username_bad);
			$(username).trigger('keyup');
		});

		testVisible('ajax error should be shown', $(username).closest('.form_row').find('.error'))
		.trigger(function(){
			$(username).val(username_error_from_ajax);
			$(username).trigger('keyup');
		});

		testVisible('username is valid', $(username).closest('.form_row').find('.valid'), function(){ })
		.trigger(function(){
			$(username).val(username_good);
			$(username).trigger('keyup');
		});

		// ------- form validation, after ALL good, it should NOT hasClass('error')
		test("Form .error class remove", function() {
			ok( ! $(form).hasClass('error') );
		});

		testVisible( 'Upload image popup', $('#upload_image_dialog')).trigger(function()	{
			$('#email_image_upload_button').click();
		});

		if ( window.callPhantom !== undefined ) {

			var _image;
			var _main_image;

			testCondition( 'Upload image test', function () {
				return ( $('#upload_image_dialog img.uploaded-image-preview').attr('src') !== _image);
			} )
			.trigger( function () {
				_image = $('#upload_image_dialog img.uploaded-image-preview').attr('src');
				_main_image = $('#create_user img.uploaded-image-preview').attr('src');
				window.callPhantom('uploadFile', ['#upload_img_filename']);
			} );

			test("Main image is changed",function(){
				ok ($('#create_user img.uploaded-image-preview').attr('src') !== _main_image);
			});
		
		}

	});

}

