define(['plugins/jquery.form', 'common/ajaxForm', 'common/ajaxList', 'common/custom_title', 'jquery', 'jquery-ui'], function() {
	
	/* ====================== Variables ================ */
	
	//Selectors
	var self = '#lists .listManager_managerColumn';
	var menu = ' .addList_mediaButtons';
	var menu_items = menu+' a';
	
	var posts_list = ' .editList_upper';
	
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
	var last_post_info = "";
	var add_new = false;
	
	/**
	 * Page contents are loaded asyncronously and the form wont validate until its done
	 */
	var submit_interval = null;
	
	/* ===================== Private Functions ================== */
	
	function init() {

		 $( ".editList_upper" ).sortable({
		 	 items: 'li',
			 stop: function(e, ui) {
				 if (ui.item.attr('id') == 'newsfeed_id_undefined') {
					 $(self+' form.temp [name=after]').val(ui.item.prevAll('li:first').length ? ui.item.prevAll('li:first').attr('id').replace('newsfeed_id_','') : '');
				 } else {
					 $.post('/manage_lists/'+php.folder.folder_id+'/resort_posts', $(this).sortable('serialize'), function(res) {
						 console.info(res);
					 });
				 }
			 }
		 });
		 
		 $( ".editList_upper" ).disableSelection();
		 if (location.search.indexOf('add=true') > -1) {
			 $(self+' .editList_actions a.editList_preview').click();
		 }

	}
	
	/**
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
							$(self+type_input).val('content');
							$(self+img_input).val('');
						} else { //Video thumb
							switch_to('embed', true);
							$(self+type_input).val('embed');
							$(self+media_input).val(data.media);
							$(self+img_input).val(data['data'][0].src);
							$(self+img_preview+' img' ).attr('src', data['data'][0]['src']);
						}
					
					},'json');
				}
			} else {  //Video thumb
				switch_to('embed', true);
				$(self+type_input).val('embed');
				$(self+media_input).val(data.media);
				$(self+img_input).val(data['data'][0].src);
				$(self+img_preview+' img' ).attr('src', data['data'][0]['src']).removeClass('loading');
			}

			//save information
			last_post_info = "";
			$(self+' form').find('input:visible,textarea:visible').each( function() {
				last_post_info += $(this).val();
			});
		},'json');
	}
	
	/**
	 * Switch to content|embed|image - if the server responce is html but the user selected video or imageURL
	 * the internal scraper will automaticaly switch to html (Be smart)
	 */

	 var image = {};
	 var _type = 'image';

	function switch_to(type, no_reset) {
		
		console.info('{internal scraper} - switch to ', type, no_reset);
		image[_type] = $(self+' form.temp').find( img_preview + ' img' ).attr('src');

		 if (typeof image[type] != 'undefined')	{
		 	$(self+' form.temp').find( img_preview + ' img' ).attr('src',image[type]);
		 } else {
		 	$(self+' form.temp').find( img_preview + ' img' ).attr('src','/images/1x1.png');
		 }
		 _type = type;

		$(self+menu_items).removeClass('active');
		$(self+menu+" [href='#"+(type=='html' ? 'content' : type)+"']").addClass('active');
		$(self+' form.temp').attr('class', 'temp '+(type=='html' ? 'content' : type));
		$(self+' form.temp .error').hide();
		$(self+' .hidden_upload').show();
		
		if (!no_reset) {
			//var url = $(self+link_input).val();
			$(self+type_input).val(type);
			//$(self+' form.temp')[0].reset();
			//$(self+link_input).val(url);
		}

		//Remove image validation for text mode
		if (type == 'text') {
			$(self+img_input).removeAttr('data-validate');
			$(self+title_input).removeAttr('data-validate');
			$(self+link_input).removeAttr('data-validate');
			if (!add_new) $(self+link_input).attr('readonly', true);
		} else if (type == 'content') {
			$(self+img_input).removeAttr('data-validate');
			$(self+title_input).attr('data-validate','required|maxlength');
			$(self+link_input).attr('data-validate','required');
			if (!add_new) $(self+link_input).attr('readonly', true);
		} else if (type == 'image') {
			$(self+img_input).attr('data-validate', 'required|url'); //url|
			$(self+img_input).attr('data-error-required', 'Please select an image');
			$(self+title_input).attr('data-validate','required|maxlength');
			$(self+link_input).removeAttr('data-validate');
			if (!add_new) $(self+link_input).attr('readonly', false);
		} else if (type == 'embed' || type=='html') {
			$(self+img_input).attr('data-validate', 'required');
			$(self+img_input).attr('data-error-required', 'The URL doesn\'t appear to be valid');
			$(self+title_input).attr('data-validate','required|maxlength');
			$(self+link_input).attr('data-validate','required');
			if (!add_new) $(self+link_input).attr('readonly', true);
		}

		if (add_new) {
			$(self+link_input).attr('readonly', false);
		}
		
		//Autofocus the first visible field
		//$(self+' .form_row:visible textarea:first').focus();

		//To update the .textLimit element
		if ($(self+' .textLimit').closest('.form_row').find('[maxlength]').length) {
			$(self+' .textLimit').text( $(self+' .textLimit').closest('.form_row').find('[maxlength]').attr('maxlength') );	
		} else if($(self+' .textLimit').closest('.form_row').find('[data-maxlength]').length)	{
			$(self+' .textLimit').text( $(self+' .textLimit').closest('.form_row').find('[data-maxlength]').attr('data-maxlength') );
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
	
	function ajaxList_process(data) {
	
		console.warn('data - data',data);

		$(this).find('.itemLink .ico').addClass(data.link_type);
		if (data.link_type == 'text') {
			$(this).find('.itemLink .textContent').css('display','').html( data.content );
		} else {
			$(this).find('.itemLink img').show();
		}

		$('.itemDelete',this).attr("data-confirm","Delete " + data.description + " ?");

	} 
	
	/* ======================== Events ================== */
	
	$( document ).on( 'scroll_bottom', self+' .editList_upper', function() {
		if (typeof this.ajaxList_process != 'undefined') return;
		this.ajaxList_process = ajaxList_process;
	});

	/**
	 * Choose mode - 
	 * 1. opens the form
	 * 2. adds the selected type as class to the #internal scraper
	 * 3. resets the form
	 * 4. adds the .selected class  to the clicked item
	 * 5. shows the cancel button
	 */
	$(document).on('click', self+menu_items, function() {

		if ($(this).is('.active') || $(this).is('.disabled')) return false;
		var type = $(this).attr('href').replace('#','');
		
		switch_to(type);
		//FD-4727, avoid loaded image disappears when selecting another menu tab
		//$(self+img_preview+' img').attr('src', placeholder_src)
		//$(self+img_input).val('');
		$(self+'form .error').hide()

		//save information
		last_post_info = "";
		$(self+' form').find('input:visible,textarea:visible').each( function() {
			last_post_info += $(this).val();
		});
		
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
		.on('keypress',self+link_input,function(e) {
			if ($('form.temp').hasClass('image')) return;
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
			if ($('form.temp').hasClass('image')) return;
			var $this = $(this);
			setTimeout(function() { match_url($this); }, 100);
		});

	/**
	 * Section 1 - Upload a photo
	 */
	$(document).on('change',self+' form.temp '+temp_img_input, function() {

		if (!$(this).val()) return;
		
		var $this = $(this);
		var $form = $this.closest('form');
		var container = $form.find(img_preview);
		
		if ($form.hasClass('loading')) return false;
		
		console.log('{internal scraper} - upload image');
		
		var img = $form.find(img_preview+' img').attr('src', loading_src);
		
		$form.find('.error').hide();
		
		$form.addClass('loading')
			.ajaxSubmit({
				success:  function(response)  {

					console.log('{internal scraper} - upload image callback', response);
					
					if (response.error) {
						console.warn('form',$form.find('.error'));
						$form.find(' .error:first').show().html(response.error);
						$('.addList_previewContainer img:eq(0)',$form).attr("src",placeholder_src);
						return;
					}

					$form.find('> .error').hide();
					
					img.attr('src', response.thumb);
					$form.find(img_input).val(response.thumb);
						
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
	
	$( self+img_preview + ' img' ).load(function() {
		var diff = $(this).parent().height() - $(this).height();
		$(this).css('margin-top', diff/2);
		if ($(this).attr('src') != loading_src) {
			var posts_list_img = $(self+posts_list+' li[data-index='+$(this).closest('form').attr('data-index')+'] img'); 
				posts_list_img.attr('src', $(this).attr('src'));
		}
	});
	
	$( self + posts_list )
		/**
		 * Delete a post
		 */
		.on('click', 'li[id="newsfeed_id_undefined"]', function(e) { //for non saved posts
			console.info('delete unsaved');
			$(self+' .editList_actions .addList_cancel').trigger('click');
			return false;
		})
		.on('success', 'li a.itemDelete', function() { //for saved posts
			$(this).closest('li').hide('fade');
		})
		/**
		 * Select post item to EDIT
		 */
		.on('click', 'li a.itemEdit', function() {

			add_new = false;
			var $this = $(this);
			if ($this.closest('li').hasClass('active')) return false;
			
			var $form = $(self+' form.temp');

			var current_post_info = "";

			$form.find('input:visible,textarea:visible').each( function() {
				current_post_info += $(this).val();
			});
			
			if ($this.closest('.listManager_managerColumn').hasClass('active')) {
				if ($form.hasClass('loading')) return false;
				if ( last_post_info != "" && current_post_info != last_post_info ) {
					console.info('unsaved post pending');
					$form.submit();
					return false;
				}
			}

			last_post_info = current_post_info;
			
			$this.closest('.listManager_managerColumn').addClass('active');
			set_containers_height();
			
			$this.closest('ul li.active').removeClass('.active');
			$this.addClass('active');
			
			var data = {
				'link_type': $.trim($this.closest('li').find('.ico').attr('class').replace('ico','')),
				'link_url': $this.closest('li').attr('data-link_url'),
				'newsfeed_id': $this.closest('li').attr('id').replace('newsfeed_id_',''),
				'description': $this.closest('li').find('.itemDescription span').text(),
			};

			// console.warn( 'type', $this.closest('li').find('.ico') );

			if (data['link_type'] == 'text') {
				data['content'] = $this.closest('li').find('.textContent').text();
			} else {
				data['img'] = $this.closest('li').find('img').attr('src');
			}

			console.info('Edit', data);
			
			switch_to(data['link_type']);
			$form.find('[name=newsfeed_id]').val(data['newsfeed_id']);
			//$form.find(media_input).val(data['media']); //RR - media cant be edited
;			$form.find(link_input).val(data['link_url']); // .attr('readonly','readonly'); // RR - url cant be edited
			$form.find(title_input).val(data['description']).trigger('keyup');
			$form.find(img_input).val(data['img']);
			$form.find('[name=after]').val('');

			if ( ! $('.editList_finish.publish ').length) {
				$form.find('.hidden_upload').hide();
			}

			if (data['link_type'] == 'text') {
				$form.find(content_input).val(data['content']);
			}
			
			$form.find(img_preview+' img').attr('src', data['img']);
			
			$(self+menu_items).addClass('disabled');

			//save information
			last_post_info = "";
			$(self+' form').find('input:visible,textarea:visible').each( function() {
				last_post_info += $(this).val();
			});

			// scroll to edit section
			$('input,textarea,select',$form).filter(':visible:first:not([readonly])').focus();
			$('body').animate({scrollTop:$form.offset().top});
			
			return false;
		});
	
	/**
	 * Add new post btn
	 */
	$(document).on('click',self+' .editList_actions a.editList_preview', function() {

		add_new = true; // GG
		$(this).closest('.listManager_managerColumn').addClass('active');
		set_containers_height();
		
		/* RR - disabled due to Alexi's request
		var _top = $(self+posts_list).scrollTop();
		var lis = $(self+posts_list+' li[id]');
		for (var i=0;i < lis.length; i++) {
			if (lis[i].offsetTop > _top) break;
		}
		if (i == lis.length) i--;
		
		console.info("Insert after", lis[i]);*/
		
		var new_item = $( $(self+posts_list).attr('data-template') ).tmpl({
			'description': '+ New post'
		});
		new_item.find('[data-confirm]').removeAttr('data-confirm');
		if ($(self+posts_list+' li[id]').length) { 
			var after = $(self+posts_list+' li[id]:last').attr('id').replace('newsfeed_id_','');
			new_item.insertAfter($(self+posts_list+' li[id]:last'));
		} else {
			var after = '';
			$(self+posts_list).append(new_item);
		}
		
		switch_to('image');
		$(self+' form.temp'+img_preview+' img').attr('src',placeholder_src);
		$(self+' form.temp'+link_input).removeAttr('readonly');
		$(self+' form.temp [name=newsfeed_id]').val('');
		$(self+' form.temp'+title_input).val('');
		$(self+' form.temp'+content_input).val('');
		$(self+' form.temp [name=after]').val( after );
		$(self+menu_items).removeClass('disabled');

		//save information
		last_post_info = "";
		$(self+' form').find('input:visible,textarea:visible').each( function() {
			last_post_info += $(this).val();
		});
		
		return false;
	});
	
	/**
	 * Cancel btn
	 */
	$(document).on('click',self+' .editList_actions .addList_cancel', function() {
		$(this).closest('.listManager_managerColumn').removeClass('active');
		set_containers_height();
		
		$(self+posts_list+' li[id="newsfeed_id_undefined"]').remove();
		$(self+' .editList_actions .addList_save').removeAttr('disabled').val('Save');
		$(self+' .editList_actions .addList_save_add').removeAttr('disabled');
		return false;
	});
	
	/**
	 * Save btn
	 */
	$(self+' .editList_actions .addList_save').on('click', function() {
		add_new = false;
	});
	
	$(self+' .editList_actions .addList_save_add').on('click', function() {
		add_new = true;
	});
	
	/**
	 * Save temp form
	 */
	 var selector = self+' form.temp';

	$(document)
		.on('validate', selector, function(e, callback) {
			$(self+' form.temp').trigger('prevalidate');
			if ($(self+' form.temp').hasClass('error')) {
				console.info('ERROR ');
				return callback.call(this, {'status': false});
			}
			$(self+' .editList_actions .addList_save').attr('disabled','disabled').val('Loading...');
			$(self+' .editList_actions .addList_save_add').attr('disabled','disabled');
			return callback.call(this, {'status': true});
		})
		.on('success', selector, function(e, res) {
			var $this = $(this);
			$(self+' .editList_actions .addList_save').removeAttr('disabled').val('Save');
			$(self+' .editList_actions .addList_save_add').removeAttr('disabled');
			if (!res || !res.status) {
				$this.find('.error:visible:first').html(res.error);
				return;
			}
			var newsfeed_id = $this.find('[name=newsfeed_id]').val() ? $this.find('[name=newsfeed_id]').val() : 'undefined';
			var li = $(self+posts_list+' li[id="newsfeed_id_'+newsfeed_id+'"]');

			var data = {
					'newsfeed_id': res.id,
					'link_url': $this.find(link_input).val(),
					'link_type': $this.find(type_input).val(),
					'_img_thumb': $this.find(img_preview+' img').attr('src'),
					'description': $this.find(title_input).val(),
					'content' : $this.find(content_input).val()
				};

			li.replaceWith( $( $(self+posts_list).attr('data-template') ).tmpl(data, ajaxList_process) );
			$this.find('[name=newsfeed_id]').val(res.id);

			last_post_info = "";

			if (add_new) {
				$(document).trigger("popup_info","The post was added.");
				$(this).get(0).reset();
				image = {};
				$('.addList_previewContainer img',this).attr("src",placeholder_src);
				$(self+' .editList_actions a.editList_preview').trigger('click');
			} else {
				$(document).trigger("popup_info","The post was updated.");
				$(self+' .editList_actions .addList_cancel').trigger('click');
			}
		});
	
	/* ================== Direct CODE ============================= */
	
	if ($('.editList_upper').length) {
		init();
	} else {
		$(function() { init(); });
	}

	$(document).on('success','.setAsCover',function(){
		$('#lists .setAsCover').removeClass("covered js-disabled");
		$(this).addClass("covered js-disabled");
	})

	return this;
});
