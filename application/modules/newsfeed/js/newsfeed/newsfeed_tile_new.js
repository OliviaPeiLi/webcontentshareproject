/**
 * Newsfeed (Timeline view)
 * Logic for most actions inside a Timeline newsfeed
 * Things like Like/Unlike, Comment Like/Unlike
 * @link /collection/test_user1/asd
 * @uses newsfeed/newsfeed - for general newsfeed operations
 * @uses profile/badge - for showing the user badge on hover over the avatar
 * @uses jquery
 */
define(['newsfeed/newsfeed', "profile/badge", 'jquery'], function() {
	
		/* ===================== Vars ======================== */
		var self = this;
		var container = ' #list_newsfeed';
		var videos = ' .newsfeed_entry [data-class=tl_video]';
		var thumb_container = ' .photoContainer';
		//var comment_count = ' .comment_count';
		//var comment_btn = ' .newsfeed_comments_lnk';
		
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
		
		/* ==================== Events ======================== */
		
		/**
		 * Clicking on links inside popup trigerer is prevented by default but this one should open it
		 */
		//$(container+comment_count).on('click', function(){
		//	$(this).closest('.tile_new_entry_subcontainer').click();
		//	return false;
		//});
		
		/**
		 * Clicking on links inside popup trigerer is prevented by default but this one should open it
		 */
		//$(container+comment_btn).on('click', function(){
		//	$(this).closest('.tile_new_entry_subcontainer').click();
		//});
		
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
		 * Process json items on autoscroll
		 */
		var fb_like_refresh = null;

		$(document).on('scroll_bottom', container, function() {

			if (this.ajaxList_process instanceof Function) return;

			this.ajaxList_process = function(data) {
				
				var $this = $(this);
				
				console.warn('list_newsfeed');
			
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
				if (data.user_from.role == 1) {
					$this.find(staff_writer).show();
				} else if (data.user_from.role == 3) {
					$this.find(featured_user).show();
				}
				
				if (!data.can_edit) {
					$this.find(edit_btn).hide();
				}
				if (!data.link_url) {
					$this.find(dropped_via).remove();
				}
							
				if (data.link_type == 'text') {
					$this.find(pinterest_btn).remove();
					$this.find('.textContainer').show().find('.large_text').html(data.content).end().find('.text_content').html(data.content);
					$this.find('.photoContainer').remove();
				} else {
					if (data.complete == '1') { //RR - sample element is inactive by default
						$this.find('.ext_share a').removeClass("inactive");
						$this.find('.ext_share a.share_fb_app').removeClass("disabled_bg");
					}

					if (data.link_type != 'embed' && data.complete) {
						$this.find('.photoContainer img.drop-preview-img').addClass('has_zooming');
					}

					$this.find('.photoContainer').show().find('img.drop-preview-img').addClass('imgLoaded');
					$this.find('.textContainer').remove();

					if (data.link_type == 'image') {
						$this.find('.photoContainer img.drop-preview-img').addClass('watermarked');
					}
					if (data.link_type == 'embed') {
						$this.find('.play_container').show();
					} else {
						$this.find('.play_container').remove();
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
		
		/*
		$('.post_col .like_text > a').on('click', function( e ) {
	  		if ($(this).attr('rel') !== 'popup') {
		  		e.preventDefault();
		  		document.location.href = $(this).attr('href');
		  	}
			return false;
		});
		*/
		
		/*
		$('.newsfeed_entry.tile_new_entry:not(#tile_preview)').on('mouseover', function(e){
			e.stopPropagation();
			var preview = $(this).clone().attr('id','tile_preview');
			preview.offset({top: $(this).offset().top-25, left: $(this).offset().left-25});
			$('body').append(preview);
			preview.show();
		});
		$('.newsfeed_entry.tile_new_entry, #tile_preview').on('mouseout', function(e){
			e.stopPropagation();
			console.log($(e.target).closest('#tile_preview'));
			if ($(e.target).is('#tile_preview') || $(e.target).closest('#tile_preview').length > 0) {
			
			} else {
				$('#tile_preview').hide().remove();
			}
		});
		*/
		
	return this;
});
