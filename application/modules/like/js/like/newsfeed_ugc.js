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
	$(document)
		.on('preAjax', '#list_newsfeed .upvote, #folders .upvote', function() {
			console.info('{newsfeed_ugc} - like fast');
			var up_count = parseInt($(this).parent().find('.js_upvotes_count').eq(0).text()) || 0;
			$(this).parent().find('.js_upvotes_count').removeClass('like').addClass('unlike');
			$(this).parent()
				.find('.downvote').show()
				.end().find('.upvote').hide()
				.end().find('.js_upvotes_count').html(up_count + 1)
		});
	
	/*
	 * Downvote drop
	 */
	$(document)
		.on('preAjax', '#list_newsfeed .downvote, #folders .downvote', function() {
			console.info('{newsfeed_ugc} - unlike fast');
			var up_count = parseInt($(this).parent().find('.js_upvotes_count').eq(0).text()) || 1;
			$(this).parent().find('.js_upvotes_count').removeClass('unlike').addClass('like');
			$(this).parent()
				.find('.downvote').hide()
				.end().find('.upvote').show()
				.end().find('.js_upvotes_count').html( up_count - 1)
		});

});