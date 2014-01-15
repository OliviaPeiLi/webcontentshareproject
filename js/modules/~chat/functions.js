
function sound_toggle()
{
	 if (soundmute == 0) {
		  soundmute = 1;
		  soundtext = base_url_woi + 'chatui/images/msoundoff.png';
	 } else {
		  soundmute = 0;
		  soundtext = base_url_woi + 'chatui/images/msoundon.png';
	 }

	var img = document.getElementById("sound_toggle");
	img.src = soundtext;

	save_session();

}

function online_toggle()
{
	 if (selfonline == 0) {
		   selfonline = 1;
			onlinetext = base_url_woi + 'chatui/images/monline.jpg';
	 } else {
		   selfonline = 0;
			onlinetext = base_url_woi + 'chatui/images/moffline.jpg';
	 }

	var img = document.getElementById("online_toggle");
	img.src = onlinetext;
	img.title = 'Go online/offline';

	save_session();

	buddy_show = 1;

}

function get_current_name() {
	for (d=0; d<friendlist.length; d++) {
		 if (current_friend == friendlist[d]) {
			  return friendlist_name[d];
		 }
	}
}

function create_chat_layout(friendid, friendlist, friendlist_name, message_enable)
{
		var color;
		$("#active_friend_layout").html('');

		if (friendlist.length > 0) {
		 	for (f=0; f < friendlist.length; f++) {
				 if (friendlist[f] == 0) {
				 } else {
					var current_content = $("#active_friend_layout").html();
					var ff = friendlist[f];
					current_content = current_content +
						 '<div class="active_friend" id="button_friend_' + ff + '" onclick="friend_select(\'' + ff + '\',\'true\'); return false;">' +
						 '	<label>' + friendlist_name[f] + '</label>' +
						 '	<button class="close" id="friend_select_mouse_over' + ff + '" onclick="friend_close(\''+ff+'\'); cancel_propagation(event); return false;" ></button>' +
						 '</div>';
						 $("#active_friend_layout").html(current_content);
				 }
			}
		} else {
		   $("#chatboxlayout").html('');
		   $("#chatboxlayout").css('display','none');
			chatbox_show = 0;
			return false;
		}


		if (friendid == 0) {
			 // no chatting -> no message box
		   $("#chatboxlayout").html('');
		   $("#chatboxlayout").css('display','none');
			chatbox_show = 0;
			return false;
		} else {
		   $("#chatboxlayout").html('');
			current_content = $("#chatboxlayout").html();
			current_content = current_content +
					'<div class="chatboxtitle">' +
					'	<label id=current_name></label>' +
					'	<label id="typing">(idle)</label>' +
					'	<button class="close" onclick="friend_close(\''+current_friend+'\'); cancel_propagation(event); return false;" ></button>' +
					'</div>' +
					'<div class="chatbox" id="chatbox">updating...<br />' +
					'</div>' +
					'<div class="messagebox">' +
					'	<textarea id="message" autocomplete="off"></textarea>' +
					'</div>' ;
			$("#chatboxlayout").html( current_content );
		}

	 // stylist chatbox
	if (chatbox_show == 1) {
		 if (friendid == 0) {
			$("#chatbox").html('');
			$("#chatboxlayout").css('display', 'none');
			chatbox_show = 0;
			return false;
		 } else {

			//get_chat_messages(current_friend);
			$("#chatbox").html('');
			if (current_friend != 0) {
				if (messages[current_friend]) {
					var str = "";
					for (d=0; d<messages[current_friend].length; d++) {
						 str = str + messages[current_friend][d];
					}
					$("#chatbox").html(str);
				 }
				$("#current_name").html(get_current_name());
				$("#message").focus();
			}
			$("#chatboxlayout").css('display', 'block');
		 }
	} else {
		$("#chatboxlayout").html('');
		$("#chatboxlayout").css('display', 'none');
		return false;
	}

	if ($("#chatboxlayout").css('display') == 'block') {
				$("#current_name").html(get_current_name());
				$("#message").focus();
	}

	return false;

}

