/**
 *  Logic for the newsfeed edit popup. Opens when you click "Coversheet" on any drop.
 *  Allows uploading thumbails, cropping and edit caption and link.
 *  @link /
 *  @uses common/loader_icon - used for the image upload #newsfeed_edit #thumbnails-form
 *  @uses plugins/jcrop - for cropping the coversheet
 *  @uses jquery
 */
define(['common/loader_icon','plugins/jcrop','jquery'], function(loader_icon) {
	
	/* ============================= Variables ============================= */
	
	php.lang['change_coversheet'] = 'Edit Drop Coversheet';
	php.lang['coversheet_crop'] = 'Edit Drop - Crop Coversheet';
	php.lang['coversheet_preview'] = 'Edit Drop Coversheet - Preview';
	
	/* ============================ Private functions ======================== */
	
	//RR - This is moved as a function because it may go to common/utils
	function get_thumb(src, thumb) {
		console.log('get_thumb: '+src);
		if (!src || src=='') return '';
		thumb = thumb ? '_'+thumb : '';
		var ext = src.substring(src.lastIndexOf('.'));
		var base = src.indexOf('_') > -1 ? src.split('_')[0] : src.replace(ext, '');
		return base+thumb+ext;
	}

	function add_google_image(result) {
		var item = $('#newsfeed_edit .google-list li.sample').clone().removeClass('sample');
			item.find('img:not(.loading_icon)').attr('src', result.thumb);
			item.attr('data-image', result.img);
		$('#newsfeed_edit .google-list').append(item);
	}
	function populate_google_images(val) {
		$("#newsfeed_edit .google-list li:not(.sample)").remove();
		$.post('/google_image_search', {'type':'json', 'query': val}, function(data) {
			if (!data.results) return;
			for (var i=0;i<data.results.length;i++) {
				add_google_image(data.results[i]);
			}
		},'json');
	}

	function set_data(content, data) {

		console.info('{Newsfeed Edit popup} - Data', data);
		console.log('ONLOAD');
		
		$('#newsfeed_edit [name=save]').hide();
		content.attr('data-newsfeed_id', data.newsfeed_id);
		content.find('.delete_button').attr('data-delurl', '/del_link/'+data.newsfeed_id);
		//Left Side - Set thumb image src
		if (!content.find('.newsfeed-thumb img').attr('data-loading')) {
			//content.find('.newsfeed-thumb img').attr('data-loading', content.find('.newsfeed-thumb img').attr('src'));
			loader_icon.hide_loader(content.find('.newsfeed-thumb'));
		}
		if (data.thumb) { //non text posts
			content.css('width','');
			content.find('.left').show();
			content.find(".right a[href='#change_image']").show();
			
			content.find('.newsfeed-thumb img').unbind('load').bind('load', function() {
				$("#newsfeed_edit").animate({'margin-top': -$("#newsfeed_edit").height()/2});
			});
			content.find('.newsfeed-thumb img').attr('src', get_thumb(data.thumb, 'tile') );
			content.find('.collection-thumb img').attr('src', get_thumb(data.thumb, 'bigsquare') );
			if (data.watermarked) {
				content.find('.collection-thumb img, .newsfeed-thumb img').addClass('watermarked');
			} else {
				content.find('.collection-thumb img, .newsfeed-thumb img').removeClass('watermarked');
			}
			if (content.find("a.crop").show().hasClass('cropping')) content.find("a.crop").trigger('click');
		} else {  //Text posts
			content.width(360);
			content.find('.left').hide();
			content.find("a.crop").hide();
			content.find(".right a[href='#change_image']").hide();
			window.setTimeout(function() {
				$("#newsfeed_edit").animate({'margin-top': -$("#newsfeed_edit").height()/2});
			},300);
		}
		//Right side - Set the form data
		content.find("[name=newsfeed_id]").val( data.newsfeed_id );
		content.find("textarea.caption").val( data.description );
		if (data.link) {
			content.find("[name='activity[link]']").val( data.link ).parent().show();
		} else {
			content.find("[name='activity[link]']").parent().hide();
		}
		content.find('.data_status').hide();
		content.find('.delete_button').show();
		content.find('.cancel_button').hide();
		content.find(".right .editSection_One").height(0);
		content.find(".right .editSection_Two").css('height','');
		content.find(".right .google-query").val(data.description);
		
		populate_google_images(content.find(".right .google-query").val());
		
		content.one('shown', function() {
			if (content.find('.crop').hasClass('cropping')) {
				content.find('.crop').click();
			}
		});
		content.find()
	}
	
	/* =================================== Events ========================= */

	/**
	 * Populate the popup with selected drop data
	 */
	$(document).on('before_show',"#list_newsfeed [href='#newsfeed_edit']", function(e, content) {
		e.stopPropagation();
		console.info('{Newsfeed Edit popup} - Open');
		var container = $(this).closest('[data-newsfeed_id]');
		set_data(content, {
			'newsfeed_id': container.attr('data-newsfeed_id'),
			'thumb': container.find('.drop-preview-img').length ? container.find('.drop-preview-img').attr('src') : false,
			'watermarked': container.find('.drop-preview-img').length ? container.find('.drop-preview-img').hasClass('watermarked') : false,
			'description': $.trim(container.find('.drop-description').text()),
			'link': $.trim(container.find('.link_url').text()),
		});
	});

	/**
	 * Populate the popup with selected drop data (PREVIEW POPUP CASE)
	 */
	$(document).on('before_show', "#preview_popup [href='#newsfeed_edit']", function(e, content) {
		var container = $('#preview_popup');
		var newsfeed_id = $(this).closest('[data-newsfeed_id]').attr('data-newsfeed_id');
		console.info('{Newsfeed Edit popup} - Open inside popup', newsfeed_id);
		set_data(content, {
			'newsfeed_id': newsfeed_id,
			'thumb': container.find('img.full-img').attr('src') ? container.find('img.full-img').attr('src') : false,
			'watermarked': container.find('img.full-img').attr('src') ? container.find('img.full-img').hasClass('watermarked') : false,
			'description': $.trim( container.find('h2.pop_up_title').text() ),
			'link': $.trim( container.find('.drop-link').text() ),
		});
	});

	/**
	 * Upload image link - shows section 1, 2 or crop
	 */
	$(document).on('click', "#newsfeed_edit .right a[href='#change_image']", function() {
		console.info('Change image');

		$("#newsfeed_edit .right .google-query").val($("#newsfeed_edit textarea.caption").val());
		populate_google_images($("#newsfeed_edit .right .google-query").val());
		
		$('#newsfeed_edit [name=save]').show();
		$("#newsfeed_edit .right .editSection_One").animate({'height': 0});
		//var h = $("#newsfeed_edit .right .editSection_Two").css({'height': 'auto'}).height();
		var h=394;
		console.log('height: '+h);
		$("#newsfeed_edit .right .editSection_Two").height(0).animate({'height': h}, function() {
			$("#newsfeed_edit").animate({'margin-top': -$("#newsfeed_edit").height()/2});
		});
		$('#newsfeed_edit .delete_button').hide('fade');
		$('#newsfeed_edit .cancel_button').show();
		$("#newsfeed_edit a.crop").hide('fade');
		$('#newsfeed_edit .modal-header h3').html(php.lang['change_coversheet']);
		return false;
	});
	$(document).on('click', "#newsfeed_edit .right a[href='#basic_info']", function() {
		console.info('Basic info');
		$("#newsfeed_edit .right .editSection_Two").animate({'height': 0}, function() {
			$("#newsfeed_edit").animate({'margin-top': -$("#newsfeed_edit").height()/2});
		});
		var h = $("#newsfeed_edit .right .editSection_One").css({'height': 'auto'}).height();
		$("#newsfeed_edit .right .editSection_One").height(0).animate({'height': h});
		$('#newsfeed_edit .delete_button').show('fade');
		$('#newsfeed_edit .cancel_button').hide();
		$("#newsfeed_edit a.crop").show('fade');
		$('#newsfeed_edit .modal-header h3').html(php.lang['change_coversheet']);
		return false;
	});
	$(document).on('click', '#newsfeed_edit .cancel_button', function() {
		console.log('cancel1');
		$(this).closest('.modal').modal('hide');
		/*
		if ($('#newsfeed_edit .crop').hasClass('cropping')) {
			$('#newsfeed_edit .crop').trigger('click');
		}
		$("#newsfeed_edit .right a[href='#basic_info']").trigger('click');
		$('#newsfeed_edit [name=save]').hide('fade');
		*/
		return false;
	});

	/**
	 * Autosave - only when editing caption or link
	 */
	var autosave_timer;
	$(document).on('keyup', "#newsfeed_edit textarea.caption, #newsfeed_edit [name='activity[link]']", function() {
		//TODO: Validate
	 	console.log('validate');
		if ($(this).hasClass('caption')) {
			if ($(this).val() == '') {
				$('#description_err').show();
				return false;
			} else {
				$('#description_err').hide();
			}
		}
		if (autosave_timer) window.clearTimeout(autosave_timer);
		autosave_timer = window.setTimeout(function() {
			$("#newsfeed_edit #thumbnails-form").submit();
		}, 1000);
	});

	/**
	 * Google images select
	 */
	$(document).on('click', "#newsfeed_edit .google-list li a", function() {
		console.info('{coversheet popup} - google image	', $(this).closest('[data-image]').attr('data-image'));
		$("#newsfeed_edit #thumbnails-form [name=url]").val( $(this).closest('[data-image]').attr('data-image') );
		$("#newsfeed_edit #thumbnails-form").submit();
		return false;
	});
	var search_timeout;
	$(document).on('keyup', "#newsfeed_edit .right .google-query", function() {
		if (search_timeout) window.clearTimeout(search_timeout);
		search_timeout = window.setTimeout(function() {
			populate_google_images($("#newsfeed_edit .right .google-query").val());
		}, 500);
	});

	/**
	 * Image upload
	 */
	$(document).on('change', '#newsfeed_edit #thumbnails-form [type=file]', function() {
		var allow_upload = false;
		var allowed_sfxs = ['jpg','jpeg','gif','png'];
		console.log($(this).val());
		var fname = $(this).val().substr($(this).val().lastIndexOf('\\') + 1);
		var sfx = '';
		if (fname.lastIndexOf('.') >= 0) {
			sfx = fname.substr(fname.lastIndexOf('.') + 1);
			//console.log(sfx.toLowerCase());
			//console.log(allowed_sfxs);
			allow_upload = (allowed_sfxs.indexOf(sfx.toLowerCase()) >= 0);
		}
		if (allow_upload) {
			console.log('allowed');
			var form = $(this).closest('form').addClass('loading');
			form.find('.data_status').show().html('Uploading...');
	
			form.ajaxSubmit({
			        success:  function(responce) {
			        	form.removeClass('loading');
			        	$('#newsfeed_edit .newsfeed-thumb img').attr('src', get_thumb(responce.thumb, 'tile') );
			        	form.find('[name=img_newsfeed]').val(responce.thumb);
			        	$('#newsfeed_edit .collection-thumb img').attr('src', get_thumb(responce.thumb, 'bigsquare') );
			        	form.find('[name=img_collection]').val(responce.thumb);
			        	
			        	//RR - remove autosave http://dev.fantoon.com:8100/browse/FD-1543
			        	//form.submit();
			    		form.removeClass('loading').find('.data_status').hide();
			    		form.find('.cancel_button').show('fade');
			    		form.find('[name=save]').show('fade');
			        },
			        dataType: 'json'
			    });
		} else {
			console.log('disallowed');
			alert('Uploading files of type .'+sfx+' is not supported');
		}
		return false;
	});
	
	/**
	 * Crop thumbnails
	 */
	var jcrop_api_left = null;
	var jcrop_api_right = null;
	$(document).on('click', '#newsfeed_edit .crop',  function () {
		$('#newsfeed_edit [name=save]').show('fade');
		var form = $('#newsfeed_edit #thumbnails-form');
		var left = $('#newsfeed_edit .left');
		var right = $('#newsfeed_edit .right');
		if ($(this).hasClass('cropping')) {
			console.info('Cancel crop', left, right);
			$('#newsfeed_edit').removeClass('cropping').css({
				'margin-left': -$("#newsfeed_edit").width()/2,
				'margin-top': -$("#newsfeed_edit").height()/2
			});
			$('#newsfeed_edit .delete_button').show('fade');
			$('#newsfeed_edit .modal-header h3').html(php.lang['change_coversheet']);
			$('#newsfeed_edit .cancel_button').hide();

			var w_right = right.show().css('width', '').outerWidth();
			console.info('right', w_right);
			right.width(0).animate({'width': w_right}, function() { right.css('width',''); });

			var w_left = left.css('width', '').outerWidth();
			console.info('left', w_left);
			left.width($('#newsfeed_edit .left .thumbs-list').width()).animate({'width': w_left}, function() {
				left.css('width','');
			});

			$(this).removeClass('cropping').find('.actionButton_text').html('Crop');
			jcrop_api_left.release(); jcrop_api_left.destroy();
			if (jcrop_api_right) {
				jcrop_api_right.release(); jcrop_api_right.destroy();
			}
			for (var i in {'x':'','y':'','w':'','h':''}) {
				form.find('[name=img_newsfeed_'+i+']').val('');
				form.find('[name=img_collection_'+i+']').val('');
			}
			$(this).find('.actionButton_text').text('Crop');
		} else {
			console.info('crop');

			$('#newsfeed_edit .delete_button').hide('fade');
			left.animate({'width': $('#newsfeed_edit .left .thumbs-list').width() });
			right.animate({'width': 0 }, function() {
				right.hide();

				$('#newsfeed_edit .crop').addClass('cropping').hide('fade').find('.actionButton_text').html('Cancel')
				$('#newsfeed_edit').addClass('cropping').css({
					'margin-left': -$("#newsfeed_edit").width()/2,
					'margin-top': -$("#newsfeed_edit").height()/2
				});
			});
			$('#newsfeed_edit .modal-header h3').html(php.lang['coversheet_crop']);
			$('#newsfeed_edit .cancel_button').show();

			var img = $('#newsfeed_edit .newsfeed-thumb img');
			jcrop_api_left = $.Jcrop(img, {
				onChange : function(coords) {
					for (var i in {'x':'','y':'','w':'','h':''}) {
						form.find('[name=img_'+img.closest('[data-thumb_group]').attr('data-thumb_group')+'_'+i+']').val(Math.round(coords[i]));
					}
				},
				setSelect: [0, 0, img.width(), img.height() - (img.hasClass('watermarked') ? 35 : 0) ],
				minSize: [Math.min(100, img.width()), Math.min(200, img.height()- (img.hasClass('watermarked') ? 35 : 0) )]
			});

			var img_coll = $('#newsfeed_edit .collection-thumb img');
			if (img_coll.length) {
				jcrop_api_right = $.Jcrop(img_coll, {
					onChange : function(coords) {
						for (var i in {'x':'','y':'','w':'','h':''}) {
							form.find('[name=img_'+img_coll.closest('[data-thumb_group]').attr('data-thumb_group')+'_'+i+']').val(Math.round(coords[i]));
						}
					},
					setSelect: [0, 0, img_coll.width(), img_coll.height()],
					minSize: [Math.min(50, img.width()), Math.min(50, img.height())],
					aspectRatio: img_coll.width()/img_coll.height()
				});
			}
			if (img.hasClass('watermarked')) {
				img.parent().find('.jcrop-holder').css('margin-bottom', -35);
			} else {
				img.parent().find('.jcrop-holder').css('margin-bottom', 0);
			}
			$(this).find('.actionButton_text').text('Cancel');
		}
		return false;
	});

	 /**
	  * Save Data
	  */

	var selector = "#newsfeed_edit #thumbnails-form";

	$(document).on('preAjax', selector, function() {

		if ($(this).find('[name=url]').val()) {
			var img = $('#newsfeed_edit .thumbs-list .newsfeed-thumb img');
			//img.attr('src', img.attr('data-loading'));
			loader_icon.show_loader($('#newsfeed_edit .thumbs-list .newsfeed-thumb'), 100, undefined, true);
		} else {
			$(this).find('.data_status').html('Saving...').show();
			$(this).find('[name=save]').hide();
		}
		
	})
	.on('success', selector, function(e, responce) {

		if ($(this).find('[name=url]').val()) { //Upload Via Url
			$(this).find('[name=url]').val('');
    		$('#newsfeed_edit .newsfeed-thumb img:not(.loading_icon)').attr('src', get_thumb(responce.thumb, 'tile') );
    		$(this).find('[name=img_newsfeed]').val(responce.thumb);
    		$('#newsfeed_edit .collection-thumb img:not(.loading_icon)').attr('src', get_thumb(responce.thumb, 'bigsquare') );
    		$(this).find('[name=img_collection]').val(responce.thumb);
    		$('#newsfeed_edit .data_status').hide('fade');
    		$('#newsfeed_edit [name=save], #newsfeed_edit a.cancel_button').show('fade');
    		$('#newsfeed_edit .modal-header h3').html(php.lang['coversheet_preview']);
		} else {                                 //Finally Save !                          
			var container = $('[data-newsfeed_id='+$(this).find('[name=newsfeed_id]').val()+']');
				container.find('.drop-description').html(responce.caption);

			container.find('.drop-preview-img').attr('src', get_thumb(responce.img, 'thumb') );
			container.attr('data-coversheet_updated', '1');
			$(this).find('.newsfeed-thumb img:not(.loading_icon)').attr('src', get_thumb(responce.img, 'tile') );
    		$(this).find('.collection-thumb img').attr('src', get_thumb(responce.img, 'bigsquare') );
			//go back to main mode
			if (jcrop_api_left) {
	    		console.info('CROP success');
				jcrop_api_left.release(); jcrop_api_left.destroy();
				if (jcrop_api_right) {
					jcrop_api_right.release(); jcrop_api_right.destroy();
				}

	    		if ($('#newsfeed_edit .crop').hasClass('cropping')) $('#newsfeed_edit .crop').trigger('click');
			} else {
				$("#newsfeed_edit .right .editSection_Two").height(0);
				$("#newsfeed_edit .right .editSection_One").css('height','');
				$('#newsfeed_edit .crop').show('fade');
			}
			//$(this).find('[name=save]').show('fade');
			$('#newsfeed_edit .delete_button').show('fade');

			$(this).find('.data_status').html('Saved!');
			$(this).closest('.modal').modal('hide');
			/*window.setTimeout(function() {
				$('#newsfeed_edit .data_status').hide('fade');
			},2000);*/
		}

		loader_icon.hide_loader($(this).find('.newsfeed-thumb'));

	})

	return this;
})
