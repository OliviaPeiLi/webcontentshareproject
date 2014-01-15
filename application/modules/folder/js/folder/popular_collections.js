/**
 *  Logic for popular collections in homepage and landing page
 *  @uses common/autoscroll_new
 *  @uses jquery
 *  @link /
 */
define(['common/autoscroll_new', 'jquery'], function() {
	
	/* ================= Vars ================ */
	var container = '#popular_collections_list';
	var collections = container+' li.js-folder';
	var up_box = ' .collectionUpbox';
	var up_btn = ' .up_button';
	var unup_btn = ' .undo_up_button';
	var up_count = ' .up_count';
	var staff_writer = ' .js-staff_writer';
	var featured_user = ' .js-featured_user';
	var thumbs = ' .folder_pics_container .folder_item';
	var thumbs_text = ' .bookmarked_text';
	var thumbs_play = ' .play_button';
	var thumbs_img = ' img:not(.play_button)';
	
	/* ====================== Events ==================== */
	
	/**
	 * Redirects to a colleciton on click
	 */
	$(document).on('click', collections, function() {
		window.location = $(this).attr('data-url');
	});
	
	/**
	 * Dont redirect for upBox
	 */
	$(document).on('click', collections+up_box, function(e) { e.stopPropagation(); });
	
	/**
	 * Up button
	 */
	$(document).on('preAjax', collections+up_btn, function() {
		console.info('Up collection', this);
		//toggle buttons
		$(this).hide().parent().find(unup_btn).show();
		//update count
		$(this).parent().find(up_count).each(function() {
			$(this).text(parseInt($(this).text()) + 1);
		}) 
	});
	
	/**
	 * UnUp button
	 */
	$(document).on('preAjax', collections+unup_btn, function() {
		console.info('Up collection', this);
		//toggle buttons
		$(this).hide().parent().find(up_btn).show();
		//update count
		$(this).parent().find(up_count).each(function() {
			$(this).text(parseInt($(this).text()) - 1);
		}) 
	});
	
	/**
	 * Follow/Unfollow button on a collection.
	 * When you are viewing someone else's profile, you can follow or unfollow his collections
	 */
	 
	 var selector = '.folder_follow';

	$(document).on('preAjax', selector, function() {
		if (!php.userId) return;
		console.info('follow fast....');
		$(this).hide().parent().find('.folder_unfollow').css("display","block");
	}).on('click', selector, function(e) {
		e.stopPropagation();
	})

	var selector = '.folder_unfollow';

	$(document).on('preAjax', selector, function() {
		console.info('unfollow fast....');		
		$(this).hide().parent().find('.folder_follow').css("display","block");
	}).on('click', selector, function(e) {
		e.stopPropagation();
	})	
	
	$(document).on('scroll_bottom', container, function() {

		if (this.ajaxList_process instanceof Function) return;

		this.ajaxList_process = function(data) {

			if (data.is_liked) {
				$(this).find(up_box+up_btn).hide().end().find(up_box+unup_btn).show();
			} else {
				$(this).find(up_box+up_btn).show().end().find(up_box+unup_btn).hide();
			}

			if ( $('.pin-it-button',this).length > 0 && data.pinterest_url != "" )	{
 				$('.pin-it-button',this).removeClass("inactive");
			}
			
			if (data.role == '1') {
				$(this).find(staff_writer).show();
			} else if (data.role == '3') {
				$(this).find(featured_user).show();
			}
			
			var item, newsfeeds = data.recent_newsfeeds;
			var num_items = 0;

			for (var i=0;i<newsfeeds.length;i++) {

				item = $(this).find(thumbs+':nth-child('+(i+1)+')');
				
				if (newsfeeds[i].link_type == 'text') {
					num_items++;
					item.find(thumbs_text).show().html(newsfeeds[i].content);
				} else {
					item.find(thumbs_img).show().attr('src', newsfeeds[i]._img_bigsquare).attr('alt', newsfeeds[i].description_plain).attr('title', newsfeeds[i].description_plain);
					num_items++;
					if (newsfeeds[i].link_type == 'embed') {
						item.find(thumbs_play).show();
					}
				}

			}

			if (!php.userId)	{
				$('.follow_unfollow_btn .folder_unfollow',this).remove();
				$('.follow_unfollow_btn .folder_follow',this).attr("href","/signup?redirect_url="+escape(data._folder_url)).show();
			}

			if (typeof data.folder_uri != 'undefined') {
				$('.js-userdata > a',this).attr("href",data.folder_uri).html(data.first_name + " " + data.last_name).parent().show();
			}

			// http://dev.fantoon.com:8100/browse/FD-4171
			if (newsfeeds.length == 0)	{
				$('.collectionsTweets a',this).addClass("inactive").addClass("disabled_bg").addClass("disabled");
			}	else	{
				$('.collectionsTweets a',this).removeClass("inactive").removeClass("disabled_bg").removeClass("disabled");
			}

			// http://dev.fantoon.com:8100/browse/FD-3910
			if ( data.pinterest_url != "")	{
				$(this).find('.pin-it-button').removeClass("inactive");
			} else	{
				$(this).find('.pin-it-button').addClass("inactive");
			}

			if ( data.is_owned ) {
				$(this).find( '.follow_unfollow_btn' ).remove();
			} else {
				if ( data.is_followed ) {
					$(this).find( 'a.folder_follow' ).hide();
					$(this).find( 'a.folder_unfollow' ).css("display","block");
				} else {
					$(this).find( 'a.folder_follow' ).css("display","block");
					$(this).find( 'a.folder_unfollow' ).hide();
				}
			}

		}
	
	});

});