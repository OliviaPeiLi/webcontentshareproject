/*
 * Add/Edit folder popup
 * @link - collections page
 * @uses folder/folder_main - to call ajaxList_process when new folder is created
 * @uses common/ajaxList - for creating new collection func
 * @uses jquery
 */
define(['folder/folder_main', 'common/ajaxList', 'plugins/jquery.form', 'jquery','plugins/jquery-ui-timepicker-addon'], function( folder_main ) {


	/* =============================== Variables ============================= */
	var self = '#edit_folder_popup';
	var folder_top = '#folder_top';
	var form = self+' form';
	
	var file_input = ' input[type=file]';
	var name_input = ' input[name=folder_name]';
	var id_input = ' input[name=folder_id]';
	var img_input = ' input[name=logo]';
		
	/* =========================== Private functions ========================== */
	
	/**
	 * Used in profile page (list of collections). Returns the folder_item object or creates a new one
	 * if not found
	 * @see $(self+form).on('success'
	 */
	function update_folder_item ( data ) {
		console.info('{Contest popup} - update folder item');
		
		var $folder =  $( ".js-folder[data-folder_id='" + data.folder_id + "']" );
		var $item = $( '#tmpl-folder-item' ).tmpl( data, folder_main.ajaxList_process );
		if ( $folder.length > 0 ) {
			$folder.replaceWith( $item ) ;
		} else {
			$( '#create_new_collection_folder' ).after( $item );
		}
	}
	
	/**
	 * Used in user collection page
	 * @see $(self+form).on('success'
	 */
	function update_folder_page(data) {
		console.info('{Contest popup} - update folder page');
		//update header
		$('#sxsw_top .sxsw-title').attr('href', data.folder_url);
		$('#sxsw_top .dashboard').attr('href', data.folder_url+'/dashboard');
		
		//update etitle
		$(folder_top+' h1').text(data.folder_name).attr('title',data.folder_name);
		$(folder_top+' .info').html(data.info);
		$(folder_top+' h2 .ends_at').show().find('span').html(data._ends_at_formatted);
	
		//replace the url with new url
		if (typeof history.replaceState == 'function') {
			history.replaceState({foo: "bar"}, "", data.folder_url);
		} else {
			window.location.hash = data.folder_url;
		}
			
		//bobef: #FD-1705 - rename title
		document.title = data.folder_name;
		
		//update the edit button data
		$('.edit_folder_btn').attr({
								'data-folder_name':data.folder_name,
								'data-ends_at': data.ends_at,
								'data-info': data.info,
								'data-is_open': data.is_open,
							});
	}
		
	/* ============================ Events ========================= */
	
	/**
	 * Submit popup form (Add/Edit folder)
	 */
	$(document)
		.on('validate', form, function(e,callback) {
			var $self = $(this);
			if ($(this).hasClass('error')) {
				return callback.call(this, {status:false});
			}

			// validation (by back-end) for exist folder info
			var folder_name = $self.find(name_input).val();
			var contest_id = $self.find('input[name="contest_id"]').val();
			var folder_id = $self.find(id_input).val();
			$.post('/validate_collection/'+folder_id, {'folder_name': folder_name, 'contest_id': contest_id}, function(response) {
				if (response.status) {
					$self.find('.error:first').show().text(php.lang.duplicate_name);
					return callback.call(this, {status: false});
				} 
				return callback.call(this, {status: true});
			},'json');
		})
		.on('preAjax', form, function(event,response){

			var datestr = $('input.contest_endDate',form).attr("disabled",true).val();
				datestr = datestr.split('/');
			$('input.contest_replaced_ends_date',form).val(datestr[2]+"-"+datestr[0]+"-"+datestr[1]);

		})
		.on('success', form, function(event, response) {
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
			
			// collection edit page
			if ( $( '#all_folders[data-template="#tmpl-folder-item"]' ).length > 0 ) { //collections
				update_folder_item( data );
			} else { //profile

				var d = data.ends_at.split(' ');
				var od = d[0].split('-');
				data.ends_at = od[1] + "/" + od[2] + "/" + od[0] + ' ' + d[1];

				update_folder_page(data);
			}
				
			$form.closest('.modal').modal('hide');
		}); //end success
	
	$(document).on('change', form+file_input, function() {
		console.log('{edit form} - upload image');
		var $this = $(this);
		var $form = $this.closest('form');
		
		//container.find('li:not(.sample)').remove();
		$form.find('.error').hide();
		
		$form.addClass('loading')
			.ajaxSubmit({
				success:  function(response)  {
					console.log('{edit form} - upload image callback', response);
					$form.removeClass('loading')
					if (response.error) {
						$form.find('> .error').show().html(response.error);
						return;
					}
					$(self+img_input).val(response.filename);
					$('#sxsw_top .sxsw-title img').attr('src', response.thumb);
	
				},
				complete: function () { },
				dataType: 'json'
			});
	});
	//End popup form events        

	/**
	 * Edit or Add folder popup - set data / populate the popup
	 */
	var selector = '[href="#edit_folder_popup"], [data-url="#edit_folder_popup"]';
	
	$(document).on('before_show', selector, function(ui, content) {

		$( ".contest_endDate" ).datepicker( "destroy" ).datepicker({
		      changeMonth: true,
		      changeYear: true,
		      minDate : 0,
		      maxDate : '+4Y'
		}).attr("readonly",true).attr("disabled",false);

		$('.contest_endTime').timepicker({
			altFieldTimeOnly: false,
			timeFormat: 'hh:mm:ss',
			dateFormat: 'mm/dd/yy'
		});

		console.info('{Edit folder popup} - Show');
		//Get the folder data
		var container = $(this).closest('[data-folder_id]');
				
		if (container.attr('data-folder_name')) {
			content.find('form').removeClass('error');
		} else {
			content.find('form').addClass('error');
		}

		// change create new collection save button text
		content.find(' input[type="submit"]').val( container.attr('data-folder_id') ? php.lang.save_button : php.lang.create_button );
		
		//Reset - form fields visibility
		content.find('form .error').hide()
		//Set the popup data
			if (container.length) {
				content.find(' [name=folder_id]').val(container.attr('data-folder_id'));
				// var ends_at = (container.attr('data-ends_at').split(' ')[0]).split("-");
				// 	console.warn('ends_at',ends_at);
					//ends_at = ends_at[1] + "/" + ends_at[2] + ends_at[0];
				content.find(' [name="ends_at[date]"]').val(container.attr('data-ends_at').split(' ')[0]);
				content.find(' [name="ends_at[time]"]').val(container.attr('data-ends_at').split(' ')[1]);
				content.find(' [name="info"]').val(container.attr('data-info'));
				if (container.attr('data-is_open') == 1) {
					content.find(' [name="is_open"]').attr('checked','checked');
				} else {
					content.find(' [name="is_open"]').removeAttr('checked');
				}
				content.find(' input[name="folder_name"]').val(container.attr('data-folder_name')).focus();
			}
			
	});
	
	if (location.search.indexOf('edit=true') > -1) {
		$('[href="#edit_folder_popup"]').trigger('click');
	}

});
