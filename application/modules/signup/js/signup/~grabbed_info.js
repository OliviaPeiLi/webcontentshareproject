define(['jquery'], function(){
	function check_for_invalid() {
		if ($('.input_status.invalid').length > 0 ) {
			console.info('invalid keypress so that submit button is disabled');
			$('#preview_info_finish').attr('disabled', 'true');
			$('#preview_info_finish').addClass('disabled');
		} else {
			$('#preview_info_finish').removeAttr('disabled');
			$('#preview_info_finish').removeClass('disabled');
		}
	}
	
	$(function() {
	
        if (typeof(mixpanel) !== 'undefined') {
        	mixpanel.track("New Account Created");
        }
	
		$('#add_collection').on('click', function() {
			var cloned = $('.sample_collection').clone();
			cloned.removeClass('sample_collection');
			$('#collections_items').append(cloned);
			cloned.show('fade');
			return false;
		});
		
		$('.collection_input').on('keyup',function(e) {
			console.log('keypress');
			$(this).parent().find('.input_status').removeClass('valid').removeClass('invalid');
	        if ($(this).val().match(/^[a-zA-Z0-9 ]+$/)) {
	        	$(this).parent().find('.input_status').addClass('valid');
	        } else {
	        	$(this).parent().find('.input_status').addClass('invalid');
	        }
	        check_for_invalid();
		});
		
		$('.delete_collection_input').on('click', function() {
			$(this).closest('li').hide('fade').remove();
			check_for_invalid();
			return false;
		});
		
	});
	
	
});
