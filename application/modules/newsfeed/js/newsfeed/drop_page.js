/**
 * JS logic for the drop page
 * @link /drop/some-drop-link
 * @uses comments/newsfeed_comments - for the comments in right side
 * @uses social/all - for .share_fb_app
 * @uses like/drop_page - for up/down vote logic
 * @uses jquery
 */
define(['comments/newsfeed_comments', 'social/all', 'like/drop_page', 'folder/collect', 'jquery'], function() {
	
	/* ======================= Vars ===================== */
	var self = this;
	var max_iframe_width = $(window).width()-325; //if iframe width is bigger than this then a zoom is applied
	var max_zoom = 0.6; //if the zoom is bigger than this then iframe horizontal scroll is applied
	//Selectors
	var collection = '.drop-page .folder_link';
	var content = '.drop-page .preview_popup_main';
	var thumb_img = content+' img.thumb-img';
	var big_img = content+' img.full-img';
	var iframe_sel = content+' iframe';	
	var social_wrap = content + ' .social';
	var referrals_input = '#topReferral_competitorsList input[type="text"]';
	
	/* ====================== Private functions =================== */
	
	function get_type() {
		return $('.tl_icon').length ? $.trim($('.tl_icon').attr('class').replace('tl_icon','')) : '';
	}
	/**
	 *  Resize the iframe. Called on iframe doc.ready.
	 *  @see init()
	 */
	function iframe_callback(data, ownerWindow) {

		var iframe = $(iframe_sel);

		//var new_height = $(window).height() - iframe.offset().top 
		//if (iframe.height() < new_height) iframe.height(new_height);
		//set_iframe_size(iframe);
		$(content).removeClass('img-loaded').addClass('iframe-loaded');
		if (get_type() == 'tl_RSS' && (data.width < 100 || data.width < 300 && data.height > 5000)) return;
		if (data.width == 0 && data.height == 0) { //FF on html content
			ownerWindow.postMessage(JSON.stringify({'action':'get_size', "fandrop_message": true }), "*");
		}
		console.info('iframe size', iframe.width(), iframe.height());
		//Set iframe zooming based on its width
		var zoom = 1;
		if (data.width && data.width > iframe.width()) {
			zoom = Math.round(iframe.width() / data.width * 100)/100;
			if (zoom < max_zoom) zoom = max_zoom;
			console.info('ZOOM', zoom);
			ownerWindow.postMessage(JSON.stringify({'action':'zoom', 'zoom': zoom, "fandrop_message": true }), "*");
		}
		//set iframe height to full contents height to hide the autoscroll
		if (data.width && data.height) {
			console.info('new size', data);
			data.height = data.height * zoom;
			iframe.height(data.height);
		}
	}
	
	/**
	 * Called when the full image is loaded
	 * @see init()
	 */
	function big_image_callback() {

		if ($(content).hasClass('iframe-loaded')) return;

		$(content).addClass('img-loaded')
	}

	/* =========================== Events ======================== */

	if ($(big_img).length) {
		if ($(big_img).hasClass('loaded')) {
			big_image_callback();
		} else {
			$(big_img).load(function() {
				big_image_callback();
			});
		}
	} else {
		$(function() {
			if ($(big_img).hasClass('loaded')) {
				big_image_callback();
			} else {
				$(big_img).load(function() {
					big_image_callback();
				});
			}
		});
	}
	/**
	 * Iframe communication
	 */
	$(window).bind('message', function(e) {
		var msg;
		if (!e.originalEvent && window.tmp_message) {
			e.originalEvent = window.tmp_message;
			window.tmp_message = null;
		} 
		msg = e.originalEvent.data;
		if (!msg) return;
		if (msg.indexOf('{') != 0) return;
		data = eval('('+msg+')');
		if (!data.fandrop_message) return;
		console.info('MESSAGE', data);
		if (data.action == 'doc_ready') {
			console.info(e.originalEvent);
			iframe_callback(data, e.originalEvent.source);
		}
	});
	
	/**
	 * Show/hide char count and comment button
	 */

	 var selector = '#content.drop-page .fd_mentions';

	$(document)
	.on('focus', selector, function(){
		$(this).parent().find('.comment_char_count').show();
		$(this).parent().find('input[name="submit"]').show();
	})
	.on('blur', selector, function(){
		//do not hide instantly because the button gets hidden before its clicked
		var $this = $(this);
		if ( $('#right textarea[name=comment_orig]').length && $('#right textarea[name=comment_orig]').val().length == 0 ) {
			window.setTimeout(function() {
				$this.parent().find('.comment_char_count').hide();
				$this.parent().find('input[name="submit"]').hide();
			}, 200);
		}
	});
	
	/**
	 * Specific code for drop page delete func
	 */
	$(document).on('success','#delete_dialog .delete_yes', function() {			
		window.location.href = $(collection).attr('href');
	});
	
	/**
	 * Drop page doesnt open a popup of another drop but redriects to it
	 */
	$(document).on('click', 'a[href="#preview_popup"]', function() {
		window.location.href = '/drop/'+$(this).attr('data-url');
	});
	
	$(document).on('success', '#referral_list form', function(e, responce) {
		if (!responce.status) {
			$(this).find('.error').html(responce.error).show();
			return;
		}
		$(this).find('.form_row.email').hide();
		$(this).find('.form_row.success').show().find('textarea').val(responce.url);
		
		$(this).find('.form_row.success .share_twt_app').removeClass('inactive').attr('data-url', responce.url);
		$(this).find('.form_row.success .share_fb_app').removeClass('disabled_bg').attr('data-url', responce.url);
		$(this).find('.form_row.success .pin-it-button').removeClass('inactive').attr('data-url', responce.long_url);
		$(this).find('.form_row.success .share_gplus_app').removeClass('inactive').attr('data-url', responce.url);
		$(this).find('.form_row.success .share_likedin_app').removeClass('inactive').attr('data-url', responce.url);
		php.referral = responce.id;
	});
	
	$(document).on('share_success', function(e, data) {
		//if (php.referral) {
			$('#main-content').find('.js-points_count').text(parseInt($('#main-content').find('.js-points_count').text()) + 10);
		//}
	});
	
	$(document).on('click', '#referral_list form textarea', function() {
		$(this).select();
	});
	
	$(document).on('focus', referrals_input, function() {
		if ($(this).hasClass('initialized')) return;
		$(this).addClass('initialized');
		var $this = $(this);
		$this.autocomplete({
			'source': '/post_referrals/'+$('#content').attr('data-newsfeed_id'),
			'autoFocus': true,
			/*'select': function(e, ui) {
				var name = $.trim(ui.item.value.split('[')[0]);
				var points =  ui.item.value.split('[')[1].replace(']','');
				console.info(name, points);
			}*/
		})
		.data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li>" )
				.append( '<a href="'+item.url+'">'
							+'<span class="name">'+item.name+'</span>'
							+'<span class="url">'+item.url+'</span>'
							+'<span class="points">'+item.points+' pts</span>'
						+'</a>')
				.appendTo( ul );
		};
	});

	$(document).on('click', '#topReferral_competitorsList input[type="button"]',  function() {
		$.get('/post_referrals/'+$('#content').attr('data-newsfeed_id'), {'term': $(referrals_input).val()}, function(res) {
			$(referrals_input).val(res[0]);
		},'json');
		return false;
	});

})
