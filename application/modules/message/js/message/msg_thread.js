/*
 * Handlers for Message Threads and Replies to msg threads
 * @uses jquery
 * @uses message/msg_inbox - for common event handlers - Delete, msgList dependency
 */
define(['jquery', "message/msg_inbox"], function(){
	
	/**
	 * Shows the users in the thread from "between you and NN other people" link
	 */
	$('#num_people').hover(function() {
		var pos = $(this).position();
		$('#thread_hover').css({'top': pos.top+22, 'left': pos.left-28}).show('fade',300);
	}, function() {
		$('#thread_hover').hide();
	});
	
	/**
	 *  Send new message in the current thread
	 */
	$(document)
		.on('validate','#msg_thread_message_form', function(e,callback) {

			$(this).find('.error').hide();
			var msg_body = $(this).find('textarea[name=msg_body]');
			
			if ( ! $.trim(msg_body.val()) ) {
				$(this).find('.error.blank_body').show('fade');
				callback.call(this, {status:false});
				return;
			}

			callback.call(this, {status:true}); 
		})
		.on('success', function(e, msg) {
			if (!msg.status) {
				return false;
			}
			msg = msg.data;
			
			var new_msg =  $('.js-msg_entry:last').after(
					$('.js-messages_container .js-messages_constrictor')
						.msgList('post_msg', msg )
						.show('fade')
			);

			$('.js-msg_entry:last a.js-delete_float').attr("href","/del_msg/" + msg.msg_id);
			
			$(this).find('#private_msg_body').val('').text('');

			$(this).find('.textLimit').text($(this).find('#private_msg_body').attr('maxlength'));		

	    })
	    .find('#private_msg_body').on('keyup', function(e) {

			var textLimit = $(this).attr("data-maxlength");
			var nums = $('.textLimit',$(this).parent());
	
			console.info($(this).val().length);

			if ($(this).val().length > textLimit)	{
				$(this).css({color:'red'});
				nums.addClass("negative");
				nums.html( textLimit - $(this).val().length );
				$('input[type=submit]',$(this).closest("form")).addClass("disabled").attr( "disabled", true );
			}	else	{
				$(this).css({color:'black'});
				nums.removeClass("negative");
				nums.html( textLimit - $(this).val().length );
				$('input[type=submit]',$(this).closest("form")).removeClass("disabled").attr( "disabled", false );
			}
			
		});
	
});
