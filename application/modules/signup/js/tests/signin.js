/**
 * Tests for /signin
 */
QUnit.module("Signin page");

	QUnit.asyncTest("Form submit", 1, function() {
		$('#login_form').on('submit', function() {
			ok(true, "Login form submitted");
			return false;
		});
		
		$('#login_form')
			.find('[name=email]').val('alexi_dst@yahoo.com')
			.end().find('[name=password]').val('321321')
			.end().submit();
	});
	
	QUnit.test("User logged in", function() {
		ok(true, "BAsix");
	});