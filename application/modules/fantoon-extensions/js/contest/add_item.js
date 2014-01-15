/**
 * 
 */
define(['plugins/jquery.form', 'jquery'], function() {
	
	var self = '.selfSubmission_container';
	var file_input = ' input[type=file]';
	var img_input = ' input[name=img]';

	$(document).on('change', self+file_input, function() {

		console.log('{submission form} - upload image');
		var $this = $(this);
		var $form = $this.closest('form');
		
		//container.find('li:not(.sample)').remove();
		$form.find('.error').hide();
		
		$form.addClass('loading')
			.ajaxSubmit({
				success:  function(response)  {
					
					console.log('{submission form} - upload image callback', response);
					
					if (response.error) {
						$form.find('> .error').show().html(response.error);
						return;
					}
					
					$(self+img_input).val(response.thumb);
					
					$(self+' .preview img').attr('src', response.thumb).load(function(){
						setTimeout(function(){
							$form.removeClass('loading');
						},500);
					});
				
				},
				complete: function () {
				},
				dataType: 'json'
			});
	});
	
	$(function() {
		if ($(self+img_input).val()) {
			$(self+' .preview img').attr('src', $(self+img_input).val());
		}
	});

});