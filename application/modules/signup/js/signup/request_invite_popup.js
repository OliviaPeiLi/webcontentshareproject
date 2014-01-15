/**
 *  Logic for the request invite popup
 *  @link - landing page
 */
define(['jquery', 'common/formPlaceholder'], function() {
	var container = '.js-landing-signup';
	var email_input = ' input[name=email]';
	var form_el = ' .js-invite-init';
	var success_el = ' .js-invite-success';
	var error_el = ' .js-invite-error';
		
	$(document).on('success', container+' form', function(e, data) {
		var $container = $(this).closest(container);
		if (!data.status) {
			$container.find(error_el).text(data.error).show('fade');
			return;
		}
		$container.find(form_el).hide();
		$container.find(success_el).show();
		$(email_input).val('');
		window.setTimeout(function() {
			$container.find(form_el).show();
			$container.find(success_el).hide();
		},5000);
		window.setTimeout(function() {
			$('#emailInvite_box').modal('hide'); //hide the popup on success
		}, 1000);
	})
	
	$(document).on('keyup', container+email_input, function(){
		$(this).closest(container).find(error_el).hide();
	});
	
})