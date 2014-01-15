/* *********************************************************
 * Not sure where this is used, but needs to be consolidated with other similar case scenarios
 *
 * ******************************************************* */

define (['plugins/hoverintent', 'jquery'], function() {
	$(function() {
				//Hover over avatar should pop-up a badge
			    var hoverintent_config = {
			        over: show_badge,
			        timeout:200,
			        interval: 300,
			        out: hide_badge
			    };
				$('.show_badge').hoverIntent(hoverintent_config);
				$('.newsfeed_entry_options .newsfeed_view_comments_lnk').each(function() {
					$(this).text($(this).text().replace('Hide','View'));
				});
	});
});
