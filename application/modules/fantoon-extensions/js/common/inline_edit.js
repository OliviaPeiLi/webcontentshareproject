/**
 *  Inline edit plugin
 *    has to be added to a html element with a rel attribute pointing to
 *    form editable element. The form element will be shown on the html 
 *    element`s place when clicked.
 * @to-do swtich to live event
 * @to-do write docs
 * @deprecated 1/4/2013 - currently not used anywhere
 */
define(['jquery'], function () {
	
	$.fn.inline_edit = function() {
		this.on('click', function() {
			var $element = $(this).hide();
			$("[name='"+$(this).attr('rel')+"']").show().focus()
				.blur(function() {
					$(this).hide();
					$element.show();
				})
				.change(function() {
					$element.html($(this).val());
				});
		});	
	};
});