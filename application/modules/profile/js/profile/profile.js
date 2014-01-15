/*
 * Profile/User collections page - Profile top
 * @link /test_user1
 * @uses jquery
 * @usess social/all - for the connect to fb button
 * @uses common/autoscroll_new - to show more collections on scrolldown
 */
define(["social/all", "common/autoscroll_new", 'jquery'], function() {
	
	/* ======================== Vars ====================== */
	var connect_fb_btn = '#profile_fb_1.disconnected';
	var about = '#profile_about';
	var about_cnt = about+' .profile_about_text_container';
	var about_txt = about+' .profile_about_text';
	var about_more = '#profile_about_more';
	
	/* ========================== Events ============================== */

	/**
	 * Unfollow button on a collection item when viewing someone elses profile
	 */
	$(document).on('success','#profile_buttons .request_unfollow', function(e,m){
		$('#all_folders').find('.js-folder .folder_unfollow').hide();
		$('#all_folders').find('.js-folder .folder_follow').show();

		// reduce follower counter
		var $follower_count = $('#profile_button_holder .followingFollowers_top_stat:last .top_stats_number');
		$follower_count.html( parseInt($follower_count.html()) - 1 );
	});
	
	/**
	 * Follow button on a collection. When viewing someone elses profile
	 */
	$(document).on('success','#profile_buttons .request_follow', function(e,m){
		$('#all_folders').find('.js-folder .folder_follow').hide();
		$('#all_folders').find('.js-folder .folder_unfollow').show();

		// increase follower counter
		var $follower_count = $('#profile_button_holder .followingFollowers_top_stat:last .top_stats_number');
		$follower_count.html( parseInt($follower_count.html()) + 1 );
	});
	
	$(document).on('click', about_more, function() {
		var container = $(this);
		if (container.hasClass('open')) {
			var final_height = 28;			
			$(this).text('+ More');
			container.removeClass('open');
		} else {
			var final_height = container[0].scrollHeight;
			$(this).text('- Less');
			container.addClass('open');
		}
		container.animate({
			'height': final_height
		});
	});
	
	/**
	 * Specific delete drop code for profile top view 
	 * @to-do - need to remake current collection box that contains the remove newsfeed.
	 */
	$(document).on('preAjax','#delete_dialog .delete_yes', function(){
	    var newsfeed_id = $(this).attr('href').replace('/del_link/','');
	    console.info('{Delete drop} - check for profile drops ', newsfeed_id);
	    // remove the drop from profile_drops
	    var recent_drop = $('#profile_drops .profile_drop[data-newsfeed_id="'+newsfeed_id+'"]');
	    if (!recent_drop.length) return;
	    
	    $('#profile_drops .profile_drop[data-newsfeed_id="'+newsfeed_id+'"]').remove();
			//load one more
			$.get('/get_feature_drops', function (data) {
				if ( data !== '' ) {
					$('#profile_drops .profile_drop[data-url]:last').after( data );
				} else {
					// add a blank item
					$('#profile_drops .profile_drop[data-url]:last').after('<li class="profile_drop inlinediv blank"></li>');
				}
			});
	 })
	
	 /**
	  * Connect to fb button
	  */
	$(document).on('click', connect_fb_btn, function() {
		var $this = $(this);
		fb_login(function(auth) {
			$this.removeClass('disconnected').addClass('connected').attr('href', 'http://www.facebook.com/profile.php?id='+user_data.id);
		});
		return false;
	})
	
	/* ===================== Direct code ========================== */
	if ( $(about_txt).height() < $(about_cnt).height()) {
		$(about_more).hide();
	} else { // FD-3675, put about_more as hidden in default
		$(about_more).show();
	}

});
