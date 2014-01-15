define(['plugins/bootstrap-modal', 'jquery'], function () {
	
	var opened_confirm = false;
	
	$(document).on('click', '[data-confirm]', function() {
		$('#confirm p').html($(this).attr('data-confirm'));
		$('#confirm').addClass('modal fase').show().modal('show');
		$('#confirm').css({'margin-left': -$('#confirm').width()/2, 'margin-top': $('#confirm').height()/2});
		$('#confirm [rel=ajaxButton]').attr('href', $(this).attr('href'));
		opened_confirm = $(this);
		return false;
	});
	
	$(document).on('success', '#confirm [rel=ajaxButton]', function(e, response) {
		$('#confirm').modal('hide');
		if (response.redirect_url) {
			console.info('redirecting...');
			window.location.href = response.redirect_url;
			return false;
		}
		if (opened_confirm) opened_confirm.trigger('success', response);
	});
});