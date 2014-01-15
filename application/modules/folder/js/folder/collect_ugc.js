/*
 * Logic for the redrop popup
 * @link home page, collection page, profile top
 * @uses jquery
 * @uses common/formValidation - for the description validaton
 * @uses common/error_tooltip - used for showing an error if invalid char is added in the
 *                              "add new" in collections dropdown
 */
define(['jquery', 'common/formValidation', 'common/error_tooltip'], function() {
	/* ========================= Variables ================= */
	
	var redrop_btn = "[href='#collect_popup']";
	
	/* ======================== Events ===================== */

	/**
	 * Add hashtags
	 */
	$(document).on('click','#collect_into_folder_form .hashtag', function() {
		var textarea = $(this).closest('form').find('#redrop_description');
		textarea.val(textarea.val() + ' ' + $(this).attr('href'));
		return false;
	});

	var selector = '#redrop_description';

	$(document).on('keydown', selector, function (e) {
		var code = e.keyCode || e.which;
		if (code == 13 && !e.shiftKey ) {
			$(this).closest('form').find('input[type="submit"]').click();
			e.preventDefault();
		}
	})
	.on('keyup', function (e) {
		var code = e.keyCode || e.which;
		if (code == 13 && e.shiftKey ) {
			$(this).val( $(this).val() + '\n' );
		}
	});

	var selector = '#collect_into_folder_form';
	
	/**
	 * Validate, preAjax, submit the redrop popup form
	 */
	$(document)
		/**
		 * @since 7/17/2012 - RR - preAjax stuff for faster UI
		 * @since 8/16/2012 - RR - .on events doesnt need ot be in $(function() {})
		 * @since 8/27/2012 - RR - moved tokenInput.get outside the var definition FD-203
		 * @since 8/8/2012 - Ray - fix for new collection, the folder_id will be '0' caused folder_url broken, soluction is remove <a> if there is new collection.
		 * @since 4/15/2013 - RR - moved success popup to .success event bc folder_url cant be generated from the frontend
		 */
		.on('postAjax',selector, function() {
			console.info('redrop fast...');
			$(this).closest('#collect_popup').modal('hide');
		})
		/**
		 * @since 7/18/2012 - RR - fix refresh token input. Bug appears when redrop is used multiple times for new added colleciton
		 * @since 8/27/2012 - RR - moved tokenInput.get outside the var definition, added destroy method in token-list FD--203
		 * @since 4/15/2013 - RR - moved success popup to .success event bc folder_url cant be generated from the frontend
		 */
		.on('success',selector, function(event,data) {
			var folder_id; //it defined on the next line because $(this) returns the var itself
				folder_id = $(this).find("select[name='folder_id']").tokenInput('get')[0].id;
			
			if ( ! folder_id) {
				 $(this).find('[name=folder_id]').tokenInput('destroy');
				 // fix problem with validation
				 $(this).find('[name=folder_id]').find('option[value=0]').remove();
				 $(this).find('[name=folder_id]').append('<option value="'+data.folder.folder_id+'">'+data.folder.folder_name+'</option>');
				 jQuery.fn.initTokenInput();
				folder_id = data.folder.folder_id;
				//add new folder into the list
				$('#folders_list ul').append('<li class="folder_list_item"><a href="'+data.folder._folder_url+'">'+data.folder._display_name+'</a></li>');
			}

			console.log('redrop success: '+folder_id);

			$(document).trigger('popup_info', ['You have successfully shared this post in  <a href="'+data.folder._folder_url+'">'+data.folder._display_name+'</a>', 'success']);

			var newsfeed_id = this['newsfeed_id'].value;
			var newsfeed = $('.newsfeed_entry[data-newsfeed_id=' + newsfeed_id + ']');
			
			var redrop_count_container = newsfeed.find('.js_collect_count');
			var redrop_count_num = parseInt(redrop_count_container.text(), 10);		
			redrop_count_container.text(redrop_count_num + 1);
			
			// update redrop counts on folder top
			var total_folder_drops = $('#folderTop .js-total-redrop-count').eq(0);
				total_folder_drops.text(parseInt(total_folder_drops.text()) + 1);

			//Mixpanel tracking
			if (typeof(mixpanel) !== 'undefined') {
				var user = php.userId ? php.userId : 0;
				mixpanel.people.identify(user);
				mixpanel.track('Redrop', {'id':$(this).find('[name=newsfeed_id]').val(), 'folder':folder_id, 'user':user});
			}

		});

	/**
 	 * Open the collect popup
	 * invoked by clicking on the 'Collect' link anywhere on the newsfeed or popup.
	 * @since 7/17/2012 - RR - added container and newsfeed_id vars with more universal and maintenanble selector
	 */
	$(document).on('before_show', redrop_btn, function(e, content) {

		//RR - hack for closing edit popup - I couldnt find where the event is stopped.
		if (window.opened_ft_dropdown) window.opened_ft_dropdown.trigger('ft_dropdown_close');
		e.stopPropagation();
		$(content).find('form .error').hide();
		var container = $(this).closest('[data-newsfeed_id]');
		var newsfeed_id = container.attr('data-newsfeed_id');
		console.log('INIT COLLECT', container, newsfeed_id, container.find('.drop-description'));
		
		$(content).find('input[name="newsfeed_id"]').val(newsfeed_id);
		var desc = '';
		if ($('.in #preview_popup, #preview_popup.in').length > 0) { //poppup click
			desc = $.trim($('.in #preview_popup .pop_up_title, #preview_popup.in .pop_up_title').text());
		}
		else if ($('#preview_popup.full_page').length > 0) {
			desc = $.trim($('#preview_popup.full_page .pop_up_title').text());
		}
		else {  //direct click from newsfeed
			desc = $.trim(container.find('.js-description').text());
		}

		var textarea = $(content).find("textarea[name=description]");
			textarea.val(desc);
		$(content).find(".textLimit").text(parseInt(textarea.attr("maxlength")) - parseInt(textarea.val().length));

	});

});
