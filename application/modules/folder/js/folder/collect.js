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

	$(document).on('keydown', '#redrop_description', function (e) {
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

	$(document).on('onAdd','input.add-item-btn',function(){
		$('div.error_cstm').hide();
	});

	/**
	 * Validate, preAjax, submit the redrop popup form
	 */
	 var selector = '#collect_into_folder_form';
	$(document)
		/*
		 * @since 8/25/2012 DK - Validation if description is empty.
		 * @todo - use common/formValidate
		 */
		.on('validate',selector,function(e,callback) {

			var $form = $(this);
			if ($(this).hasClass('error')) {
				return callback.call(this, {status:false});
			}
			$('div.error_cstm', $form.find("select[name='folder_id']").parent()).hide();
			
			var selected_folder = $form.find("select[name='folder_id']").tokenInput('get')[0];

			if (!selected_folder || selected_folder['name'] == 'Click to Add') {
				$('div.error_cstm', $form.find("select[name='folder_id']").parent()).show();
				return callback.call(this, {status:false});
			}

			if (selected_folder['id']) { //Collection selected so validate

				$.post('/validate_collection/'+selected_folder['id'], {'folder_name': selected_folder['name']}, function(data) {
					if (! data.status) {
						callback.call(this, {status:true});
					} else {
						$form.find('.error').html('The collection '+selected_folder['name']+' has been deleted recently and does not exist anymore!').show();
						callback.call(this, {status:false});
					}
				},'json');

			} else {                   //New collection created
				callback.call(this, {status:true});				
			}
		})
		/**
		 * @since 7/17/2012 - RR - preAjax stuff for faster UI
		 * @since 8/16/2012 - RR - .on events doesnt need ot be in $(function() {})
		 * @since 8/27/2012 - RR - moved tokenInput.get outside the var definition FD-203
		 * @since 8/8/2012 - Ray - fix for new collection, the folder_id will be '0' caused folder_url broken, soluction is remove <a> if there is new collection.
		 * @since 4/15/2013 - RR - moved success popup to .success event bc folder_url cant be generated from the frontend
		 */
		.on('postAjax', function() {
				
			console.info('redrop fast...');

			$(this).closest('#collect_popup').modal('hide');

		})
		/**
		 * @since 7/18/2012 - RR - fix refresh token input. Bug appears when redrop is used multiple times for new added colleciton
		 * @since 8/27/2012 - RR - moved tokenInput.get outside the var definition, added destroy method in token-list FD--203
		 * @since 4/15/2013 - RR - moved success popup to .success event bc folder_url cant be generated from the frontend
		 */
		.on('success', selector, function(event,data) {

			var folder_id; //it defined on the next line because $(this) returns the var itself
				folder_id = $(this).find("select[name='folder_id']").tokenInput('get')[0].id;
				
			if ( ! folder_id) {
				$(this).find('[name=folder_id]').tokenInput('destroy');
				$(this).find('[name=folder_id]').append('<option value="'+data.folder.folder_id+'">'+data.folder.folder_name+'</option>');
				jQuery.fn.initTokenInput();
				folder_id = data.folder.folder_id;
				//add new folder into the list
				$('#folders_list ul').append('<li class="folder_list_item"><a href="'+data.folder._folder_url+'">'+data.folder._display_name+'</a></li>');
			}

			console.log('redrop success: '+folder_id);
			
			$('#collect_success_popup').show().css({'margin-top': -190, 'z-index':3000}).removeClass('fade')
				.find('a').attr('href', data.folder._folder_url).html(data.folder._display_name);
			
			window.setTimeout(function() {
				$('#collect_success_popup').hide();
			}, 5000);

	
			var newsfeed_id = this['newsfeed_id'].value;
			var newsfeed = $('.newsfeed_entry[data-newsfeed_id=' + newsfeed_id + ']');
			var redrop_count_container = newsfeed.find('.js-redrop_count');	
			var redrop_count_num = parseInt(redrop_count_container.text(), 10);		

			// redrop count in preview_popup or in drop_page
			var redrop_numbers = $('#preview_popup div.redrop_count, #content div.redrop_count');
				redrop_numbers.text( parseInt( redrop_numbers.text()) + 1  );

			// update drop count in main screen
			var main_redrop_numbers = $('#folder_top a.num_drops strong').eq(0);
				main_redrop_numbers.text( parseInt(main_redrop_numbers.text()) + 1 );

			if ( ! isNaN(redrop_count_num)) {
				redrop_count_container.text(++redrop_count_num);
				newsfeed.find('.js-redrop_count').text(redrop_count_num);
			}

			// add myself to redrop list
			var redrop_list = $('.popup_right-redrops ul:first');
			redrop_list.find('.no_redrops').hide().end().parent().find('h3').show();
			redrop_list.find('.sample').clone().removeClass('sample')
				.find('.folder_name').html( data.folder.folder_name )
				.end().prependTo( redrop_list ).show();

			// if redrop count > 4
			if ( redrop_list.find('.redrop:not(.sample)').length > 4 ) {
				var redrop_more_count = $('#preview_popup #redrop_more_count, #content #redrop_more_count');
				if ( redrop_more_count.parent().is(':hidden') ) {
					redrop_more_count.parent().show();
					redrop_more_count.html( '1' );
				} else {
					redrop_more_count.html( parseInt(redrop_more_count.html()) + 1 );
				}

				redrop_list.find('.redrop:not(.sample):last').hide().remove();
			}

			//remove last item & add recent drop
			if ( data.profile_top_element ) {
				$('#profile_drops .profile_drop:last').remove();
				$('#profile_drops .profile_drop[data-url]:first').before( data.profile_top_element );
			}
	
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
		$(content).find('.error,.error_cstm').hide();
		var container = $(this).closest('[data-newsfeed_id]');
		var newsfeed_id = container.attr('data-newsfeed_id');
		
		console.log('INIT COLLECT', container, newsfeed_id, container.find('.drop-description'));
		
		$(content).find('input[name="newsfeed_id"]').val(newsfeed_id);
		if ($('.in #preview_popup, #preview_popup.in').length > 0) { //poppup click
			$(content).find("textarea[name='description']").val($.trim($('.in #preview_popup .pop_up_title, #preview_popup.in .pop_up_title').text()));
		} else if ($('#preview_popup.full_page').length > 0) {
			$(content).find("textarea[name='description']").val($.trim($('#preview_popup.full_page .pop_up_title').text()));
		} else {  //direct click from newsfeed
			$(content).find("textarea[name=description]").val($.trim(container.find('.drop-description').text()));
		}
	});

	$(document).ready(function() {

		var orig_input = $(this).find("select[name='folder_id'][tooltip-pos]");

		/**
		 * Add validation to the "add new" folder in collections dropdown 
		 * RR - removed - http://dev.fantoon.com:8100/browse/FD-3248
		 */
		//$(this).find("select[name='folder_id']").tokenInput('option','addBoxValidate', function(e) {
		//	if (e.which >= 48 && e.which <= 59 && e.shiftKey || (this.value.length > 40 && e.which != 8) ) {
		//		
		//		$(this).attr('tooltip-pos',orig_input.attr('tooltip-pos'));
		//		$(this).attr('tooltip-class',orig_input.attr('tooltip-class'));
		//		$(this).error_tooltip();
		//		if (this.value.length > 40) {
		//			$(this).trigger('activate_tooltip','Please type shorter name');
		//		} else {
		//			$(this).trigger('activate_tooltip','Special Characters are not allowed');
		//		}
		//		
		//		return false;
		//	}
		//	$(this).trigger('deactivate_tooltip');
		//	return e.which==8 || e.which==37 || e.which==39 || String.fromCharCode(e.which).match(/[^A-Za-z0-9-_\s]/) === null;
		//});
	
	});

});
