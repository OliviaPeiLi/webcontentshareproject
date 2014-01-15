/**
 * Code for bookmarklet embed button
 * @deprecated
 */
define(['jquery'], function() {

	$(document).on('change', '#embed-form .embed-button-style input:checkbox', function() {
		if (this.checked) {
			$(this).closest('.embed-button-style').find('ul li span').show();
		} else {
			$(this).closest('.embed-button-style').find('ul li span').hide();
		}
		generate_code();
	});
	
	$(document).on('click', '#embed-form .embed-button-style ul li a', function() {
		$(this).closest('ul').find('li').removeClass('selected');
		$(this).closest('li').addClass('selected');
		generate_code();
		return false;
	});
	
	$(document).on('keyup', '#embed-form input[type=text]', function() {
		generate_code();
	});

	$('#embed-form input[type=text]')
	.hover(function() {
		$(this).next().trigger('show_title');
	}, function() {
		$(this).next().trigger('hide_title');
	});
	
	function generate_code() {
		var new_code = '<div id="fandrop_embed_btn"';
			if ($('#embed-form .embed-button-style input:checkbox').is(':checked')) {
				new_code += ' data-count="true" ';
			}
			new_code += ' data-style="'+$('#embed-form .embed-button-style li.selected').attr('rel')+'" ';
			if ($('#embed-form input[name=link]').val()) {
				new_code += ' data-link="'+$('#embed-form input[name=link]').val()+'" ';
			}
			if ($('#embed-form input[name=title]').val()) {
				new_code += ' data-title="'+$('#embed-form input[name=title]').val()+'" ';
			}
			if ($('#embed-form input[name=description]').val()) {
				new_code += ' data-description="'+$('#embed-form input[name=description]').val()+'" ';
			}
			new_code += ' data-content="'+$('#embed-form input[name=selector]').val()+'"';
		new_code += '></div>';
		$('#embed-form .embed-code textarea').val($('#embed-form .embed-code textarea').val().replace(/<div.*?div>/gi, new_code));
	}

	if ($('#embed-form .embed-code textarea').length) {
		generate_code();
	}

});