/* *********************************************************
 * Fix the height of the Manage List viewing element.
 *
 * ******************************************************* */

define (['jquery'], function($) {

	$(function() {
		$('#circle_ui_content').height($(window).height());
	});

});
