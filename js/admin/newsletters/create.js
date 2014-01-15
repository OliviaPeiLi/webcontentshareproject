/**
 * Specific JS for the admin newsletters create form
 */
var preview_link = '/admin/newsletters/preview';
var preview_btn = '[href="#preview"]';
var preview_form = '.js-template-data';
var preview = '.js-preview';
var iframe;

function attachCopyEvents()	{
	
	$('#insert_template_data a.delete_item').live("click",function(){
		// var prev = $(this).parent().prev();
		// if (prev.is("br"))	prev.remove();
		$(this).parent().remove();
		return false;
	});

	$('#insert_template_data a.create_column,a.create_row').click(function(){

		// is it a popular_drop input, then we replace column with rows - php bug
		var is_popular = $('input[name^=popular_drops]',$(this).parent().parent()).length > 0 ? true : false;

		if ($(this).hasClass("create_row"))	{

			var wrap = $('span.input_wrap:last',$(this).parent().parent()).clone();
			var elem = $('input',wrap).eq(0);
				placeholder = elem.attr("placeholder").replace(" Row","").replace(" Column","");
				elem.attr("placeholder", placeholder + " Row");

			if ($('a',wrap).length == 0)	{
				elem.after("<a href='#' class='delete_item'></a>");
			}

			var num = parseInt(/\[(\d+)\]/.exec(elem.attr("name"))[1]);
				elem.attr("name",elem.attr("name").replace("[" + num + "]","[" + (num+1) + "]" ));
				$('span.input_wrap:last', $(this).parent().parent()).after(wrap).after("<br />");
				//console.warn($('span.input_wrap:last', $(this).parent().parent()));

			return false;

		}	else	{

			var wrap = $('span.input_wrap:last',$(this).parent()).clone();
			var elem = $('input',wrap).eq(0);

				placeholder = elem.attr("placeholder").replace(" Row","").replace(" Column","");
				elem.attr("placeholder",placeholder + " Column");

			if ($('a',wrap).length == 0)	{
				elem.after("<a href='#' class='delete_item'></a>");
			}

			$('span.input_wrap:last', $(this).parent()).after(wrap);

		return false;
		}

	});

}

$('.js-template select').live('change', function() {
	
	if ($('#save_success').length > 0 )	{
		$('#save_success').remove();
	}
	
	var $form = $(this).closest('form');
	var val = $(this).val();
	$.get(preview_link, {template: val}, function(response) {
		$(preview_form).html(response);
		$(preview_btn).trigger('click');
		attachCopyEvents();
	});
});

$(preview_btn).live('click', function() {
	var $form = $(this).closest('form');
	
	var $error = [];
	
	$('input',$form).each(function(){
		if ($(this).attr("name").indexOf(":") !== -1)	{
			// input contains models ids
			if ($(this).val() == "")	{
				$error.push( $(this).attr("placeholder") + " is empty." );
			}
		}
	})
	
	if ($error.length > 0)	{
		alert($error.join("\n"));
	return;
	}
	
	$.post(preview_link, $form.serialize(), function(data) {

		if (!iframe) {
			iframe = document.createElement('iframe');
			iframe.src = 'about:blank';
			iframe.width = '100%';
			iframe.height = '600px';
			$(preview).append(iframe);
			iframe = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;
			iframe = $(iframe).contents();
		}
		iframe.html(data);
		// $('body',iframe).html(data);
		console.info(iframe.height());
		$(preview+' iframe').height(iframe.height());
	});
	
});