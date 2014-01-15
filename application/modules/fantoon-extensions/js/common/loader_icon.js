/*
 * Loader icon with fading logo
 * to include it, make a call to show_loader(element)
 * where "element" is the element that will contain this loader
 * OR
 * in cases of uploading a file,
 * give the following class name to the form input field:
 * img_upload_for_preview
 * available sizes: [30,40,60,100]
 * @to-do write docs
 */
define(['jquery'], function() {
	
	this.show_loader = function(elt,size,pos,transparent) {
		//<img class="loading_icon" src="/images/loading_icons/100x100_transparent.gif" style="vertical-align: middle; text-align: center; position: absolute; width: 100px;">
		console.log(transparent);
		var tpt = transparent ? '_transparent' : '';
		var base = '/images/loading_icons/';
		var loader_html = '<img class="loading_icon" src="'+base+''+size+'x'+size+''+tpt+'.gif" style="vertical-align: middle; text-align: center; position: absolute; width: '+size+'px;">';
		var loader = $(loader_html);
		var s = Math.floor( size / 2 );

		var left=0, top=0;

		console.log('=================================================');
		if (pos && pos.left) {
			left = pos.left;
		} else {
			left = elt.width()/2-s;
		}

		if (pos && pos.top) {
			console.log('top='+pos.top);
			top = pos.top;
		} else {
			console.log('eltht='+elt.height());
			console.log(elt);
			top = (elt.height() > 0) ? (elt.height()/2-s)+'px' : '30%'
		}
		//var left = 10;
		//var top = 10;
		console.log('top: '+top+', left: '+left);
		loader.css('left',left+'px').css('top', top);
		//elt.addClass('addBorder');
		console.warn('append loader to ' , elt);
		elt.append(loader);
		console.log('add loader', loader);
	}
	
	this.hide_loader = function(element) {
		try {
			var loading_icon = element.find('.loading_icon');
			loading_icon.hide('fade').remove();
		} catch (e) {
			console.log('catching');
		}
	}
	
	return this;
});