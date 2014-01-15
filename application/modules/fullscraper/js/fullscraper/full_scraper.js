/**
 * Logic for Edit tile popup such as Ajax for update
 * Handles changes to Description inside the tile.
 * invoked by clicking on edit button inside a newsfeed item.
 * The delete button logic is in newsfeed.js and drop_page.js
 * @uses plugins/mentions - for the description textarea
 * @uses common/formValidation
 * @uses jquery
 */
define(['plugins/mentions', 'common/formValidation', 'plugins/jquery.form', 'jquery'],function() {
	
	var wrap = $('#full_scraper');
	var copy_wrap = $('#drops_elements');

	// file upload field
	var temp_img_input = " .hidden_upload [type=file]";
	var link_input = " [name='activity[link][link]']";
	var content_input = " [name='activity[link][content]']";
	//var title_input = " textarea[name=description]";
	var temp_img_input = " .hidden_upload [type=file]";
	var media_input = " [name='activity[link][media]']";
	var img_input = " textarea[name=img]";
	var forward_link = '';
	var preloader_status_ok = ".preloaders img.status_ok";
	var preloader_status_preloader = '.preloaders img.preloader';

	var cancel_obj = $('#drops_elements div.submit_all .cancel_button');
	var submit_button = $('#drops_elements div.submit_all input[type=submit]')

	var _folder_id = 0;

	function match_url($form, $input,$folder_id, callback) {

		if ( !window.validate_url($input, false) ) return;
		
		$(preloader_status_ok,$form).hide();
		$(preloader_status_preloader,$form).show();

		if ($input.val().indexOf('fandrop.com') > -1) {
			$(self+link_input).val('');
			$('#open_redrop_info').click();
			return;
		}

		$.post('/internal_scraper/get_cached_content', {'link': $($input).val(), 'folder_id':$folder_id }, function (data) {

			if (!data.status || data.error) {
				$input.closest('.form_row').find('.error').show().html(data.error);
				//$('.js_image_upload,.js_url_upload',$form).find('.error').show().html(data.error);
				//$(preloader_status_preloader,$form).hide();
				return;
			}

			$(preloader_status_preloader,$form).hide();
			$(preloader_status_ok,$form).show();
			

			console.info('{internal scraper} - content', data);

			if ( typeof data.folder_url != 'undefined' && data.folder_url != "")	{
				forward_link = data.folder_url;
			}

			if (data['class'] == 'image') {

				// content is an image
				$('div.image_preview',$form).css("background",'url("' + data.image + '")');
				//$('.image_preview img', $form).attr("src",data.image);
				$('.image_preview img', $form).remove();

				callback();
			
			} else if (data['class'] == 'html') { //Page contents	

				// set link url
				$('input[name=link_url]',$form).val($($input).val());

				//The cached data may not have content but just images
				if (data['content']) {
					// switch to content
					$(content_input,$form).val($('<div/>').html( data.content ).text()); //decode contents which are encoded bc of new relic
					$(img_input,$form).val('');
					callback();

				} else {

					$.post('/internal_scraper/get_content', {'link': $($input).val()}, function (data) {

						if (data['class'] == 'html') {

							$(content_input,$form).val($('<div/>').html(data.content).text()); //decode contents which are encoded bc of new relic
							$(img_input,$form).val('');
							
							// new content get
							console.info('content');
							callback();

						} else { // Video thumb

							$(media_input, $form).val(data.media);
							$(img_input, $form).val(data['data'][0].src);
							// $(self+media_input).val(data.media);
							// $(self+img_input).val(data['data'][0].src);
							callback();
						
						}

					},'json');

				}
			} else {  //Video thumb
				// set link url
				$('input[name=link_url]',$form).val($($input).val());

				// embed video
				$( 'input[name=img]', $form ).val( data['data'][0].src );
				$( media_input,$form).val( data.media );

				// console.warn(data.media);

				//$('.image_preview img', $form ).attr('src', data['data'][0]['src']).removeClass('loading');
				$('div.image_preview',$form).css("background",'url("' + data['data'][0].src + '")');
				//$('.image_preview img', $form).attr("src",data.image);
				$('.image_preview img', $form).remove();				

				callback();
			}

		},'json');
	}

	// add templates on click
	$('ul.modes_menu a',wrap).on("click",function(){

		 if (cancel_obj.hasClass("disabled"))	{
		 	return false;
		 }

		var clonned_form = $($(this).attr("href")).clone().removeAttr("id");
		copy_wrap.prepend(clonned_form);

		var folder_id = $("#full_scraper select[name='folder_id']").tokenInput('get')[0];
		$('input[name=folder_id]',clonned_form).attr("name","folder_id[" + folder_id.id + "]").val(folder_id.name);
		_folder_id = folder_id.id;

		var type= $(this).attr("href").replace("#fullscraper_add_","");

		$('a.use_an_url',clonned_form).click(function(){
			$('dd.js_image_upload',clonned_form).hide();
			$('dd.js_url_upload',clonned_form).show();
			$('input[name=type]',clonned_form).val('url');
		return false;
		});

		$('a.upload_file',clonned_form).click(function(){
			$('dd.js_url_upload',clonned_form).hide();
			$('dd.js_image_upload',clonned_form).show();
			$('input[name=type]',clonned_form).val('image');
		return false;
		});

		$('input[value=Cancel]',clonned_form).click(function(){
			$(clonned_form).remove();
		});

		$("span.hashtags a",clonned_form).click(function() {
			var textarea = $('textarea.fd_mentions',clonned_form);
			textarea.val( textarea.val().replace('Enter a title...','') +' '+ $(this).attr('href') ).focus().keydown().keyup();
			return false;
		});		

		$('#drops_elements div.submit_all').show();

	});

	/* image url change - useful for image preview */
	$('#drops_elements textarea[name=img]').on("change",function(){
		var $form = $(this).closest("form");
		match_url( $form, $(this), _folder_id , function() {} );		
	});

	/* image url change - useful for image preview */
	$('#drops_elements ' + link_input).on("change",function(){
		var $form = $(this).closest("form");
		match_url( $form, $(this), _folder_id , function() {} );		
	});

	/* preloader for url change */
	$('textarea.with_preloader').on("change",function(){
		var $form = $(this).closest("form");
		match_url( $form, $(this), _folder_id , function() {} );
	});

	$('.upload_img_filename').on("click",function(){
		$('input[type=file]',$(this).parent()).trigger("click");
	});

	$('#drops_elements form input[type=file]').on('change', function() {

		var _this = $(this);
		$form = $(this).closest("form");
		$(preloader_status_ok,$form).hide();
		$(preloader_status_preloader,$form).show();

		/* check max size */
		var max_size = $(this).attr("data-max_mb") * 1024 * 1024;

		if (this.files[0].size > max_size)	{
			$form.find('.error:first').show().html('<span class="error_contents"></span>' + 'The file size should be less then ' + $(this).attr("data-max_mb") + "MB");
			$(preloader_status_preloader,$form).hide();
			return;
		}

		$form.addClass('loading')
			.ajaxSubmit({
				success:  function(response)  {

					console.log('{internal scraper} - upload image callback', response);
					$form.removeClass('loading')

					if (response.error) {
						$form.find('.error:first').show().html('<span class="error_contents"></span>' + response.error);

						_this.wrap('<form>').closest('form').get(0).reset();
						_this.unwrap();

						$(preloader_status_preloader,$form).hide();
						return;
					}

					$('div.image_preview',$form).css("background",'url("' + response.thumb + '")');
					$('.image_preview img', $form).remove();	
					$(_this).addClass("done");

					//$('.image_preview img',$form).attr('src', response.thumb).removeClass('loading');
					$(img_input,$form).val(response.thumb);

					$(preloader_status_preloader,$form).hide();
					$(preloader_status_ok,$form).show();

				},
				complete: function () {
					// re-enable upload button
					$form.find( '.cancel_button' ).removeAttr( 'disabled' );
					$form.find('[type=submit]').addClass( 'blue_bg' ).removeClass( 'disabled_button' ).removeAttr( 'disabled' );
				},
				dataType: 'json'
			});

	});

	var hasError = false;
	var num_success_forms = 0;

	var selector = '#drops_elements form';

	$(document)
		.on('validate', selector, function(e,callback) {

			var _this = $(this);
			if ($('textarea[data-maxlength]',this))	{
				var maxlength = $('[data-maxlength]',this);
				if (maxlength.length > $('[data-maxlength]',this).attr("data-maxlength"))	{
					callback.call( _this, {status:false} );
				}	else	{
					callback.call( _this, {status:false} );
				}
			}
			if ( $('input[name=link_type]',this).val() == 'content' || $('input[name=link_type]',this).val() == 'image' || $('input[name=link_type]',this).val() == 'embed' )	{
				var $input = $('input[name=link_type]',this).val() == "image" ?  $('textarea[name=img]',this).eq(0) : $('textarea[name^=activity]',this).eq(0);
				match_url( this, $input, _folder_id , function(){
					callback.call( _this, {status:true} );
				});
			}	else	{
				callback.call( _this, {status:true} );
			}

			//submit_button.addClass("redroping").val($(submit_button).attr("data-loading"));

		}).
		live("preAjax",function(){})
		.on('success', function(e, data) {
			 if ( data.status ) {

			 	$('dl dd',this).hide();
			 	$('dd.success,dd.preview',this).show();
			 	$(this).addClass("success");

			 	num_success_forms++;
			 	
			 	if ($('#drops_elements form').length > 0 && $('#drops_elements form.success').length == num_success_forms)	{
			 		// success upload on all forms
			 		window.location.href = data.folder_url;
			 	}

			 return false;

			 }	else	{
				cancel_obj.removeClass("disabled");
				submit_button.removeClass("redroping").val( $(submit_button).attr("data-default") );
			 }

		});

	// remove form 
	$('#drops_elements form a.close').on("click",function(){
		if (cancel_obj.hasClass("disabled"))	{
			return false;
		}
		$(this).closest("form").unbind("validate postAjax success submit").remove();
		if ( $('#drops_elements form').length == 0 )	{
			$('#drops_elements div.submit_all').hide();
		}
	return false;
	});


	// submit all forms
	$('#drops_elements div.submit_all input[type=submit]').click(function(){

		var _validate_error = false;

		if ( $(this).hasClass("redroping") ) return false;

		var _input = $(this);
			_input.addClass("redroping").val(_input.attr("data-validating"));

		 $('#drops_elements form').each(function(){

		 	// http://dev.fantoon.com:8100/browse/FD-4364
		 	var finput = $('input[type=file]',this);
		 	if (finput.length && !finput.hasClass("done") && finput.is(":visible"))	{
		 		$(this).addClass("error");
		 		_validate_error = true;
		 	}
		 	//

		 	$(this).trigger("prevalidate");
		 	 if ($(this).hasClass("error"))	{
		 	 	_validate_error = true;
		 	 }
		 });

		 setTimeout(function(){
			 if (  _validate_error )	{
			 	_input.removeClass("redroping").val( $(_input).attr("data-default") );
			 return false;
			 } else	{

			 	_input.addClass("redroping").val( _input.attr("data-loading") );
			 	cancel_obj.addClass("disabled");

			 	// create collection before submit if need
				$.post('/validate_collection/', {'folder_name': $("#full_scraper select[name='folder_id']").tokenInput('get')[0].name }, function(result) {

					if (result.status == false)	{
						$.post('create_collection', {'folder_name': $("#full_scraper select[name='folder_id']").tokenInput('get')[0].name }, function(data) {
							// set folder id
							$( '#colection_dropdown_wrap input[name^=folder_id],#drops_elements input[name^=folder_id]').attr("name","folder_id[" + data.data.folder_id + "]").val( data.data.folder_name )
							_folder_id = data.data.folder_id;
							$('#drops_elements form').reverse().each( function(){
								$(this).submit();
							});
						},"json");

					} else {
						$('#drops_elements form').reverse().each( function(){
							$(this).submit();
						});
					}

				},'json');
			 }

 		}, 1000 );
	
	});

	// Cancel button - remove all forms from elements
	cancel_obj.click(function(){
		if ($(this).hasClass("disabled"))	{
			return false;
		}
		$('#drops_elements form').unbind("validate postAjax success submit").remove();
		$(this).closest('div.submit_all').hide();
		var submit_button = $('#drops_elements div.submit_all input[type=submit]');
			submit_button.removeClass("redroping").val( $(submit_button).attr("data-default") );
	return false;
	});

	// if the collection is changed then update all folder_id inputs values
	$('select.tokenInput').bind("change",function(){
		 var folder_id = $("#full_scraper select[name='folder_id']").tokenInput('get')[0];
		$('#drops_elements input[name^=folder_id]').attr("name","folder_id[" + folder_id.id + "]").val(folder_id.name);
		_folder_id = folder_id.id;
	});

});
