/**
 *  Check email validation of forget password & redirect
 *  @uses - jquery - 
 */
define(['jquery','common/formValidation','common/ajaxForm'], function() {
	
	var self = '#forgot-popup';
	
	$(document).on('success',self+' form', function (e, data) {
		if (!data.status) return false;
		$(this).hide();
		$('.resetPasswordStep2').show();
	});
	
	$('[href="#forgot-popup"]').on('before_show', function(e, content) {
		$('#login-popup, #signup-popup').modal('hide');
	});

});
