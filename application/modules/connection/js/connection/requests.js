/**
 * Requests
 * Logic for handling Follow/Unfollow user/interest and accept users
 *  invoked by clicking on 'Follow' and 'Unfollow' buttons
 */
define(['jquery'], function(){

	function disable_follow_all_button()	{

	}

	//follow/unfollow USER
	$(document).on('preAjax','.request_follow', function(event, data) {
		console.log('request follow');
		$('')
		if (php.userId) {
	   	$(this).hide().parent().find('.request_unfollow').show();

		if ( !$('ol.invitesList .request_follow:visible').length )	{
			$('.request_follow_all').addClass("disabled_button");
		}

	   }
	});	

	$(document).on('preAjax', '.request_unfollow', function(event, data) {
		console.log('request unfollow');
		if (php.userId) {
	   	$(this).hide().parent().find('.request_follow').show();

		 if ( $('ol.invitesList .request_follow:visible').length )	{
		 	$('.request_follow_all').removeClass("disabled_button");
		 }

	   }
	});	
	
});
