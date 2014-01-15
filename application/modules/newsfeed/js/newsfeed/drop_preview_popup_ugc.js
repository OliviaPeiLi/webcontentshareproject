/*
 * Manages resizing and populating the drop preview popup
 * @link /
 * @uses like/drop_page - for up/down vote logic
 * @uses jquery
 */
define(['like/drop_page', 'jquery'], function() {

	/* ============================= Variables ======================= */
	var self = this;
	var max_iframe_width = $(window).width()-325; //if iframe width is bigger than this then a zoom is applied
	var max_zoom = 0.6; //if the zoom is bigger than this then iframe horizontal scroll is applied
	
	//Selectors for popup
	var popup_container = '.preview_popup_main';
	var popup_iframe = popup_container+' iframe';
	var popup_thumb = popup_container+' .thumb-img';
	var popup_img = popup_container+' .full-img';
	var popup_desc = '.js-description';
	var popup_top = '.item_top';
	var popup_edit_btn = ' [href="#newsfeed_popup_edit"]';
	
	//Selectors for page
	var popup_trigerers = "a[href='#preview_popup']:not('.link-popup'), div[data-url='#preview_popup'], li[data-url='#preview_popup']";
	var drop_thumb = '.drop-preview-img'; //for non-text drops
	var user_el = '.newsfeed_dropInfo_nameAndDateLink';
	var drop_desc = '.js-description';
	var drop_desc_plain = '.drop_desc_plain';
	var folder_el = 'a.folder-url';
	var drop_time = '.newsfeed_dropInfo_dropDate';
	var edit_trigerer = "a[href='#newsfeed_popup_edit']";
	var like_container = '.upbox';
	var like_count = ' .js_upvotes_count:first';
	var redrop_count = '.js_collect_count';

	/* ======================= Private functions ======================= */
	
	/**
	 * This is an instant popup so it pulls the data from the clicked drop
	 */
	this.get_drop_data = function(container) {
		console.warn(container);
		var data = {
			'id': container.attr('data-newsfeed_id'),
			'user_link': container.find(user_el).attr('href'),
			'user_avatar': container.find(user_el+' img').attr("src"),
			'user_fullname': container.find(user_el+' .newsfeed_dropInfo_dropUser').text(),
			//'user_role': container.find(user_el+' + .roleTitle').text(),
			'drop_link':  '/drop/'+container.attr('data-url'),
			'drop_desc': container.find(drop_desc).html(),
			'drop_desc_plain': container.find(drop_desc).text(),
			'folder_url': container.find(folder_el).attr('href'),
			'folder_name': container.find(folder_el).html(),
			'drop_time': container.find(drop_time).html(),
			
			'can_edit': container.find(edit_trigerer).length ? true : false,
			'can_like': container.find(like_container).length ? true : false,
			'is_liked': container.find(like_container+like_count).hasClass('unlike'),
			'like_count': container.find(like_container+like_count).text(),
			'redrop_count': container.find(redrop_count).text(),
			'complete' : container.find(drop_thumb).attr('data-complete'),
		};
		
		data['drop_image'] = container.find(drop_thumb).attr('src');
		data['img_width'] = container.attr('data-img_width');
		data['img_height'] = container.attr('data-img_height');
		data['iframe'] = '/bookmarklet/snapshot_preview/'+data['id'];
		
		return data;
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
	 * Img class can be:
	 * portrait  -  width: 100%
	 * (none) - height: 100%;
	 */
	this.set_size = function() {
		var content = $('#preview_popup')
		//text, image, video, HTML
		var w = content.find(popup_thumb).attr('data-width');
		var h = content.find(popup_thumb).attr('data-height');

		content.find(popup_thumb).css({width:'auto',height:'auto',opacity:1});

		var $container =  content.find(popup_container);
		var $img = $container.find('img');

		$img.addClass('portrait');

		// check if is preloader icon
		if ($img.attr("data-complete") == 1)	{
			if ($img.attr('data-width') && parseInt($img.attr('data-width')) > 1) {
				$img.height('auto').width(Math.min($img.attr('data-width'), parseInt($container.css('max-width'))));
			} else {
				$img.height('auto').width($container.css('max-width')*0.7);
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
		
		content.find(popup_desc).html( data['drop_desc'] );
		
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

		content.find(popup_thumb+','+popup_img).removeClass('watermarked');
		
		//For the phishing protection
		var exdate=new Date();
			exdate.setHours(exdate.getHours()+1)
		document.cookie="preview="+escape(data['id'])+"; expires="+exdate.toUTCString()+"; path=/";
		console.info('set cookie', 'preview', data['id']);
		
		content.find(popup_iframe)
			.width(parseInt(data['img_width']) > 10 ? data['img_width'] : 800).height(parseInt(data['img_height']) > 10 ? data['img_height'] : 800)
			.attr('src', php.baseUrl.replace('https://','http://')+data['iframe']);
			
		content.find(popup_iframe).css("min-width","700px");

	}
	
	/**
	 *  Resize the iframe. Called on iframe doc.ready.
	 *  @see init()
	 */
	function iframe_callback(data, ownerWindow) {
		var iframe = $('#preview_popup '+popup_iframe);
		$(popup_container).removeClass('loaded').addClass('iframe_loaded');
		if (data.width < 100 || data.width < 300 && data.height > 5000) return;
		
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

		var linktext = data['link'].length > 31 ? data['link'].substring(0,31) + '...' : data['link'];
		
		content.find('#permalinks').addClass('has-link');
		content.find('#permalinks > a').attr('href',data['link'] );
		content.find('#permalinks #link_favicon').attr("src","http://www.google.com/s2/favicons?domain="+data['source']);
		content.find('#permalinks .linktext').text(linktext);
		content.find('#permalinks .linktext').parent().attr('href', data['link']);
		
		if (data['link'].indexOf('https://')>-1) {
			content.find(popup_iframe).attr('src', content.find(popup_iframe).attr('src').replace('http://','https://'));
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

		//Edit
		if (data['can_edit']) {
			console.log('show EDIT button');
			content.find(popup_edit_btn).show();
		} else {
			console.log('hide EDIT button');
			content.find(popup_edit_btn).hide();
		}
		
		if (data['can_like']) {
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
		
		if (data['can_edit']) {
			console.log('show coversheet');
			content.find('.edit_coversheet').show();
		}

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
			$(window).scrollTop($(document).height()-$(window).height());
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
	$(document).on('shown','#preview_popup', function() {

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
		},'json');

	}); //END on_open event

	/**
	 * When close the popup. Reset the iframe, url, metacontent and iframe resizing
	 */
	$(document).on('hide','#preview_popup', function() {
		$(this).find('img.full, iframe').unbind('load').attr('src','');
		$(this).removeClass("newsfeed_activity");
	}); //END on_hide event

	/**
	 * Goto previous drop preview
	 */
	$(document).on('click','#preview_popup #popup_arrow_left', function() {

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
	$(document).on('click','#preview_popup #popup_arrow_right',  function() {

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
