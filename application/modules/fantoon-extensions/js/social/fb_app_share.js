/**
 * Initialize facebook
 * Logic for initializing facebok login.
 *
 */
define(['jquery'], function() {
	
	/* ==================== Variables ================== */
	
	var self = this;
	this.my_fb_info = null;
	var auth = null;
	this.action_type = 'drop';
	
	/* ======================= Public functions =============== */

	this.fb_login = function(callback, error, share) {

		console.info('fb login');
		$('#inviteRight').show();

		if ( typeof FB == 'undefined')	{
			if ($('#preview_popup:v isible').length)	{
				$('#facebook_error_msg').css({'z-index':$.topZIndex('#preview_popup:visible')+1})
			}
			$('#facebook_error_msg .text_loading').html('No connection with Facebook.');
			$('#facebook_error_msg').fadeIn();
			setTimeout(function(){
				$('#facebook_error_msg').fadeOut();
				$('#facebook_login_button_header').removeClass("loading");
			},3000)			
			return false;
		}

		FB.login(function(response) {

			console.info('FB LOGIN', response);

			if (response.status == 'connected') {
				auth = response.authResponse;

				if (share && share == 'share')	{
					callback.call(this, auth);
				} else if(share && share == 'friends')	{

					// no matter if is set to another user or not to display friends
					$.post('/set_fb_data/only_token', {'auth': auth}, function(res) {
						callback.call(this, auth);
					},'json');

				} else {

					$.post('/set_fb_data', {'auth': auth}, function(res) {
						if (!res.status) {
						 	if (error) error.call(this, res.error);
						 } else {
						 	callback.call(this, auth);
						 }
					},'json');

				}

			} else	{
				// not logged in
				if (error) error.call(this, response);
			}

		},{scope: 'email,publish_actions'});
	}
	
	this.get_friends = function(callback, next, data) {
		if (next) {
			$.get(next, function(friends) {
				data = data.concat(friends.data);
				if (friends.paging && friends.paging.next) {
					get_friends(callback, friends.paging.next, data);
				} else {
					callback.call(this, data);
				}					
			}, "json");
		} else {
			FB.api('/me/friends', function(friends) {
				console.info('{FB} - get_friends', friends);
				if (friends.error) {
					fb_login(function() {
						get_friends(callback);
					},function(){
						$('#inviteRight').hide();
						$('#inviteLeft .emailInvite').trigger("click");
					},'friends');
					return;
				}
				if (friends.paging && friends.paging.next) {
					get_friends(callback, friends.paging.next, friends.data);
				} else {
					callback.call(this, friends.data);
				}					
			});
		}
	}
	
	this.fb_share = function(type, item_id, success, error) {
		console.warn('type',type);
		if (type == 'drop') {
			var check_data = {'newsfeed_id': item_id};
			var url = php.baseUrl+'drop/'+item_id+'?ext=fb&';
			var action_type = 'drop';
		} else if (type == 'collection') {

			if (item_id instanceof Object) {
				var url = item_id.attr('data-url');
				if (url.indexOf('http') == -1) url = php.baseUrl+url;
				var check_data = {
						'folder_id': item_id.attr('data-folder_id') ? item_id.attr('data-folder_id') 
							: (
								item_id.closest('data-folder_id').length
									? item_id.closest('data-folder_id').attr('data-folder_id')
									: item_id.closest('.js_folder').find('[data-folder_id]').attr('data-folder_id')
							)
					};
			} else {
				var url = $("meta[property='og:url']").attr('content') 
					|| php.baseUrl.substring(0,php.baseUrl.length -1) + $('[data-id=' + item_id + '],[data-folder_id=' + item_id + ']').attr("data-url");
				
				var check_data = { 'folder_id': item_id };
			}
			 
			var action_type = 'share';

		} else {
			console.warn('Type not recognized: ', type);
			if (error) error.call(this, 'Type not recognized: '+type)
			return;
		}

		console.warn('{fb app share} - share', check_data, url);
	
		// FD-3628: userId is fandrop userid. It should be fb id here for login
		// if (!php.userId) {
			if (!auth) {
				self.fb_login(function() {
					self.fb_share(type, item_id, success, error);
				},function(){},'share');
				return;
			} else {
				console.warn(auth);
				check_data.social_user_id = auth.userID;
			}
		// }

		if ($('#preview_popup:visible').length)	{
			$('#loading-messages').css({'z-index':$.topZIndex('#preview_popup:visible')+1})
		}

		$('#loading-messages').fadeIn();
		
		jQuery.post('/check_fb_drop', check_data, function(data) {

			if ( ! data.status) {
				console.warn('SHARE CHECK ERROR', data);
				$('#loading-messages').fadeOut();

				if (error) error.call(this, 'SHARE CHECK ERROR');
				return;
			}
			
			if (location.pathname.indexOf('/winsxsw/') == 0) {
				var fb_url = '/me/og.likes?object='+encodeURIComponent(url);
			} else {
				var fb_url = '/me/'+php.fb_app_namespace+':'+action_type+'?'+type+'='+encodeURIComponent(url);
			}

			console.info('{fb app share} - share ', fb_url);

			FB.api(fb_url, 'post', function(response) {
			
				if (!response || response.error) {
					if (response.error.code == 200) {
						return fb_login(function() {
							self.fb_share(type, item_id, success, error);
						});
					}
					console.log('{fb app share} - error ', response);
					$('#loading-messages').fadeOut();
					$(document).trigger("popup_info","Sharing error.")
					if (error) error.call(this, response);
					return;
				}

				if (_kmq) {
					_kmq.push(['identify', ''+php.username+'']);
					_kmq.push(['record', 'shared a '+type+' on facebook', check_data]);
				}

				// show popup message
/*				if ($('#fb-share-success-popup').length) {
					$('#fb-share-success-popup').modal('show');
					window.setTimeout(function() {
						$('#fb-share-success-popup').modal('hide');
					},3000);				
				}
*/
				$('#loading-messages').fadeOut();

				console.info('{fb app share} - shared', response);
				if (php.referral) {
					data.referral = p
					hp.referral;
				}
				jQuery.post('/insert_fb_drop', check_data, function(res) {
					if (!res.status) {
						console.info('Share error', res);
						return;
					}
					//$(document).trigger('share_success', ['fb']);
					if (type == 'collection') {
						$(document).trigger('popup_info',"Story is shared.");
					} else {
						$(document).trigger('popup_info',"Drop is shared.");
					}
					success.call(this);
				}, 'json');
			});

		},'json');
	}
	
	/* ======================= Private functions ================== */
	
	function get_my_fb_info(callback, authResponse) {
		console.log('get_my_fb_info');
		if (typeof FB == 'undefined') {
			window.setTimeout(function() {
				get_my_fb_info(callback);
			},100);
			return;
		}
		console.info('get fb info');
		if (self.my_fb_info) {
			console.info('already have it');
			callback.call(this, self.my_fb_info, authResponse);
			return;
		}
		console.info('me request');
		
		FB.api('/me', function(fb_user_info) {
			console.log(fb_user_info);
			if (fb_user_info.error) {
				console.info('fb login request', fb_user_info);
				fb_login(function() {
					get_my_fb_info(callback);
				});
				return;
			} else {
				console.info('logged in', fb_user_info);
				self.my_fb_info = fb_user_info;
				callback.call(this, self.my_fb_info, authResponse);
			}
		},{scope: 'email,publish_actions'});
	}
	
	$(document).on('click', '.fb_share_collection', function() {

		var $this = $(this);

		if ($this.hasClass('disabled_bg')) return false;
		//jQuery('#share_fb_app_coll.fb_share_collection').addClass('disabled_bg');

		// jQuery('#share_fb_app_coll.fb_share_collection').addClass('disabled_bg');
				
		self.fb_share('collection', $this, function() {

			$this.addClass('disabled_bg');

			if (jQuery('#redirect_to_home').length) {
				document.location.href = '/';
			}
		});
		
		return false;
	})
	//for postcard and other newsfeed items where there are multiple fb_shares on the same page
	.on('click', '.share_fb_app', function() {
		var $this = $(this);
		if ($this.hasClass('fb_share_collection')) return false;
		if ($this.hasClass('disabled_bg')) return false;
		
		var container = $(this).closest('[data-newsfeed_id]');
		var newsfeed_id = container.attr('data-newsfeed_id');

		console.log('{share fb app} - newsfeed id ', newsfeed_id);
		
		$('[data-newsfeed_id='+newsfeed_id+']').addClass('liked');
        
		self.fb_share('drop', newsfeed_id, function() {

			$this.addClass('disabled_bg');

			if (jQuery('#redirect_to_home').length) {
				document.location.href = '/';
			}
			if ($('#fb-share-success-popup').length) {
				$('#fb-share-success-popup').modal('show');
				window.setTimeout(function() {
					$('#fb-share-success-popup').modal('hide');
				},2000);				
			}

			container.find('.js-share_count').text(parseInt(container.find('.js-share_count').text()) + 1);
		});
		
		return false;
	});

	/*
	//@update 7/25/2012 - Added check for disabled bg to avoid duplicates
	$(function() {
		//self.autoShareCheck();
		
		if (window.location.href.indexOf('share_drop_on_fb') > -1) {
			share_bookmarklet_drop();
		}
	});
	*/

	return this;
});
