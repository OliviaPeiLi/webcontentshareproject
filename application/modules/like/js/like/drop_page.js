/**
 * JS logic for Up/Down vote for drop page and preview popup
 * @link /
 * @see newsfeed/drop_page.js
 * @see newsfeed/drop_preview_popup.js
 * @uses jquery
 */
define(['jquery'], function(){
	
	var selector = '#preview_popup .upbox .up_button, #content.drop-page .upbox .up_button';

	$(document).on('preAjax', selector, function(event, msg) {

		console.log('newsfeed_popup_gen like');
		
		var undo_btn = $(this).closest('.upbox').find('.undo_up_button');
		var up_btn = $(this);
		
		up_btn.hide();
		undo_btn.show();
		
		$('.ui-effects-wrapper').remove();

		var new_count = parseInt( $('#preview_popup .upbox .up_count, #content.drop-page .upbox .up_count').text() )+1;
		$('#preview_popup .upbox .up_count, #content.drop-page .upbox .up_count').text(new_count);
		//Modify newsfeed
		var newsfeed_id = $(this).closest('[data-newsfeed_id]').attr('data-newsfeed_id');
		//wrong selector for newsfeed FD-2709
		var newsfeed_item = $('.newsfeed_entry[data-newsfeed_id='+newsfeed_id+']');
		newsfeed_item.find('.upbox .up_button').hide();
		newsfeed_item.find('.upbox .undo_up_button').show();
		newsfeed_item.find('.upbox .up_count').text(new_count);
		newsfeed_item.find('.upbox .up_count').removeClass('like').addClass('unlike');

		// add image to right panel likes only if not liked yet

		if ($('.popup_right-likes li:not(.sample) a[data-user_id="' + php.userId + '"]').length == 0) {

			var you = $('.popup_right-likes .sample').clone();
			you.removeClass('sample');
			$('.popup_right-likes ul').prepend(you);
			you.show('fade');
			if ($('.popup_right-likes:hidden').length > 0) {
					$('.popup_right-likes ul .no_likes').hide();
				$('.popup_right-likes').show('fade');
			}

		}

		$('.profile_drop[data-newsfeed_id='+newsfeed_id+']').find('.upbox .up_count').removeClass('like').addClass('unlike').text(new_count);
	})
	
	/*
	 * News posts undo Like invoked by clicking on 'Unlike' link or 'Unlike' button in the like box
	 */
	 var selector = '#preview_popup .upbox .undo_up_button, #content.drop-page .upbox .undo_up_button';

	$(document).on('preAjax', selector, function(event, msg) {

		console.info('newsfeed_popup_gen unlike');
		
		$(this).hide();
		$(this).closest('.upbox').find('.up_button').show();
		$('.ui-effects-wrapper').remove();

		// if myself is hidden, the +more count is updated
		var myself = $('.popup_right-likes li:not(.sample) [data-user_id='+php.userId+']');
		if ( myself.length <= 0 ) {
			var like_more_count = $('#preview_popup #like_more_count');
			like_more_count.html( parseInt(like_more_count.html()) - 1 );
		}

		//unlike logic
		$('.popup_right-likes ul li:not(.sample)').find('[data-user_id='+php.userId+']').parent().hide('fade').remove();
		if ($('.popup_right-likes ul li:not(.sample):not(.no_likes)').length <= 0) {
				//$('.popup_right-likes ul .no_likes').show();
			$('.popup_right-likes').hide('fade');
		}
		
		var curr_count = parseInt( $('#content.drop-page .upbox .up_count').length ? $('#content.drop-page .upbox .up_count').text() : $('#preview_popup .upbox .up_count').text() );
		var new_count = curr_count > 0 ? curr_count-1 : curr_count;
		$('#preview_popup .upbox .up_count, #content.drop-page .upbox .up_count').text(new_count);
		//Modify newsfeed
		var newsfeed_id = $(this).closest('[data-newsfeed_id]').attr('data-newsfeed_id');
		//wrong selector for newsfeed FD-2709
		var newsfeed_item = $('.newsfeed_entry[data-newsfeed_id='+newsfeed_id+']');
		newsfeed_item.find('.upbox .undo_up_button').hide();
		newsfeed_item.find('.upbox .up_button').show();
		newsfeed_item.find('.upbox .up_count').text(new_count); 
		newsfeed_item.find('.upbox .up_count').removeClass('unlike').addClass('like');
		$('.profile_drop[data-newsfeed_id='+newsfeed_id+']').find('.upbox .up_count').removeClass('unlike').addClass('like').text(new_count);

	});
		
});
