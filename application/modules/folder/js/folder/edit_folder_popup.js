/*
 * Add/Edit folder popup
 * @link - collections page
 * @uses folder/folder_main - to call ajaxList_process when new folder is created
 * @uses social/all - to connect user to fb if he selects facebook.com for source
 * @uses plugins/token-list - for the sources, collaborators and hashtags dropdowns
 * @uses common/ajaxList - for creating new collection func
 * @uses jquery
 */
define([ 'folder/folder_main', 'social/all' ,'plugins/token-list', 'common/ajaxList', 'common/error_tooltip', 'jquery'], function( folder_main ) {


	/* =============================== Variables ============================= */
	var self = '#edit_folder_popup';
	var form = ' #basic_form';
	var folder_top = '#folder_top';
	var folder_title = ' #folder_title';
	var name_input = ' input[name=folder_name]';
	var id_input = ' input[name=folder_id]';
	//folder page selectors
	var hashtags_container = '#collection_topic_list';
	var hashtags = ' .js-hashtag';
	var hashtag_sample = '#js-hashtag';
	var collaborators_container = '#collection_collaborator_list';
	var collaborator_sample = '#js-collaborator';
	/**
	 * save the original url so upon folde rename we can find relevant drop menu entries and rename them too
	 * @since 01/01/1990 - bobef: #FD-1705
	 */
	var originalFolderUrl = location.href;
	
	/* =========================== Private functions ========================== */
	
	/**
	 * Used in profile page (list of collections). Returns the folder_item object or creates a new one
	 * if not found
	 * @see $(self+form).on('success'
	 */
	function update_folder_item ( data ) {
		
		var $folder =  $( ".js-folder[data-folder_id='" + data.folder_id + "']" );
		var $item = $( '#tmpl-folder-item' ).tmpl( data, folder_main.ajaxList_process );
		if ( $folder.length > 0 ) {
			$folder.replaceWith( $item ) ;
		}
		else {
			$( '#create_new_collection_folder' ).after( $item );
		}
	}
	
	/**
	 * Update the hashtags in collection page top
	 * @see update_folder_page()
	 */
	function update_hashtags(hashtag) {
		if (!$(hashtags_container).length) return;
		$(hashtags_container).html($(hashtag_sample).tmpl(hashtag)); 
	}
	
	/**
	 * Update the collaborators in collection page top
	 * @see update_folder_page()
	 */
	function update_collaborators(collaborators) {

		console.warn('collaborators',collaborators);
		//var collaborators = {'_url':'test','_avatar_small':'https://s3.amazonaws.com/fantoon-dev/users/4fe234cfa6a9b_small.jpg','full_name':'Geno Genov'};

		if (!$(collaborators_container).length) return;

		// FD-3384 avoid collaborators list from adding an empty box
		if ( collaborators ) {

			$('[href="#edit_folder_popup"], [data-url="#edit_folder_popup"]').attr("data-collaborators_json",collaborators);

			 if (collaborators)	{
			 	collaborators = jQuery.parseJSON(collaborators);
			 }

			$(collaborators_container).html($(collaborator_sample).tmpl(collaborators));
			console.log('update collaborator: ', collaborators);
		}
	}
	
	/**
	 * Used in user collection page
	 * @see $(self+form).on('success'
	 */
	function update_folder_page(data) {
		//update etitle
		$(folder_top+folder_title).attr('href',data.folder_url)
			.find('h1, h2').text(data.folder_name).attr('title',data.folder_name);
	
		//replace the url with new url
		if ($('#main_folder_content').length > 0) {
			if (typeof history.replaceState == 'function') {
				history.replaceState({foo: "bar"}, "", data.folder_url);
			} else {
				window.location.hash = data.folder_url;
			}
		}
		//bobef: #FD-1705 - rename title
		document.title = data.folder_name;

		//rename drop menu entry
		$( '#folder_top .collections-dropdown #folders_list .folder_list_item > a[href="' + originalFolderUrl + '"]' )
			.prop( 'href', data.folder_url )
			.text( data.folder_name );
		
		if ($('.edit_folder_btn').attr("data-sort_by") != data.sort_by)	{
			// reload the first page in order to sort the page
			reload_first_page = true;
		}
		//update the edit button data
		$('.edit_folder_btn').attr({
								'data-folder_name':data.folder_name,
								'data-hashtag-hashtag_name': data.hashtag ? data.hashtag._hashtag_name : '',
								'data-hashtag_id': data.hashtag_id,
								'data-rss_source_id': data.rss_source_id,
								'data-sort_by': data.sort_by,
								'data-rss_source-source': data.rss_source ? data.rss_source.source : ''
							});
		//update the hashtag button data
		//quang: change data.hashtag.hashtag -> _hashtag_name (FD-3474)
		$('.topic_add_btn').attr({
								'data-hashtag-hashtag_name': data.hashtag ? data.hashtag._hashtag_name : '',
								'data-hashtag_id': data.hashtag_id			
		});
		
		update_hashtags(data.hashtag);

		update_collaborators(data._collaborators_json); // "folder_collaborators" changed to "contributors"

		// add code to reload drops in the page

		if (typeof reload_first_page != 'undefined' && reload_first_page)	{
			$.get('/newsfeed/collection/' + data.folder_id + '?page=0',function(html){
				$('#folder_contents').html(html);
				reload_first_page = false;
			});
		}

	}
	
	/* ============================= Direct code ======================== */
	$.fn.initTokenInput();
	
	/* ============================ Events ========================= */
	/**
	 * Connect to twitter and others is done on click event to prevent popup blocking
	 */
	$(document).on('click',self+form+' [type=submit]', function() {
		var $self = $(this).closest('form');
		if ($self.find('[name="rss_source_id[1]"]').length && !php.has_fb_token) {
			fb_login(function() {
				php.has_fb_token = true;
			}, function() {
				$self.find('.error:first').show().text('You need to be connected to fb.');
			});
		} else if ($self.find('[name="rss_source_id[2]"]').length && !php.has_twt_id) {
			twt_login(function(id) {
				php.has_twt_id = true;
			}, function(msg) {
				$self.find('.error:first').show().text('You need to be connected to twitter.');
			});
		}
	});
	/**
	 * Submit popup form (Add/Edit folder)
	 */
	$(document)
		.on('validate',self+form, function(e,callback) {
			var $self = $(this);
			if ($(this).hasClass('error')) {
				return callback.call(this, {status:false});
			}

			// if folder information is NOT changed (ex: only change hashtag,..)
			// then we don't post validation by back-end
			if ( $(self+form).find('.basic_section').is(':hidden') ) {
				return callback.call(this, {status:true});
			}
		
			// validation (by back-end) for exist folder info
			var folder_name = $self.find(name_input).val();
			var folder_id = $self.find(id_input).val();
			$.post('/validate_collection/'+folder_id, {'folder_name': folder_name}, function(response) {

				if (response.status) {
					$self.find('.error:first').show().text(php.lang.duplicate_name);
					return callback.call(this, {status: false});
				}

				if ($self.find('[name="rss_source_id[1]"]').length && !php.has_fb_token) {

					function wait_fb_token() {
						if (php.has_fb_token) {
							callback.call(this, {status: true});
						} else {
							window.setTimeout(wait_fb_token, 100);
						}
					}

					return wait_fb_token();

				} else if ($self.find('[name="rss_source_id[2]"]').length && !php.has_twt_id) {

					function wait_twt_id() {
						if (php.has_twt_id) {
							callback.call(this, {status: true});
						} else {
							window.setTimeout(wait_twt_id, 100);
						}						
					}

					return wait_twt_id();
				} else {

					var rss_source = $self.find('.rss_source_section .token-input-list input');
					if (rss_source.val()) {
						window.validate_url(rss_source)
						$self.find('.rss_source_section input.tokenInput').tokenInput('add', {
							'id' : 0,
							'name' : rss_source.val()
						});						
					}
					
					return callback.call(this, {status: true});
				}
			},'json');
		})
		.on('success', ,self+form, function(event, response) {

			// remove delete_hashtag
			if ( $(this).find('input[name=delete_hashtag]:hidden').length > 0 ) {
				$(this).find('input[name=delete_hashtag]:hidden').remove();
				$('#collection_topic_list')
					.find('.js-hashtag').hide().remove()
					.end().append('<li class="js-hashtag topic item warning" style="background-color: rgb(255, 200, 0)"> <a class="item_link" href="#"> <span class="item_label" id="select_hashtag">Select a hashtag</span> </a> </li>');
				$('#edit_folder_popup .hashtag_section select').tokenInput('clear');

				// clean up old data
				$('.topic_add_btn').attr({'data-hashtag_id':'0', 'data-hashtag-hashtag_name':''});
				$('div.topic_add .item_label').html('+Add');
			}
			
			var $form = $(this);
			
			if (!response.status) {
				console.info('ERROR: ',response.error);
				if (typeof response.field != 'undefined')	{
					$(this).find('.' + response.field).show().html(response.error);
				}	else{
					$(this).find('.error:first').show().html(response.error);
				}
				return false;
			}
			
			data = response.data;
			
			if (typeof(mixpanel) !== 'undefined') {
				var user = php.userId ? php.userId : 0;
				mixpanel.people.identify(user);
				mixpanel.track('Create New Colection', {'user':user});
			}
			
			// collection edit page
			if ( $( '#all_folders[data-template="#tmpl-folder-item"]' ).length > 0 ) { //collections
				if (!$form.find('[name=folder_id]').val() && !data.rss_source_id && data.hashtag && data.hashtag._hashtag_url) {
					location.href = data.hashtag._hashtag_url+'&action=created_collection&folder_id=' + data.folder_id;
				} else {
					if (data.rss_source_id == 1) {
						$('#create_new_collection_folder [data-rss_source_id=1]').addClass('disabled');
					} else if (data.rss_source_id == 2) {
						$('#create_new_collection_folder [data-rss_source_id=2]').addClass('disabled');
					} 

					update_folder_item( data );
				}
			} else { //profile
				update_folder_page(data);
			}
				
			$form.closest('.modal').modal('hide');

			// to hide or show collaborator_showmore text (FD-2641)
			$('#collection_collaborator_list').trigger('update'); 

			$('.topic_add_btn .item_label').text('Change');


		}); //end success
	//End popup form events

	$(document).on('click', "#select_hashtag", function(){
		$('.topic_add_btn').trigger('click');
	})

	$(document).on('click','.delete_hashtag', function(){
		$(self+form).append('<input type="hidden" name="delete_hashtag" value="yes">');
		$('#save_data').closest('form').submit();
	})

	/**
	 * Edit or Add folder popup - set data / populate the popup
	 */
	$(document).on('before_show', '[href="#edit_folder_popup"], [data-url="#edit_folder_popup"]', function(ui, content) {

		console.info('{Edit folder popup} - Show');
		//Get the folder data
		var container = $(this).closest('[data-folder_id]');
				
		if ($(this).hasClass('new_folder_btn')) {
			content.addClass('new_collection');
		} else {
			content.removeClass('new_collection');
		}
		
		if (container.attr('data-folder_name')) {
			content.find('form').removeClass('error');
		} else {
			content.find('form').addClass('error');
		}

		// change create new collection save button text
		content.find('#save_data').val( container.attr('data-folder_id') ? php.lang.save_button : php.lang.create_button );
		
		content
		//Reset - form fields visibility
			.find('form .error').hide()
		//Set the popup doata
			.end().find('#basic_form input[name=folder_id]').val(container.attr('data-folder_id'))
			.end().find('#basic_form input[name=folder_name]').val(container.attr('data-folder_name')).focus()
			

			.end().find('#basic_form input[name=sort_by][value=' + (container.attr('data-sort_by') || 0) + ']').attr("checked",true)
			
			.end().find('#basic_form input[name=exclusive]').attr('checked', container.attr('data-exclusive') == '1')
			.end().find('#basic_form input[name=edittype]').val($(this).attr('data-edittype'))
		//Reset - Collaborators
			.end().find('#collaborator_form').tokenInput('clear')
			.end().find('#token-input-collaborator_form').val('').trigger('blur')
			.end().find('#collaborators .item:not(.sample)').remove()
			.end().find('#basic_form input[name=collaborators]').remove()
			
		var hashtag_input = content.find('.hashtag_section input.tokenInput');
		hashtag_input.tokenInput('clear');
		if (container.attr('data-hashtag_id') && container.attr('data-hashtag_id') != '0') {
			hashtag_input.tokenInput('add', {
											'id':container.attr('data-hashtag_id'),
											'name': container.attr('data-hashtag-hashtag_name')
										});
		}
		hashtag_input.tokenInput('option','addBoxValidate', function(e) {
			var is_valid = e.which==8 || e.which==37 || e.which==39 || String.fromCharCode(e.which).match(/[^A-Za-z0-9-_]/) === null;
			
			if (!is_valid) {
				$(this).attr('tooltip-pos',hashtag_input.attr('tooltip-pos'));
				$(this).attr('tooltip-class',hashtag_input.attr('tooltip-class'));
				$(this).error_tooltip();
				if (this.value.length > 40) {
					$(this).trigger('activate_tooltip','Please type shorter name');
				} else {
					$(this).trigger('activate_tooltip','Special Characters are not allowed');
				}
				return false;
			}
			$(this).trigger('deactivate_tooltip');
			return is_valid;
		});
		
		var rss_source_id = parseInt(container.attr('data-rss_source_id') || $(this).attr('data-rss_source_id') || 0);
		content.find('.rss_source_section input.tokenInput').tokenInput('clear');
		content.find('.rss_source_section .token-input-list input').val('');
		content.find('.collaborators_section').hide();
		content.find('.rss_source_section').hide();
		if (rss_source_id == 1) {
			content.find('[name=folder_name]').val('Facebook List').attr('readonly','readonly');
			content.find('.rss_source_section input.tokenInput').tokenInput('add', { 'id' : 1, 'name' : 'Facebook' });
		} else if (rss_source_id == 2) {
			content.find('[name=folder_name]').val('Twitter List').attr('readonly','readonly');
			content.find('.rss_source_section input.tokenInput').tokenInput('add', { 'id' : 2, 'name' : 'Twitter' });
		} else if (rss_source_id == 0) {
			content.find('[name=folder_name]').removeAttr('readonly');
			content.find('.collaborators_section').show();
		} else {
			content.find('[name=folder_name]').removeAttr('readonly');
			content.find('.rss_source_section').show();
			if (rss_source_id > 0) {
				content.find('.rss_source_section input.tokenInput').tokenInput('add', { 'id' : rss_source_id, 'name' : container.attr('data-rss_source-source') });
			}
		}
		
		if (container.attr('data-collaborators_json')) {
			var collaborators = $.parseJSON(container.attr('data-collaborators_json').replace(/'/g,'"'));
			for (var i in collaborators) {
				$('#collaborator_form').tokenInput('add',{'id': collaborators[i].id, 'name': collaborators[i].name});
			}
		}

		//Used for adding hashtag only or collaborators only. The "+Add" button in folder header
		if ($(this).attr('data-edittype') == 'hashtags') {
			content.find('.basic_section, .collaborators_section').hide();
			content.find('.hashtag_section').show();
		} else if ($(this).attr('data-edittype') == 'collaborators') {
			content.find('.basic_section, .hashtag_section').hide();
			content.find('.collaborators_section').show();
		} else {
			//If the user is not an owner hide the basic info.
			if (container.attr('data-owner') != '0' || $(this).hasClass('new_folder_btn')) {
				content.find('.basic_section').show();
			} else {
				content.find('.basic_section').hide();
			}
			content.find('.hashtag_section').show();
		}

		// show or hide 'delete_hashtag' button
		if (container.attr('data-hashtag_id') && container.attr('data-hashtag_id') != '0') {
			content.find('.delete_hashtag').show();
		} else {
			content.find('.delete_hashtag').hide();
		}
		
	});//End popup set data

});
