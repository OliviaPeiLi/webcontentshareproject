/**
 * Logc for the email signup form.
 * @link /signup/form
 * @uses common/loader_icon - used by "upload photo" popup #upload_image_dialog
 * @uses plugins/jquery.form - used by "upload photo" popup #upload_image_dialog
 * @uses common/bootstrap_popup - for the "upload photo" popup in #email_image_upload
 * @uses common/formValidation - for the form validation
 * @uses jquery
 */
define(["common/loader_icon", "plugins/jquery.form", "common/formValidation", "jquery"], function(loader_icon){

	var self = '#signup-popup';
	var username_input = ' input[name="uri_name"]';
	var password_input = ' input[type="password"]';
	var email_input = ' input[name="email"]';
	var upload_input = "#upload_img_filename";
	
	this.init = function() {
		$(self+password_input).val('');
		$(self+email_input).val('');
		if (location.search.indexOf('signup=true') > -1) {
			$('[href="#signup-popup"]').click();
		}
	}
	
	/* =========================== Events =========================== */
	
	$(document)
	.on('preAjax',self+' form', function(e,response){
		$('input[type=submit]',this).attr( "disabled", false );
	})
	.on('success', self+' form', function(e, response) {
		if (!response.status) {
			$('input[type=submit]',this).attr( "disabled", false );
			return;
		}
		// FD-5123
		$('input[type=submit]',this).attr("disabled",true);
		var _this = this;
		setTimeout(function(){
			window.location.href = $(_this).find('[name="redirect_url"]').val();
		},1500);
	});
	
	/**
	 * Handler for upload photo
	 */
	$(document).on('change', upload_input, function() {
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
	
	$(document).on('click', '#fb-register', function() {
		var $this = $(this);
		$this.addClass('loading');
		fb_login(function(auth) {
	    	if( ! auth.userID) {
	    		$this.removeClass('loading');
	    		return;
	    	}
    		window.location.href = '/facebook_after?redirect_url='+$this.closest('.modal').find('[name="redirect_url"]').val();
		}, function() {
			$this.removeClass("loading");
		});

		return false;
	});
	
	$(document).on('click', '#twt-register', function() {
		var $this = $(this);
		twt_login(function() {
			window.parent.location.href = '/twitter_after?redirect_url='+$this.closest('.modal').find('[name="redirect_url"]').val();
		});
		return false;
	});
	
	$(document).on('before_show', '[href="#signup-popup"]', function(e, content) {
		$('#login-popup, #forgot-popup').modal('hide');
		if (location.search.indexOf('role=featured_user') > -1) {
			$(self+' [name=role]').val(3);
		} else {
			$(self+' [name=role]').val(0);
		}
	}).on('click', '[href="#signup-popup"]', function() {
		// delay to display the popup first - next fosus and show hint
		setTimeout(function(){
			$(self+username_input).trigger('focus');
		},500)
	})
	
	/* ===================== DIRECT CODE =================== */
	if ($(self+password_input).length) {
		init();
	} else {
		$(function() { init(); })
	}

});
