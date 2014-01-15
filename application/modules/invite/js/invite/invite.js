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
	
	var email_btn = 'a[href="#emailInvite"]';
	var facebook_btn = 'a[href="#facebookInvite"]';
	var gmail_btn = 'a[href="#gmailInvite"]';
	var yahoo_btn = 'a[href="#yahooInvite"]';
	
	var menus = '#inviteContainer .inviteMenusColumn';
	var container = '#inviteContainer .invitesColumn';
	var loader = '#inviteContainer .mainLoader';
	
	/* ===================== Private functions ============================= */
	
	/**
	 * Add selected class when a menu is clicked
	 */
	function select_menu(el) {
		$(container+' div.error').hide();
		$(menus+' a').removeClass('selected');
		el.addClass('selected');
	}
	
	function show_loader() {
		if (!$('#inviteRight .mainLoader').length) {
			$(container).html($(loader).clone().show());
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

		$('.inviteBody_users input:checkbox:checked').each(function() {
			if (fb_friend_id.length < 50) {
				$this = $(this).closest('.inviteBody_userUnit');
				
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
		
		console.info('Send invite to: ', fb_friend_id, fb_friend_data);
		
		var conf = {
				method: 'apprequests',
				new_style_message: true,
				message: php.fb_invite_message,
				display: 'popup',
				to: fb_friend_id.join(","),
				name: php.fb_invite_title,
				picture: php.picture,
				link: php.baseUrl.replace('www.','public.')+'index.php/signup?a=1&b=fbinvite&fu='+php.user_id
			};

		FB.ui(conf, function(response) {
			
			console.warn('response',response);
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

	}
	
	/* ======================== Events ================================== */
	
	/**
	 * Invite popup - shows when you click on invite friend button from gmail or yahoo
	 */
	$(document).on('before_show', '.inviteButton', function(e,content){
		var item = $(this).closest('.inviteBody_userUnit');
		// $('#invite_popup #friend_name').text(item.attr('data-full_name'));
		// FD-3821
		// $('#invite_popup #friend_email').text(item.attr('data-email'));
		$("#invite_popup [name='email[]']").val(item.attr('data-email'));
		$('#invite_popup [name=message]').val('');
	});

	$(document).on('success', '#invite_friends', function(e,msg) {
		var _this = this;
		$('#invite_popup').modal('hide');
		setTimeout(function(){
			$(document).trigger('popup_info',"Invited successfully.");
			var email = $('input[name^=email]',_this).eq(0).val();
			var button = $('button[data-email="' + email + '"]');
			button.hide();
			button.next().show().attr("disabled",true);
		},500);
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
		return false;
	});

	$(document).on('success','ol.invitesList .request_unfollow',function(){
		$(this).hide();
		$(this).next().show();
	});

	$(document).on('success','ol.invitesList .request_follow',function(){
		$(this).hide();
		$(this).prev().show();
	});

	/**
	 * Submit email invite form
	 */
	$(document)
		.on('submit','#email_invite_friends',function() {
			$('.error,.valid,.success',$(this)).hide();
		})
		.on('validate','#email_invite_friends', function(e,callback){
			// Quang: by some reason, .filter('[value!=]') does not work but $(this).val()
			// if ( $(this).find('input.inviteField_email').filter('[value!=]').length == 0 ) {
			if ( $(this).find('input.inviteField_email').filter( function() { return $(this).val() != ""}).length == 0 ) {
				console.log('all email fields are empty, add .error class to the form');
				$(this).addClass('error');
			}
			callback.call(this, {status: ! $(this).hasClass('error') });
		})
		.on('preAjax','#email_invite_friends', function(e, data) {
			var form = $(this);
			if($('#emailInviter:visible').length > 0) {
				var invites = 0;
				$(this).find('.inviteField_email').each(function(){
					if($(this).val() != '') invites++;
				});
			}
		})
		.on('success', '#email_invite_friends', function(e, data) {

			if (typeof data.button != 'undefined')	{
				if ($('#add_collect').length == 0)	{
					$('#headerLinks li.hdrnav_item').eq(0).before(data.button);
				}
				if ($('#get_bookmarklet_dialog').length == 0)	{
					$('body').append(data.dialog);
				}
			}
			
			$(this).find('.inviteField_email').each( function(index) {

				// only hide & show success for the valid email
				var _this = this;

				 if ( $(_this).val() && data.status)	{
				 	$(_this).parent().find('.error').hide();
				 	$(_this).parent().find('.valid').hide();
				 	// $(_this).parent().find('.success').show().delay(3000).fadeOut();
				 	$(this).val('');
				 }

				if( $(_this).val() && data.status == false && typeof data.error[index+1] != 'undefined' ){

					setTimeout(function(){
						$(_this).parent().find('.error').html(data.error[index+1]).show();
						$(_this).parent().find('.valid').hide();
						$(_this).parent().find('.success').hide();
					},500);

					

				} else if ( $(_this).val() && data.status == false && typeof data.error[index+1] == 'undefined' ) {

					$(_this).parent().find('.error').hide();
					$(_this).parent().find('.valid').hide();
					// $(_this).parent().find('.success').show().delay(3000).fadeOut();
					$(this).val('');
				}

			});

			$(this).find('.inviteMessage').val('');
			
		});
	
	// FD-3745
	$(document).on( 'keyup', '#email_invite_friends .inviteField_email', function(){
		var form = $(this).closest('form');
		if ( form.find('input.inviteField_email').filter('[value!=]').length == 0 ) {
			form.addClass('error');
		}
	}).on( 'blur', '#email_invite_friends .inviteField_email',function(){
		if ($(this).val() == "")	{
			$('.error,.valid,.success',$(this).parent()).hide();
		}
	});
		
	/**
	 * Email invite
	 */
	$( email_btn ).on('click', function() {
		select_menu($(this));
		show_loader();
		$('#email_invite_friends').removeClass("error");
		$(container).load('/invite_email').show();
		return false;
	});
	
	/**
	 * Gmail invite
	 */
	$(gmail_btn).on('click', function(){
		select_menu($(this));
		show_loader();

		var s_error = false;
		var p_window = {};
		
		if (! php.auth_gmail) {

			p_window = window.open('/gmail_auth', '', 'width=1000,height=600,scrollbars=yes');// window title is not supported in IE
			window.gmail_success = function() {
				php.auth_gmail = true;
				$(container).load('/invite_gmail');
			}
			window.gmail_error = function() {
				$('#inviteRight').hide();
				$('#invite_body .gmail_error').show();
				//FD-4625
				s_error = false;
			}

		} else {
			$(container).load('/invite_gmail');
		}

		var timer = setInterval(checkGmailChild, 500);

		// code if child window is closed and not authentificated
		function checkGmailChild() {
			if (p_window.closed && !php.auth_gmail && !s_error) {
				$('#inviteRight').hide();
				$(email_btn).trigger("click");
				clearInterval(timer);
			}

		}		

		return false;
	});
	
	/**
	 * Yahoo invite
	 */
	$(yahoo_btn).click(function(){
		select_menu($(this));
		show_loader();

		var s_error = false;
		var p_window = {};

		if (! php.auth_yahoo) {
			p_window = window.open('/yahoo_email_auth', '', 'width=1000,height=600,scrollbars=yes');
			window.yahoo_success = function() {
				php.auth_yahoo = true;
				$(container).load('/invite_yahoo');
			}
			window.yahoo_error = function() {
				$('#inviteRight').hide();
				$('#invite_body .yahoo_error').show();
				s_error = true;
			}
		} else {
			$(container).load('/invite_yahoo');
		}

		var timer = setInterval(checkYahooChild, 500);

		// code if child window is closed and not authentificated
		function checkYahooChild() {
			if (p_window.closed && !php.auth_yahoo && !s_error) {
				$('#inviteRight').hide();
				$(email_btn).trigger("click");
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
	$(document).on('click', facebook_btn, function() {
		select_menu($(this));
		show_loader();
		fb_app.get_friends(function(friends) {
			console.info('friends', friends);
			$.post('/invite_facebook', {'friends': friends}, function(data) {
				$(container).html(data);
			});
		});

		return false;
	});
	
	/**
	 * Triggers the invite friends dialog
	 */
	$(document).on('click', '.fb_invite_all_btn', function() {
		update_fb_dialog();
		return false;
	});
	
	/**
	 * Deselects all friends
	 */
	$(document).on('click','.fb_deselect_all', function() {
		$('.inviteBody_users input:checkbox:checked').removeAttr('checked');
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
		return false;
	});
	
	/**
	 * Selects all friends
	 */
	$(document).on('click','.fb_select_all', function() {
		console.warn($('.inviteBody_users input:checkbox'))
		$('.inviteBody_users input:checkbox').each(function(){
			this.checked = true;	
		});

		$(this).removeClass('fb_select_all').addClass('fb_deselect_all').text('Deselect All');
		$('#inviteRight .users-meter h2 span').text('');
		$('#inviteRight .users-meter li').addClass('selected');
		return false;
	});
	
	/**
	 * Update the invite friends dialog
	 * @deprecated
	 */
	$(document).on('change', '.inviteBody_users input:checkbox', function() {
		var num_checked = $('.inviteBody_users input:checkbox:checked').length;
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
	});
	
	/**
	 * Filters the friends list
	 */
	$(document).on('keyup', '#search_friends', function() {
		var query = $.trim($(this).val());
		if (! query ) {
			$('.inviteBody_userUnit').show();
		} else {
			var fullname, email;
			$('.inviteBody_userUnit').each(function() {
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
	});
	
	if (!$('ol.invitesList .request_follow:visible').length)	{
		$('.request_follow_all').addClass("disabled_button");
	}

});