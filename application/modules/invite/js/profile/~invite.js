/*
 * Js logic for invite page and signup step4
 * 
 * @link /invites
 * @link /signup_invite
 * 
 * @uses jquery
 * @uses common/formValidation - by the invite by email form
 * @uses common/popup_info - to display success / fail
 * @to-do need to move popup_info to a popup inside the html - to be easier to maintain by michael
 */
define(['social/fb_app_share', 'common/formValidation', 'common/popup_info', 'jquery'], function(fb_app){
	
	/* ====================== Vars ========================== */
	
	var position_interval; //Used for positioning the fb dialog over the invite all btn @deprecated
	 	php.fb_invite = false;

	/* ===================== Private functions ============================= */
		
	/**
	 * Positions fb invite dialog over the ivite button with opacity 0
	 * @deprecated
	 */
	function position_fb_dialog() {
		$('#fb-root').show().css({'opacity':0})
		var dialog;
		$('#fb-root .fb_dialog_advanced').each(function() {
			if ($(this).offset().top >= 0) dialog = $(this);
		});
		if (!dialog) {
			console.info('You cant invite users from fb.');
			return;
		}
		
		dialog.find('iframe').width(200).height(36);
		//console.info();
		dialog.find('.fb_dialog_close_icon').hide();
		var left = $('.fb_invite_all_btn').offset().left-30;
		var top = $('.fb_invite_all_btn').offset().top - 2;
		if (dialog.offset().left != left || dialog.offset().top != top) { //reposition
			dialog.css({'padding': 0,'left': left,'top': top});
		} else { //loaded
			$('.fb_invite_all_btn').removeClass('loading');
		}
		
	}
	
	/**
	 * Triggers invite friends dialog. Executed after clicking "Invite all" button
	 * @see $('.fb_invite_all_btn').on('click'
	 */
	function update_fb_dialog() {
		if (typeof FB == 'undefined') {
			window.setTimeout(function() { fblogin(); }, 100);
			return;
		}
		var fb_friend_data = [];
		var fb_friend_id = [];
		$('.friend-list input:checkbox:checked').each(function() {
			if (fb_friend_id.length < 50) {
				$this = $(this).closest('.friend');
				
				fb_friend_id.push($this.attr('data-fb_id'));
				fb_friend_data.push({
					'fb_id': $this.attr('data-fb_id'),
					'full_name': $this.attr('data-full_name'),
				})
			}
		});
		if (fb_friend_id.length == 0) {
			if (php.redirect_to) location.href = php.redirect_to;
			return;
		}

		/*
		if (fb_friend_id.length == 0 || fb_friend_id.length > 50) {
			$('#fb-root').hide();
			window.clearInterval(position_interval);
			return; //This is limited by fb we are just avoiding an invite popup error
		}*/

		console.info('Send invite to: ', fb_friend_id, fb_friend_data);
	
		var conf = {
				method: 'apprequests',
			    new_style_message: true,
			    message: php.fb_invite_message,
			    //method: 'send',
			    display: 'popup',//'iframe',
			    //"level": "debug",
			    to: fb_friend_id,
			    name: php.fb_invite_title,
			    //description: php.fb_invite_description,
			    picture: php.picture,
			    link: php.baseUrl.replace('www.','public.')+'index.php/signup?a=1&b=fbinvite&fu='+php.user_id
			};

		FB.ui(conf, function(response) {

			if (response && response.request > 0) {

				$('.fb_invite_all_btn').addClass('success');
				$(document).trigger('popup_info', "Invited Successfully");
				
				//kissmetrics
				_kmq.push(['identify', ''+php.username+'']);
				_kmq.push(['record', 'invited friends via facebook', {'friends number':''+fb_friend_id.length+''}]);
				
				//update server
				$.post('/invited_users', {'users': fb_friend_data},function(data){

					if (typeof data.button != 'undefined')	{
						if ($('#add_collect').length == 0)	{
							$('#headerLinks li.hdrnav_item').eq(0).before(data.button);
						}
						if ($('#get_bookmarklet_dialog').length == 0)	{
							$('body').append(data.dialog);
						}
					}

				},"json");

			} else {

				$('.fb_invite_all_btn').addClass('fail');
				$(document).trigger('popup_info', "Your invitation was not sent to your Facebook friends.  Please Try again");

			}
			
			if (php.redirect_to) location.href = php.redirect_to;
		});

		/*$('.fb_invite_all_btn').addClass('loading');
		position_fb_dialog();
		position_interval = window.setInterval(function() {
			position_fb_dialog();
		},1000);*/
	}
	
	/* ======================== Events ================================== */
	
	/**
	 * Invite popup - shows when you click on invite friend button from gmail or yahoo
	 */
	$(document).on('before_show','.invite_btn', function(e,content){ // 
		var item = $(this).closest('.friend');
		$('#invite_popup #friend_name').text(item.attr('data-full_name'));
		// FD-3821
		// $('#invite_popup #friend_email').text(item.attr('data-email'));
		$("#invite_popup [name='email[]']").val(item.attr('data-email'));
		$('#invite_popup [name=message]').val('');
	});
	$(document).on('success', '#invite_popup #invite_friends', function(e,msg) { //
		$('#invite_popup').modal('hide');
	});

	/**
	 * Follow all button
	 */
    $(document).on('click', '.request_follow_all', function(event, data) {

    	if ($(this).hasClass("disabled_button"))	{
    		return false;
    	}

    	$('ol.invitesList .request_follow:visible').trigger('click');
    	$('.request_follow_all').addClass("disabled_button");

    	// $(this).hide();
	    return false;
	});

	if (!$('ol.invitesList .request_follow:visible').length)	{
		$('.request_follow_all').addClass("disabled_button");
	}
	    
	/**
	 * Submit email invite form
	 */
	 var selector = '#email_invite_friends';
	$(document)
		.on('validate',selector, function(e,callback){
			if ( $(this).find('input.inviteField_email').filter('[value!=]').length == 0 ) {
				console.log('all email fields are empty, add .error class to the form');
				$(this).addClass('error');
			}
			callback.call(this, {status: ! $(this).hasClass('error') });
		})
		.on('preAjax', selector, function(e, data) {
			var form = $(this);
			if($('#emailInviter:visible').length > 0) {
				var invites = 0;
				$(this).find('.inviteField_email').each(function(){
					if($(this).val() != '') invites++;
				});
				if (invites > 1) form.attr('success', 'Your friends have been successfully invited!');
				else form.attr('success', 'Your friend has been successfully invited!');
			}
		})
		.on('success', selector, function(e, data) {
			
			if (typeof data.button != 'undefined')	{
				if ($('#add_collect').length == 0)	{
					$('#headerLinks li.hdrnav_item').eq(0).before(data.button);
				}
				if ($('#get_bookmarklet_dialog').length == 0)	{
					$('body').append(data.dialog);
				}
			}
			
			$(this).find('.inviteField_email').each( function() {
				// only hide & show success for the valid email
				if($(this).val()){
					$(this).parent().find('.valid').hide();
					$(this).parent().find('.success').show();
					$(this).val('');
				}
			});
			$(this).find('.inviteMessage').val('');
			
		});

		// FD-3745
		$(document).on('keyup', '#email_invite_friends .inviteField_email', function(){
			var form = $(this).closest('form');
			if ( form.find('input.inviteField_email').filter('[value!=]').length == 0 ) {
				form.addClass('error');
			}
		});
	/**
	 * Email invite
	 */
	$(document).on('click','#inviteLeft .emailInvite', function() {
		php.fb_invite = false;
		$('#invite_body div.error').hide();
		$('#inviteLeft ul li a').removeClass('selected');
		$(this).addClass('selected');
		
		$('#inviteRight').html($('#sectionRightLoader').clone().show());
		$('#inviteRight').load('/invite_email',function(){
			$('#inviteRight').show();
		});
		return false;
	});
	
	/**
	 * Gmail invite
	 */
	$(document).on('click','#inviteLeft .gmailInvite', function(){
		php.fb_invite = false;
		$('#invite_body div.error').hide();
		$('#inviteLeft ul li a').removeClass('selected');
		$(this).addClass('selected');
		
		$('#inviteRight').html($('#sectionRightLoader').clone().show()).show();	

		var s_error = false;
		var p_window = {};
		
		if (! php.auth_gmail) {

			p_window = window.open('/gmail_auth', '', 'width=1000,height=600,scrollbars=yes');// window title is not supported in IE
			window.gmail_success = function() {
				php.auth_gmail = true;
				$('#inviteRight').load('/invite_gmail');
			}
			window.gmail_error = function() {
				$('#inviteRight').hide();
				$('#invite_body .gmail_error').show();
				s_error = true;
			}

		} else {
			$('#inviteRight').load('/invite_gmail');
		}

		var timer = setInterval(checkGmailChild, 500);

		function checkGmailChild() {

		    if (p_window.closed && !php.auth_gmail && !s_error) {
		        // code if child window is closed and not authentificated 
		        $('#inviteRight').hide();
		        // $('#invite_body .gmail_error').show();
		        $('#inviteLeft .emailInvite').trigger("click");
		        clearInterval(timer);
		    }

		}		

		return false;
	});
	
	/**
	 * Yahoo invite
	 */
	$('#inviteLeft .yahooInvite').click(function(){
		php.fb_invite = false;
		$('#invite_body div.error').hide();
		$('#inviteLeft ul li a').removeClass('selected');
		$(this).addClass('selected');
		
		$('#inviteRight').html($('#sectionRightLoader').clone().show()).show();	

		var s_error = false;
		var p_window = {};

		if (! php.auth_yahoo) {
			p_window = window.open('/yahoo_email_auth', '', 'width=1000,height=600,scrollbars=yes');
			window.yahoo_success = function() {
				php.auth_yahoo = true;
				$('#inviteRight').load('/invite_yahoo');
			}
			window.yahoo_error = function() {
				$('#inviteRight').hide();
				$('#invite_body .yahoo_error').show();
				s_error = true;
			}
		} else {
			$('#inviteRight').load('/invite_yahoo');
		}

		var timer = setInterval(checkYahooChild, 500);

		function checkYahooChild() {

			console.warn(p_window.closed, !php.auth_gmail, !s_error);
		    if (p_window.closed && !php.auth_yahoo && !s_error) {
		        // code if child window is closed and not authentificated 
		        $('#inviteRight').hide();
		        $('#inviteLeft .emailInvite').trigger("click");
		        //$('#invite_body .yahoo_error').show();
		        s_error = false;
		        clearInterval(timer);
		    }
		
		}

		return false;
	});
	
	/**
	 * Facebook invite
	 */
	
	/**
	 * Switch to facebook invite page
	 */

	$(document).on('click','#inviteLeft .facebookInvite', function() { // 

		php.fb_invite = true;

		$('#invite_body .error').hide();
		$('#inviteLeft ul li a').removeClass('selected');
		$(this).addClass('selected');
		
		//$('#sectionRightLoader').show();

		$('#inviteRight').html($('#sectionRightLoader').clone().show());
		
		var attempt = 0;

		fb_app.get_friends(function(friends) {
			console.info('friends', friends);
			$.post('/invite_facebook', {'friends': friends}, function(data) {
				$('#inviteRight').html(data);
				$('#sectionRightLoader').hide();
				$('.fb_invite_all_btn').removeClass('loading');
				//loader.remove();
			})
		});

		return false;
	});
	
	/**
	 * Triggers the invite friends dialog
	 */
	$(document).on('click', '.fb_invite_all_btn', function() {
		//if (php.redirect_to) location.href = php.redirect_to;
		update_fb_dialog();
		return false;
	});
	
	/**
	 * Deselects all friends
	 */
	$(document).on('click','.fb_deselect_all', function() {
		$('.friend-list input:checkbox:checked').removeAttr('checked');
		$(this).removeClass('fb_deselect_all').addClass('fb_select_all').text('Select All');
		var meter = $('#inviteRight .users-meter');
		meter.find('h2 span').text(meter.attr('data-base') || 5);
		meter.find('li').each(function(i) {
			if (i < 5-parseInt(meter.attr('data-base'))) {
				$(this).addClass('selected');
			} else {
				$(this).removeClass('selected');
			}
		})
		//update_fb_dialog();
		return false;
	});
	
	/**
	 * Selects all friends
	 */
	$(document).on('click','.fb_select_all', function() {
		$('.friend-list input:checkbox').attr('checked','checked');
		$(this).removeClass('fb_select_all').addClass('fb_deselect_all').text('Deselect All');
		$('#inviteRight .users-meter h2 span').text('');
		$('#inviteRight .users-meter li').addClass('selected');
		//update_fb_dialog();
		return false;
	});
	
	/**
	 * Update the invite friends dialog
	 * @deprecated
	 */
	$(document).on('change','.friend-list input:checkbox', function() {
		var num_checked = $('.friend-list input:checkbox:checked').length;
		num_checked += 5-parseInt($('#inviteRight .users-meter').attr('data-base'));

		var count = Math.max(0, 5-num_checked);
		
		$('#inviteRight .users-meter h2 span').text(count || '');
		$('#inviteRight .users-meter li').each(function(i) {
			if (i < num_checked) {
				$(this).addClass('selected');
			} else {
				$(this).removeClass('selected');
			}
		});
		//update_fb_dialog();
	});
	
	/**
	 * Filters the friends list
	 */
	$(document).on('keyup','#search_friends', function() {
		var query = $.trim($(this).val());
		if (! query ) {
			$('.friend').show();
		} else {
			var fullname, email;
			$('.friend').each(function() {
				fullname = $(this).attr('data-full_name');
				email = $(this).attr('data-email');
				if ((fullname && fullname.toLowerCase().search(query.toLowerCase()) >= 0) || (email && email.toLowerCase().search(query.toLowerCase()) >= 0)) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		}
	});
	
	$(function() { //for the signup
		$('.fb_invite_all_btn').removeClass('loading');
		if (php.invite_type == 'facebook') {
			$('#inviteLeft .facebookInvite').trigger('click');
		}
		/*window.setTimeout(function() {
			update_fb_dialog();
		},1000);*/
	});	

});
