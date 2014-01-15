define(['common/formValidation', 'plugins/jquery.form', 'jquery'], function() {
	
	var self = '.selfSubmission_container';
	var file_input = ' input[type=file]';
	var img_input = ' input[name=logo]';
	var preview_img = ' img[alt="preview"]';
	
	var collection_input = ' input.add_category';
	var collection_btn = ' input[value="Add"]';
	var collection_list = ' input[name="categories[]"]';
	
	$(document).on('change', self + file_input, function() {

		console.log('{submission form} - upload image');

		var $this = $(this);
		var $form = $this.closest('form');
		
		//container.find('li:not(.sample)').remove();
		$form.find('.error').hide();
		
		$form.addClass('loading')
			.ajaxSubmit({
				success:  function(response)  {
					console.log('{submission form} - upload image callback', response);
					$form.removeClass('loading')
					if (response.error) {
						$form.find('> .error').show().html(response.error);
						return;
					}
					$(self+img_input).val(response.filename);
					$(self+preview_img).attr('src', response.thumb);
	
				},
				complete: function () { },
				dataType: 'json'
			});
	
	});
	
	$(document).on('click',self+collection_btn, function() {
		var val = $.trim($(self+collection_input).val());
		if (!val) return;
		console.info('{create contest} - add', val);
		$(self+collection_list).tokenInput('add', {'id':0, 'name': val});
		$(self+collection_input).val('');
	});
	
});