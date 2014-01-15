/**
 * Logic for Edit tile popup such as Ajax for update
 * Handles changes to Description inside the tile.
 * invoked by clicking on edit button inside a newsfeed item.
 * The delete button logic is in newsfeed.js and drop_page.js
 * @uses plugins/mentions - for the description textarea
 * @uses common/formValidation
 * @uses jquery
 */
define(['plugins/mentions', 'common/formValidation', 'jquery'],function() {

	var share_email_form_wrap = '#share_email_form_wrap';
	var share_email_form = '#share_email_form';
	// var share_name_field = '#share_name_to';
	var share_email_field = '#share_email_to';
	var share_body_field = '#share_email_body';

 	$(document).on('before_show',"a[href='#share_email_form_wrap']", function(e, content) {

 		$(share_email_form).get(0).reset();
 		$('#share_email_to').tokenInput("clear");

 		if ($(this).attr("data-type") == 'folder')	{
 			$('#share_email_newsfeed_id').attr("name","folder_id").val($(this).attr("data-folder_id"));
 		}	else {
			var container = $(this).closest('[data-newsfeed_id]');
			$('#share_email_newsfeed_id').attr("name","newsfeed_id").val(container.attr("data-newsfeed_id"));
 		}
 	
	return false;
	});

	$('#share_email_body').keyup(function(){

		var hint = $('.textLimit',$(this).parent());
		var maxlength = $(this).attr("data-maxlength");

		var c_length = maxlength - $(this).val().length;

		$(hint).text(c_length);

		if (c_length < 0)	{
			$(this).css({'color':'red'});
			hint.addClass("negative");
			$('#share_email_button').addClass("disabled_button");
		}	else {
			$(this).css({'color':'black'});
			hint.removeClass("negative");
			$('#share_email_button').removeClass("disabled_button");
		}

	});

	$(document)
		.on('validate', share_email_form, function(e,callback) {

			if ($('#share_email_button').hasClass("disabled_button"))	{
				callback.call(this, {status:false});
				return false;
			}

			callback.call(this, {status:true});
		})
		.on('success', share_email_form, function(event, msg) {
		
			if (msg.status == true)	{
				$('#share_email_form_wrap').modal('hide');
				$('#share-email-message').show('show');

				setTimeout(function(){
					$('#share-email-message').fadeOut('slow');
				},2000);
			
			}

		});

		var _form_wrap = '#share_email_form';
		var _tokenInput = '#token-input-share_email_to';
		
		$(document).on('blur', '#token-input-share_email_to', function() {
		 	var val = $.trim( $(this).val() );
		 	if (val == "") return false;
		 	$('#share_email_to').tokenInput('add', {'id':0, 'name': val });
		 	$(this).focus();
		});
	
});
