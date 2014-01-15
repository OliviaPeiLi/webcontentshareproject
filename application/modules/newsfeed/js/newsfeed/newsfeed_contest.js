/**
 * Newsfeed (Contest page)
 * @link /kittenmeme
 * @uses newsfeed/newsfeed - for general newsfeed operations
 * @uses jquery
 */
define(['newsfeed/newsfeed', 'jquery'], function() {
	
	/* ===================== Vars ======================== */
	var container = ' #list_newsfeed';
	var videos = ' .newsfeed_entry[data-link_type=embed]';
	var thumb_container = ' .photoContainer';	
	var fb_btn = ' .share_fb_app';
	var pinterest_btn = ' .pin-it-button';
	var twitter_btn = ' .share_twt_app';
	
	/* ==================== Events ======================== */
	
	/**
	 * Process json items on autoscroll
	 */
	$(document).on('scroll_bottom', container, function() {

		if (this.ajaxList_process instanceof Function) return;
		
		this.ajaxList_process = function(data) {
			if (data.is_shared) {
				$(this).find('.newsfeed_entry').addClass('liked');
				$(this).find(fb_btn).addClass('disabled_bg');
			}
			//@todo
			$(this).find('.share_btn').removeClass('inactive');
			
			$(this).find('.photoContainer').show().find('img.drop-preview-img').addClass('imgLoaded');
			
			$(this).find('.post_what a:not(.drop-link)').attr('href', data.short_url ? data.short_url : php.baseUrl+'/drop/'+data.url).html( data.short_url ? data.short_url : (php.baseUrl+'/drop/'+data.url).substr(0,60)+'&#8230;');
		}

	});
	
	/**
	 * When click on the thumbnail replace it with embed video
	 */
	$(document).on('click', container+videos+thumb_container, function(e) {
		e.stopPropagation();
		var $this = $(this);
		$.get('/bookmarklet/snapshot_preview/'+$this.closest('[data-newsfeed_id]').attr('data-newsfeed_id'), function(data) {
			var obj = $(data).find('iframe, object, embed').first();
				obj.width($this.parent().width()).height($this.parent().height()).addClass('ft-video-iframe');
			console.info('{tile_new} - replacing thmbnail with: ', obj);
			$this.hide().after(obj);
		});
	});
	
	/**
	 * Redirect to drop page on click
	 */
	$(document).on('click', container+' .newsfeed_entry', function(e) {
		if ($(e.target).closest('[rel=popup]').length) return;
		location.href = '/drop/'+$(this).attr('data-url');
	});
	
	/**
	 * Show the popup on share success
	 */
	$(document).on('share_success', function(e, data) {
		if (php.userId) return; //Show the email popup only for not logged in users
		if ($('#list_newsfeed').find('.js-points_count').length) {
			$('#list_newsfeed').find('.js-points_count').each(function() {
				$(this).text(parseInt($(this).text()) + 10);
			})
		} else {
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
		}
	});
	
	return this;
});
