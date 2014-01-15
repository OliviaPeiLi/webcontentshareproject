/**
 * Logic for profile settings page
 * @link /account_options
 * @uses jquery
 */
define(["social/all", "jquery"], function() {
	
	var social_container = '#sharing_options';
	var share_enable = ' .sharelink_enable';
	var share_disable = ' .sharelink_disable';
	
	var fb_container = '#fb_connect_area';
	var fb_connect = fb_container+' .account_soc_connect';
	var fb_disconnect = fb_container+' .account_soc_disconnect';
	var fb_share_enable = fb_container+share_enable;
	var fb_share_disable = fb_container+share_disable;
	var fb_confirm = '#disconnect_fb_confirmation';
	var fb_confirm_yes = fb_confirm+' .confirm_yes';
	var fb_disconnect_button = fb_container + ' a[href="#disconnect_fb_confirmation"]';
	
	var twt_container = '#twitter_connect_area';
	var twt_connect = twt_container+' .account_soc_connect';
	var twt_disconnect = twt_container+' .account_soc_disconnect';
	var twt_share_enable = twt_container+share_enable;
	var twt_share_disable = twt_container+share_disable;
	var username = $('#settings [name=uri_name]').val();

	/* ===================== Events =========================== */
	
	$(document).on('success',social_container+share_enable, function() {
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			mixpanel.track('Social Share ON', {'user':user});
		}
		$(this).hide().parent().find(share_disable).show();
	});

	$(document).on('success',social_container+share_disable, function() {
		if (typeof(mixpanel) !== 'undefined') {
			var user = php.userId ? php.userId : 0;
			mixpanel.track('Social Share OFF', {'user':user});
		}
		$(this).hide().parent().find(share_enable).show();
	});	
	
	/**
	 * Executes fb login popup on "connect to fb" button click
	 * @uses fblogin()
	 */
	 var fb_error = false;
	$(document).on('click',fb_connect, function(){
		$('#error_fb, #error_twitter').hide();
		fb_login(function(auth) {
			if (!fb_error)	{
				$([fb_disconnect, fb_share_disable].join(',')).show();
				$([fb_connect, fb_share_enable].join(',')).hide();
				fb_error = false;
			}
		}, function (error) {
			fb_error = true;
			$('#fb_alert').html(error);
		});
		return false;
	});

	// disconnect with facebook - disable popup confirmation
	$(document).on('success','#fb_disconnect_wh_password', function(){
		$(this).hide();
		$(fb_connect).show();
		$( fb_container + ' .sharelink_disable').hide();
		return false;
	});

	$(document).on('success',fb_confirm_yes, function() {
		$(this).closest(fb_confirm).modal('hide');
		$([fb_disconnect, fb_share_enable, fb_share_disable].join(',')).hide();
		$(fb_connect).show();
	});
	
	/**
	 * Connect to twitter button
	 * @uses - openTwitter()
	 */
	$(document).on('click',twt_connect, function() {
		$('#error_fb, #error_twitter').hide();
		twt_login(function(id) {
			$([twt_disconnect, twt_share_disable].join(',')).show();
			$([twt_connect, twt_share_enable].join(',')).hide();
		}, function(msg) {
			$('#error_twitter').text(response.msg).show();
		})
		return false;
	});

	$(document).on('success', twt_disconnect, function() {
		$([twt_disconnect, twt_share_enable, twt_share_disable].join(',')).hide();
		$(twt_connect).show();
	});
	
	/**
	 * Success event for all forms in the page. Error handling only
	 */
	$(document).on('success', '#settings form', function(e, data) {

		$(this).find('.account_ok, .account_err').hide();
		
		if (!data.status) {
			$(this).find('.account_ok').hide();
			// $(this).find('.account_err')/*.text(data.error)*/.show('fade');
			return;
		}
		
		// $(this).find('.account_err').hide();
		// $(this).find('.status_ok').show('fade').delay(3000).fadeOut();
		$(this).find('input[type=submit]').animate({'opacity':'0.4'},200).delay(3000).animate({'opacity':'1'});
		
		if ($(this).attr("id") == 'account_basic')	{

			var val = $(this).find('[name=uri_name]').val();
			var firstname = $(this).find('[name=first_name]').val();
			var lastname  = $(this).find('[name=last_name]').val();

			if ( val != username ) {
				var _this = this;
				$('#mainProfileInfo a.followButton, #mainProfileInfo .pageSelect_button').each(function(){
					$(this).attr("href",$(this).attr("href").replace( username, val ) );
				});
				username = val;
			}

			// update account_link (profile)
			$('#account_link').attr('href', '/' + val);
			$('#account_options .list_url').attr('href', '/' + val);
			$('#account_link .header_userName').text(firstname + ' ' + lastname);

		}

		// reset form after change
		if ($(this).attr("id") == 'change_password')	{
			$(this).get(0).reset();
		}

		if ($('textarea[name=about]',this).length)	{
			var _p = $('#mainProfileInfo .currentUser_panel_bio > p');
				_p.html( $('textarea[name=about]',this).val() );
		}		
		
	});
	
});