/**
 * send_message 
 * 	It requests to server for putting message to database
 * 	If returned value from server is 'ok', it means
 *		communication works properly
 */
function send_message() {

	 var message = $("#message").val() ;

	// call the controller for updating message to database
	$.post(base_url + 'business_code/welcome/ajax_send_message', {friend:current_friend,message:message}, function(data) {
		if (data.status == 1) {
			// communication ok with controller
		} else {
			alert('Something wrong in communication between chat.js & business_code/welcome/ajax_send_message');
		}
		 }, "json");

	// update chatbox
	var current_content = $("#chatbox").html();
	var format_message = '<b  style="color:red">' + myname + '</b>: ' + message + '<br />';
	$("#chatbox").html(current_content + format_message);

	if ($("#chatbox").length) { chatbox_scrollend (); }

	if (messages[current_friend]) {
		var index = messages[current_friend].length;
		messages[current_friend][index] = format_message;
	} else {
		messages[current_friend] = new Array();
		messages[current_friend][0] = format_message;
	}

	// clear the message box for next message
	$("#message").val('');

	return false;
}

/**
 * get_chat_messages 
 * 	It requests server to get the message transferred between 2 userids
 *		Userid is not need to post to server because server is keeping
 *		then in session['userid'] & session['friend']
 *
 *		Result is put to chatbox window
 */
function get_chat_messages(friend)
{
	// get message form database
	var unread = 0;
	if (typeof(messages[friend]) == 'undefined') {
	} else {
		 if (messages[friend].length > 0) {
		 	unread = 1;
		 }
	}

	$.post(base_url + 'business_code/welcome/ajax_get_message', {friend:friend,unread:unread}, function(data) {
		if (data.status == 1) {

			if (chatbox_show == 1) {

				// get the chatbox content
				if (unread == 0) {
					// if read all message then clear buffer in advance
					messages[friend] = new Array();
					var index = 0;
					$("#chatbox").html('');
				} else {
					if (messages[friend]) {
						var index = messages[friend].length;
						for (d=0; d<index; d++) {
							var current_content = $("#chatbox").html();
							$("#chatbox").html(current_content + messages[friend][d]);
						}
					} else {
						// if read all message then clear buffer in advance
						messages[friend] = new Array();
						var index = 0;
						$("#chatbox").html('');
					}
				}
				for (d=0; d< data.result.length; d++) {
					var current_content = $("#chatbox").html();
					$("#chatbox").html(current_content + data.result[d]);

					// save to buffer
					messages[friend][index++] = data.result[d];
				}

				// have new message, scroll to end
				if ($("#chatbox").length) { chatbox_scrollend (); }
			}
		}
		}, "json");

//	return false;
}

function save_new_message(friend,new_message)
{

	// get message form database
//	var unread = 0;
	if (typeof(messages[friend]) == 'undefined' || messages[friend] == null) {
		 messages[friend] = new Array();
		 var index = 0;
	} else {
		var index = messages[friend].length;
	}

	for (d=0; d< new_message.length; d++) {
		// save to buffer
		messages[friend][index++] = new_message[d];
	}


	if (chatbox_show == 1) {

		//get_chat_messages(current_friend);
		var str = "";
		for (d=0; d<new_message.length; d++) {
			 str = str + new_message[d];
		}
		var current_content = $("#chatbox").html();
		$("#chatbox").html(current_content + str);

		// have new message, scroll to end
		if (new_message.length > 0 && current_friend != 0) {
			if ($("#chatbox").length) { chatbox_scrollend (); }
		}
	}

}

