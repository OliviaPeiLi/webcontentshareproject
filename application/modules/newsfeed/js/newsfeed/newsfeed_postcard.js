/**
 * JS logic for Newsfeed postcard view
 * @link /
 * @uses newsfeed/newsfeed - for general newsfeed operations
 * @uses comments/newsfeed_comments - for comments
 * @uses profile/badge - for user badge which appears on hover over user avatar
 * @uses common/fd-scroll - for the custom scroll of the comments
 * @uses jquery
 */
define(["newsfeed/newsfeed","comments/newsfeed_comments", "profile/badge", "common/fd-scroll",'jquery'], function(){
	
	/* ========================== Private Vars =================== */
	var container = '#list_newsfeed';
	var videos = ' .newsfeed_entry [data-class=tl_video]';
	var thumb_container = ' .item_left_cell';
	var undo_up_btn = ' .undo_up_button';
	var up_btn = ' .up_button';
	var up_count = ' .up_count';
	var icon = ' .tl_icon';
	var staff_writer = ' .js-staff_writer';
	var featured_user = ' .js-featured_user';
	var edit_btn = ' .topPost_actions > div, .topPost_actions > a';
	var fb_btn = ' .share_fb_app';
	var pinterest_btn = ' .pin-it-button';
	var dropped_via = ' .itemDroppedVia_Title';
	
	/* ======================== Events ========================= */

	$(document).on('scroll_bottom_success', '.fd-autoscroll', function(){
		exec_img_correction();
	});
	
	$(document).on('click',container+' .newsfeed_entry .item_left_cell a', function() {
		$(this).closest('[rel="popup"]').click();
		return false;
	});


	/**
	 * When click on the thumbnail replace it with embed video
	 */
	$(document).on('click',container+videos+thumb_container, function(e) {
		e.stopPropagation();
		var $this = $(this);
		$.get('/bookmarklet/snapshot_preview/'+$this.closest('[data-newsfeed_id]').attr('data-newsfeed_id'), function(data) {
			var obj = $(data).find('iframe, object, embed').first();
				obj.width($this.width()).height($this.height()).addClass('ft-video-iframe');
			console.info('{postcard} - replacing thmbnail with: ', obj);
			$this.hide().after(obj);
		});
	});
	
	/**
	 * Process json items on autoscroll
	 */
	var fb_like_refresh = null;
	$(document).on('scroll_bottom', container, function() {

		if (this.ajaxList_process instanceof Function) return;
		this.ajaxList_process = function(data) {
			var $this = $( this );
/*			
			if ($this.find('.fb-like').length) {
				if (fb_like_refresh) window.clearTimeout(fb_like_refresh);
				fb_like_refresh = window.setTimeout(function() {
					FB.XFBML.parse();
				}, 500);
			} else 
*/
			if (data.is_shared) {
				$this.find('.newsfeed_entry').addClass('liked');
				$this.find(fb_btn).addClass('disabled_bg');
			}

			if (data.is_liked) {
				$this.find(undo_up_btn).show().end().find(up_btn).hide();
				$this.find(up_count).addClass('unlike').removeClass('like');
			}
			
			if (data['user_from-role'] == '1') {
				$this.find(staff_writer).show();
			} else if (data['user_from-role'] == '1') {
				$this.find(featured_user).show();
			}
			if (!data.can_edit) {
				// $this.find(edit_btn).hide();
				$this.find(edit_btn).remove();
			}
			if (!data.link_url) {
				$this.find(dropped_via).remove();
			}
						
			if (data.link_type == 'text') {
				$this.find(pinterest_btn).remove();
				$this.find('.text_wrapper').show().find('.text_content').html(data.content);
				$this.find('.js-play_button, .postcard_img_wrapper').remove();
			} else {

				$(container).trigger('check_default_image',[this]);
				
				if (data.complete == '1') { //RR - sample element is inactive by default
					$this.find('.ext_share a').removeClass("inactive");
					$this.find('.ext_share a.share_fb_app').removeClass("disabled_bg");
				} 

				$this.find('.postcard_img_wrapper').show().find('img').addClass('imgLoaded');
				$this.find('.text_wrapper').remove();
				if (data.link_type == 'image') {
					$this.find('.postcard_img_wrapper img').addClass('watermarked');
				}
				if (data.link_type == 'embed') {
					$this.find('.js-play_button').show();
				} else {
					$this.find('.js-play_button').remove();
				}
				
				
				var pint_it_url = "http://pinterest.com/pin/create/button/"
					+"?url="+encodeURIComponent( php.baseUrl+'/drop/'+data.url )
					+"&amp;media="+encodeURIComponent(data._img_full)
					+"&amp;description="+encodeURIComponent(data._description_plain);
				$this.find(pinterest_btn).attr('href', pint_it_url);
			}

			if (data.twt_shared)	{
				$('.share_twt_app',$this).addClass("inactive")
			}

			if (data.fb_shared)	{
				$('.share_fb_app',$this).addClass("disabled_bg")
			}

			if (data.pin_shared)	{
				$('.pin-it-button',$this).addClass("inactive")
			}

		}
	});

});
