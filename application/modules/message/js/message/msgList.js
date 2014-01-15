/* *********************************************************
 * List of Messages (new message)
 *
 * ******************************************************* */

define(['common/utils','jquery'], function(u){

	function pullMessage(data,c_obj)	{
	
		$(c_obj).addClass("ajax_entry");

		// set Big thumbnail

		// console.warn(data.msg_info);
		$('div.avatars_preview > img:first',c_obj).attr("src",data.msg_info.user_from.avatar_73);

		for (var i=0;i<data.users.length;i++) {

			var names = $('.js-convo_partners',c_obj).append('<a href="' + data.users[i].url + '" class="convo_partners_item">' + data.users[i].full_name + '</a> ');
				if (i < data.users.length - 1) names.append(",");
					
					var li = $('.avatars_preview li:first',c_obj).clone();
						$('img',li).attr("src",data.users[i].avatar_25);
						$('.avatars_preview ul',c_obj).append(li);
			}

			$('.avatars_preview li:first',c_obj).hide();

			if ( php.userId != data.from )	{
				$('.body_user_link',c_obj).html(data.msg_info.user_from.full_name);
			}	else	{
				$('.body_user_link',c_obj).hide();
			}

	return c_obj;
	}

	$( document ).on( 'scroll_bottom', '#inbox_messages', function() {

		 if ( this.ajaxList_process instanceof Function ) return;

		this.ajaxList_process = function ( data ) {
			// this is returned from template
			return pullMessage(data,this);
		}

	});

  	jQuery.fn.msgList = function( type, data ) {

  		// TODO:
  		// $('div.inner').replaceWith - replace threads with new where threads have equal id

  		if (type == 'post_thread')	{
	  		var wrap = $('#inbox_messages');
	  		$(wrap).prepend( $(wrap.attr('data-template')).tmpl(data, function(){
	  			return pullMessage( data, this );
	  		}));
  		}	else if( type == 'post_msg' )	{
  		
  			return $( this.attr('data-template') ).tmpl(data, function(){
  				if (php.userId == $('.inbox_msg_line',this).attr("data-from"))	{
  					$('.inbox_msg_line',this).addClass("msg_from_you").removeClass("msg_from_others");
  				}	else	{
  					$('.inbox_msg_line',this).removeClass("msg_from_you").addClass("msg_from_others");
  				}
  			});

  		}

	};

});
