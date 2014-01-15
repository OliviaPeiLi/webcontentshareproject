/*
 * Logic for custom placeholder text (text that appears on top of any text field and disappears on focus).
 * @link http://tools.fantoon.com/dokuwiki/doku.php?id=ui_components&#formplaceholder
 * @see signup/step1_form.js
 */
define(["jquery"],function(){
	
	/**
	 * Hide/show placeholder element on focus/blur
	 */
	$(document).on('keyup', '.input_placeholder_enh', function() {
		if ($(this).val() == '') {
			$(this).parent().find('.tmp_input_holder').fadeIn('fast');
		} else {
			$(this).parent().find('.tmp_input_holder').fadeOut('fast', function() {$(this).hide(); });
		}
	}).on('blur','.input_placeholder_enh', function() {
		if ($(this).val() == '') {
			$(this).parent().find('.tmp_input_holder').fadeIn('fast');
		}
	});
	
	/**
	 * Focus the input field on placeholder click
	 */
	$(document).on('click','.tmp_input_holder', function() {
		//$(this).fadeOut('fast', function() {
			$(this).parent().find('.input_placeholder_enh').focus();
		//});
		return false;
	});
	
	/**
	 * Create the placeholder element
	 */
	$(function() {

		//  $('.input_placeholder_enh').on("blur change", "", function() {
		//  	alert('change');
		//  	if ($(this).val() != $(this).attr('placeholder'))	{
		//  		$(this).parent().find('.tmp_input_holder').hide();	
		//  	}
		// });

		$('.input_placeholder_enh').each(function() {

			console.warn('placeholder',$(this).attr("placeholder"));
			//Create the placeholder element
			if ( ! $(this).parent().find('.tmp_input_holder').length) {
				$(this).parent().append('<span class="tmp_input_holder">' + ($(this).attr('placeholder') || "" ) +'</span>');
				// adjust .tmp_input_holder item similar with .input_placeholder_enh
				// element. The 'padding' is set by CSS
				var $placeholder = $(this).parent().find('.tmp_input_holder');
				$placeholder.offset( $(this).offset() );
			}
			$(this).removeAttr('placeholder');
			
			//Show/hide the placeholder
			if (!this.value || this.value == $(this).attr('placeholder')) {
				$(this).parent().find('.tmp_input_holder').show();
			} else {
				$(this).parent().find('.tmp_input_holder').hide();				
			}
		
		});

	});

});
