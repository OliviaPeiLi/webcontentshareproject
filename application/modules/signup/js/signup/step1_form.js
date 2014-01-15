/**
 * Logc for the email signup form.
 * @link /signup/form
 * @uses common/loader_icon - used by "upload photo" popup #upload_image_dialog
 * @uses plugins/jquery.form - used by "upload photo" popup #upload_image_dialog
 * @uses common/bootstrap_popup - for the "upload photo" popup in #email_image_upload
 * @uses common/formValidation - for the form validation
 * @uses jquery
 */
define(["common/loader_icon", "plugins/jquery.form", "common/bootstrap_popup", 'common/formValidation', 'jquery'], function(loader_icon){

	var container = '#login_wrapper';
	var password_input = ' input[type="password"]';
	
	$(container+password_input).val('');

	/* =========================== Events =========================== */
	
	/**
	 * Handler for upload photo
	 */
	$(document).on('change','#upload_img_filename', function() {
		var form = $(this).closest('form');
		form.find('.upload_err').hide();
		loader_icon.show_loader($('.image_placeholder'), 60, true)
		form.ajaxSubmit({ 
			success:  function(data) {
				console.info('Upload success', data.thumb);
				loader_icon.hide_loader($('.image_placeholder'));
				if (!data.success) {
					form.find('.error').html(data.error).show();
					return;
				}
				form.find('.error').hide();
				
				//update the hidden input
				$('form#create_user input[name=avatar]').val(data.filename);
				
				//update the preview images
				$('img.uploaded-image-preview').show().attr('src', data.thumb);				
			},
			dataType: 'json'
		});
	});

	var password = false;
	var typed = false;

	$('#password').keydown(function(){
		password = true;
	});

	function checkAutopopulateFields()	{

		var _p = $('#password');

		if ( _p.val() != "" && !password )	{
			_p.val('');
			$('.tmp_input_holder',_p.parent()).show();
		}	else if(password)	{
			$('.tmp_input_holder',_p.parent()).hide();
		}
			
		setTimeout(checkAutopopulateFields,150);
	}

	checkAutopopulateFields();

});
