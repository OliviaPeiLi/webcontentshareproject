	var counter=0;
	
    function InitializeFB()
	{
      FB.init({
        appId      : '315227451867064', // App ID
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
      
      FB.api('/me', function(response) {
		counter=counter+1;
        userInfo.innerHTML = '<img src="https://graph.facebook.com/' 
	  + response.id + '/picture">' + response.name;
		if(counter>=2)
		{
		 init();
		}
      });
    } 
  }

  // run once with current status and whenever the status changes
  FB.getLoginStatus(updateButton);
  FB.Event.subscribe('auth.statusChange', updateButton);	
};
	
$(function() {
  var e = document.createElement('script'); e.async = true;
  e.src = document.location.protocol 
    + '//connect.facebook.net/en_US/all.js';
  document.getElementById('fb-root').appendChild(e);
  InitializeFB();
}());

function init() {
	  $("#divConfirm").show();
	  reshare();
}
function reshare()
{
	
	var url="http://test.fandrop.com/like_demo";
	FB.api('/me/fandrop:like?picture='+encodeURIComponent(url),'post',
		function(response) 
		{
			if (!response || response.error) 
			{
				//alert('Error occured');
			} 
			else 
			{
				alert('Post was successful! Action ID: ' + response.id);
			}
		});
	   
}