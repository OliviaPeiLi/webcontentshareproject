/**
 * Newsfeed (Timeline view)
 * Logic for most actions inside a Timeline newsfeed
 * Things like Like/Unlike, Comment Like/Unlike
 * @link /collection/test_user1/asd
 * @uses profile/badge - for showing the user badge on hover over the avatar
 * @uses jquery
 * @sinse 6/3/2013 - RR - removed newsfeed/newsfeed - loads old design js and breaks upvote
 */
define(['like/newsfeed_ugc', 'common/custom_title', "profile/badge", 'jquery'], function() {
	
	/* ===================== Vars ======================== */
	var self = this;
	var container = ' #list_newsfeed';
	var videos = ' .newsfeed_entry[data-link_type=embed]';
	var thumb_container = ' .photo-container';
	var play_container = ' .play_container';
	
	var undo_up_btn = ' .downvote';
	var up_btn = ' .upvote';
	var up_count = ' .js_upvotes_count';
	var edit_btn = ' .js_edit_newsfeed';
	var dropped_via = 'div.itemDroppedVia_Title';

	/* Not used for now */
	/*
	var icon = ' .tl_icon';
	var staff_writer = ' .js-staff_writer';
	var featured_user = ' .js-featured_user';
	var fb_btn = ' .share_fb_app';
	var pinterest_btn = ' .pin-it-button';
	*/
	
	/* ==================== Events ======================== */
	/**
	 * When click on the thumbnail replace it with embed video
	 */
	$(document).on('click', container+videos+thumb_container, function(e) {
	
		e.stopPropagation();
		var $this = $(this);
		$('.play_container',$(this).closest('.newsfeed_dropContent_container')).hide();
		// $(container+videos+play_container).hide();
		$.get('/bookmarklet/snapshot_preview/'+$this.closest('[data-newsfeed_id]').attr('data-newsfeed_id'), function(data) {
			var obj = $(data).find('iframe, object, embed').first();
				obj.width($this.parent().width()).height($this.parent().height()).addClass('ft-video-iframe');
			console.info('{tile_new} - replacing thmbnail with: ', obj);
			$this.hide().after(obj);
		});

	});
	
	// attach play button if not video will open in the popup window

	$(document).on('click', container+videos+play_container, function(e) {
		e.stopPropagation();
		$(this).hide().prev(thumb_container).trigger("click");
	});

	/**
	 * Process json items on autoscroll
	 */
	$(document).on('scroll_bottom', container, function() {

		if (this.ajaxList_process instanceof Function) return;

		this.ajaxList_process = function(data) {

			var $this = $(this);

			if ( data.is_liked ) {
				$this.find(undo_up_btn).css({"display":""}).end().find(up_btn).hide();
				$this.find(up_count).addClass('unlike').removeClass('like');
			}
			
			if (!data.can_edit) {
				$this.find(edit_btn).hide();
			}
			
			if (!data.link_url) {
				$this.find(dropped_via).hide();
			}
			
			$this.find('.share_twt_app').attr('data-url',php.baseUrl+data.folder.folder_url.replace("/collection","")).attr('data-text',data._description_plain)

			if (data.link_type == 'text') {
				$this.find('.text-container').show().html(data.content);
				$this.find('.photo-container').remove();
				$this.find('.play_container').remove();
			} else {
				$this.find('.text-container').remove();
				var img = $this.find('.photo-container').show().find('img.drop-preview-img')
					.attr('src', data._img_576);
				
				img[0].onload = function() { _set_img_size(this); };
				if (img[0].width > 20 && img[0].height > 20) {
					_set_img_size(img[0]);
				}
				
				if (data.complete == '1') { // RR - sample element is inactive by default
					$this.find('.ext_share a').removeClass("inactive");
					$this.find('.ext_share a.share_fb_app').removeClass("disabled_bg");
				}
				if (data.link_type != 'content') {
					$this.find('.newsfeed_dropContent').removeAttr('rel');
				}
				if (data.link_type != 'embed' && data.complete) {
					$this.find('.photo-container img.drop-preview-img').addClass('has_zooming');
				}
				
				if (data.link_type == 'image') {
					$this.find('.photo-container').addClass('watermarked');
				}
				if (data.link_type == 'embed') {
					$this.find('.play_container').show();
				} else {
					$this.find('.play_container').remove();
				}
			}
		}

	});
	
	return this;
});
