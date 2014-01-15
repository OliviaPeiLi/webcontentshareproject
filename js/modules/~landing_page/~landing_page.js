/* *********************************************************
 * Landing page and signup while not logged in
 *  JS to validate all input fields
 *	and to handle different steps of signup process.
 *   LEGACY CODE, NO LONGER USED
 *
 * ******************************************************* */

define(['jquery'], function() {
	
$(function() {
	//Old, no longer used
    /*$('.vibe_input').keypress(function(e) {
        if (e.keyCode === 13 || e.keyCode === 9 || e.keyCode === ','.charCodeAt(0)) {
            //$(this).val().trim(',');
            $(this).next('.vibe_input').focus();
            return false;
        }
    });*/
    //Old, no longer used
    /*$('#email_signup_next').live('click', function() {
        var vibes = [];
        var url = php.baseUrl+'save_vibes_in_session';
        $('.vibe_input').each(function() {
            if ($(this).val() === 'Enter Your Interest' || $(this).val().trim() === '') {
                $(this).remove();
            } else {
                vibes.push($(this).val());
            }
        });
        var data = {
            'vibe[]': vibes,
            ci_csrf_token: $("input[name=ci_csrf_token]").val(),
            interests_only: '1'
        };
        $.ajax({
            url: url,
            type: 'POST',
            data: data
        });
        $('#signup_options').show();
        $('#interests').hide('blind',1000);
        setTimeout(function() {
        $('#register_box').css('overflow','visible');
        },1000);
        return false;
    });*/

	//Old, no longer used
    /*$('#vibe_input1').live('focus',function() {
        if($('#vibe_input2:hidden').length > 0) {
            $('#vibe_input2').show('blind');
            $('#vibe_input3').show('blind');
            $('#vibe_input4').show('blind');
            $('#vibe_input5').show('blind');
        }
    });*/
    
    //Old, no longer used
	/*$('#signup_options .step_title').live('click', function () {
		var step = $(this).parent().attr('id');
		if (step === 'external') {
			//alert('step 1');
		}
		else if (step === 'signup') {
			//alert('step 2');
		}
		var step_body = $(this).next();
		if(step_body.css('display') === 'none') {
			$(this).removeClass('step_title_closed');
			step_body.show('blind');
		} else {
			$(this).addClass('step_title_closed');
			step_body.hide('blind');
		}
		//alert('step');
	});*/
/*
    $('#twitter_signup_submit').live('click', function() {
        ext_connect_success();
        return false;
    });
*/

});
//Old, no longer used
/*function ext_connect_success() {
    var url = php.baseUrl;
    var vibes = [];
    $('#vibes .vibe_input').each(function() {
        vibes.push($(this).val());
    })
    var data = {
        'vibe[]': vibes,
        ci_csrf_token: $("input[name=ci_csrf_token]").val(),
        interests_only: '1'
    };
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function() {
            window.location=php.baseUrl;
        }
    });
    
}
*/

})