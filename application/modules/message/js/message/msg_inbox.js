/*
 * Handlers for Inbox and New Message box
 * @link /messages
 * @uses message/msgList - to add new message on submit success
 */
define(["message/msgList", 'jquery'], function(){
	
	//Selectors
	var container = '#inbox';
	var messages = container+' .msg_entry';
	
	//New msg
	var new_msg_btn = '#new_message';
	var new_msg_form = '#send_msg_message_form';
	var new_msg_text = new_msg_form+' textarea[name="msg_body"]';

	/* ======================== Direct Code ============================ */
	
	//Mixpanel tracking
	if (typeof(mixpanel) !== 'undefined') {
		var user = php.userId ? php.userId : 0;
		console.log('Mixpanel: Messages');
		mixpanel.people.identify(user);
		mixpanel.track('Messaging Center', {'user':user});
	}
	
	/* ========================= Events ============================== */

	/**
	 * Redrirect to the message thread when clicked on meesage item container
	 */
	$(document).on('click',messages, function() {
		window.location.href = $(this).attr('data-url');
	});

	/**
	 * New message popup - set data
	 */
	$(document).on('before_show', new_msg_btn, function(evt, content) {
		
		content.find('.js-popup_row_error,.js_error_msg').hide();
		$('#private_msg_name').tokenInput('clear');
		var count = $(new_msg_text).val('').text('').attr("maxlength");
		$('.textLimit').text(count);
	});

	/**
	 * New message popup - Send message form
	 * @uses message/msgList
	 * @to-do - use common/formValidation
	 */
	$(document)
		.on('validate',new_msg_form, function(e,callback) {

			var validated = true;

			$(this).find('.js-popup_row_error').hide();
			if ( ! $(this).find('.tokenInput-hidden').length) {
				$(this).find('.js-err_msg_receiver').show();
				validated = false;
			}
			
			var msg_body = 	$(new_msg_text);

			if ( ! $.trim(msg_body.val()) || msg_body.val() == msg_body.attr('placeholder')) {
			  	$(this).find('.js-err_msg_text').show();
			  	validated = false;
			}

			callback.call(this, {status:validated});
		})
		.on('success', function(e, msg) {

			if (!msg.status) {
				alert(msg.error);
				return;
			}
			msg = msg.data;
			
			$('#private_msg_name').tokenInput('clear');
			$(new_msg_text).val('').text('');
			$('#private_msg_form').modal('hide');

			var msgs = $('.js-msg_entry');
			var done = false;

			var new_msg = $('#inbox_messages').msgList('post_thread',msg);

			msgs.each(function() {
				if ($(this).attr('rel') === msg.thread_id) {
					$(this).html(new_msg.html()).show('fade');
					done = true;
				}
			});

			if ($('#thread_messages').length > 0)	{
				var current_thread = parseInt($('#thread_messages').attr("data-thread_id"));
				var new_thread = parseInt(msg.thread_id);
				if (current_thread != new_thread)	{
					window.location.href = "/view_msg/" + new_thread;
					return false;
				}
			}

			if (!done) {
				$('.js-no_messages').hide();
				$('#inbox_messages').prepend($(new_msg).show('fade'));
			}

		});

	/**
	 * Delete message
	 */
	$(document).on('success','.js-delete_float', function() {
		
		$(this).closest('.js-msg_entry').hide('fade').remove();

		if ( ! $('#inbox_messages .js-msg_entry').length) {
			$('#inbox_messages .js-no_messages').show();
		}

		//remove notification
		var href = $(this).attr('href');
		var href_components =  href.split('/');
		var from_user_id = href_components.pop();
		$('a[data-ref='+from_user_id+']', $("#notifications")).eq(0).closest('li').remove();
	}).on('click','.js-delete_float', function(e) {
		e.stopPropagation();
	});

});
