/**
 * Newsfeed (Sxsw)
 * @link /sxsw
 * @uses newsfeed/newsfeed - for general newsfeed operations
 * @uses jquery
 */
define(['newsfeed/newsfeed', 'jquery'], function() {
	
	/* ===================== Vars ======================== */
	var container = ' #list_newsfeed';
	var videos = ' .newsfeed_entry [data-class=tl_video]';
	var thumb_container = ' .photoContainer';	
	var fb_btn = ' .share_fb_app';
	var pinterest_btn = ' .pin-it-button';
	var twitter_btn = ' .share_twt_app';
	
	/* ==================== Events ======================== */
	
	/**
	 * Process json items on autoscroll
	 */
	$(document).on('scroll_bottom',container, function() {
	
		if (this.ajaxList_process instanceof Function) return;
		
		this.ajaxList_process = function(data) {
			if (data.is_shared) {
				$(this).find('.newsfeed_entry').addClass('liked');
				$(this).find(fb_btn).addClass('disabled_bg');
			}
			
			$(this).find('.photoContainer').show().find('img.drop-preview-img').addClass('imgLoaded');
			var url = php.baseUrl+'/drop/'+data.url;
			var pint_it_url = "http://pinterest.com/pin/create/button/"
				+"?url="+encodeURIComponent( url )
				+"&media="+encodeURIComponent(data._img_full)
				+"&description="+encodeURIComponent(data.title+' is in the VentureBeat WinSXSW contest! Help us win the contest by sharing our video here at '+url);
			$(this).find(pinterest_btn).attr('href', pint_it_url);
			
			$(this).find(twitter_btn)
				.attr('data-text', data.title+' is in the VentureBeat WinSXSW contest! Help us win the contest by sharing our video here at ')
				.attr('data-hashtags', 'WinSXSW')
		}

	});
	
	/**
	 * When click on the thumbnail replace it with embed video
	 */
	$(document).on('click', container+videos+thumb_container, function(e) {
		e.stopPropagation();
		var $this = $(this);
		$.get('/bookmarklet/snapshot_preview/'+$this.closest('[data-newsfeed_id]').attr('data-newsfeed_id'), function(data) {
			var obj = $(data).find('iframe, object, embed');
				obj.width($this.width()).height($this.height()).addClass('ft-video-iframe');
			console.info('{tile_new} - replacing thmbnail with: ', obj);
			$this.hide().after(obj);
		});
	});
	
	/**
	 * Show the popup on share success
	 */
	$(document).on('share_success', function(e, data) {
		var popup = $('#emailInvite_box').show().addClass('modal');
		popup.prepend('<button class="new_close" data-dismiss="modal"></button>');
		popup.css({
			'margin-left':-popup.outerWidth()/2 || -250,
			'margin-top': -Math.min( popup.height()/2 || 280, $(window).height()/2-50 ) 
		})
		popup.modal({
				keyboard: true,
				show: true,
				backdrop: true
			});
		
	});

	return this;
});