function refine_buddy_list(buddy_all, data_online_id, data_online_name, data_online_unread, data_offline_id, data_offline_name, data_offline_unread )
{
			if (buddy_all == 1) {
				 // all buddy is got
				online_id = new Array();
				online_name = new Array();
				online_unread = new Array();

				offline_id = new Array();
				offline_name = new Array();
				offline_unread = new Array();

				for (d=0; d<data_online_id.length; d++) {
					 online_id[d] = data_online_id[d];
					 online_name[d] = data_online_name[d];
					 online_unread[d] = data_online_unread[d];
				}

				var i=0; var str="";
				for (d=0; d<data_offline_id.length; d++) {
					 offline_id[d] = data_offline_id[d];
					 offline_name[d] = data_offline_name[d];
					 offline_unread[d] = data_offline_unread[d];

					 str = str + offline_id[d] + ':' + offline_name[d] + ':' + offline_unread[d] + "\n";
				}
				// alert('buddy all 1 ' + str);

			} else {
				 // updating buddy is got
				var online_id_new = new Array();
				var online_name_new = new Array();
				var online_unread_new = new Array();

				var offline_id_new = new Array();
				var offline_name_new = new Array();
				var offline_unread_new = new Array();

				for (d=0; d<data_online_id.length; d++) {
					 online_id_new[d] = data_online_id[d];
					 online_name_new[d] = data_online_name[d];
				}

				for (d=0; d<data_online_unread.length; d++) {
					 online_unread_new[d] = data_online_unread[d];
				}

				for (d=0; d<data_offline_id.length; d++) {
					 offline_id_new[d] = data_offline_id[d];
					 offline_name_new[d] = data_offline_name[d];
				}

				for (d=0; d<data_offline_unread.length; d++) {
					 offline_unread_new[d] = data_offline_unread[d];
				}

				// set new online_id/name
				var i=0;
				var online_id_tmp = new Array();
				var online_name_tmp = new Array();
				var online_unread_tmp = new Array();
				for (d=0; d<online_id.length; d++) {
					 if ( offline_id_new.in_array( online_id[d] ) ) {
					 } else {
						  online_id_tmp[i] = online_id[d];
						  online_name_tmp[i] = online_name[d];
						  online_unread_tmp[i] = online_unread[d];
						  i++;
					 }
				}
				for (d=0; d<online_id_new.length; d++) {
					 online_id_tmp[i] = online_id_new[d];
					 online_name_tmp[i] = online_name_new[d];
					 online_unread_tmp[i] = online_unread_new[d];
					 i++;
				}

				// set new offline_id/name
				var i=0;
				var offline_id_tmp = new Array();
				var offline_name_tmp = new Array();
				var offline_unread_tmp = new Array();

				var str ="";
				for (d=0; d<offline_id.length; d++) {
					 if ( online_id_new.in_array( offline_id[d] ) ) {
					 } else {
						  offline_id_tmp[i] = offline_id[d];
						  offline_name_tmp[i] = offline_name[d];
						  offline_unread_tmp[i] = offline_unread[d];
					 	  str = str + offline_id_tmp[i] + ':' + offline_name_tmp[i] + ':' + offline_unread_tmp[i] + "\n";
						  i++;
					 }
				}
				for (d=0; d<offline_id_new.length; d++) {
					 offline_id_tmp[i] = offline_id_new[d];
					 offline_name_tmp[i] = offline_name_new[d];
					 offline_unread_tmp[i] = offline_unread_new[d];
					 	  str = str + offline_id_tmp[i] + ':' + offline_name_tmp[i] + ':' + offline_unread_tmp[i] + "\n";
					 i++;
				}

				// alert('buddy 0 - ' + str);

				online_id = online_id_tmp.slice();
				online_name = online_name_tmp.slice();
				online_unread = online_unread_tmp.slice();

				offline_id = offline_id_tmp.slice();
				offline_name = offline_name_tmp.slice();
				offline_unread = offline_unread_tmp.slice();

			}
}

function getsound() {
//debugging
//	jwplayer('sound').setup({
//		 'flashplayer': base_url_woi + 'public/player.swf',
//	    'file': base_url_woi + 'chatui/sound/message_receive.mp3',
//	    'width': '2',
//	    'height': '2',
//	    'controlbar': 'bottom',
//		 autostart: true});
	var soundfile =  base_url_woi + 'chatui/sound/message_receive.mp3';
	var data =  base_url_woi + 'chatui/sound/player_mp3_mini.swf';

	var str = '<object type="application/x-shockwave-flash" data="' + data + '"   width="1" height="1" >' +
	'<param   name="movie" value="' + data + '" />' +
	'<param name="flashvars"   value="mp3=' + soundfile + '&amp;autoplay=1" />' +
	'<param name="wmode" value="transparent"   />' +
	'</object>';

	$('#sound').html(str);

}

