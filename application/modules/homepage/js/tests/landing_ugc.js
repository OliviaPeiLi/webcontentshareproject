QUnit.Utils._export( this );

var qunit_module = queryParams().qunit_module;

if ( qunit_module === undefined || qunit_module == 'basic' || qunit_module == 'signup' ) {
	
	testModule( 'Basic/Signup', function () {
		var container = "#signup-popup";
		
		testEvent("Upvote", container, "shown")
		.trigger('.bigBox .folderItem .upbox .upvote','click');
		
		if ( qunit_module === undefined || qunit_module == 'signup' ) {
					
			testVisible("Signup form validation 1", container+' form .error')
			.trigger(function() {
				$(container+' form [name=uri_name]').val('');
				$(container+' form [name=email]').val('');
				$(container+' form [name=password]').val('');
				$(container+' form [name=first_name]').val('');
				$(container+' form ').submit();
			});
			testVisible("User name error reqired", $(container+' form [name=uri_name]').closest('.form_row').find('.error'));
			testVisible("Email error reqired", $(container+' form [name=email]').closest('.form_row').find('.error'));
			testVisible("Password error reqired", $(container+' form [name=password]').closest('.form_row').find('.error'));
			testVisible("First name error reqired", $(container+' form [name=first_name]').closest('.form_row').find('.error'));
			
			testVisible("Signup form validation 2", container+' form .error')
			.trigger(function() {
				$(container+' form [name=uri_name]').val('min');
				$(container+' form [name=email]').val('invalid.email');
				$(container+' form [name=password]').val('123');
				$(container+' form [name=first_name]').val('special@chars');
				$(container+' form ').submit();
			});
			testVisible("User name error min", $(container+' form [name=uri_name]').closest('.form_row').find('.error'));
			testVisible("Email error invalid", $(container+' form [name=email]').closest('.form_row').find('.error'));
			testVisible("Password error short", $(container+' form [name=password]').closest('.form_row').find('.error'));
			testVisible("First name error special@chars", $(container+' form [name=first_name]').closest('.form_row').find('.error'));
			
			testVisible("Signup form validation 3", container+' form .error')
			.trigger(function() {
				$(container+' form [name=uri_name]').val('spcial.chars');
				$(container+' form [name=email]').val('test.user1@example.com');
				$(container+' form [name=password]').val('some_pass');
				$(container+' form [name=first_name]').val('Firstname');
				$(container+' form ').submit();
			});
			testVisible("User name error special.chars", $(container+' form [name=uri_name]').closest('.form_row').find('.error'));
			testVisible("Email error alredy used", $(container+' form [name=email]').closest('.form_row').find('.error'));
			testVisible("Password", $(container+' form [name=password]').closest('.form_row').find('.valid'));
			testVisible("First name", $(container+' form [name=first_name]').closest('.form_row').find('.valid'));
			
			testVisible("Signup form validation 4", container+' form .error')
			.trigger(function() {
				$(container+' form [name=uri_name]').val('test_user1');
				$(container+' form [name=email]').val('some@email.com');
				$(container+' form ').submit();
			});
			testVisible("User name error already used", $(container+' form [name=uri_name]').closest('.form_row').find('.error'));
			testVisible("Email ", $(container+' form [name=email]').closest('.form_row').find('.valid'));
			
			testVisible("User name valid", $(container+' form [name=uri_name]').closest('.form_row').find('.valid'))
			.trigger(function() {
				$(container+' form [name=uri_name]').val('test_user3').trigger('keyup');
			});
		}
		
		testEvent("Signup close", "#signup-popup", 'hide')
		.trigger('.modal-backdrop', 'click')
		
	});

}

if ( qunit_module === undefined || qunit_module == 'login' || qunit_module == 'forgot_pass') {
	
	testModule( 'Login', function () {
		
		var container = "#login-popup";
		//var error = container+' form .error';
		var error = '#notification_bar p';
		
		testEvent("Open login popup", container, "show")
		.trigger('.header_content .right [href="'+container+'"]','click')
		
		if ( qunit_module === undefined || qunit_module == 'forgot_pass' ) {
			var forgot_container = '#forgot-popup';
			
			testEvent("Open forgot popup", forgot_container, "shown")
			.trigger(container+' [href="'+forgot_container+'"]', 'click');
			
			testInvisible("Login popup hidden", container);
			
			
			
			//testEvent("Open login popup from forgot", container, "shown")
			testVisible("Open login popup from forgot", container)
			.trigger(forgot_container+' [href="'+container+'"]', 'click');
			
			testInvisible("Forgot popup hidden", forgot_container);
			
		}
		
		testVisible("Login form validation 1", error)
		.trigger(function() {
			$(container+' form [name=email]').val('');
			$(container+' form [name=password]').val('');
			$(container+' form ').submit();
		})
		testVisible("Login form validation 1", error)
		.trigger(function() {
			$(container+' form [name=email]').val('asd');
			$(container+' form [name=password]').val('');
			$(container+' form ').submit();
		})
		testVisible("Login form validation 2", error)
		.trigger(function() {
			$(container+' form [name=email]').val('asd');
			$(container+' form [name=password]').val('asd');
			$(container+' form ').submit();
		});
		
		QUnit.asyncTest("Login", 1, function() {
			
			window.onbeforeunload = function() {
				window.clearTimeout(checkTimeout);
				ok(true, "Page redirected");
				start();
				return false;
			}
			
			$(container+' form [name=email]').val('test.user1@example.com');
			$(container+' form [name=password]').val('lFDvlksDF');
			window.setTimeout(function() {
				$(container+' form ').submit();
			}, 300);
			
			
			var checkTimeout = window.setTimeout(function() {
				ok(false, "Login - timedout");
				start();
			}, 10 * 1000);
		});
	});
	
}