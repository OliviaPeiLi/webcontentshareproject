/* ONLY USED FOR STANDALONE FB APP FOR FB TEAM APPROVAL */

define(['jquery'], function(){
	var counter=0;
	
    function InitializeFB() {
    	if (typeof FB == 'undefined') {
			window.setTimeout(function() { InitializeFB(); }, 100);
			return;
		}
		//console.log(php.fb_app_id);
    	FB.init({
	        appId      : php.fb_app_id, // App ID
	        status     : true, // check login status
	        cookie     : true, // enable cookies to allow the server to access the session
	        xfbml      : true,  // parse XFBML
			oauth: true
    	});
	  
		function updateButton(response) {
			//var button = document.getElementById('fb-auth');
			var userInfo = document.getElementById('user-info');	
	
		    if (response.authResponse) {
		      //user is already logged in and connected
				fbi.fbEnsureInit(function() {
			      FB.api('/me', function(response) {
					counter=counter+1;
			        userInfo.innerHTML = '<img src="https://graph.facebook.com/' + response.id + '/picture">' + response.name;
					if(counter>=2)
					{
						$("#divConfirm").show();
					}
			      });
			    });
		    } 
		}

		//run once with current status and whenever the status changes
		FB.getLoginStatus(updateButton);
		FB.Event.subscribe('auth.statusChange', updateButton);
		
		$('.fb-login').on('click', function(){
			fblogin();
			return false;
		});
		$('#LikeBtn').on('click', function() {
		  	reshare();
		  	return false;
		});
    }
	
	$(function() {
		InitializeFB();
	}());
	
	function reshare()
	{
		//console.log('inside reshare');
		var url=php.baseUrl+"like_demo";
		FB.api('/me/'+php.fb_app_namespace+':like?picture='+encodeURIComponent(url),'post',
			function(response) 
			{
				//console.log('fb.api call made');
				if (!response || response.error) 
				{
					//console.log(response);
					//alert('Error occured');
				} 
				else 
				{
					alert('Post was successful! Action ID: ' + response.id);
				}
			}
		);
	}

	//Facebook login
	function fblogin() {
		FB.login(function(response) {
				//console.log(response);
				if (response.status === 'connected') {
				    $.post("/like_demo?fb=1", {}, function(response) {
						document.location.reload();
				    });
			    }
			}, {scope:'user_about_me,email,user_birthday,user_interests,publish_actions,offline_access,read_stream'}
		);
	}
	
});
