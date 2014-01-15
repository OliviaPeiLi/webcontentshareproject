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
	
	/* ===================== Events =========================== */
	
	$(document).on('success', social_container+share_enable, function() {
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
	$(document).on('click', fb_connect, function(){
		$('#error_fb, #error_twitter').hide();
		fb_login(function(auth) {
			$([fb_disconnect, fb_share_disable].join(',')).show();
			$([fb_connect, fb_share_enable].join(',')).hide();
		}, function (error) {
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

	$(document).on('success', fb_confirm_yes, function() {
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
	$(document).on('success','#accountSettings form', function(e, data) {
		$(this).find('.account_ok, .account_err').hide();
		if (!data.status) {
			$(this).find('.account_ok').hide();
			$(this).find('.account_err')/*.text(data.error)*/.show('fade');
			return;
		}

		$(this).find('.account_err').hide();
		$(this).find('.account_ok').show('fade');
		
		if ($(this).find('[name=uri_name]').length) {
			$('#profile_name a').attr("href",php.baseUrl+$(this).find('[name=uri_name]').val());
			$('a.js-profile_url').attr('href',php.baseUrl+$(this).find('[name=uri_name]').val());
		}
	});
	
	/**
	 * Add more schools button in the "Education" container
	 * @since 4/23/2013 - schools are disabled
	 */
	//$('#addSchoolForm_lnk').on('click', function() {
	//	$(this).hide('fade');
	//	$('#add_new_school').show('fade');
	//});
	
	/**
	 * Save school in the form which appears on Edit
	 * @to-do preAjax should use the default hidden inputs added by tokenList
	 * @to-do move to json response
	 * @to-do merge with add new school form
	 * @since 4/23/2013 - schools are disabled
	 */
	//$('#school_entry_form, #school_edit_entry_form')
	//	.on('preAjax', function() {
	//
	//		var form_entry = $(this);
	//
	//		var schools = JSON.stringify(form_entry.find('input[name=school]').tokenInput('get'));
	//		if (form_entry.find('input[name=school]').length) {
	//			form_entry.find('input[name=school]').val(schools);
	//		} else {
	//			form_entry.append("<input type='hidden' name='school' value='"+schools+"'>");
	//		}
	//
	//		var year = form_entry.find('select[name=school_year] option:selected').text();
	//		form_entry.find('input[name=year]').val(year);
	//		
	//		var majors = JSON.stringify(form_entry.find('input[name=major]').tokenInput('get'));
	//		if (form_entry.find('input[name=major]').length) {
	//			form_entry.find('input[name=major]').val(majors);
	//		} else {
	//			form_entry.append("<input type='hidden' name='major' value='"+majors+"'>");
	//		}
	//
	//	})
	//	.on('success', function(event, data) {
	//		var $self = $(this);
	//	 	$self.find('input[name=school][type=hidden]').remove();
	//		$self.find('input[name=major][type=hidden]').remove();
	//		if( /^{/g.test(data) ) data = JSON.parse(data);
	//		if(typeof data == 'object' && data.success === false)
	//		{
	//			//BP: FD-3074
	//			//$(document).trigger('popup_info', data.error);
	//			if ( data.status ) {
	//				$self.find( '.account_err' ).hide();
	//				$self.find( '.account_ok' ).show( 'fade' );
	//			}
	//			else {
	//				//BP: #FD-2996, IMO this is better fixed in the backend, but I don't want to risk breaking something else
	//				if ( data.error && data.error.charAt( 0 ) == '<' ) {
	//					data.error = $( data.error ).text();
	//				}
	//				//end of #FD-2996
	//				$self.find( '.account_err' ).text( data.error ).show( 'fade' );
	//				$self.find( '.account_ok' ).hide();
	//			}
	//			//end of FD-3074
	//			$('#addSchoolForm_lnk').hide('fade');
	//		} else
	//		{
	//			if( $self.is('#school_edit_entry_form') ) $self.closest('.school_entry').remove();
	//			$self.find('input[name=school][type=text]').tokenInput('clear');
	//			$self.find('input[name=major][type=text]').tokenInput('clear');
	//			$self.find('select[name=school_year]').val(0);
	//			$('#school_entry_list').append(data);
	//			$('#add_new_school').hide('fade');
	//			$('#addSchoolForm_lnk').show('fade');
	//		}
	//	});

	/**
	 * Success event of the form which appears after "Add more schools" button click
	 * @to-do - Use sample element for that form
	 * @since 4/23/2013 - schools are disabled
	 */
	//$('.school_edit_entry_form').on('success', function(event,data){
	//	var entry = $(this).closest('.school_entry');
	//	entry.html(data);
	//	return false;
	//});

	/**
	 * Remove already added school
	 * @to-do - change to ajaxButton
	 * @since 4/23/2013 - schools are disabled
	 */
	//$('.school_entry .removeSchool').on('click',function() {
	//	var school_id = $(this).closest('.school_entry').attr('rel');
	//	var data = {
	//		'id': school_id,
	//		ci_csrf_token: $("input[name=ci_csrf_token]").val()
	//	};
	//	$.post("/del_school" , { ci_csrf_token: $("input[name=ci_csrf_token]").val(),'id': school_id},function(alive){if(alive=='removed'){
	//			$('#user_school_'+school_id).hide('fade').remove();
	//		}
	//	});
	//	return false;
	//});
	
	/**
	 * Edit already added school
	 * @to-do - optimize
	 * @since 4/23/2013 - schools are disabled
	 */
	//$('.school_entry .editSchool').on('click',function() {
	//	$.fn.initTokenInput();
	//	$(this).closest('.school_entry').find('.school_view_mode').hide('fade');
	//	$(this).closest('.school_entry').find('.school_edit_mode').delay(500).show('fade');
	//	$(this).hide('fade');
	//	$(this).closest('.school_entry').find('.updateSchool').show('fade');
	//	$(this).closest('.school_entry').find('.cancelUpdateSchool').show('fade');
	//	return false;
	//});
	
	/**
	 * Cancel button in the Edit schoool form
	 * @to-do - optimize
	 * @since 4/23/2013 - schools are disabled
	 */
	//$('.school_entry .cancelUpdateSchool').on('click',function() {
	//	$(this).closest('.school_entry').find('.school_edit_mode').hide('fade');
	//	$(this).closest('.school_entry').find('.school_view_mode').delay(500).show('fade');
	//	$(this).hide('fade');
	//	$(this).closest('.school_entry').find('.updateSchool').hide('fade');
	//	$(this).closest('.school_entry').find('.editSchool').show('fade');
	//	return false;
	//});

});
