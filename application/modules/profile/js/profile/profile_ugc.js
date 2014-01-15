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
	$(document).on('success','#profile .currentUser_panelUnfollow', function(e,m){
		$(this).hide();
		$(this).next(".currentUser_panelFollow").show();

		// reduce follower counter
		var following_obj = $('#profile .followingButton span').eq(0);
			following_obj.text( parseInt(following_obj.text()) - 1 );

	});
	
	/**
	 * Follow button on a collection. When viewing someone elses profile
	 */
	$(document).on('success', '#profile .currentUser_panelFollow', function(e,m){

		$(this).hide();
		$(this).prev(".currentUser_panelUnfollow").show();

		// increase follower counter
		var following_obj = $('#profile .followingButton span').eq(0);
			following_obj.text( parseInt(following_obj.text()) + 1 );
		
	});
	
	/*
	$(about_more).on('click', function() {
		var container = $(about_cnt);
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
	*/

	/**
	 * Specific delete drop code for profile top view 
	 * @to-do - need to remake current collection box that contains the remove newsfeed.
	 */
	 /*
	$('#delete_dialog .delete_yes').on('preAjax', function(){
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
	*/
	 /**
	  * Connect to fb button
	  */
	  /*
	$(connect_fb_btn).on('click', function() {
		var $this = $(this);
		fb_login(function(auth) {
			$this.removeClass('disconnected').addClass('connected').attr('href', 'http://www.facebook.com/profile.php?id='+user_data.id);
		});
		return false;
	})
	*/
	
	/* ===================== Direct code ========================== */
	/*
	if ( $(about_txt).height() < $(about_cnt).height()) {
		$(about_more).hide();
	} else { // FD-3675, put about_more as hidden in default
		$(about_more).show();
	}
	*/
});
