/**
 * Logic for Edit tile popup such as Ajax for update
 * Handles changes to Description inside the tile.
 * invoked by clicking on edit button inside a newsfeed item.
 * The delete button logic is in newsfeed.js and drop_page.js
 * @uses plugins/mentions - for the description textarea
 * @uses common/formValidation
 * @uses jquery
 */
define(['plugins/mentions', 'common/formValidation', 'jquery'],function() {
	
	var notification_wrap = '#scroll_notifications';
	var title = 'textarea[name="title"]';
	var description = 'textarea[name="description"]';

	/**
	 * Autosave
	 */
	$(document).on('keyup,change','.newsfeed_entry .media_text, .newsfeed_timeline_edit .media_text, .newsfeed_popup_edit .media_text', function(e) {
		console.log('{timeline edit} - save');
		$(this).closest('form').submit();
	});
	
	/**
	 * Hashtags
	 */
	$(document).on('click','form.edit_post_form .hashtag', function() {
		var textarea = $(this).closest('form').find('textarea.media_text');
		textarea.val(textarea.val() + ' ' + $(this).attr('href')).trigger('keyup');
		return false;
	});

	// we need to handle it in 'success' or finished validate
	$(document).on('click', 'form.edit_post_form .done_button', function() {
			$(this).closest('form').submit();
			return false;
	});
	
	/*
	 * News posts delete posts 
	 * (invoked by clicking an 'x' in top right corner of newsfeed item post)
	 * When delete is clicked, a confirmation popup appears. Delete only occurs when user clicks on Yes.
	 */
	$(document).on('before_show',"a[href='#delete_dialog']", function(e, content) {
		console.warn('newsfeed delete dialog');
		e.stopPropagation();
		$(content).find('.delete_yes').attr('href',$(this).attr('data-delurl'));
	});
 
	/**
	 * General code for the delete success its extended in some views
	 * @since 2/1/2013 - moved specific code for profile_top to profile.js
	 */
	$(document).on('success', '#delete_dialog .delete_yes', function(e, data){
		// close dialogs
		$('#delete_dialog').modal('hide');
		$('#newsfeed_popup_edit').modal('hide');
		if ($('#preview_popup:visible').length) {
			$('#preview_popup').modal('hide')
		}
		$( notification_wrap + ' a[data-newsfeed_id="' + data.id + '"]').closest("li").remove();

		// http://dev.fantoon.com:8100/browse/FD-3939
		$('img[data-newsfeed_id=' + data.id + ']').remove();

	})

	/* Not Complete */

	var selector = 'form.edit_post_form';

	$(document)
		.on('validate', selector, function(e,callback) {
			var is_valid = true;
			$(this).find('[data-validate]').each(function() {
				if ( ! window.validate($(this))) {
					is_valid = false;
				}
			});
			if (is_valid) $(this).closest('.newsfeed_timeline_edit, .newsfeed_popup_edit').find('.new_close').click();
			callback.call(this, {'status': is_valid});
		})
		.on('success', selector, function(event, data) {
			if (!data.status) {
				console.info('Error', data);
				return;
			}

			$(this).closest('.modal').modal('hide');
			$(this).find('.data_status').removeClass('in_progress').text('Saved!');
	  		
	  		$container = $("[data-newsfeed_id="+data.newsfeed_id+"]");

	  		$container.find('.drop-title span').html(data.title); //update title

			// check if data source != Dropped via - right info
			if ( data.source )	{
				// change there and in main info
				// change in right panel
				$container.find('a.itemDroppedVia_Title').attr("href",data.source).html(data.source);
			}
	
	  		var text_plain = $('<div></div>').html(data.text).text();
	  		if (text_plain.length >= 70) { data.longer_text += '...' }

	  		$container.find('.drop-description').html(data.longer_text); //new timeline		  		
		});
		
	/**
	 * Set popup data
	 */
	$(document).on('before_show', "a[href='#newsfeed_popup_edit']", function(e, content) {
		
		var container = $(this).closest('[data-newsfeed_id]');
		
		var data = {
			'id': container.attr('data-newsfeed_id'),
			'title': $.trim( container.find('.drop-title span').text() ),
			'description': $.trim( container.find('.drop-description').text() ),
			'link': $.trim( container.find('a.itemDroppedVia_Title').attr('href') ),
			'top_prize': $.trim( container.attr('data-top_prize') ),
			'share_goal': $.trim( container.attr('data-share_goal') ),
			'del_url': '/del_link/' + container.attr('data-newsfeed_id'),
			'sub_type': container.attr('data-sub_type'),
		}
		
		console.info('{edit popup} - show', data);
		
		if ( data['title'] ) content.find(title).val(data['title']);

		content.find( description ).val( data['description'] );
		content.find( '[name=top_prize]' ).val( data['top_prize'] );
		content.find( '[name=share_goal]' ).val( data['share_goal'] );
		
		if (content.find('[name="sub_type"]').length) {
			content.find('[name="sub_type"] option').removeAttr('selected');
			content.find('[name="sub_type"] option[value="'+data['sub_type']+'"]').attr('selected','selected');
			content.find('[name="sub_type"]').val(data['sub_type']);
		}
		
		if (data['link']) {
			content.find('.source_url').val(data['link']).parent().show();
		}	else	{
			content.find('.source_url').val(data['link']).parent().hide();
		}
		
		content.find('[data-delurl]').attr('data-delurl', data['del_url']);
		content.find('[name=id]').val(data['id']);
		
		var $target = content.find('textarea.fd_mentions');
		
		$target.parent().find('.maxLength').text( $target.attr('maxlength') - $target.val().length);
		e.stopPropagation();
		
	});

});
