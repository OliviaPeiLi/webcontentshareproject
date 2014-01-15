define(['common/formValidation', 'plugins/jquery.form', 'jquery'], function() {
	
	var form = '#contest_edit_popup form';
	var file_input = ' input[type="file"]';
	var img_input  = ' input[name="logo"]';
	
	$(document).on('change', form+file_input, function() {
		console.log('{edit form} - upload image');
		var $this = $(this);
		var $form = $this.closest('form');
		
		//container.find('li:not(.sample)').remove();
		$form.find('.error').hide();
		
		$form.addClass('loading')
			.ajaxSubmit({
				success:  function(response)  {
					console.log('{contest edit form} - upload image callback', response);
					$form.removeClass('loading')
					if (response.error) {
						$form.find('> .error').show().html(response.error);
						return;
					}
					$(form+img_input).val(response.filename);
					console.info($form.find('[name="id"]').val(), $('#contests [data-id="'+$form.find('[name="id"]').val()+'"]'));
					$('#contests [data-id="'+$form.find('[name="id"]').val()+'"] img').attr('src', response.thumb);
				},
				complete: function () { },
				dataType: 'json'
			});
	
	});
	
	$(document).on('success', form, function(event, response) {

		if (!response.status) {
			console.info('ERROR: ',response.error);
			if (typeof response.field != 'undefined')	{
				$(this).find('.' + response.field).show().html(response.error);
			}	else{
				$(this).find('.error:first').show().html(response.error);
			}
			return false;
		}
		
		var contest = $('#contests [data-id="'+$(this).find('[name="id"]').val()+'"]');
			contest.find('a.dashboard_btn').attr('href', '/'+response.url+'/dashboard');
			contest.find('a.contests_image').attr('href', '/'+response.url);
			
		$(this).closest('.modal').modal('hide');
	
	});
	
	$(document).on('before_show', '[href="#contest_edit_popup"]', function(e, content) {
		console.info('{contest popup} - Open');
		var container = $(this).closest('[data-id]');
		
		content.find('[name="id"]').val(container.attr('data-id'));
		content.find('[name="url"]').val(container.attr('data-url'));
	});
	
});