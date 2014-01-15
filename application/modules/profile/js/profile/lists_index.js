define(['jquery'], function() {
	
	
	$( document ).on( 'scroll_bottom', '.allLists_body', function() {
		if (typeof this.ajaxList_process != 'undefined') return;
		this.ajaxList_process = function(data) {
			
			if (data.recent_newsfeeds[0]) {
				if (data.recent_newsfeeds[0].link_type == 'text') {
					$(this).find('.listUnit_upper .textContent').show().html(data.recent_newsfeeds[0].text);
				} else {
					$(this).find('.listUnit_upper img').show().attr('src', data.recent_newsfeeds[0]._img_thumb);
				}
			}
			
			for (var i=1;i<=3;i++) {
				if (data.recent_newsfeeds[i]) {
					if (data.recent_newsfeeds[i].link_type == 'text') {
						$(this).find('.listUnit_lower .textContent:nth-child('+i+')').show().html(data.recent_newsfeeds[0].text);
					} else {
						$(this).find('.listUnit_lower img:nth-child('+i+')').show().attr('src', data.recent_newsfeeds[0]._img_thumb);
					}
				}
			}
		}
	});

})