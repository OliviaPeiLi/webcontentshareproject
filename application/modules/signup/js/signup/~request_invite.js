/* *********************************************************
 *   E-mail validation when invite is requested.
 *   Created October 17, 2012, SylwiaFP.
 *
 * ******************************************************* */

define(['jquery', 'common/popup_info'], function(){

    var start_validate_email = 0;    // Because of FR-203 changed from 5 to 0. With 0 validation happens after every input.

    //Check email string for validation
    function emailValidate (value) {
        return  /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(value);
    }

    function email_check(elt) {
        //console.log(elt, 'text = ', elt.val(), 'length = ', elt.val().length);
        
        if (elt === null || elt === undefined) {
            var elt = $(this);
        }
        
        /*
        elt.closest('.invite_email_field').find('.messageEmail p').not('.keep').hide();
        elt.closest('.invite_email_field').find('.messageEmail p.keep').show();
        */
        
        if (elt.val() == '') {
            if (elt.closest('.invite_email_field').find('.messageEmail p.keep').length <= 0) {
                elt.closest('.invite_email_field').find('.messageEmail p.blank').show();
            }
        }else if (emailValidate(elt.val()) ){            
            console.info('req_invite_email_valid');
            //$('#request_invite_intro').html('<div class="req_invite_email_valid">Valid Email</div>');
            $('#request_invite_intro').html('Valid Email<div class="req_invite_email_valid"></div>').css('color', 'green');
            
            /*
            // only validate when >= start_validate_email characters	
            if (elt.val().length >= start_validate_email) {	
                elt.closest('.invite_email_field').find('.messageEmail p.ok').not('.invitesent').show();
            } else {
                elt.closest('.invite_email_field').find('.blank').show();
            }
            */
        }else{
            
            console.info('req_invite_email_invalid');
            //$('#request_invite_intro').html('<div class="req_invite_email_invalid">Invalid Email</div>');
            $('#request_invite_intro').html('Invalid Email<div class="req_invite_email_invalid"></div>').css('color', 'red');;
            
            /*
            // only validate when >= start_validate_email characters	
            if (elt.val().length > start_validate_email) {	
                elt.closest('.invite_email_field').find('.messageEmail p.invalid').show();
            } else {
                elt.closest('.invite_email_field').find('.blank').show();
            }
            */
        }
    }


    //$(document).ready(function(){
        $('#request_invite_email').each(function() {
            email_check($(this));
        });

        $('#request_invite_email').focus(function(){
            console.log('request_invite_email.focus');
            email_check($(this));
        }).keyup(function() {
            email_check($(this));
        }).blur(function(){
            console.log('request_invite_email.blur: '+$(this).val());
            email_check($(this));
        });
    //});



});
