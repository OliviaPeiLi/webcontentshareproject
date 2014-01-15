/*
 * Logic for handling image upload, everything inside profile image upload popup 
 * including generation/resizing of images and thumbnails
 * @link /Radil1
 * @uses jquery
 * @uses plugins/jquery.form - ajax upload library
 * @uses common/loader_icon - plugin for show/hide loading icon during upload
 * @uses plugins/jcrop - plugin for cropping uploaded image
 */

define(['common/loader_icon', 'plugins/jcrop', 'plugins/jquery.form', 'jquery'], function(loader_icon){
	
	var upload_btn = '#select_file_btn_hidden';
	var preview_img = '#imgupload_preview #preview img';
	
	window.edit_picture_js_loaded = true; //Used for the tests

	/* ============================= Private functions ======================== */
	
	/**
	 * called on successfull image upload
	 * @see $('#select_file_btn_hidden').on('change' 
	 */
	function imageUploadedHandler(data) {
		console.log('{edit picture}', data);
		loader_icon.hide_loader($('div[id=imgupload_preview]:last'));
		if (!data.status) {
			console.log('{edit picture} - error');
			$('.upload_err').html(data.error).show();
			return;
		}
		console.log('{edit picture} - success');
		$('#imgupload_preview #preview').show();
		$('#get_pasted_url').hide();
		$('.upload_err').html('').hide();
		$('#save_image_page, #imgupload_options, #preview, #save_preview').show();
		$(preview_img).attr('src',data.thumb).addClass("changed");
		/*
		if (data.thumb.indexOf('users/') > -1 ) {
			$('#account_avatar').attr('src', data.thumb);
		}
		*/
		$("#profile_pic input[name='src_img']").val(data.thumb);
		
		$("#thumb_form input[name=img_path]").val(data.thumb);
		$("#profile_pic textarea[name='src_img_url']").val('');
	}
	
	/* ==================================== Events ============================== */
	
	/**
	 * Option 1: Upload a file
	 * If a new file has been uploaded (file selected), even during update of image
	 * (after user has clicked on the save/open button in the file browser)
	 */
	$(document).on('click', '#choose_file_btn', function(){
		$(upload_btn).click();
	});
	
	$(document).on('change', upload_btn, function(e) {

		if ($(this).val() == "") return;
		// TODO preloader not apear after first upload
		loader_icon.show_loader($('div[id=imgupload_preview]:last'),100,true);
		$('div[id=preview]').hide();

		// remove 'src_img' to make BE realizing that it is file upload
		// (not save click) http://dev.fantoon.com:8100/browse/FD-3012
		$(this).closest('form').find('[name="src_img"]').attr('value', '');
		
		var $form = $(this).closest('form'); 
		$form.ajaxSubmit({
		     dataType: 'json',
			 success: imageUploadedHandler
		});

    });
	
	/**
	 * Option 2: Copy/paste URL of image
	 * upload from copy and paste url
	 */
	$(document).on('click','#get_pasted_url', function() {
		var $form = $(this).closest('form'); 
		$form.ajaxSubmit({
		     dataType: 'json',
			 success: imageUploadedHandler
		});
		return false;
	});

	$(document).on('input paste keyup','#src_img_url', function(e) {
		if ( ! $(this).val()) {
			$('#get_pasted_url').hide('fade');
		} else {
			$('#get_pasted_url').show('fade');
			if (e.keyCode && e.keyCode == 13) {
				$('#get_pasted_url').click();
				return false;
			}
		}
	});

	$(document).on('update', function() {
		if ($('#src_img_url').length && php.imgurl) {
			$('#src_img_url').val(php.imgurl);
			$('#get_pasted_url').click();
		} 
	});
	
	$(document).on('submit', '#orig_img_upload_form_url', function() {
		console.log('img upload form url');
		loader_icon.show_loader($('#imgupload_preview'),100,true);
		$('#imgupload_preview #preview').hide();
		
		var $form = $(this).closest('form'); 
		$form.ajaxSubmit({
		     dataType: 'json',
			 success: imageUploadedHandler
		});
		return false;
	});
	
	/**
	 * Click on the Save button in Image upload wizard
	 */
	$(document)
		.on('preAjax','.profile_picture_modal', function() {
			$(this).find(':submit').removeClass('blue_bg').addClass('inactive_bg')
				.after('<div id="saving_image" class="inlinediv">Saving… </div>');
			$(this).closest('#imgupload_options').find('#get_pasted_url').hide();
		})
		.on('success','.profile_picture_modal', function(e, data) {
			console.info('{edit picture} - success', data);
			$('#profilePic_auth, #account_avatar').attr('src',data.img).addClass("changed");
			
			//different logic for dialog vs inline
			if($('#upload_profilepic_dlg').siblings('#not_dialog').length > 0) {
				//redirect
				$('#upload_profilepic_dlg').hide();
				window.location.replace(window.location.href);
			} else {
				$('#profilePic_auth').attr('src',data.img).addClass("changed");
				$('img.loading_icon').hide();
				$('#account_avatar, #account_link img, #profile .currentUser_panelFollowBox img').attr('src',data.img);
				$(this).closest('.modal').modal('hide');
			}
			$('#saving_image').remove();

			// if it is in notification for upload data, it should be closed
			// as FD-3562. Use .click to submit ajax for next notification if any
			if ( $('#systemNotification:visible').length && data && data.status ) {
				$('#notification_close').click();
			}
           return false;
		});
	
	/**
	 * Click on the 'Edit Thumbnail' link in the dialog
	 */
	/*
	var jcrop_api = null;
	$('#upload_editthumb_lnk').on('click', function () {
		
		if ($('#upload_profilepic_dlg #imgupload_thumb_pane').is(':visible')) {
			jcrop_api.release();
			jcrop_api.destroy();
			$('#upload_thumb').removeClass('blue_bg').addClass('inactive_bg');
			$('#upload_profilepic_dlg #imgupload_thumb_pane').hide('blind');
		} else {
			$('#upload_thumb').removeClass('inactive_bg').addClass('blue_bg');
			$('#upload_profilepic_dlg #imgupload_thumb_pane').show('blind');
			$('#upload_profilepic_dlg #thumbnail img').attr('src',$('#preview img').attr('src'));
			console.log('preview img width: '+$('#imgupload_preview #preview img').width());
			var imgwidth = $('#imgupload_preview #preview img').width();
			$('#imgupload_preview #preview img').css('max-width',imgwidth+'px !important').css('width',imgwidth+'px !important');
			
			jcrop_api = $.Jcrop('#imgupload_preview #preview img', {
							onSelect: showPreview,
							onChange: showPreview,
							aspectRatio: 1
						});
			console.log('preview img width: '+$('#imgupload_preview #preview img').width());
			var x1 = Math.ceil(imgwidth*0.1);
			var y1 = Math.ceil($('#imgupload_preview #preview img').height()*0.1);
			var x2 = Math.ceil(imgwidth*0.9);
			var y2 = Math.ceil($('#imgupload_preview #preview img').height()*0.9);
			var sq_side = Math.min(x2-x1, y2-y1);
			jcrop_api.setSelect([x1,y1,x1+sq_side,y1+sq_side]);
		}
		return false;
	});
	*/
	
	/**
	 * Handler for Image crop for thumbnails in Image Upload
	 */
	//Disabled for time being
	/*
	function showPreview(coords)
	{
		var scaleX = $('#thumbnail').width()/coords.w;
		var scaleY = $('#thumbnail').height()/coords.h;
		
		var imgScaleX = $('#preview img').width()/coords.w;
		var imgScaleY = $('#preview img').height()/coords.h;
		
		//Setting Thumb
		$('#thumbnail img').css({
			'width': Math.round( coords.w * scaleX * imgScaleX*100)/100,
			'height': Math.round( coords.h * scaleY * imgScaleY*100)/100,
			'margin-left': Math.round( -coords.x * scaleX *100)/100,
			'margin-top': Math.round( -coords.y * scaleY *100)/100
		});

		//Actual image crop dimensions
		$("#imgupload_thumb_pane #thumb_form input[name='x']").val(Math.round(coords.x));
		$("#imgupload_thumb_pane #thumb_form input[name='y']").val(Math.round(coords.y));
		$("#imgupload_thumb_pane #thumb_form input[name='w']").val(Math.round(coords.w));
		$("#imgupload_thumb_pane #thumb_form input[name='h']").val(Math.round(coords.h));
		$("#imgupload_thumb_pane #thumb_form input[name='p_w']").val($('#preview > img').width());
	};
	*/

	/**
	 * Click on Save Thumbnail button
	 */
	//Disabled for time being
	/*
	$('#thumb_form').on('submit', function() {
		$('#upload_editthumb_lnk').trigger('click');
		$.post($(this).attr('action'), $(this).serialize(), function(data) {
			if (!data.status) {
				//show error
			} else {
				$('#thumb_saved').show('blind');
				var d = new Date();
				if (data.update) {
					$('#header #account_avatar').attr('src', $.trim(data.thumb)+'?'+d.getTime());
				}
			}
		}, 'json');
		return false;
	});
	*/

	return this;
});
