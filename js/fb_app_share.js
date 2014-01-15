/* *********************************************************
 * Initialize facebook
 * Logic for initializing facebok login.
 *
 * ******************************************************* */

define(['jquery','facebook/fb_init'], function($,fbi){


	var counter=0;


	jQuery('head').prepend(jQuery('#header-data').html());
	
		
    function InitializeFB()
	{
		/*
		if (jQuery('#postsignup_fb_share').length > 0) {
			fbi.fbEnsureInit(function() {
				FB.init({
				//appId      : '315227451867064', // App ID
				appId      : php.fb_app_id,
				status     : true, // check login status
				cookie     : true, // enable cookies to allow the server to access the session
				xfbml      : true,  // parse XFBML
				oauth: true
				});
			});
		}
		*/
		console.log('INIT FB');
		
		function updateButton(response) {
			//var button = document.getElementById('fb-auth');
			var fb_response = response;
			fbi.fbEnsureInit(function() {
				console.log('trying 1');
				var userInfo = document.getElementById('user-info');	
				console.log(response);
				//if (response.authResponse) {
					//user is already logged in and connected
					
					FB.api('/me', function(response) {
						counter=counter+1;
						/*
					    userInfo.innerHTML = '<img src="https://graph.facebook.com/' 
					  + response.id + '/picture">' + response.name;
					  */
						if(counter>=2)
						{
						 init(fb_response);
						}
					});
				//} 
			});
  		}

		// run once with current status and whenever the status changes
		if (jQuery('#postsignup_fb_share').length <= 0) {
			console.log('updatebutton triggered');
			fbi.fbEnsureInit(function() {
				console.log('trying 4');
				FB.getLoginStatus(updateButton);
				FB.Event.subscribe('auth.statusChange', updateButton);	
			});
		}
	};
		
	$(function() {
		/*
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol 
		+ '//connect.facebook.net/en_US/all.js';
		console.log(document.getElementById('fb-root'));
		document.getElementById('fb-root').appendChild(e);
		*/
		InitializeFB();
		if ($('#postsignup_fb_share').length) {
			fbi.fbEnsureInit(function() {
				console.log('trying 5')
				FB.getLoginStatus(signup_share);
			});
		} else {		
			$('#share_fb_app').live('click', function() {
				console.log('FBSHARE CLICKED!');
				fbi.fbEnsureInit(function() {
					console.log('trying 3');
					FB.getLoginStatus(check_login_and_reshare);
				});
				return false;
			});
		}
	});
	
	$('.post_col, .ticker_link_preview, .link-popup', '.bookmarklet-success').live('before_show', function(e, content) {
		console.info('popup open fb share handler', $('#share_fb_app'), content.find('#share_fb_app'));
		if (content.find('#postsignup_fb_share').length) {
			fbi.fbEnsureInit(function() {
				FB.getLoginStatus(signup_share);
			});
		} else {		
			content.find('#share_fb_app').live('click', function() {
				fbi.fbEnsureInit(function() {
					FB.getLoginStatus(check_login_and_reshare);
				});
				return false;
			});
		}
	});

	function init(response) {
		  $("#divConfirm").show();
			if (jQuery('#execute_fb_share').length) {
				if (response.authResponse) {
					reshare(response);
				} else {
					console.log('TRIGGER');
					location.href = jQuery('#fb_login_exec').attr('href');
				}
			}
		  //reshare();
	}

	function check_login_and_reshare(response) {
		if (response.authResponse) {
			reshare(response);
		} else {
			location.href = jQuery('#fb_login_exec').attr('href');
		}
	}


	//Used by drop page
	function reshare(fbresponse)
	{
		
		//var url="http://kunwar.fantoon.com/timeline/imagefile.php";
		fbi.fbEnsureInit(function() {
			var newsfeed_id = $('head').find('#meta_newsfeed_id').attr('content');
			var url = document.location.href;
			if (!newsfeed_id || newsfeed_id === '') {
				newsfeed_id = jQuery('.bookmarklet-success').data('newsfeed_id');
				url = jQuery('#newsfeed_url').attr('href');
			}
	        var post_data = {
				fb_id: fbresponse.authResponse.userID,
				newsfeed_id: newsfeed_id,
				ci_csrf_token: php.csrf.hash
			};
			$.post('/check_fb_drop',post_data, function(data) {
				if (data.status === 'OK') {
				
					if (jQuery('#execute_fb_share').length) {
						var url2 = '/me/'+php.fb_app_namespace+':share?picture='+encodeURIComponent(url)
					} else {
						var url2 = '/me/'+php.fb_app_namespace+':drop?picture='+encodeURIComponent(url)
					}
				
					FB.api(url2,'post',
						function(response) 
						{
							if (!response || response.error) 
							{
								//alert('Error occured: '+response.error);
								console.log(response.error);
							} 
							else 
							{
								$.post('/insert_fb_drop',post_data, function() {
									if (jQuery('#redirect_to_home').length) {
										document.location.href = '/';
									} else {
										//alert('Post was successful! Action ID: ' + response.id);
										
										jQuery('#share_fb_app').die('click').live('click', function(){return false;}).addClass('disabled_bg');
									}
								});
							}
					});
				}
			},'json');
		});
		   
	}
	
	//Used only by signup.

	function signup_share() {
		//var url="http://kunwar.fantoon.com/timeline/imagefile.php";
		//var url = document.location.href;
		var url = jQuery('#url').text();
		fbi.fbEnsureInit(function() {		
			FB.api('/me/'+php.fb_app_namespace+':drop?picture='+encodeURIComponent(url),'post',
				function(response) 
				{
					if (!response || response.error) 
					{
						//alert('Error occured: '+response.error);
						console.log(response.error);
					} 
					else 
					{
						document.location.href = '/';
					}
			});
		});
	}


return this;

});