function get_buddy(param)
{
	 if (param == null) {
		  param = get_buddy_all;
	 }
	//$.post(base_url + 'main_page/ajax_get_buddy', {second:2,chatwith:current_friend,get_all_message:param}, function(data) {
	$.post(base_url + 'business_code/welcome/ajax_get_buddy', {second:2,chatwith:current_friend,get_all_message:param}, function(data) {
		if (data.status == 1) {

			if (buddy_show == 1) {
				$("#nav_content").html('');
			}

			// update global varialble (for refresh function)
			get_buddy_all = 0;
	
			var i = -1;
			for (d=0; d<data.online_id.length; d++) {
				 i++;
				if (buddy_show == 1) {
					var current_content_nav = $("#nav_content").html();
					$("#nav_content").html(current_content_nav + 
							  '<button class="buddy_select" onclick="buddy_select(\'' + data.online_id[d] + '\'); return false;">' +
							  '<img src="' + base_url_woi + 'chatui/images/ionline.jpg">' +
							  '(' + data.unread[i] + ')' +
							  data.online_name[d] +
							  '</button>' + "<br />" );
				}
			}

	
			for (d=0; d<data.offline_id.length; d++) {
				 i++;
				 if (buddy_show == 1) {
					var current_content_nav = $("#nav_content").html();
					$("#nav_content").html(current_content_nav + 
							  '<button class="buddy_select" onclick="buddy_select(\'' + data.offline_id[d] + '\'); return false;">' +
							  '<img src="' + base_url_woi + 'chatui/images/ioffline.jpg">' +
							  '(' + data.unread[i] + ')' +
							  data.offline_name[d] +
							  '</button>' + "<br />" );
				 }
	
			}
				
			if (buddy_show == 1) {
				var current_content_nav = $("#nav_content").html();
				$("#nav_content").html(current_content_nav + '<br />');
			}

			// for sound
			if (soundmute == 0 && data.new_mess == 1) {
				 getsound();
			} else {
				 $("#sound").html('');
			}

			// for typing
			if (current_friend != 0 && data.typing == 1) {
				 $("#typing").html('(typing)');
			} else {
				 $("#typing").html('(idle)');
			}

			save_new_message(current_friend, data.new_message);

		} else {
//			alert('Something wrong in communication between chat.js & main_page/ajax_get_message');
		}
  }, "json");

}

$(".chatboxtitle").live('click', function() {
		create_chat_layout(0, friendlist, friendlist_name, 0);
});
	

function friend_select(name)
{
	 var exist = 0;
	 for (d=0; d<friendlist.length; d++) {
		  if (friendlist[d] == name) {
				exist = 1;
				break;
		  }
	 }

	 if (exist == 0) { return false; }

		$.post(base_url + 'business_code/welcome/ajax_change_active_friend', {friendid:name}, function(data) {
			var objDiv = document.getElementById("chatboxlayout");
			 if (data.status == 1) {

				current_friend = name;

				if (current_friend != 0 && typeof(messages[current_friend]) == 'undefined') {
					 get_buddy(1);
				} else {
					 if (messages[current_friend].length == 0) {
					 	get_buddy(1);
					 }
				}

				//objDiv.style.display = "block";
				chatbox_show = 1;
				create_chat_layout(current_friend, friendlist, friendlist_name, 1);

				if ($("#chatbox").length) { chatbox_scrollend (); }

			 } else if (data.status == 'toggle') {
				if (chatbox_show == 0) {
					 chatbox_show = 1;
				 	 //create_chat_layout(current_friend, friendlist, friendlist_name, chatbox_show, true);
				 	 create_chat_layout(current_friend, friendlist, friendlist_name, chatbox_show);
					if ($("#chatbox").length) { chatbox_scrollend (); }


				} else {
					 chatbox_show = 0;
					 //create_chat_layout(current_friend, friendlist, friendlist_name, chatbox_show, true);
					 create_chat_layout(current_friend, friendlist, friendlist_name, chatbox_show);
				}

			 } else {
				alert('Something wrong in .active_friend.click function ');
			 }
		}, "json");

		return false;
}

