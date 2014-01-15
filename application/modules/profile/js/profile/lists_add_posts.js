/**
 * Code for the internal scraper in home page
 * @see /fantoon-extensions/views/includes/internal-scraper.php
 * @uses plugins/jquery.form - for the image upload
 * @uses jquery
 */
define(['plugins/jquery.form', 'common/ajaxForm', 'jquery'], function() {
	
	/* ====================== Variables ================ */
	
	//Selectors
	var self = '#lists .listManager_managerColumn';
	var menu = ' .addList_mediaButtons';
	var menu_items = menu+' a';
	
	var posts_list = ' .addList_postsStrip';
	
	var type_input = ' [name=link_type]';
	var img_input = " [name=img]";
	var temp_img_input = " .hidden_upload [type=file]";
	var media_input = " [name='activity[link][media]']";
	var link_input = " [name='link_url']";
	var folder_input = " [name='folder_input']";
	var content_input = " [name='activity[link][content]']";
	var title_input = " textarea.fd_mentions";
	
	var img_preview = ' .addList_previewContainer';	
	var hashtags = ' a.hashtag';
		
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
	
	/* ===================== Private Functions ================== */
	
	/*
	 * Called on link_input paste or keypress timeout to pull contents from the requested URL
	 */

	function match_url($input) {

		if (!window.validate_url($input, false)) return;
		
		if ($input.val().indexOf('fandrop.com') > -1) {
			$(self+link_input).val('');
			$('#open_redrop_info').click();
			return;
		}
		var img = $( self+img_preview+' img' ).attr('src', placeholder_src).addClass('loading');
			$(img).parent().css("margin-top","0");
			
		$.post('/internal_scraper/get_cached_content', {'link': $(self+link_input).val(),'folder_id' : $(self+' form.temp'+folder_input).val() }, function (data) {
			if (!data.status || data.error) {
				$input.closest('.form_row').find('.error').show().html(data.error);
				return;
			}

			console.info('{internal scraper} - content', data);
			if (data['class'] == 'image') {
				switch_to('image', true);
				$(self ).addClass('image_url')
				$(self+img_input ).val(data['image']);
				$(self+link_input ).val('');
				$(self+img_preview+' img' ).attr('src', data['image']);
			} else if (data['class'] == 'html') { //Page contents	
				//The cached data may not have content but just images
				if (data['content']) {
					switch_to('content', true);
					$(self+img_input).val('');
				} else {
					$.post('/internal_scraper/get_content', {'link': $(self+link_input).val()}, function (data) {

						if (data['class'] == 'html') {
							switch_to('content', true);
							$(self+img_input).val('');
						} else { //Video thumb
							switch_to('embed', true);
							$(self+media_input).val(data.media);
							$(self+img_input).val(data['data'][0].src);
							$(self+img_preview+' img' ).attr('src', data['data'][0]['src']);
						}
					
					},'json');
				}
			} else {  //Video thumb
				switch_to('embed', true);
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
	function switch_to(type, no_reset) {
		
		console.info('{internal scraper} - switch to ', type);

		$(self+menu_items).removeClass('active');
		$(self+menu+" [href='#"+type+"']").addClass('active');
		$(self+' form.temp').attr('class', 'temp '+type);
		$(self+' form.temp .error').hide();
		
		if (!no_reset) {
			var url = $(self+link_input).val();
			$(self+type_input).val(type);
			$(self+' form.temp')[0].reset();
			$(self+link_input).val(url);
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
		
		//Autofocus the first visible field
		//$(self+' .form_row:visible textarea:first').focus();
		
		//To update the .textLimit element
		
		$(self+' .textLimit').text( $(self+' .textLimit').closest('.form_row').find('[maxlength]').attr('maxlength') );
	}
	
	function get_data_element(index) {
		var data_element = $(self+' form:not(.temp) .sample[data-index='+index+']');
		if (!data_element.length) {
			data_element = $($(self+' form:not(.temp) script').html());
			data_element.attr('data-index', index);
			data_element.find('input, textarea').each(function() {
				$(this).attr('name', $(this).attr('name').replace('item[0]','item['+index+']'));
			})
			$(self+' form:not(.temp) .sample:last').after(data_element);
		}
		return data_element;
	}
	
	function set_current_data() {
		var index = $(self+' form.temp').attr('data-index');
		console.info('{add posts set data} - ', index);
		var data_element = get_data_element(index);
		console.info(data_element);
		
		$('form.temp').find('input, textarea').each(function() {
			var name = 'item['+index+']['+($(this).attr('name').indexOf('[') > -1 ? $(this).attr('name').replace('[','][') : $(this).attr('name')+']');
			var data_element_input = data_element.find('[name="'+name+'"]');
			if (data_element_input) {
				console.info('set data', $(this).val());
				data_element_input.val($(this).val());
			}
		});
	}
	
	function get_data(index) {
		console.info('{add posts get data} - ', index);
		var data_element = get_data_element(index);
		console.info(data_element);
		
		var type = data_element.find('[name="item['+index+'][link_type]"]').val();
		switch_to(type || 'image');
		
		$('form.temp').find('input, textarea').each(function() {
			var name = 'item['+index+']['+($(this).attr('name').indexOf('[') > -1 ? $(this).attr('name').replace('[','][') : $(this).attr('name')+']');
			var data_element_input = data_element.find('[name="'+name+'"]');
			if (data_element_input.length) {
				$(this).val(data_element_input.val());
			}
		});
		
		var img = data_element.find('[name="item['+index+'][img]"]');
		if (img.val()) {
			$(img_preview+' img').attr('src', img.val());
		} else {
			$(img_preview+' img').attr('src', placeholder_src);
		}
		
		if (data_element.find('[name="item['+index+'][newsfeed_id]"]').val()) {
			$(self+menu_items).addClass('disabled');
			$(self+' form.temp .form_row.link textarea').attr('readonly','readonly');
		} else {
			$(self+menu_items).removeClass('disabled');
			$(self+' form.temp .form_row.link textarea').removeAttr('readonly');
		}
	}
	
	function append_post_element() {
		var new_li = $(self+posts_list+' ul li:first').clone();
			new_li.find('img').attr('src','');
			new_li.find('a').hide();
			new_li.find('.addList_itemNumber').text($(self+posts_list+' ul li').length+1);
			if (($(self+posts_list+' ul li').length+1) % 5 == 0) {
				new_li.addClass('last');
			}
			new_li.attr('data-index', parseInt($(self+posts_list+' ul li:last').attr('data-index'))+1);
		$(self+posts_list+' ul').append(new_li);
	}
	
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
		if ($(this).is('.active') || $(this).is('.disabled')) return false;
		var type = $(this).attr('href').replace('#','');
		
		switch_to(type);
		$(self+img_input).val('');
		//FD-4727, avoid loaded image disappears when selecting another menu tab
		//$(self+img_preview+' img').attr('src', placeholder_src)
		$(self+'form .error').hide()
		
		return false;
	});
	
	/**
	 * Hashtags
	 */
	$(document).on('click',self+hashtags, function() {
		var textarea = $(this).closest('form').find( title_input );
		textarea.val(textarea.val().replace('Enter a title...','') +' '+$(this).attr('href')).focus().keydown().keyup();
		return false;
	});
		
	/**
	 * Auto submit url, video
	 */
	$(document)
		.on('keypress',self+link_input, function(e) {
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
	 * Section 1 - Upload a photo
	 */
	$(document).on('change', self+temp_img_input, function() {
		if (!$(this).val()) return;
		console.log('{internal scraper} - upload image');
		
		var container = $(self+img_preview);
		var $this = $(this);
		var $form = $this.closest('form');
		
		//use placeholder_src for reloading (FD-3215)
		var img = $(self+img_preview+' img').attr('src', loading_src).addClass('loading');
		
		$form.find('.error').hide();
		
		$form.addClass('loading')
			.ajaxSubmit({
				success:  function(response)  {
					console.log('{internal scraper} - upload image callback', response);
					
					if (response.error) {
						$form.find('> .error').show().html(response.error);
						return;
					}
					$form.find('> .error').hide();
					
					img.attr('src', response.thumb).removeClass('loading');
					$(self+img_input).val(response.thumb);
	
					//Mixpanel tracking
					if (typeof(mixpanel) !== 'undefined') {
						mixpanel.track('Upload Picture (Photo Post)', {'user':php.userId ? php.userId : 0});
					}
				},
				complete: function () {
					//re-enable upload button
					$form.removeClass('loading');
				},
				dataType: 'json'
			});
	});
	
	$(self+img_preview+' img' ).load(function() {
		var diff = $(this).parent().height() - $(this).height();
		$(this).css('margin-top', diff/2);
		if ($(this).attr('src') != loading_src) {
			var posts_list_img = $(self+posts_list+' li[data-index='+$(this).closest('form').attr('data-index')+'] img'); 
				posts_list_img.attr('src', $(this).attr('src'));
		}
	});
	
	/**
	 * Delete a post
	 */
	$(document).on('click', self+posts_list+' li a.addList_itemDelete', function() {

		if ($(self+posts_list+' li a.addList_itemDelete:visible').length == 1) {
			var redirect_url = location.href.indexOf('edit_post/') > -1 ? location.href.split('edit_post/')[0] : location.href.replace('/add_posts',''); 
			window.location.href = redirect_url;
			return false;
		}

		var index = $(this).closest('li').attr('data-index');
				
		$(self+' form:not(.temp) .sample[data-index='+index+']').remove();
		
		var ul = $(this).closest('ul');
		append_post_element();
		$(this).closest('li').remove();
		ul.find('li').each(function(i) {
			$(this).removeClass('last').find('.addList_itemNumber').text(i+1);			
			if ((i+1) % 5 == 0) $(this).addClass('last');
		});
		
		if ($(self+' form.temp').attr('data-index') == index) {
			index = ul.find('li:first').attr('data-index');
			$(self+' form.temp').attr('data-index', index);
			get_data(parseInt(index));
		}
		
		if (ul.find('li a.addList_itemEdit:visible').length == 1) {
			ul.find('li a.addList_itemEdit:first').addClass('disabled');
		}
		
		return false;
	});
	
	/**
	 * Select post item to EDIT
	 */
	$(document).on('click', self+posts_list+' li a.addList_itemEdit', function() {

		var $this = $(this);
		if ($this.hasClass('disabled')) return false;

		// FD-4729
		// if it is first loading, execute prevalidate
		// unless, skip it because 'edit' is select to change image properties
		if ($this.hasClass('loading')) {

			$(self+' form.temp').trigger('prevalidate');
			if ($(self+' form.temp').hasClass('error')) {
				console.info('ERROR ');
				return false;
			}

			$this.removeClass('loading');
		}
		
		$(self+' form.temp').trigger('validate', function(res) {
			console.info('DONE ', res);
			$(self+posts_list+' li').removeClass('active');
			
			var index = $this.closest('li').addClass('active').attr('data-index');
			$(self+' form.temp').attr('data-index', index);
			get_data(parseInt(index));
		});
		
		return false;
	});

	
	/**
	 * Save temp form
	 */
	$(document)
		.on('validate', self+' form.temp', function(e,callback) {
			console.info('temp form validate');
			
			set_current_data();
			callback.call(this, {status:true});
		})
		.on('submit', self+' form.temp', function() {
			return false;
		})
	
	/**
	 * Add new post
	 */
	$(document).on('click', self+' .addList_actions a.addList_add', function() {
		$(self+' form.temp').trigger('prevalidate');
		if ($(self+' form.temp').hasClass('error')) {
			console.info('ERROR ');
			return false;
		}
		
		$(self+' form.temp').trigger('validate', function(res) {
			$(self+posts_list+' li a.addList_itemEdit').removeClass('disabled');

			var next = $(self+posts_list+' li a:hidden:first').parent();
			if (!next.length) {
				for (var i=0; i<5; i++) {
					append_post_element();
				}
				next = $(self+posts_list+' li a:hidden:first').parent();
			}
			next.find('a').show();
			next.find('a').addClass('loading');   // add 'loading' to enable 'prevalidate'
			next.find('a.addList_itemEdit').trigger('click');	
		});
		return false;
	});
	
	/**
	 * Finally save data
	 */
	 var selector = self+' form:not(.temp)';
	$(document)
		.on('validate', selector, function(e, callback) {
			if ($(posts_list+' li a.addList_itemEdit:visible').length == 1 
				&& !$(self+' form.temp '+title_input).val()
				&& !$(self+' form.temp '+link_input).val()
				&& !$(self+' form.temp '+img_input).val()
			) {
				console.info('REDIRECTING');
				var redirect_url = location.href.indexOf('edit_post/') > -1 ? location.href.split('edit_post/')[0] : location.href.replace('/add_posts',''); 
				window.location.href = redirect_url
				return false;
			}
			$(self+' form.temp').trigger('prevalidate');
			if ($(self+' form.temp').hasClass('error')) {
				console.info('ERROR ');
				return callback.call(this, {status:false});
			}
			
			$(self+' form.temp').trigger('validate', function(res) {
				console.info('DONE ', res);
				return callback.call(this, res);
			});
		})
		.on('success', selector, function(e, data) {
			if (!data.status || data.error) {
				console.info('ERROR', data);
				var $this = $( this );
				$this.find('.error:first').text(data.error).show();
				return;
			}
			var redirect_url = location.href.indexOf('edit_post/') > -1 ? location.href.split('edit_post/')[0] : location.href.replace('/add_posts',''); 
			window.location.href = redirect_url
		});
	
	if ($(self+' form:not(.temp) .sample').length) {
		if ($(self+' form:not(.temp) .sample [name="item[0][newsfeed_id]"]').val()) {
			get_data(0);
		}
	} else {
		$(function() {
			if ($(self+' form:not(.temp) .sample [name="item[0][newsfeed_id]"]').val()) {
				get_data(0);
			}
		})
	}
	
});
