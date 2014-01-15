/** 
 * Code for the list of folders page.
 * Handles: redirect to folder on click, follow, unfollow, embed popup, delete folder
 * @package    Folder
 * @author     Radil, Dmitry
 * @link       /test_user1
 * @uses       jquery
 * @uses       
 on/autoscroll_new  - for collections autoscroll
 * @sinse 12/28/2012 - RR - removed 'newsfeed/newsfeed', as dependency
 */

define(["common/autoscroll_new", 'common/custom_title', 'jquery','social/all'], function() {

	/* =========================== Variables ========================= */
	var collections = '.folder_ugc';
	var hashtag = ' .collection_tags';
	
	var up_box = ' .upbox';
	var up_btn = ' .upvote';
	var unup_btn = ' .downvote';
	var up_count = ' .js_upvotes_count';
	
	/* ======================= Public Funcs =========================== */
	
	this.ajaxList_process = function ( data ) {
		
		var $this = $( this );

		if (!data.can_edit) {
			$this.find('a.edit').remove();
		}
		
		if (data.is_shared_fb) {
			$this.find('.fb_share_collection').addClass('disabled_bg');
		} else {
			$this.find('.fb_share_collection').removeClass('disabled_bg');
		}
		
		if (data.is_shared_twitter) {
			$this.find('.share_twt_app').addClass('inactive');
		} else {
			$this.find('.share_twt_app').removeClass('inactive');
		}

		if ( data.is_liked ) {

			// TODO: to do working with class all buttons, not with changing display attribute
			$this.find( '.upbox .upvote' ).hide();
			$this.find( '.upbox .downvote' ).css('display','inline-block');

			 // $this.find( '.upbox .upvote' ).addClass('uhidden');
			 // $this.find( '.upbox .downvote' ).removeClass('hidden');

		} else {

			$this.find( '.upbox .upvote' ).css('display','inline-block');
			$this.find( '.upbox .downvote' ).hide();;

			 // $this.find( '.upbox .upvote' ).removeClass('uhidden');
			 // $this.find( '.upbox .downvote' ).addClass('uhidden');

		}
		
		var hash_sample = $this.find('.hashtags a:first');
		for (var i in data.hashtags) {
			$this.find('.hashtags').append(
					hash_sample.clone()
							.attr('href', data.hashtags[i].replace('_hash_','/search?q=%23'))
							.text(data.hashtags[i].replace('_hash_','#'))
			)
		}

		// hash_sample.remove();

		if ($('#folders').hasClass("profile") && data.recent_newsfeeds.length > 0)	{
			$('.newsfeed_dropContent_container',this).show();
			$('.no_drops_in_list',this).hide();
		}	else if($('#folders').hasClass("profile") && data.recent_newsfeeds.length == 0)	{
			$('.newsfeed_dropContent_container',this).hide();
			$('.no_drops_in_list',this).show();
		}
		
		if (data.recent_newsfeeds[0]) {
			if (data.recent_newsfeeds[0].link_type == 'text') {
				$(this).find('.newsfeed_upperContent .text-container').show().html(data.recent_newsfeeds[0].text);
			} else {
				$(this).find('.newsfeed_upperContent .photo-container').show().find('img').attr('src', data.recent_newsfeeds[0]._img_thumb);
				if (data.recent_newsfeeds[0].link_type == 'image') {
					$(this).find('.newsfeed_upperContent .photo-container').addClass('watermarked');
				}
			}
		}
		
		for (var i=1;i<=3;i++) {
			if (data.recent_newsfeeds[i]) {
				if (data.recent_newsfeeds[i].link_type == 'text') {
					$(this).find('.newsfeed_lowerContent .span6:nth-child('+i+') .text-container').show().html(data.recent_newsfeeds[i].text);
				} else {
					$(this).find('.newsfeed_lowerContent .span6:nth-child('+i+') .photo-container').show().find('img').attr('src', data.recent_newsfeeds[i]._img_thumb);
					if (data.recent_newsfeeds[i].link_type == 'image') {
						$(this).find('.newsfeed_lowerContent .span6:nth-child('+i+') .photo-container').addClass('watermarked');
					}
				}				
			}
		}
		
		$this.find( 'a[href="#folder_popup_edit"]' ).parent().remove();
		
	};

	/* =============== Events ========================================= */
	/**
	 * Redirect to the folder when the user clicks on it.
	 */

	var selector = collections+ ' .newsfeed_dropContent, li.folder_ugc h2';

	$(document).on('click', selector, function(e) {

		var play_button = $('.play_button:visible',$(e.target).closest(".newsfeed_upperContent"));
		
		if ( play_button.length )	{
			// play video
			play_button.hide();
			var thumb = $('.newsfeed_upperContent img.drop-preview-img',this);
			$.get(thumb.attr("data-vurl"),function(data){
				var obj = $(data).find('iframe, object, embed').first();
					// remove parent to fit with previous image from width and height
					obj.width(thumb.closest('.photo-container').width())
					   .height(thumb.closest('.photo-container').height())
					   .addClass('ft-video-iframe');
					thumb.hide().after(obj);
			});
		}	else {
			window.location.href = $(this).closest('.folder_ugc').find('h2 a').attr('href');
		}

		return false;
	});
	
	/**
	 * Dont redirect for upBox
	 */
	 
	// $(collections+up_box).on('click', function(e) { e.stopPropagation(); });
	
	/**
	 * Up button
	 */
	$(document).on('preAjax', collections + up_btn + ", #folder_ugc_top" + up_btn, function() {
		console.info('Up collection', this);
		$(this).hide().parent().find(unup_btn).show();
		$(this).parent().find(up_count).each(function() {
			$(this).text(parseInt($(this).text()) + 1);
		});

	});
	
	/**
	 * UnUp button
	 */
	$(document).on('preAjax', collections + unup_btn + ", #folder_ugc_top" + unup_btn, function() {
		console.info('Down collection', this);
		// $(this).addClass('hidden').parent().find(up_btn).removeClass('hidden');
		$(this).hide().parent().find(up_btn).show();
		$(this).parent().find(up_count).each(function() {
			$(this).text(parseInt($(this).text()) - 1);
		}) 
	});
	
	/**
	 * Embed popup
	 * @since 8/3/2012 RR - added sample textarea with the text because once {folder_id} is replaced its not updated
	 */
	$(document).on('before_show', '.folder_embed', function(ui, content) {
		var folder_id = $(this).closest('[data-folder_id]').attr('data-folder_id');
		$('#embed_collection_overview #embed_code').text(
					$('#embed_collection_overview textarea.sample')
						.text().replace('{folder_id}',folder_id)
				);
	});
	
	/**
	 * Embed popup - selects the whole textarea contents on click
	 */
	$(document).on('click', '#embed_code', function(){
		$(this).select(); return false;
	});
	
	/**
	 * Make collection hashtag clickable
	 */
	$(document).on('click',collections+hashtag, function(e) {
		e.stopPropagation();
	});

	$( document ).on( 'scroll_bottom', '#folders > ul.fd-autoscroll', function() {
		if (typeof this.ajaxList_process != 'function') this.ajaxList_process = ajaxList_process;
	});
	
	/* RR - doesnt appear to be used
	// THE FOLLOWING 3 BLOCKS BELOW ARE FOR MIXPANEL ONLY
	 $('#create_fb_collection').on('click', function() {
		//Mixpanel tracking
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			mixpanel.people.identify(user);
			mixpanel.track('Create FB Colection', {'user':user});
		}
	});
	$('#create_twtr_collection').on('click', function() {
		//Mixpanel tracking
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			mixpanel.people.identify(user);
			mixpanel.track('Create TWITTER Colection', {'user':user});
		}
	});
	//END MIXPANEL
	*/

	/** comment section */
	
	return this;
});
