$(document).ready(function() {

	//console.log('load'+typing);
	setInterval(function() { get_buddy(); }, 3000);
	setInterval(function() { check_typing(); }, 2000);

	// stay online status update
	setInterval(function() { stay_online(); }, 5000);

	// get useronline for the first time
	get_buddy();
	check_init_status();

$("#online_toggle").click (function() {
	 online_toggle();
	 return false;
});

$("#sound_toggle").click (function() {
	 sound_toggle();
	 return false;
});

function check_init_status() {
	if (selfonline == 0) {
		var img = document.getElementById("online_toggle");
		img.src = base_url_woi + 'chatui/images/moffline.jpg';
	}

	if (soundmute == 1) {
		var img = document.getElementById("sound_toggle");
		img.src = base_url_woi + 'chatui/images/msoundoff.png';
	}
}

});


//keydown event for IE
$("#message").live("keydown", function(e) {
	// typing indicator
	typing = 1;

	// enter button
	if (e.which == 13) {
		var message = new String ( $("#message").val() ) ;

		if (message.trim() == "") {
			// do nothing when message is empty
		} else {
			send_message();
			$("#message").css('height', '39px');
			$("#message").css('overflow', 'hidden');
			$("#chatbox").css('height', '147px');
			if ($("#chatbox").length) { chatbox_scrollend (); }
		}
		return false;
	} else {
		var message = new String ( $("#message").val() );
		if ( message.length < 78 ) {
			$("#message").css('height', '40px');
			$("#message").css('overflow', 'auto');
			$("#chatbox").css('height', '147px');
		} else if (78 <= message.length && message.length < 120) {
			$("#message").css('height', '50px');
			$("#message").css('overflow', 'auto');
			$("#chatbox").css('height', '137px');
		} else if (120 <= message.length && message.length < 160) {
			$("#message").css('height', '65px');
			$("#message").css('overflow', 'auto');
			$("#chatbox").css('height', '122px');
		} else if (160 <= message.length && message.length < 200) {
			$("#message").css('height', '80px');
			$("#message").css('overflow', 'auto');
			$("#chatbox").css('height', '107px');
		} else if (200 <= message.length) {
			 // do nothing, scrollbar is insertted automatically
		}
	}
});


$(".active_friend").live("mouseover mouseout", function(event) {
  if ( event.type == "mouseover" ) {
    // do something on mouseover
	 $(this).find(".close").css('display','block');
  } else {
    // do something on mouseout
	 $(this).find(".close").css('display','none');
  }
});


$("#nav_title").live("click", function() {
		  show_buddy_toggle();
});

