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
	var description_fd_mention = ' textarea.fd_mentions';

	/**
	 * Autosave
	 */
	 var selector = '.newsfeed_entry .media_text, .newsfeed_timeline_edit .media_text, .newsfeed_popup_edit .media_text';

	$(document).on('keyup,change', function(e) {
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
	$(document).on('before_show', "a[href='#delete_dialog']", function(e, content) { // 
		console.warn('newsfeed delete dialog');
		e.stopPropagation();
		$(content).find('.delete_yes').attr('href',$(this).attr('data-delurl'));
	});
 
	/**
	 * General code for the delete success its extended in some views
	 * @since 2/1/2013 - moved specific code for profile_top to profile.js
	 */
	$(document).on('success','#delete_dialog .delete_yes', function(e, data){
		// close dialogs
		$('#delete_dialog').modal('hide');
		$('#newsfeed_popup_edit').modal('hide');
		if ($('#preview_popup:visible').length) {
			$('#preview_popup').modal('hide')
		}

		$( notification_wrap + ' a[data-newsfeed_id="' + data.id + '"]').closest("li").remove();

		var num_obj = $('#folderTop .js-total-redrop-count');
			num_obj.text(parseInt(num_obj.text()) - 1);

		// http://dev.fantoon.com:8100/browse/FD-3939
		$('.newsfeed_entry[data-newsfeed_id="'+data.id+'"]').remove();
		$('img[data-newsfeed_id=' + data.id + ']').remove();

	})

	var f_selector = 'form.edit_post_form';

	$(document)
		.on('validate', f_selector, function(e,callback) {
			var is_valid = true;
			$(this).find('[data-validate]').each(function() {
				if ( ! window.validate($(this))) {
					is_valid = false;
				}
			});
			if (is_valid) {
				$(this).closest('.newsfeed_timeline_edit, .newsfeed_popup_edit').find('.new_close').click();
				var newsfeed_id = $('input[name=id]',this).val();
				var newsfeed_container = $('.newsfeed_entry[data-newsfeed_id=' + newsfeed_id + ']');
				var longer_text = $(description,this).val();

				if (longer_text.length > 70)	{
					longer_text = longer_text.substring(0,70) + "...";
				}

				$('.js-description',newsfeed_container).html(longer_text);
			}
			callback.call(this, {'status': is_valid});
		})
		.on('success', f_selector, function(event, data) {

			var $form = $(this);

			if (!data.status) {
				console.info('Error', data);
				return;
			}

			$(this).closest('.modal').modal('hide');
	  		$form.find('.data_status').removeClass('in_progress').text('Saved!');
	  		
	  		var timeline_container = $("[data-newsfeed_id="+data.newsfeed_id+"]");
	  		// console.warn('timeline container',timeline_container.find('.js-description'));
	  		// timeline_container.find('.js-description').html(data.description); //update title

	  		// timeline_container.find('.drop-description').html(data.description); //update title
	  		// timeline_container.find('.drop_desc_plain').html(data.description);
	  		// timeline_container.find('.drop_desc_minified').html(data.description);
	  		// console.warn('timeline_container',timeline_container);
			
	  		// var preview_popup = $("#preview_popup");
			// preview_popup.find(".social #permalinks").attr("href", data.link_url);
			// preview_popup.find(".social #permalinks .linktext").text(data.limited_url);

			// check if data source != Dropped via - right info
			if ( data.source )	{
				// change there and in main info
				// change in right panel
				timeline_container.find('a.itemDroppedVia_Title').attr("href",data.source).html(data.source);
			}
/*
				if(data.source != preview_popup.find('.popup_right-source h3 a').text()) {
					preview_popup.find('.social #link_favicon').attr('src', 'http://www.google.com/s2/favicons?domain=' + data.source);
					preview_popup.find('.comments_list')
						.html('<img src="/images/loading_icons/bigRoller_32x32.gif" style="margin: 115px 0 0 115px;"/>')
						.load('/popup-right/'+data.newsfeed_id, function(html) {
							$('#preview_popup .comments_list').scroll(function() {
								if ( $(this).offset().top + $(this).height() < $(this).find('.comments-bottom').offset().top + $(this).find('.comments-bottom-container').height()) {
									$(this).find('.comments-bottom').addClass('overflow');
								} else {
									$(this).find('.comments-bottom').removeClass('overflow');
								}
							})
							
							timeline_container.find('.item_right .popup_right-source').html($(html).find('.popup_right-source').html());

							var scrollTo = Math.max(0, preview_popup.find('.comments_list')[0].scrollHeight - preview_popup.find('.comments_list').height() - preview_popup.find('#popup_right').height());
							console.info('Scroll to: ', scrollTo, preview_popup.find('#popup_right'));
							preview_popup.find('.comments_list').animate({'scrollTop': scrollTo});
						});
				}
*/
	
	  		var text_plain = $('<div></div>').html(data.short_text).text();
	  		// if (text_plain.length >= 28) { data.short_text += '...' }
	  		if (text_plain.length >= 70) { data.longer_text += '...' }
	  		// $(this).closest('.newsfeed_entry').find('.drop_desc_minified').html(data.short_text); //new timeline
	  		$(timeline_container).find('.js-description').html(data.longer_text); //new timeline		  		
		});
	
	/**
	 * Set popup data
	 */
	$(document).on('before_show', "a[href='#newsfeed_popup_edit']", function(e, content) {
	
		var container = $(this).closest('[data-newsfeed_id]');
		
		var data = {
			'id': container.attr('data-newsfeed_id'),
			'title': $.trim( container.find('.js-description').text() ),
			'description': $.trim( container.find('.js-description').text() ),
			//'description': $.trim(container.find('.text-container').text()),
			'link': $.trim( container.find('a.itemDroppedVia_Title').attr('href') ),
			'del_url': '/del_link/' + container.attr('data-newsfeed_id'),
		}
		
		console.info('{edit popup} - show', data , 'title',content.find(title));
		if ( data['title'] ) content.find(title).val(data['title']);

		content.find( description + "," + description_fd_mention ).val( data['description'] );
		
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
