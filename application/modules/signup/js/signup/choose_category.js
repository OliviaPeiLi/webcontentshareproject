/*
 * JS logic to choose category
 * @link /choose_category
 * @uses jquery
 * @to-do use some general form extensions plugin for placeholders, custom checkboxes etc.
 * @to-do move validation to common/formValidate
 */
define(['jquery'], function(){

	$(document).on('change','#category_form input:checkbox', function() {
		if ($(this).is(':checked')) {
			$(this).closest('label').addClass('selected');
		} else {
			$(this).closest('label').removeClass('selected');
		}
		//Validate
		var form = $(this).closest('form'); 
		if (form.find(':checked').length) {
			form.find('.blue-btn').removeClass('disabled_bg');
		} else {
			form.find('.blue-btn').addClass('disabled_bg');			
		}
	});

});