/**
 * JS logic for Up/Down vote for newsfeed
 * @link /
 * @see newsfeed/newsfeed.js
 * @uses jquery
 */
define(['jquery'], function() {
	/*
	 * Up vote drop
	 */

	 var selector = '#list_newsfeed .upbox .up_button';

	$(document)
		.on('preAjax', selector, function() {
			console.info('{newsfeed} - like fast');
			var up_count = parseInt($(this).parent().find('.up_count').text()) || 0;
			$(this).parent().find('.up_count').removeClass('like').addClass('unlike');
			$(this).parent()
				.find('.undo_up_button').show()
				.end().find('.up_button').hide()
				.end().find('.up_count').html(up_count + 1)
		})
		.on('success', selector, function(event, msg) {
			console.log('{newsfeed} - like success');
			if ($(this).closest('.newsfeed_entry_comment').find('.like_text').length > 0) {
				$(this).closest('.newsfeed_entry_comment').find('.like_text').html(msg.html);
			}
			$('.ui-effects-wrapper').remove();
		})
		
	/*
	 * Downvote drop
	 */
	 var selector = '#list_newsfeed .upbox .undo_up_button';
	$(document)
		.on('preAjax',selector, function() {
			console.info('{newsfeed} - unlike fast');
			var up_count = parseInt($(this).parent().find('.up_count').text()) || 1;
			$(this).parent().find('.up_count').removeClass('unlike').addClass('like');
			$(this).parent()
				.find('.undo_up_button').hide()
				.end().find('.up_button').show()
				.end().find('.up_count').html(up_count - 1)
		})
		.on('success', selector, function(event, msg) {
			if ($(this).closest('.newsfeed_entry_comment').find('.like_text').length > 0) {
				$(this).closest('.newsfeed_entry_comment').find('.like_text').html(msg.html);
			}
			$('.ui-effects-wrapper').remove();
		});
});