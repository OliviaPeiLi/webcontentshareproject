/* *********************************************************
 * Create page
 *  JS logic to create new page
 *		legacy JS, not used anymore.
 *
 * ******************************************************* */

define(['jquery'], function() {

/**
Check that page name is not empty and that role has been chosen
*/
function check_inputs() {
    //console.log('checking inputs');
    if ($('#new_interest_form input[name="page_name"]').val() !== $('#new_interest_form input[name="page_name"]').attr('rel')
        && $('#new_interest_form select[name="role"]').val() !== '0') {
        //console.log('TRUE');
        return true;
    } else {
    	//console.log('FALSE');
        //return false;
    }
}
//Check that categories and roles are okay (not empty)
$('#new_interest_form input[name="page_name"], #new_interest_form select[name="category"], #new_interest_form select[name="role"]').change(function() {
    
    $('#create_interest_errors').html('').hide();
    if (check_inputs()) {
        $('#create_page_submit :submit').removeClass('inactive_bg').addClass('blue_bg');
    }
});
//Hide errors on input of interet name
$('#new_interest_form input[name="page_name"]').keyup(function() {
	$('#create_interest_errors').html('').hide();
});

//Submission of the first form (left side of create page, before image upload)
$('#create_page_submit').live('submit',function() {
    	//console.log($('#new_interest_form input[name="page_name"]').val());
        if (!check_inputs()) {
            return false;
        }
        //console.log('proceding');
    	var post_form = $(this).closest('#pagewizard_categories');

        if (check_inputs()) {
            //console.log('page_created');
            $.post($(this).attr('action'), $(this).serialize(), function(page_info){
            	console.log('PAGE CREATED');
                if ( page_info.status === 'OK') {                    
                    //load edit thumbnail view
                    $('#page_upload_pic_div').load('interest/'+page_info.page_id+'/edit_picture', function() {
	                    if (page_info && page_info['page_id'] > 0) {
	                    	console.log('Onto smth');
	                        $('#imgupload_options form #page').val(page_info['page_id']);
	                        console.log(php.baseUrl+"interests/"+page_info['uri_name']+"/"+page_info['page_id']);
	                        console.log($('#imgupload_options'));
	                        $('#upload_profilepic_dlg').find('.redirect').text(php.baseUrl+"interests/"+page_info['uri_name']+"/"+page_info['page_id']);
	                        $('#imgupload_options orig_img_upload_form_url, #imgupload_options orig_img_upload_form').attr('action','http://'+window.location.host+'/upload_photo_profile/'+page_info['page_id']);
	                    }
	                    $('#pagewizard_categories #createpage_left_overlay').show().animate({opacity: 0.65},800,function() {});
	                    $('#pagewizard_imgupload #createpage_right_overlay').animate({opacity: 0},800,function() {}).hide();
	                    $('#create_interest_errors').hide().html('');

                    });

                    
                } else {
                	$('#create_page_submit').removeClass('blue_bg').addClass('inactive_bg');
                	$('#create_interest_errors').html(page_info.error).show('fade');
                }
            },'json');

        }
        return false;
    });
    
    $.fn.initTokenInput();
    
});