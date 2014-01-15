/* *********************************************************
 * List Newsfeed JS
 *  JS logic to init badges inside list newsfeed
 *
 * ******************************************************* */

define(['plugins/hoverintent','common/badge', 'jquery'], function(hi,b) {

	$(function() {
	
		//Hover over avatar should pop-up a badge
	    var hoverintent_config = {
	        over: b.show_badge,
	        timeout:200,
	        interval: 300,
	        out: b.hide_badge
	    };
		$('.show_badge').hoverIntent(hoverintent_config);
		$('.newsfeed_entry_options .newsfeed_view_comments_lnk').each(function() {
			$(this).text($(this).text().replace('Hide','View'));
		});
		
    });

});
