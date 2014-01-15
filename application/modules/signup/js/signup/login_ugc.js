/*
 * Js login for the login form
 * @link /signin
 * @uses jquery
 */
define(['social/all', 'jquery'], function() {

	var self = '#login-popup';
	var redirect_input = '[name="redirect_url"]';
	
	/* ==================== Events ============================= */
	
	$(document).on('validate', function(e, callback) {
		var $this = $(this);
		$this.find('.error').hide();
		/**
		 * Fix for a bug requested by a user. No one of us could replicate this
		 * unless the login form stays open for more than 30mins (csrf expire time)
		 */
		$.get('/get_csrf', function(response) {
			if (!response.status) return false;
			
			$this.find('input[name=ci_csrf_token]').val(response.csrf.hash);
			callback.call(this, {status:true});
		},'json');
	}).on('success', self+' form', function(e, response) {
		if (!response.status) {
			return;
		}
		window.location.href = $(this).find('[name="redirect_url"]').val();
	});

	$(document).on('click', self+' #fb-login', function(){
		var $this = $(this);
		if ($this.hasClass('loading')) return false;
		$this.addClass('loading');
		
		fb_login(function(auth) {
			jQuery.post("/fb_login", {}, function(response) {
				$this.removeClass('loading');
		    	if(! response.status){
		    		$this.closest('.modal-body').find('.error').html('Facebook login failed').show();
		    		return;
		    	}
		    	window.location.href = $this.closest('.modal-body').find(redirect_input).val();
		    }, 'json');
		}, function(){
			$this.removeClass('loading');
		});
		return false;
	});
	
	$(document).on('click',self+' #twt-login', function() {
		var $this = $(this);
		$this.addClass('loading');
		twt_login(function() {
			window.location.href = $this.closest('.modal-body').find(redirect_input).val();
		}, function(msg) {
			if (msg == 'closed_window')	{
				$('#signup_social_wrapper a').removeClass("loading");
				return;
			}
			$('a[href="#regiter-popup"]').trigger('click');
		}, 'login');
		return false;
	});
	
	$(document).on('before_show', '[href="#login-popup"]', function(e, content) {
		$('#signup-popup, #forgot-popup').modal('hide');
	});

	return this;
});