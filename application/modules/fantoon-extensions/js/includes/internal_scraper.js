/**
 * Code for the internal scraper in home page
 * @see /fantoon-extensions/views/includes/internal-scraper.php
 * @uses plugins/jquery.form - for the image upload
 * @uses jquery
 */
define(['plugins/jquery.form', 'jquery'], function() {
	
	/* ====================== Variables ================ */
	
	//Selectors
	var self = '#internal_scraper';
	var menu = ' ul.modes_menu';
	var menu_items = menu+' li a';
	var use_image = ' .image_url a.upload_file';
	var use_image_url = ' .image a.use_an_url';
	var cancel_btn = ' input[value=Cancel]';
	var hashtags = ' a.hashtag';
	var link_input = " [name='link_url']";
	var content_input = " [name='activity[link][content]']";
	//var title_input = " textarea[name=description]";
	var title_input = " textarea.fd_mentions";
	var type_input = " [name=link_type]";
	var img_input = " textarea[name=img]";
	var temp_img_input = " .hidden_upload [type=file]";
	var media_input = " [name='activity[link][media]']";
	
	var img_preview = ' .img_preview_container';
	var img_preview_controls = ' .paginationContainer';
	var img_preview_left = img_preview_controls+' a.left';
	var img_preview_right = img_preview_controls+' a.right';
	
	/**
	 * The loader which appears while the url is pulling
	 * @to-do use the common/loader 
	 */
	var screenshot_ico = '<li class="screenshot"><img src="/images/screenshotIcon.png"/></li>';
	/**
	 * Used for upload image func
	 */
	var loading_src = '/images/loading_icons/bigRoller_32x32.gif';
	var screenshot_src = '/images/verified.png';
	var placeholder_src = '/images/1x1.png';
	/**
	 * Used in the autosubmit func
	 */
	var key_timeout = null;
	/**
	 * Used in the autosubmit func
	 */
	var lastValue = null;
	/**
	 * Page contents are loaded asyncronously and the form wont validate until its done
	 */
	var submit_interval = null;

	//bobef: #FD-2364
	//this is still very far from valid but has dash/underline support and fixes
	var validUrlRE = new RegExp("(http://|https://|www\\.)[a-zA-Z0-9_\\-]+\\.[a-zA-Z0-9_\\-]+[^ ]*","gi");
	//end of #FD-2364
	
	/* ===================== Private Functions ================== */
	
	/*
	 * Called on link_input paste or keypress timeout to pull contents from the requested URL
	 */

	 var is_text_link_is_already_ok = true;

	function match_url($input) {

		if (!window.validate_url($input, false)) return;
		
		if ($input.val().indexOf('fandrop.com') > -1) {
			$(self+link_input).val('');
			$('#open_redrop_info').click();
			return;
		}
		var img = $( self+img_preview+' img' ).attr('src', placeholder_src).addClass('loading');
			$(img).parent().css("margin-top","0");
			
		$.post('/internal_scraper/get_cached_content', {'link': $(self+link_input).val(),'folder_id' : $('#internal_scraper select[name="folder_id"]').tokenInput('get')[0]['id'] }, function (data) {

			is_text_link_is_already_ok = true;

			$(self+img_preview+' img' ).removeClass('loading');
			if (!data.status || data.error) {
				is_text_link_is_already_ok = false;
				$input.closest('.form_row').find('.error').show().html(data.error);
				return;
			}

			console.info('{internal scraper} - content', data);
			if (data['class'] == 'image') {
				switch_to('image');
				$(self ).addClass('image_url')
				$(self+img_input ).val(data['image']);
				$(self+link_input ).val('');
				$(self+img_preview+' img' ).attr('src', data['image']);
			} else if (data['class'] == 'html') { //Page contents	
				//The cached data may not have content but just images
				if (data['content']) {
					switch_to('content');
					$(self+content_input).val($('<div/>').html(data.content).text()); //decode contents which are encoded bc of new relic
					$(self+img_preview+' img' ).attr('src', screenshot_src);
					$(self+img_input).val('');
				} else {
					$.post('/internal_scraper/get_content', {'link': $(self+link_input).val()}, function (data) {

						if (data['class'] == 'html') {
							switch_to('content');
							$(self+content_input).val($('<div/>').html(data.content).text()); //decode contents which are encoded bc of new relic
							$(self+img_preview+' img' ).attr('src', screenshot_src);
							$(self+img_input).val('');
						} else { //Video thumb
							switch_to('embed');
							$(self+media_input).val(data.media);
							$(self+img_input).val(data['data'][0].src);
							$(self+img_preview+' img' ).attr('src', data['data'][0]['src']);
						}
					
					},'json');
				}
			} else {  //Video thumb
				switch_to('embed');
				$(self+media_input).val(data.media);
				$(self+img_input).val(data['data'][0].src);
				$(self+img_preview+' img' ).attr('src', data['data'][0]['src']).removeClass('loading');
			}
		},'json');
	}
	
	/**
	 * Switch to content|embed|image - if the server responce is html but the user selected video or imageURL
	 * the internal scraper will automaticaly switch to html (Be smart)
	 */
	function switch_to(type) {

		console.info('{internal scraper} - switch to ', type);

		$(self).removeClass('content').removeClass('image').removeClass('image_url').removeClass('embed').removeClass('text')
			.addClass(type)
		$(self+menu_items).removeClass('selected');
		$(self+menu+" [href='#"+type+"']").addClass('selected');
		$(self+type_input).val(type);
		if (type == 'text') {
			$(self+' .form_row.hashtagRow').insertBefore($(self+' .form_row.collection'));
			$(self+' .form_row.title').insertBefore($(self+' .form_row.hashtagRow'));
		} else {
			$(self+' .form_row.title').insertAfter($(self+' .form_row.collection'));
			$(self+' .form_row.hashtagRow').insertAfter($(self+' .form_row.text'));
		}

		//Remove image validation for text mode
		if (type == 'text') {
			$(self+img_input).removeAttr('data-validate');
		} else if (type == 'content') {
			$(self+img_input).removeAttr('data-validate');
		} else if (type == 'image') {
			$(self+img_input).attr('data-validate', 'required|url');//url|
			$(self+img_input).attr('data-error-required', 'Please select an image');
		} else {
			$(self+img_input).attr('data-validate', 'required');
			$(self+img_input).attr('data-error-required', 'The URL doesn\'t appear to be valid');
		}
	}
	
	/**
	 * Url preview thumbnails
	 * @see $('#internal_scraper .paginationContainer a.left').on('click'
	 * @see $('#internal_scraper .paginationContainer a.right').on('click'
	 * @depecated
	 *//*
	function set_thumb(i) {
		var $form = $('#internal_scraper form');
		$form.find('[name=use_screenshot]').attr('checked',false);
		$form.find(img_preview_controls+' .total').html($form.find('img').length-1);
		$form.find(img_preview_controls+' .selected').html(i);
		$form.find(img_preview+' li').hide();
		var li = $form.find(img_preview+' li:nth-child('+(i+1)+')').show(); 
		$form.find("[name='activity[link][img]']").val(li.find('img').attr('src'));		
		$form.find('a.right, a.left').removeClass('disabled');
		if (i>=$form.find('img').length-1) $form.find('a.right').addClass('disabled');
		if (i==1) $form.find('a.left').addClass('disabled');
	}
	*/
	
	/**
	 * Executed on thumbnail load
	 * @deprecated - we use only screenshot now
	 *//*
	function images_load_handler(container, pagination) {
		pagination.find('.total').html(container.find('img').length-1);
		if (pagination.find('.total').text() == pagination.find('.selected').text()) {
			pagination.find('.right').addClass('disabled');
		}
	}
	*/
	
	/**
	 * Executed when the site is scraped
	 * @deprecated - we use only screenshot now
	 *//*
	function populate_images(images) {
		var container = $('#internal_scraper .img_preview_container');
		container.find('li:not(.sample,.screenshot)').remove();
		console.info(images);
		for (var i=0;i<images.length; i++) {
			var new_img = container.find('li.sample').clone(true).removeClass('sample');
				new_img.find('img').removeClass('loading').attr('src', images[i].src)
					.bind('load',function() { 
						var pagination = $(self+img_preview_controls);
						if (this.height < 50 || this.width < 50) $(this).parent().remove();
						images_load_handler(container, pagination);
					})
					.bind('error', function() {
						if (this.src.indexOf('_original') > -1) {
							this.src = this.src.replace('_original','');
							return;
						}
						$(this).parent().remove();
						images_load_handler(container, $(self+img_preview_controls));
					});
			container.append(new_img);
		}
		if (images.length) {
			$('#internal_scraper .title').html(images[0].alt);
		}
		container.find('li.sample').hide();
		//set_thumb(1);
	}
	*/
	
	/* ======================== Events ================== */

	/**
	 * Choose mode - 
	 * 1. opens the form
	 * 2. adds the selected type as class to the #internal scraper
	 * 3. resets the form
	 * 4. adds the .selected class  to the clicked item
	 * 5. shows the cancel button
	 */
	$(document).on('click',self+menu_items, function() {
		if ($(this).is('.selected')) return false;
		var type = $(this).attr('href').replace('#','');
		
		switch_to(type);
		$(self).addClass('open')
			.find( img_input ).val('')
			.end().find(img_preview+' img').attr('src', placeholder_src)
			.end().find('form .error').hide()
			.end().find('form')[0].reset();
		
		//Autofocus the first visible field
		$(self+' .form_row:visible textarea:first').focus();
		
		//To update the .textLimit element
		$(self+' .textLimit').text( $(self+' .textLimit').closest('.form_row').find('[maxlength]').attr('maxlength') );
		
		$(this).addClass('selected')
			.find(cancel_btn).show();
		return false;
	});
	
	/**
	 * Switch to upload local image file
	 */
	$(document).on('click',self+use_image, function() {
		$(self).removeClass('image_url');
		$(self+' form > .error').hide();
		return false;
	});
	/**
	 * Switch to upload image from url
	 */
	$(document).on('click', self+use_image_url, function() {
		$(self).addClass('image_url');
		$(self+img_input).val('');
		$(self+img_preview+' img').attr('src','/images/1x1.png');
		$(self+' form > .error').hide();
		return false;
	});
	
	/**
	 *  Close the previously shown form on document if its emtpy and click is outside it 
	 */
	$(document).on('click', function(e) {
		if ($(e.target).closest('.token-input-dropdown-fd_dropdown, .token-input-token-fd_dropdown').length) return;
		if ($(e.target).closest(self).length) return;
		if ($( self+' form').hasClass('loading')) return;
		if ($( self+title_input ).val()) return;
		if ($( self+link_input ).val()) return;
		if ($( self+img_input ).val()) return;		
		if ($( self+content_input ).val()) return;		
		$(self+cancel_btn).click();
	});
	
	/**
	 * Close the form no matter is it empty ot not;
	 */
	$(document).on('click',self+cancel_btn, function(){
		$(self)
			.removeClass('open').removeClass('image').removeClass('content').removeClass('embed').removeClass('text')
			.find('form').removeClass('loading')//.reset()
			.end().find('form .error').hide()
			.end().find(img_input).val('');
		$(self+menu_items).removeClass('selected');
		$('.token-input-dropdown-fd_dropdown').hide(); //hot fix for http://dev.fantoon.com:8100/browse/FD-2732
		$( this ).closest( 'form' ).resetValidation(); //BP: #FD-2961
		return false;
	});
	
	/**
	 * Hashtags
	 */
	$(document).on('click', self+hashtags, function() {
		var textarea = $(this).closest('form').find( title_input );
		textarea.val(textarea.val().replace('Enter a title...','') +' '+$(this).attr('href')).focus().keydown().keyup();
		return false;
	});

	// RR - ?!?
	// because input[type=file] is inside .hidden_upload so that
	// it is necessary to stop bubbling. Unless, forever loop
	//$("#internal_scraper .hidden_upload input").on('click', function(e) {
	//	console.log("hidden_upload input is clicked");
	//	e.stopPropagation();
	//});
		
	/**
	 * Auto submit url, video
	 */
	$(document)
		.on('keypress',self+link_input,function(e) {
			console.info($this);
			if (key_timeout) window.clearTimeout(key_timeout);
			var $this = $(this);
			var val = $this.val();
			if ( ! val || val == lastValue) return;
			lastValue = val;
			
			if(e.keyCode ==13) {
				match_url($this);
				return false;
			} else {
				key_timeout = window.setTimeout(function() {
					match_url($this);
				},1000);
			}
		})
		.on('paste', self+link_input, function() {
			var $this = $(this);
			setTimeout(function() { match_url($this); }, 100);
		});
	/**
	 * Auto preview the image
	 */
	$(document)
		.on('keypress',self+img_input, function(e) {
			$(self+' form > .error').hide();
			if(e.keyCode ==13) {
				$(self+img_preview+' img').attr('src', $(this).val());
				return false;
			}
		})
		.on('paste', self+img_input, function() {
			var $this = $(this);
			$(self+' form > .error').hide();
			setTimeout(function() { $(self+img_preview+' img').attr('src', $this.val()); }, 100);
		});

	/**
	 * Section 1 - Upload a photo
	 */
	$(document).on('change',self+temp_img_input, function() {

		console.log('{internal scraper} - upload image');
		var container = $(self+img_preview);
		//var new_img = container.find('li.sample').show().clone(true).removeClass('sample');
		//use placeholder_src for reloading (FD-3215)
		var img = $(self+img_preview+' img').attr('src', placeholder_src).addClass('loading');
		$(img).parent().css("margin-top","0");
		
		var $this = $(this);
		var $form = $this.closest('form');
		
		//container.find('li:not(.sample)').remove();
		$form.find('.error').hide();
		
		//disable upload button while uploading and also cancel/submit
		var $uploadbtn = $this.closest( '.colourless_button' );
		$this.hide();
		$uploadbtn.addClass( 'disabled_button' );
		//$form.find( cancel_btn ).attr( 'disabled', 'disabled' );
		$form.find('[type=submit]').removeClass( 'blue_bg' ).addClass( 'disabled_button' ).attr( 'disabled', 'disabled' );

		$form.addClass('loading')
			.ajaxSubmit({
				success:  function(response)  {
					console.log('{internal scraper} - upload image callback', response);
					$form.removeClass('loading')
					if (response.error) {
						$form.find('> .error').show().html(response.error);
						return;
					}
					
					//container.find('li.sample').hide();
					//new_img.appendTo(container).find('img').attr('src', response.thumb).removeClass('loading');
					img.attr('src', response.thumb).removeClass('loading');
					$(self+img_input).val(response.thumb);
	
					//Mixpanel tracking
					if (typeof(mixpanel) !== 'undefined') {
						mixpanel.track('Upload Picture (Photo Post)', {'user':php.userId ? php.userId : 0});
					}
				},
				complete: function () {
					//re-enable upload button
					$this.show();
					$uploadbtn.removeClass( 'disabled_button' );
					$form.find( cancel_btn ).removeAttr( 'disabled' );
					$form.find('[type=submit]').addClass( 'blue_bg' ).removeClass( 'disabled_button' ).removeAttr( 'disabled' );
				},

				dataType: 'json'
			});
	});
	
	//Finally save the data
	$(document)
		.on('validate', self+' form', function(e,callback) {

			// $(self + ' textarea[name=description]').val($(self + ' textarea[name=description_orig]').val());

			 if (!is_text_link_is_already_ok)	{
			 	// rise error;
			 	var $this = $( this );
			 	$this.find('[type=submit]').addClass('blue_bg').removeClass('disabled_button').removeAttr('disabled').val('Drop It!');
				$this.find(cancel_btn).show();
				$(self+temp_img_input).show().closest( '.colourless_button' ).removeClass( 'disabled_button' );			 
				
				callback.call(this, {status:false});

				return false;
			 }

			if ($(self+type_input).val() == 'content' && !$(self+content_input).val()) { //wait for the content to load
				submit_interval = window.setInterval(function() {
					if ( $( self + content_input ).val() ) {
						window.clearInterval(submit_interval);
						callback.call(this, {status:true});
					}
				}, 100);
			} else {
				callback.call(this, {status:true});
			}
		})
		.on('postAjax', self+' form', function() {
			var $this = $( this );
			$this.find(cancel_btn).hide('slow');
			$this.find('[value=Cancel]').hide();
			$this.find('[type=submit]').removeClass('blue_bg').addClass('disabled_button').attr('disabled','disabled').val('Dropping…');
			$(self+temp_img_input).hide().closest( '.colourless_button' ).addClass( 'disabled_button' );
		})
		.on('success', self+' form', function(e, data) {
			if (!data.status || data.error) {
				console.info('ERROR', data);
				var $this = $( this );
				$this.find('.error:first').text(data.error).show();
				$this.find('[type=submit]').addClass('blue_bg').removeClass('disabled_button').removeAttr('disabled').val('Drop It!');
				$this.find(cancel_btn).show();
				$(self+temp_img_input).show().closest( '.colourless_button' ).removeClass( 'disabled_button' );
				return;
			}
			window.location.href = data.url;
		});
	
	
	$(self+img_preview+' img' ).load(function() {
		var diff = $(this).parent().parent().height() - $(this).parent().height();
			$(this).parent().css('margin-top', diff/2);
	});

	// URL section
	// check the url again after change the collection

	$(self + ' select[name=folder_id]').change(function(){
		if ( $('#internal_scraper a.selected').attr("href") == '#content' || $('#internal_scraper a.selected').attr("href") == '#embed')
			match_url($(self+link_input));
	});

	/**
	 * Switch to previous thumbnail in the image preview
	 * @deprecated
	 *//*
	$(self+img_preview_left).on('click', function() {
		if ($(this).hasClass('disabled')) return false;
		var current = $(this).closest('form').find('.selected');
		current.html(parseInt(current.html())-1);
		set_thumb(parseInt(current.html()));
		return false;
	});*/
	
	/**
	 * Switch to the next thumbnail in the image preview
	 * @deprecated
	 *//*
	$(self+img_preview_right).on('click', function() {
		if ($(this).hasClass('disabled')) return false;
		var current = $(this).closest('form').find('.selected');
		current.html(parseInt(current.html())+1);
		set_thumb(parseInt(current.html()));
		return false;
	});*/
	
	/**
	 * Use screenshot checkbox
	 * @deprecated
	 *//*
	$('#internal_scraper [name=use_screenshot]').on('change', function(e) {
		if ($(this).is(':checked')) {
			$(self+img_preview)
				.find('li').hide()
				.end().append(screenshot_ico);
			$(self+img_input).val('');	
		} else {
			$(self+img_preview)
				.find('li').show()
				.end().find('li.screenshot').remove();
			set_thumb(parseInt($(this).closest('form').find('.selected').html()) || 1);
		}
	});*/

});
