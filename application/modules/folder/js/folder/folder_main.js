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

define(["common/autoscroll_new", 'jquery'], function() {

	/* =========================== Variables ========================= */
	var collections = '.js-folder';
	var hashtag = ' .collection_tags';
	var up_box = ' .collectionUpbox';
	var up_btn = ' .up_button';
	var unup_btn = ' .undo_up_button';
	var up_count = ' .up_count';
	
	/* ======================= Public Funcs =========================== */
	
	this.ajaxList_process = function ( data ) {
		
		"use strict";

		var $this = $( this );

		if ( data.is_liked ) {
			$this.find( '.up_button' ).hide();
			$this.find( '.undo_up_button' ).show();
		} else {
			$this.find( '.up_button' ).show();
			$this.find( '.undo_up_button' ).hide();
		}

		if ( data['private'] == '0' ) {
			$this.find( '.private_icon' ).hide();
		}

		if ( data.contributors === undefined || data.contributors.length == 0 ) {
			$this.find( '.shared_icon' ).hide();
		}

		if ( data.is_open == '0' ) {
			$this.find( '.open_icon' ).hide();
		}

		$this.find( '.folder_item' ).each(function(i) {

			if ( typeof (data.recent_newsfeeds[i]) == 'undefined' ) {
				$(this).find( '.bookmarked_text, img' ).remove();
				console.info('not found: ', i, this);
				return;
			}

			var item = data.recent_newsfeeds[i];
			console.info('item: ', item);
			
			if (item.link_type == 'text' ) {
				$(this).find( 'img' ).remove();
				$(this).find( '.bookmarked_text' ).html( item.content );
			} else {
				$(this).find('img').attr( { src: item._img_bigsquare, alt: item.description_plain, title: item.descripion_plain } );
				$(this).find('.bookmarked_text').remove();
				if ( item.link_type == 'embed' ) {
					$(this).addClass('collection_play_button');
				} else {
					$(this).find('.play_button').remove();
				}
			}

		});

		if (typeof data.folder_url == 'undefined') {
			$('.js_folder_user_name',this).attr("href",data._folder_url).html(data.first_name + " " + data.last_name).parent().show();
		}
		
		// http://dev.fantoon.com:8100/browse/FD-4171
		if (data.recent_newsfeeds.length == 0) {
			$('.collectionsTweets a',this).addClass("inactive").addClass("disabled_bg").addClass("disabled");
		} else {
			$('.collectionsTweets a',this).removeClass("inactive").removeClass("disabled_bg").removeClass("disabled");
		}

		// http://dev.fantoon.com:8100/browse/FD-3910
		if ( data.pinterest_url != "")	{
			$this.find('.pin-it-button').removeClass("inactive");
		} else	{
			$this.find('.pin-it-button').addClass("inactive");
		}

		if (! data.is_in_progress) {
			$this.find( '.collections_transfer_message' ).hide();
		}

		if ( data.is_open == '0' || ( data.is_owned && data.editable == '1' ) ) {
			$this.find( 'ul.folder_buttons_menu' ).removeClass( 'small_btns' );
		}

		if ( data.is_followed || data.is_owned ) {
			if ( data.is_open == '0' ) {
				$this.find( 'a[href="#drop_into_folder_popup"]' ).parent().remove();
			}
			if ( !data.is_owned || data.editable == '0' ) {
				$this.find( 'a[href="#edit_folder_popup"]' ).parent().remove();
				$this.find( 'a[href="#delete_folder"]' ).parent().remove();
			}
		} else {
			$this.find( '.folder_menu_trigger' ).remove();
		}

		if ( data.is_owned ) {
			$this.find( '.follow_unfollow_btn' ).remove();
		} else {
			if ( data.is_followed ) {
				$this.find( 'a.folder_follow' ).hide();
				$this.find( 'a.folder_unfollow' ).css("display","block");
			} else {
				$this.find( 'a.folder_follow' ).css("display","block");
				$this.find( 'a.folder_unfollow' ).hide();
			}
		}
	};

	/* =============== Events ========================================= */
	/**
	 * Redirect to the folder when the user clicks on it.
	 */
	$(document).on('click', '.js-folder.expandable_folder:not(.unclickable)', function() {
		window.location.href = $(this).attr('data-url');
		return false;
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
		
	/**
	 * Delete folder - opens confirm popup
	 */
	var selector = '.folder_buttons .folder_delete';

	$(document).on('before_show', selector, function(ui, content) {		
		var $this = $(this);
		content.find('.delete_yes')
			.attr('href', '/delete_folder/'+$this.closest('.js-folder').attr('data-folder_id') )
			.unbind('preAjax').bind('preAjax', function() {
				$this.closest('.js-folder').hide('fade').remove();
				if ($this.closest('.js-folder').attr('data-rss_source_id') == '1') {
					$('#create_new_collection_folder [data-rss_source_id=1]').removeClass('disabled');
				} else if ($this.closest('.js-folder').attr('data-rss_source_id') == '2') {
					$('#create_new_collection_folder [data-rss_source_id=2]').removeClass('disabled');
				}
				content.modal('hide');
				$("#created_collection_msg").hide();
			}).unbind("success").bind("success",function() {
				//RR - ?!?
				//if ($($this).hasClass("js_folder_list")) {
				//	window.location.reload();
				//}
				//return false;
			});
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
	$(document).on('click', collections+hashtag, function(e) {
		e.stopPropagation();
	});

	$( document ).on( 'scroll_bottom', '#all_folders', function() {
		this.ajaxList_process = ajaxList_process;
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

	return this;
});
