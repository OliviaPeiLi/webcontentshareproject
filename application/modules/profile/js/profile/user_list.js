/**
 * Js loggic for the user list
 * @link: /search/people?q=a
 * @link: /followers/{user_id}
 * @link: /followings/{user_id}
 * 
 * @uses jquery
 * @uses common/autoscroll_new - for automatic loading of more followers on scroll down
 */
define(["common/autoscroll_new", "jquery"], function() {
	
	var container = '#following'
	
	$(document).on('scroll_bottom', container, function() {
		if (this.ajaxList_process instanceof Function) return;
		this.ajaxList_process = function(data) {
			if (data.is_following) {
				$(this).find('.request_follow').hide().parent().find('.unfollow_button ').show();
			}
		}
	});

	$(document).on('success','.unfollow_button',function(){
		$(this).hide().parent().find('.request_follow').show();
	});
	
	$(document).on('success','.request_follow',function(){
		$(this).hide().parent().find('.unfollow_button').show();
	});

});