function cancel_propagation(event) {
   if (event.stopPropagation){
       event.stopPropagation();
   }
   else if(window.event){
      window.event.cancelBubble=true;
   }
}

function friend_close(name)
{

	$.post(base_url + 'business_code/welcome/ajax_close_friend', {friendid:name}, function(data) {
		 if (data.status == 1) {
		 	friendid = data.friendid;
		 	current_friend = data.current_friend;


			friendlist = [];
			for (d=0; d<data.friendlist.length; d++) {
				friendlist[d] = data.friendlist[d];
			}
			
			friendlist_name = [];
			for (d=0; d<data.friendlist_name.length; d++) {
				friendlist_name[d] = data.friendlist_name[d];
			}

			// save_session();

			if (friendlist.length > 0 && friendlist[0] != 0) {
				if (data.same == 1) {
					current_friend = 0;
					chatbox_show = 0;
					//create_chat_layout(0, friendlist, friendlist_name, 0, true);
					create_chat_layout(0, friendlist, friendlist_name, 0);
				} else {
					current_friend = data.current_friend;
					//create_chat_layout(data.current_friend, friendlist, friendlist_name, 1, true);
					create_chat_layout(data.current_friend, friendlist, friendlist_name, 1);
				}
			} else {
				$("#chatboxlayout").html('');
				$("#layout").html('');
				$("#active_friend_layout").html('');
				$("#chatboxlayout").css('display', 'none');
				friendlist = [];
			}

		 } else {
		 	// error
		 }
	}, "json");


	return false;
}

function stay_online()
{
	if (selfonline == 1) {
		// get message form database
		$.post(base_url + 'business_code/welcome/ajax_stay_online', {}, function(data) {
	   }, "json");
	}
	return false;

}


function call_chat_with(friend)
{
	//$.post(base_url + 'main_page/ajax_chat_with', {friend:friend}, function(data) {
	$.post(base_url + 'business_code/welcome/ajax_chat_with', {friend:friend}, function(data) {
		if (data.status == 1) {
			  if (data.maxreach == 1) {
			  		alert('Maximum chat box is reached. Please close unused for opening new box');
			  } else {
				  current_friend = friend;
				  friendlist = [];
				  friendlist_name = [];
				  for (d=0; d < data.friendlist.length; d++) {
					  friendlist[d] = data.friendlist[d];
					  friendlist_name[d] = data.friendlist_name[d];
				  }
		
				  // save_session();
				  chatbox_show = 0;
				  friend_select(current_friend);
			  }
		}
	}, "json");
	return false;

}

function buddy_select(name)
{
	call_chat_with(name);
}

function show_buddy_toggle() {
	if (buddy_show == 1) {
		buddy_show = 0;
		$("#nav").css('display','none');

	} else {
		  buddy_show = 1;
		  get_buddy();
		  $("#nav").css('display','block');
	}
}

function save_session()
{
	$.post(base_url + 'business_code/welcome/ajax_save_session', {selfonline:selfonline,soundmute:soundmute,friendlistid:friendlist}, function(data) {
		if (data.status == 1) {
			// session is saved to database
		} else {
		}
	}, "json");
	return false;
}

function check_typing()
{
	var typing = 2;
	//console.log('check_typing'+typing);
	 if (typing ==1 ) {
	   typing = 0;

		$.post(base_url + 'business_code/welcome/ajax_typing', {chatwith:current_friend}, function(data) {
//		if (data.status == 'ok') {
			// session is saved to database
//		}
		}, "json");
	}
}

function chatbox_scrollend () {
	var objDiv = document.getElementById("chatbox");
	objDiv.scrollTop = objDiv.scrollHeight;
}
