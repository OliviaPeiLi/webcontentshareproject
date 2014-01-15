/* *********************************************************
 * Create page (Not used)
 *  JS logic to create new page
 *		legacy JS, not used anymore.
 *
 * ******************************************************* */

define(['jquery'], function(){

$(function() {
	//CREATE PAGE
	//Page creation wizard submit function (site with 6 category boxes) - phased out
	$('input.pagewiz_submit_1_1').click(function() {
		var box_name = $(this).parent().parent().attr('name');
		var box_id = $(this).parent().find('input[name=box_id]').val();
		var cat = $('#interest_category_'+box_id);
		var cat1 = $('#interest_category_'+box_id+' option:selected');
		var box = 'div[name='+box_name+']';
		var page_name = $(box+' input[name=page_name]');
		var category_title = $(this).parent().find('input[name=category_title]').val();
		var cat_data = {
			number: cat1.attr('value'),
			category: cat1.text(),
			category_title: category_title,
			page_name: page_name.val(),
			ci_csrf_token: $("input[name=ci_csrf_token]").val(),
			box_id: box_id,
			ajax: '1'
		};
		$.ajax({
			url: $(this).closest('form').attr('action'),
			type: 'POST',
			data: cat_data,
			success: function(msg) {
				//alert(box);
				$(box).html(msg);
			}
		});
		return false;
	});
});

});