/*
 * Manages resizing and populating the drop preview popup
 * @link /
 * @uses comments/newsfeed_comments - for the popup comments on the right side
 * @uses plugins/jquery.elastic.source - for .comments_form textarea
 * @uses social/all - for share with #share_fb_app
 * @uses like/drop_page - for up/down vote logic
 * @uses jquery
 */
define(['comments/newsfeed_comments','plugins/jquery.elastic.source', 'social/all', 'like/drop_page', 'common/fd-scroll', 'jquery'], function() {

	/* ============================= Variables ======================= */
	var self = this;
	var max_iframe_width = $(window).width()-325; //if iframe width is bigger than this then a zoom is applied
	var max_zoom = 0.6; //if the zoom is bigger than this then iframe horizontal scroll is applied
	//Selectors for popup
	var popup_container = '.preview_popup_main';
	var popup_iframe = popup_container+' iframe';
	var popup_thumb = popup_container+' .thumb-img';
	var popup_img = popup_container+' .full-img';
	var popup_text = popup_container+' .text_wrapper';
	var popup_title = '.pop_up_title';
	var popup_desc = '.js-description-small';
	var popup_top = '.item_top';
	var popup_edit_btn = ' [href="#newsfeed_popup_edit"]';
	//Selectors for page
	var popup_trigerers = "a[href='#preview_popup']:not('.link-popup'), div[data-url='#preview_popup'], li[data-url='#preview_popup']";
	var icon = '.tl_icon';
	var drop_thumb = '.drop-preview-img'; //for non-text drops
	var drop_text = '.text_content'; //for text drops;
	var user_el = 'a.drop-popup-user';
	var drop_title = '.drop-title';
	var drop_desc = '.drop-description';
	var drop_desc_plain = '.drop_desc_plain';
	var folder_el = 'a.folder-url';
	var drop_time = '.drop-time';
	var edit_trigerer = "a[href='#newsfeed_popup_edit']";
	var like_container = '.upbox';
	var like_count = ' .up_count';
	var redrop_count = '.redrop_count';

	/* ======================= Private functions ======================= */
	
	/**
	 * When a preview popup opens it changes facebook metas to share the drop
	 */
	this.set_metacontent = function(data) {

		self.saved_meta_content = $("head meta[property*='og:']").clone();

		var meta_content = '';

		if (!$("head meta[property='fb:app_id']").length) {
			meta_content = '<meta property="fb:app_id" content="'+php.fb_app_id+'" />';
		}
			
		meta_content += '<meta property="og:type" content="'+php.fb_app_namespace+':drop" />';
		meta_content += '<meta property="og:title" content="'+data['drop_desc_plain']+'"/>';
		meta_content += '<meta property="og:url" content="'+php.baseUrl+data['drop_link']+'?ext=fb&"/>';
		if (data['drop_image']) {
			meta_content += '<meta property="og:image" content="'+data['drop_image']+'"/>';
		}
		meta_content += '<meta property="og:description" content="'+$.trim(data['drop_desc'])+'"/>';
		$("head meta[property*='og:']").remove();
		$('head').prepend(meta_content);

	}

	this.clear_metacontent = function() {
		$("head meta[property*='og:'], head meta[propery*='fb:']").remove();
		$("head").prepend(self.saved_meta_content);
	}

	/**
	 * This is an instant popup so it pulls the data from the clicked drop
	 */
	this.get_drop_data = function(container) {

		var data = {
			'id': container.attr('data-newsfeed_id'),
			'class': container.find(icon).attr('class').replace(' tl_icon',''),
			'user_link': container.find(user_el).attr('href'),
			'user_avatar': container.find(user_el).length ? container.find(user_el).attr('data-avatar_42') : false,
			'user_fullname': container.find(user_el).attr('data-full_name'),
			'user_role': container.find(user_el+' + .roleTitle').text(),
			'drop_link':  '/drop/'+container.attr('data-url'),
			'drop_desc': container.find(drop_desc).html(),
			'drop_desc_plain': container.find(drop_desc_plain).text(),
			'folder_url': container.find(folder_el).attr('href'),
			'folder_name': container.find(folder_el).html(),
			'drop_time': container.find(drop_time).html(),
			//'comment_count': container.find('.num_comments').text(),
			'can_edit': container.find(edit_trigerer).length ? true : false,
			'can_like': container.find(like_container).length ? true : false,
			'is_liked': container.find(like_container+like_count).hasClass('unlike'),
			'like_count': container.find(like_container+like_count).text(),
			'liked_in_fb': container.hasClass('liked'),
			'share_count': container.find('.js-share_count').text(),
			'redrop_count': container.find('.js-redrop_count').text(),
			'complete' : container.find('img.drop-preview-img').attr('data-complete'),
			'shared_linkedin': container.find('.share_likedin_app').hasClass('inactive'),
			'shared_gplus': container.find('.share_gplus_app').hasClass('inactive')
		};

		if (container.find(drop_title).length) {
			data['drop_title'] = container.find(drop_title).html();
		}

		if ( ! container.find(drop_thumb).length)       //TEXT content
		{
			data['full_text'] = container.find(drop_text).html();
		}
		else													 //NON-TEXT content
		{
			data['drop_image'] = container.find(drop_thumb).attr('src');
			data['coversheet_updated'] = container.attr('data-coversheet_updated')=='1';
			data['img_width'] = container.attr('data-img_width');
			data['img_height'] = container.attr('data-img_height');
			if (data['class'] != 'tl_image') {                    //NON-IMAGE content (bookmark, html, video)
				data['iframe'] = '/bookmarklet/snapshot_preview/'+data['id'];
			}
		}
		
		console.warn(data);

		return data;
	}

	/**
	 * Sets the fblike btn in the popup
	 */
	this.set_fblike_btn = function(content, data) {
		//FB like a fb like quirk that requires to reload fb-like button so that the new url is parsed
		var old_fblike = content.find('.fb-like');
		old_fblike.html('');
		var new_fblike = old_fblike.clone();
		old_fblike.remove();
		new_fblike.attr('href',php.baseUrl+data['drop_link']);
		content.find('.social').append(new_fblike);
		if (typeof FB !== 'undefined') {
			FB.XFBML.parse();
		}
		if (data['liked_in_fb']) {
			content.find('.share_fb_app').addClass('disabled_bg');
			if (content.find('.share_fb_app').attr('title-cached')) {
				content.find('.share_fb_app').attr('title', content.find('.share_fb_app').attr('title-cached'));
			}
		} else {
			content.find('.share_fb_app').removeClass('disabled_bg')
				.attr('title-cached',
					content.find('.share_fb_app').attr('title')
				).attr('title','');
		}
	}

	/**
	 * Sets the twitter btn in the popup
	 */
	this.set_tweet_btn = function(content, data) {
		content.find('.share_twt_app')
			.attr('data-text', data['drop_desc_plain'].substr(0,100))
			.attr('data-url', data['drop_link']); // .substring(1)
	}
	
	/**
	 * Sets the pin it btn
	 */
	this.set_pinit_btn = function(content, data) {
		var pinit_btn = content.find('.pin-it-button');
		if (data['drop_link'] !== '' && data['class'] != 'tl_text') {
			pinit_btn.show().attr('href', 'http://pinterest.com/pin/create/button/?'
					+'url='+php.baseUrl+data['drop_link'].substring(1)
					+'&amp;media='+data['drop_image'].replace('_thumb','_full')
					+'&amp;description='+data['drop_desc_plain'])			
		} else {
			pinit_btn.hide();
		}
	}

	/**
	 * Sets maximum popup size on doc read and window resize
	 */
	this.set_popup_size = function(popup) {
		popup = popup || $('#preview_popup')
		var max_width = $(window).width() - parseInt($(popup_container).css('margin-left')) - parseInt($(popup_container).css('margin-right'));
		var max_height = $(window).height() - 200 - parseInt($(popup_container).css('margin-top')) - parseInt($(popup_container).css('margin-bottom'));
		popup.find(popup_container).css({
			'max-height': max_height,
			'max-width': max_width
		});
	}

	/**
	 * centers the popup on window or contents resize
	 */
	this.reposition_popup = function(popup) {
		console.info('{Reposition popup} - ', popup.outerWidth(), popup.outerHeight());
		popup.css({
			'margin-left': -Math.round( popup.outerWidth()/2 ),
			'margin-top': -Math.round( popup.outerHeight()/2 )
		});
	}
	
	/**
	 * Sets the iframe to 100% of its height to hide scrolls. Called on iframe load
	 * @see iframe_callback()
	 * @see init()
	 */
	//var iframe_size_interval;
	//function set_iframe_size(iframe) {
	//	var iframe_w = Math.max(iframe.width(), iframe.contents().width());
	//	var iframe_h = Math.max(iframe.height(), iframe.contents().height(),iframe.contents().find('body')[0].scrollHeight); 
	//	console.info('iframe width: ', iframe_w, max_iframe_width);
	//	console.info('iframe height: ', iframe.height(), iframe.contents().height(), iframe.contents().find('body')[0].scrollHeight);
	//	var zoom = 1;
	//	if (iframe.contents().find("script[src*='.howstuffworks.com']").length) iframe_w += 318;
	//	if (iframe_w > max_iframe_width) {
	//		zoom = Math.round(max_iframe_width / iframe_w * 100)/100;
	//		iframe_w = max_iframe_width;
	//	}
	//	if (zoom < max_zoom) {
	//		zoom = max_zoom;
	//	} else {
	//		if (iframe.contents().find("script[src*='.howstuffworks.com']").length) {
	//			iframe.contents().find('#brandscape').height( (zoom*100)+'%' );
	//			iframe_h = iframe_h * zoom;
	//		}
	//	}
	//	var embed = iframe.contents().find('embed');
	//	if (embed.length == 1 && embed.height() > iframe.contents().find('body').height()-40 && embed.width() > iframe.contents().find('body').width()-40) { //Flash site check
	//		iframe.contents().find('embed').each(function() {
	//			var $this = this;
	//			window.setTimeout(function() {
	//				$this.Zoom(200-(zoom*100));
	//			},5000);
	//		});
	//		iframe.contents().find('body').css('overflow','hidden');
	//	} else {
	//		iframe.contents().find('body')
	//			.css('overflow','hidden')
	//			.css('zoom',zoom);
	//		iframe.contents().find('iframe').each(function() {
	//			try { $(this).contents().find('body').css('zoom',zoom); } catch (e) {}
	//		});
	//	}
	//	
	//	console.info('set size', iframe_w, iframe_h);
	//	iframe.width(iframe_w).height(iframe_h);
	//	reposition_popup($('#preview_popup'));
	//	
	//	//RR - commented out bc of: http://dev.fantoon.com:8100/browse/FD-2196
	//	//iframe.contents().find('body div').each(function() {
	//	//	if ($(this).height() == iframe.height()) {
	//	//		this.style.height = 'auto';
	//	//	}
	//	//});
	//	
	//	//iframe_size_interval = window.setInterval(function() {
	//		//iframe.height(Math.max(iframe.contents().height(), iframe.contents().find('body')[0].scrollHeight));
	//		//reposition_popup($('#preview_popup'));
	//	//}, 500);
	//}

	/**
	 * Sets browser url to the drop url
	 */
	var old_url = window.location.href;
	/*bobef: #FD-1705
	//the logic of this function is unknown to me and to Radil and is one of the causes of the issue
	//please note this code is found in other files too, since the reason is unknown i'm commenting only here
	window.onbeforeunload = function(e) {
		if (typeof history.replaceState == 'function') {
			history.replaceState({foo: "bar"}, "", old_url);
		}
		window.onbeforeunload = undefined;
	};
	// end of #FD-1705 */
	this.set_url = function(url) {
		if (typeof history.pushState == 'function') {
			history.pushState({foo: "bar"}, "", url);
		} else {
			window.location.hash = url;
		}
	}

	/**
	 * Clears the browser url on popup close
	 */
	this.clear_url = function() {
		if (typeof history.pushState == 'function') {
			history.replaceState({foo: "bar"}, "", old_url);
		} else {
			window.location.hash = '';
		}
	}
	
	/**
	 * Img class can be:
	 * portrait  -  width: 100%
	 * (none) - height: 100%;
	 */
	this.set_size = function() {

		var content = $('#preview_popup')
		//text, image, video, HTML
		var type = $.trim(content.find('.tl_icon').attr('class').replace('tl_icon','').replace('tl_',''));
		var w = content.find(popup_thumb).attr('data-width');
		var h = content.find(popup_thumb).attr('data-height');

		content.find(popup_thumb).css({width:'auto',height:'auto',opacity:1});

		if (type == 'text') return;
		
		var $container =  content.find(popup_container);
		var $img = $container.find('img');


		if (type == 'HTML' || type == 'RSS' || type == 'video' || h > w*2 || $container.css('max-width')/$container.css('max-height') < w/h) {
			$img.addClass('portrait');

			// check if is preloader icon
			if ($img.attr("data-complete") == 1)	{
				if ($img.attr('data-width') && parseInt($img.attr('data-width')) > 1) {
					$img.height('auto').width(Math.min($img.attr('data-width'), parseInt($container.css('max-width'))));
					//reposition_popup($('#preview_popup'));
				} else {
					$img.height('auto').width($container.css('max-width')*0.7);
				}

			} 

		} else {

			if ($img.attr("data-complete") == 1)	{

				$img.removeClass('portrait')
				if ($img.attr('data-height') && parseInt($img.attr('data-height')) > 1) {
					$img.width('auto').height(Math.min($img.attr('data-height'), parseInt($container.css('max-height'))));
					//reposition_popup($('#preview_popup'));
				} else {
					$img.width('auto').height($container.css('max-height')*0.7);
				}

			}
		}

		if ($('img.full-img',$container).attr("data-complete") == 0)	{
			$img.width('auto');
		}		
	}
	
	/**
	 * This function populates the popup with data pulled directly from the newsfeed
	 * @see set_popup_data() - for extended data like text content, link etc 
	 */
	this.set_popup_basic_data = function(content, data) {

		// http://dev.fantoon.com:8100/browse/FD-3857
		if (data.complete == 0)	{
			$('img.full-img',content).width('auto');
		}

		//Reset
		content.find(popup_container).removeClass('text').removeClass('loaded').removeClass('iframe_loaded');
		content.find(popup_container+' .images_container').css('margin-top', 0);
		
		set_popup_size(); //if the popup is opened before doc.ready
		set_metacontent(data);
		set_url(data['drop_link']);
		check_address();
		//RR - do we need data-url here?
		if (data['drop_title']) {
			content.find(popup_title).html( data['drop_title'] );

			// http://dev.fantoon.com:8100/browse/FD-3801
			if (data['drop_title'] != data['drop_desc'])
				content.find(popup_desc).html( data['drop_desc'] );
		
		} else {
			content.find(popup_title).html( data['drop_desc'] );
		}

		if ( data['drop_image'] ) { //non text
			if (data['coversheet_updated']) {
				content.find(popup_thumb).hide();
			} else {
				content.find(popup_thumb).show();
			}
			
			console.info('{drop preview} - image loading');
			
			content.find(popup_thumb)
				.unbind('load').bind('load', function() {
				})
				.attr('data-width', data['img_width']).attr('data-height', data['img_height'])
				.attr('src', data['drop_image'] )
				.attr("data-complete",data.complete);

			content.find(popup_img).attr("data-complete",data.complete);
			
			content.find(popup_img)
				.unbind('load').bind('load', function() {
					if (content.hasClass('iframe_loaded')) return; //if iframe loads  before the full image no need to show it
					content.find(popup_container).addClass('loaded');
															
					if ($(this).height() < $(this).closest('.preview_popup_main').height()) {
						$(this).parent().css('margin-top', ($(this).closest('.preview_popup_main').height() - $(this).height()) / 2);
					}
				})
				.unbind('click').bind('click', function() {
					window.open(data['drop_link']);
					return false;
				})
				.unbind('error').bind('error', function() {
					if (this.src.indexOf('_full') > -1) {
						this.src = this.src.replace('_full','');
					}
				})
				.attr('data-width', data['img_width']).attr('data-height', data['img_height'])
				.attr('src','')
				.attr('src', data['drop_image'].replace('_thumb','_full').replace('_tile','_full').replace('_bigsquare','_full'))

				
			if (data['iframe']) {

				content.find(popup_thumb+','+popup_img).removeClass('watermarked');
				
				//For the phishing protection
				var exdate=new Date();
					exdate.setHours(exdate.getHours()+1)
				document.cookie="preview="+escape(data['id'])+"; expires="+exdate.toUTCString()+"; path=/";
				console.info('set cookie', 'preview', data['id']);
				
				content.find(popup_iframe)
					//RR - replaced with iframe_callback(data, ownerWindow)
					//.unbind('load').bind('load', function() {
					//	$(this).closest(popup_container).removeClass('loaded').addClass('iframe_loaded');
					//	content.find(popup_container+' .images_container').css('margin-top', 0);
					//	set_iframe_size(content.find(popup_iframe));
					//})
					.width(parseInt(data['img_width']) > 10 ? data['img_width'] : 800).height(parseInt(data['img_height']) > 10 ? data['img_height'] : 800)
					.attr('src', php.baseUrl.replace('https://','http://')+data['iframe']);
					
					if (data['class'] == 'tl_RSS')	{
						content.find(popup_iframe).css("min-width","700px");
					}

			} else {
				content.find(popup_thumb+','+popup_img).each( function () {
					//BP:  FD-3275 no watermark for gifs
					if ( this.src && this.src.substr( -4 ) == '.gif' ) {
						return;
					}
					//end of FD-3275
					$( this ).addClass( 'watermarked' );
				} );
			}
		} else {
			//text posts use set_popup_data()
			content.find(popup_container).addClass('text');
		}
	}
	
	/**
	 *  Resize the iframe. Called on iframe doc.ready.
	 *  @see init()
	 */
	function iframe_callback(data, ownerWindow) {
		var iframe = $('#preview_popup '+popup_iframe);
		$(popup_container).removeClass('loaded').addClass('iframe_loaded');
		var type = $.trim($(icon).attr('class').replace('tl_icon',''));
		if (type == 'tl_RSS' && (data.width < 100 || data.width < 300 && data.height > 5000)) return;
		console.info('iframe size', iframe.width(), iframe.height());
		//Set iframe zooming based on its width
		var zoom = 1;
		if (data.width && data.width > parseInt($(popup_container).css('max-width'))) {
			zoom = Math.round(parseInt($(popup_container).css('max-width')) / data.width * 100)/100;
			if (zoom < max_zoom) zoom = max_zoom;
			console.info('ZOOM', zoom);
			ownerWindow.postMessage(JSON.stringify({'action':'zoom', 'zoom': zoom, "fandrop_message": true }), "*");
		}
		//set iframe width
		if (data.width) {
			data.width = data.width * zoom;
			iframe.width(data.width);
			
			//set iframe height to full contents height to hide the autoscroll
			//this is inside bc of http://dev.fantoon.com:8100/browse/FD-2367
			if (data.height) {
				data.height = data.height * zoom;
				iframe.height(data.height);
			}
		}
		
		console.info('new size', data.width, data.height,'on', data.action);
		//if (content.find(popup_thumb).attr('data-width') == '0') {
			reposition_popup($('#preview_popup'));
		//}
	}
	
	this.set_popup_link_data = function(content, data) {

		console.info('{link popup} - link data', data);

		// http://dev.fantoon.com:8100/browse/FD-3794 on 19.03.2013
		// if (data['source'] && data['link']) {
			var linktext = data['link'].length > 31 ? data['link'].substring(0,31) + '...' : data['link'];
			//content.find('#permalinks .actionButton_text').text( data['source'].substr(0,30) );
			// http://dev.fantoon.com:8100/browse/FD-3794#comment-18553
			content.find('#permalinks').addClass('has-link');
			content.find('#permalinks > a').attr('href',data['link'] );
			content.find('#permalinks #link_favicon').attr("src","http://www.google.com/s2/favicons?domain="+data['source']);
			content.find('#permalinks .linktext').text(linktext);
			content.find('#permalinks .linktext').parent().attr('href', data['link']);
		// } else {
		// 	content.find('#permalinks').removeClass('has-link');
		// 	content.find('#permalinks .linktext').parent().attr('href', '');
		// }
		if (data['link'].indexOf('https://')>-1) {
			content.find(popup_iframe).attr('src', content.find(popup_iframe).attr('src').replace('http://','https://'));
		}

		content.find('.comments-container').css('top', content.find(popup_top).outerHeight() + 9);

	}

	this.set_social_network = function(content, data) {
		// twitter
		if ( data.is_twitter_shared ) {
			content.find('.social .share_twt_app').addClass('inactive');
		} else {
			content.find('.social .share_twt_app').removeClass('inactive');
		}

		// fb
		if ( data.is_shared ) {
			content.find('.social .share_fb_app').addClass('disabled_bg');
		} else {
			content.find('.social .share_fb_app').removeClass('disabled_bg');
		}

		// pintit
		if ( data.is_pinit_shared ) {
			content.find('.social .pin-it-button').addClass('inactive');
		} else {
			content.find('.social .pin-it-button').removeClass('inactive');
		}

	}

	/**
	 * populates the popup with provided data
	 */
	this.set_popup_data = function(content, data) {

		console.info('{link popup} - set data', data);
		//header
		content.find('.avatar_col a').attr('href', data['user_link']);
		content.find('.avatar_col a img').attr('src', data['user_avatar']);
		if (data['user_link']) {
			content.find('.user_link').parent().show();
			content.find('.user_link').attr('href', data['user_link']).html(data['user_fullname']);			
		} else {
			content.find('.user_link').parent().hide();
		}
			
		if (data['user_role']) {
			content.find('.roleTitle').show().text(data['user_role']);
		} else {
			content.find('.roleTitle').hide();
		}

		if (data['folder_url']) {
			content.find('.folder_link').parent().show();
			content.find('.folder_link').attr('src', data['folder_url']).attr('href',data['folder_url']).html(data['folder_name']);
		} else {
			content.find('.folder_link').parent().hide();
		}
		
		content.find('.posted_when').html(data['drop_time']);

		content.find('.tl_icon').attr('class', 'tl_icon '+data['class']);

		//comment count
		//content.find('.comm_count').attr('rel',data['comment_count']);
		
		//Edit
		if (data['can_edit']) {
			console.log('show EDIT button');
			content.find(popup_edit_btn).show();
		} else {
			console.log('hide EDIT button');
			content.find(popup_edit_btn).hide();
		}
		//Like
		if (!php.userId) { //show the like button for landing page
			//content.find('.up_button').show();
		}
		
		if (data['can_like']) {
			content.find('.show').hide();
			content.find('.up_button').attr('href', '/add_like/drop/'+data['id']);
			content.find('.undo_up_button').attr('href', '/rm_like/drop/'+data['id']);
			if (data['is_liked']) {
				content.find('.up_button').hide(); content.find('.undo_up_button').show();
			} else {
				content.find('.up_button').show(); content.find('.undo_up_button').hide();
			}
			content.find('.up_count').text(data['like_count']);
		} else {
			content.find('.upbox').hide();
		}
		content.find('.up_button, .undo_up_button').attr('data-newsfeed_id', data['id']);
		content.attr('data-newsfeed_id', data['id']);
		content.attr('data-url', data['drop_link'].replace('/drop/',''));
		if (data['redrop_count'] == '' || isNaN(data['redrop_count'])) {
			content.find('.redropbox').hide();
		} else {
			content.find('.redropbox').show().find('.redrop_count').text(data['redrop_count']);
		}
		
		if (data['share_count'] != '' && !isNaN(data['share_count'])) {
			content.find('.sharebox').show().find('.share_count').text(data['share_count']);
		} else {
			content.find('.sharebox').hide();
		}
		
		set_fblike_btn(content, data);
		set_tweet_btn(content, data);
		set_pinit_btn(content, data);
		if (data['shared_linkedin']) {
			content.find('.share_likedin_app').addClass('inactive');
		} else {
			content.find('.share_likedin_app').removeClass('inactive');
		}
		if (data['shared_gplus']) {
			content.find('.share_gplus_app').addClass('inactive');
		} else {
			content.find('.share_gplus_app').removeClass('inactive');
		}

		if (data.complete != '1')	{
			$( 'div.social a:not(.share_twt_app, .share_fb_app)', content ).addClass("inactive");
			$( 'div.social a.share_fb_app', content ).addClass("disabled_bg");
		} else {
			$( 'div.social a.share_fb_app', content ).removeClass("disabled_bg");
			$( 'div.social a', content ).removeClass("inactive");
		}

		$('.share_email',content).removeClass("inactive");

		if ( data['drop_image'] ) { //non text
			if (data['can_edit']) {
				console.log('show coversheet');
				content.find('.edit_coversheet').show();
			}
		} else { //text type
			content.find(popup_text+' .text_content').html( data['full_text'] );
			content.find('.edit_coversheet').hide();
			console.log('hide coversheet');
			//reposition_popup(content);
		}

		content.find('.comments_form textarea').elastic();
		content.find('form.comments_form input[name=newsfeed_id]').val(data['id']);

		/**
		 * Populate popup - right (comments list)
		 */
		content.find('.comments_list')
			.html('<img src="/images/loading_icons/bigRoller_32x32.gif" style="margin: 115px 0 0 115px;"/>')
			.load('/popup-right/'+data['id'], function() {
				$('#preview_popup .comments_list').scroll(function() {
					if ( $(this).offset().top + $(this).height() < $(this).find('.comments-bottom').offset().top + $(this).find('.comments-bottom-container').height()) {
						$(this).find('.comments-bottom').addClass('overflow');
					} else {
						$(this).find('.comments-bottom').removeClass('overflow');
					}
				})
				
				var scrollTo = Math.max(0, content.find('.comments_list')[0].scrollHeight - content.find('.comments_list').height() - content.find('#popup_right').height());
				console.info('Scroll popup right to: ', scrollTo, content.find('#popup_right'));
				content.find('.comments_list').animate({'scrollTop': scrollTo});
			 	content.find('.comments_form textarea').focus();

			});
	}
	
	this.init_arrows = function(content, entry) {

		content.find('.popup_arrow').show();
		if (entry.prevAll('.newsfeed_entry').length) {
			content.find('#popup_arrow_left').removeClass('disabled');
		} else {
			content.find('#popup_arrow_left').addClass('disabled');
		}
		if (entry.nextAll('.newsfeed_entry').length) {
			content.find('#popup_arrow_right').removeClass('disabled');
		} else {
			//content.find('#popup_arrow_right').addClass('disabled');
			$(window).scrollTop($(document).height()-$(window).height());
			//trigger_scroll($('#list_newsfeed'), $('#list_newsfeed').find('.feed_bottom'));
		}
	}
	
	this.hide_arrows = function(content) {
		content.find('.popup_arrow').hide();
	}
	
	/* ================================== Events ======================================== */

	$(document).ready(function() {
		set_popup_size($('#preview_popup'));
	});
	
	$(window).resize(function() {
		console.info('Window resize');
		set_popup_size($('#preview_popup'));
		reposition_popup($('#preview_popup'));
	});
	
	/**
     * Comment button should focus on comment box
     * @deprecated
     */
    //$('#preview_popup .newsfeed_comments_lnk').on('click',function(){
	//    $('#preview_popup .comments_form textarea').focus();
	//    return false;
    //});
	
	/**
	 * Show/hide char_count and comment btn
	 */

	 var selector = '#preview_popup .fd_mentions';

	$(document).on('focus', selector, function(){
		$(this).parent().find('.comment_char_count').show();
		$(this).parent().find('input[name="submit"]').show();
	})
	.on('blur', selector, function(){
		//do not hide instantly because the button gets hidden before its clicked
		var $this = $(this);
		if ( $('#preview_popup textarea.fd_mentions').val().length == 0 ) {
		 window.setTimeout(function() {
		 	$this.parent().find('.comment_char_count').hide();
		 	$this.parent().find('input[name="submit"]').hide();
		 }, 200);
		}
	});

    //BP: #FD-2216
	//re-enable the rel=popup attribute
	window.__ft_drop_preview_loaded = true;
	var links = $( "a[href='#preview_popup'][rel='popup-disabled']:not('.link-popup'), div[data-url='#preview_popup'][rel='popup-disabled'], li[data-url='#preview_popup'][rel='popup-disabled']" );
	for ( var i = links.length - 1; i >= 0; --i ) {
		links[i].setAttribute( 'rel', 'popup' );
	}
	//end of #FD-2216

	/**
	 * Position the popup
	 */
	$(document).on('shown', '#preview_popup', function() {

		var $this = $(this);
		$this.find(popup_container).css({
			'min-width': '',
			'min-height': ''
		});
		
		set_size();
		reposition_popup($this);

	});
	
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
	 * Drop preview popup (instant - used in newsfeed)
	 */
	$(document).on('before_show', popup_trigerers, function(e, content) {

		console.log('{drop preview popup} - Open');

		var container = $(this).closest('[data-newsfeed_id]');
		
		if (container.find('.ft-video-iframe').length) {
			container.find('.ft-video-iframe').prev().show();
			container.find('.ft-video-iframe').remove();
		}
		
		if (container.hasClass('newsfeed_entry')) {
			init_arrows(content, container);
		} else {
			hide_arrows(content);
		}

		var data = get_drop_data(container);

		console.info('{drop preview popup} - Data ', data);
		
		set_popup_basic_data(content, data);
		set_popup_data(content, data);
		
		//The content will have this class if left or right arrows are clicked
		if (!content.hasClass('in')) {
			console.info('{preview popup} - set default position')
			content.css({
				'margin-left': - 940 / 2,
				'margin-top': -Math.round(content.outerHeight()/2)
			});
		} else {
			set_size();
			reposition_popup($('#preview_popup'));
		}

		$.get('/popup-info/'+data['id'], function(data) {
			console.log('popup-info', data);
		 	set_popup_link_data(content, data);
			set_social_network (content, data);
		},'json');

	}); //END on_open event

	function check_address()	{

		 if (window.location == old_url && $('#preview_popup').is(":visible"))	{
		 	$('#preview_popup a.close[data-dismiss=modal]').trigger("click");
		 } else	{
		 	window.setTimeout(check_address,1000);
		 }
	}

	/**
	 * When close the popup. Reset the iframe, url, metacontent and iframe resizing
	 */
	$(document).on('hide', '#preview_popup', function() {
		$(this).find('img.full, iframe').unbind('load').attr('src','');
		$(this).removeClass("newsfeed_activity");
		clear_url();
		clear_metacontent();

		//window.clearInterval(iframe_size_interval);
	}); //END on_hide event


	
	/**
	 * Goto previous drop preview
	 */
	$(document).on('click', '#preview_popup #popup_arrow_left', function() {

		var wrap = {};

		if ($('#preview_popup').hasClass("newsfeed_activity"))	{
			wrap = $('.new_activity_feed').eq(0);
		} else	{
			wrap = $('#list_newsfeed');
		}

		if ($(this).hasClass('disabled')) return false;

		// var selected = $( '.newsfeed_entry[data-newsfeed_id='+ $('#preview_popup').attr('data-newsfeed_id')+']', wrap );
		var selected = $('[data-newsfeed_id="' + $('#preview_popup').attr('data-newsfeed_id') + '"]',wrap).eq(0).closest(".newsfeed_entry");

		if ( ! selected.length) {
			$(this).addClass('disabled'); return false;
		}
		
		var prev = selected.prevAll('.newsfeed_entry:first');
		if ( ! prev.length) {
			$(this).addClass('disabled'); return false;
		}

		$('iframe',$('#preview_popup')).unbind('load').attr("src",'');

		$('#preview_popup .popup_arrow').addClass('disabled'); //loading state
		prev.find("[data-url=#preview_popup],a[href='#preview_popup']").click();
		return false;
	});

	/**
	 * Goto next drop preview
	 */
	$(document).on('click', '#preview_popup #popup_arrow_right', function() {

		var wrap = {};

		if ($(this).hasClass('disabled')) return false;

		if ($('#preview_popup').hasClass("newsfeed_activity"))	{
			wrap = $('.new_activity_feed').eq(0);
		} else	{
			wrap = $('#list_newsfeed');
		}

		// var selected = $('.newsfeed_entry[data-newsfeed_id='+$('#preview_popup').attr('data-newsfeed_id')+']',wrap);
		var selected = $('[data-newsfeed_id="' + $('#preview_popup').attr('data-newsfeed_id') + '"]',wrap).eq(0).closest(".newsfeed_entry");

		if ( ! selected.length) {
			$(this).addClass('disabled'); return false;
		}
		
		var next = selected.nextAll('.newsfeed_entry:first');
		var next_count = selected.nextAll('.newsfeed_entry').length;


		if ( ! next.length) {
			$(this).addClass('disabled'); return false;
			console.info('{preview popup} - NO MORE DROPS');
		} else if (next_count <= 1) {
			$('#list_newsfeed.fd-autoscroll')
				.trigger('scroll_bottom') //Load more drops if the last is selected
				.one('scroll_bottom_success', function() { //more drops loaded remvoe the loading state
					$('#preview_popup .popup_arrow').removeClass('disabled');
				})
			console.info('{preview popup} - LOAD MORE DROPS');
		}

		$('iframe',$('#preview_popup')).unbind('load').attr("src",'');

		$('#preview_popup .popup_arrow').addClass('disabled'); //loading state
		next.find("[data-url=#preview_popup],a[href='#preview_popup']").click();
		return false;
	});
	
	return this;
});
