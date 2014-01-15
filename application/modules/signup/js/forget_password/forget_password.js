/**
 *  Check email validation of forget password & redirect
 *  @uses - jquery - 
 */
define(['jquery','common/formValidation','common/ajaxForm'], function() {
	var password_form = '#forgetpassword_check_form';

	$(document).on('success', password_form, function (e, data) {
		if (!data.status) return false;
		$('.resetPasswordContainer').hide();
		$('.resetPasswordStep2').show();
	});

});
