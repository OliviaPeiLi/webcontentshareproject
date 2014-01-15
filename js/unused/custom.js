	// avoid dupplicate comment (double click on a comment button)






$(function() {    
    
require(["jquery","common/misc"], function ($) {
});   
    
    
    
    
	


	/* For animating the interests pane in user profile (not used anymore) */
/*
	$('#profile_interest_categories > li').width($('#profile_interest_categories').width()/3-20);
	//$('#profile_interest_categories .category_placeholder').width($('#profile_interest_categories').width());
	$('#profile_see_more_interests').live('click', function() {
		var winh = $(window).height();
		var box_width = $('#profile_interest_categories').width();
		var setup = $('#profile_interests_list_placeholder #is_open');
		if(setup.attr('isopen') === '0') {
			setup.attr('prev_height', $('#profile_interests_list_placeholder').height());
			$('#profile_interest_categories > li').width(box_width).css('float','');
			$('#profile_interest_categories .interests_list > li').css('display','inline-block');
			$('#profile_interests_list_placeholder').animate({height: ($('#profile_interests_list_placeholder > ul').height()+35)+'px'});
			$(this).text('shrink interests');
			setup.attr('isopen', '1');
		} else {
			var newh = setup.attr('prev_height');
			$('#profile_interests_list_placeholder').animate({height: newh+'px' });
			$('#profile_interest_categories > li').width(box_width/3-20).css('float','left');
            $('#profile_interest_categories > li').each(function() {
                var cnt = 0;
                $(this).find('.interests_list > li').each(function() {
                    if (cnt>=5) {
                        $(this).css('display','none');
                    }
                    cnt++;
                });
            });
			$(this).text('see more...');
			setup.attr('isopen', '0');
		}
		return false;
	});
*/





	/* Personal Info fields */
	//$('.personal_info_form .field').css('width', ($('.personal_info_form').width()-200)+'px');


	/*
	//Account options menu in header
	$('#options').blur(function() {
		//$(this).blur();
		$('#options').hide();
		$('#hdr_acct').css('background-color','').css('outline','none');
		$('#header_account').css('color','');
	});

	//Notifications menu in header
	$('#notifications').click(function(e) {
		$('#notifications').blur(function() {
			$(this).hide();
		});
	});
	*/


	/*
	//Dynamic preview upon hover in picture albums
	$('#album a').hover(function(e) {
		var href = $(this).attr('href');
		$('<img id="largerImg" src="'+href+'"/>')
			.css('top', e.pageY + offsetY)
			.css('left', e.pageX + offsetX)
			.appendTo('body');
	}, function(e) {
			$('#largerImg').remove();
		});

	$('#album a').mousemove(function(e) {
		$('#largerImg').css('top', e.pageY + offsetY)
								 .css('left', e.pageX + offsetX);

	});
	*/

/*
	$('.page_user_avatar').hover(function(e) {
		var info_div = $('#page_userinfo_hover');
		var name = $(this).find('div.page_user_avatar_name').text();
		var admin_link = $(this).find('#page_admin_link');
		if (admin_link) {
			info_div.find('#page_hover_admin_link').text(admin_link.text());
		}
		info_div.find('#avatar_id').text(name);
		var pos = $(this).position();
		var height = info_div.height();
		info_div.css('top', pos.top-height+8)
					.css('left', pos.left+4)
					.show('fade',300);
		setTimeout(1000);
		//alert('pageX='+e.pageX+', PageY='+e.pageY);
	}, function(e) {
			$('#page_userinfo_hover').hide();
	});

	$('#page_userinfo_hover').mouseleave(function(e) {
		$('#page_userinfo_hover').hide();
	});
	*/



	// PROFILE/PAGE/HOME POSTBOX TABS
	//Code to deal with profile page post tabs (Post/Photo)
	/*
	$('.profile_post_tab, .page_post_tab').live('click',function() {
        //alert('hhhh');
        var postboxwrapper = $(this).closest('.postboxwrapper');
		if ($(this).hasClass('post_tab_post') || $(this).hasClass('page_post_tab_post')) {
			postboxwrapper.find('#profile_upload_photo, #page_upload_photo').hide();
            postboxwrapper.find('.linkbuilder').hide('fade');
            postboxwrapper.find('#profile_add_link, #page_add_link').hide();
			postboxwrapper.find('#profile_add_post, #page_add_post').show();
			$(this).parent().find('.active_post_tab').removeClass('active_post_tab');
			$(this).addClass('active_post_tab');
			//init_postbox();
			var txt_val = 'What\'s on your mind?';
			postboxwrapper.find('.postbox').attr('name','post_msg').val(txt_val).attr('title',txt_val).addClass('inactive');
			if ($(this).hasClass('page_post_tab_post')) {
				postboxwrapper.addClass('page_add_post').removeClass('page_add_link').removeClass('page_add_photo');
			} else {
				postboxwrapper.addClass('profile_add_post').removeClass('profile_add_link').removeClass('profile_add_photo');
			}
			return false;
		}
		else if ($(this).hasClass('post_tab_photo') || $(this).hasClass('page_post_tab_photo')) {
			postboxwrapper.find('#profile_add_post, #page_add_post').hide();
            postboxwrapper.find('#profile_add_link, #page_add_link').hide();
            postboxwrapper.find('.linkbuilder').hide('fade');
			postboxwrapper.find('#profile_upload_photo, #page_upload_photo').show();
			$(this).parent().find('.active_post_tab').removeClass('active_post_tab');
			$(this).addClass('active_post_tab');
			//init_postbox();
			var txt_val = 'Please select a picture below and enter caption here';
			postboxwrapper.find('.postbox').attr('name','caption').val(txt_val).attr('title',txt_val).addClass('inactive');
			if ($(this).hasClass('page_post_tab_post')) {
				postboxwrapper.addClass('page_add_photo').removeClass('page_add_post').removeClass('page_add_link');
			} else {
				postboxwrapper.addClass('profile_add_photo').removeClass('profile_add_post').removeClass('profile_add_link');
			}
			return false;
		}
        else if ($(this).hasClass('post_tab_link') || $(this).hasClass('page_post_tab_link')) {
            postboxwrapper.find('#profile_add_post, #page_add_post').hide();
            postboxwrapper.find('#profile_upload_photo, #page_upload_photo').hide();
			postboxwrapper.find('#profile_add_link, #page_add_link').show();
			$(this).parent().find('.active_post_tab').removeClass('active_post_tab');
			$(this).addClass('active_post_tab');
			//init_postbox();
            
            //if (postboxwrapper.find('.linkbuilder:hidden').length > 0) {
            //    postboxwrapper.find('.linkbuilder').show('fade');
            //}
            
			var txt_val = 'Paste the link or enter it in here…';
			postboxwrapper.find('.postbox').attr('name','url').val(txt_val).attr('title',txt_val).addClass('inactive');
			if ($(this).hasClass('page_post_tab_post')) {
				postboxwrapper.addClass('page_add_link').removeClass('page_add_post').removeClass('page_add_photo');
			} else {
				postboxwrapper.addClass('profile_add_link').removeClass('profile_add_post').removeClass('profile_add_photo');
			}
			return false;
        }
        else if ($(this).hasClass('post_tab_location')) {
            //do nothing
        }
		else {
			postboxwrapper.find('#profile_add_post, #page_add_post').show('fade');
			postboxwrapper.find('#profile_upload_photo, #page_upload_photo').hide('fade');
			postboxwrapper.find('#profile_add_link, #page_add_link').hide('fade');
			return false;
		}
	});
	*/


	//Page creation wizard (code to toggle animation inside 6 boxes)
	/*
	$('div.categoryImg').hoverIntent({over: activateBox, timeout: 200, interval: 200, out: function(){}});
	function activateBox() {
		//var activeIndx = $(this).parent().parent().parent().index();
		$('div.active').removeClass('active').animate({width: '150'}, {duration: 1000, specialEasing: {width: 'easeInOutQuint'}});
		$(this).parent().parent().addClass('active').animate({width: '270'}, {duration: 1000, specialEasing: {width: 'easeInOutQuint'}});
		$(this).closest('.row').find('.cell').not('.first').stop(true,true).animate({'marginLeft': '2px'}, 300);
		$(this).closest('.row').find('.cell').not('.last').stop(true,true).animate({'marginRight': '2px'}, 300);
		$(this).closest('.row').siblings().find('.cell').not('.first').stop(true,true).animate({'marginLeft': '20px'}, 300);
		$(this).closest('.row').siblings().find('.cell').not('.last').stop(true,true).animate({'marginRight': '20px'}, 300);
		var catContainer = $(this);
		if  (catContainer.css('display') !== 'none') {
			if (cat !== null) {
				cat.stop(true,true).toggle('slide', { direction: "right" }, 1000);
				//cat.stop(true,true).fadeIn(400);
			}
			catContainer.stop(true,true).toggle('slide', { direction: "right"}, 1000);
			//catContainer.stop(true,true).fadeOut(400);
			cat = catContainer;
		}
	}
	
	$('div.categoryImg').live('click', function() {
		//alert($(this).parent().html());
		$('.open_category').each(function() {
			if ($(this).index() % 5 === 4) {
				$(this).animate({width: '190'}, {duration: 1000, specialEasing: {width: 'easeInOutQuint'}}).removeClass('open_category');
			} else {
				$(this).animate({width: '190'}, {duration: 1000, specialEasing: {width: 'easeInOutQuint'}}).removeClass('open_category');
			}
		});
		var indx = $(this).parent().parent().index();
		if (indx%5 === 4) {
			var tmp = $(this).parent().parent().prev().prev().prev().prev();
			tmp.hide('blind', {direction: "horizontal"}, 500, function() {$(this).appendTo($(this).parent()).fadeIn()});
		}
		$(this).parent().parent().animate({width: '380'}, {duration: 1000, specialEasing: {width: 'easeInOutQuint'}}).addClass('open_category');
		var catContainer = $(this);
		if  (catContainer.css('display') !== 'none') {
			if (cat !== null) {
				cat.delay(500).toggle('puff');
			}
			catContainer.delay(500).toggle('puff');
			cat = catContainer;
		}
	});
	*/
	/*
	$('div.wrap').click(function() {
		var catContainer = $(this).children('.categoryImg');
		if  (catContainer.css('display') !== 'none') {
			if (cat !== null) {
				cat.toggle('puff');
			}
			catContainer.toggle('puff');
			cat = catContainer;
		}
	});
	*/





/*
        $('#page_new_topic_submit').live('click', function() {
            var form = $(this).closest('form');
            var url = form.attr('action');
            var topics = $('.autocomplete_input #load_topics').tokenInput('get');
            //prepare_autocomplete('#new_topic','#load_topics','#topic_names');
            var data = {
                topics: JSON.stringify(topics),
                ci_csrf_token: $("input[name=ci_csrf_token]").val()
            };
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(msg) {
                    $('#new_topic').hide('blind');
                    msg = $(msg);
                    msg.find('li').hide();
                    $('#page_topics').append(msg);
                    msg.find('li').show('fade');
                    $('.autocomplete_input #load_topics').tokenInput('clear');
                }
            });
            return false;
        });
*/


	/*







	$('#page_upload_pic_div').append($('#upload_profilepic_dlg'));
	$('#page_upload_pic_div').append('<div id="not_dialog"></div>');
	$('#page_upload_pic_div #upload_profilepic_dlg').show();





    $('#notifications ul > li').live('mouseenter', function() {
        $(this).removeClass('unread_notification');
    });

    //used for correcting the size of photos in the list of photos in albums
    /*
    $('#photos .photo img').load(function() {
        console.log('.+.+.')
        var img_w = this.width;
        var img_h = this.height;
        if (img_w < img_h) {
            image.css('width','145px');
        } else {
            image.css('height','145px');
        }
    });
    */

    
    var timer;
    /*
    $("#request_status, .request_status").live({
        mouseenter:  function(e) {
        	//console.log('request status entered');
            //console.log('request button enter '+$('#loop_list').data('open'));
            e.stopPropagation();
            clearTimeout(timer);

			var profile_id = $(this).attr('rel');
            var loop_list = $('<ul id="loop_list" style="display:none;" />');
            loop_list.append('Please select a loop');
			var request_status = $(this);
			var view = '';
			if (request_status.hasClass('profile_view')) {
				var view = 'profile';
				var status = 1;
			} else if (request_status.hasClass('allrequests_view')) {
				var view = 'allrequests';
			} else if (request_status.hasClass('header_view')) {
				var view = 'header';
			} else {
			}
            
            $.ajax({
                url: '/loops_user_is_in?profile_id='+profile_id,
                type: 'GET',
                success: function(data) {
                    //alert('ajax');
                    var loops = $.parseJSON(data);
                    $.each(loops, function(k,v) {
                    	if (request_status.hasClass('accept_connection')) {
                    		var loop_entry = '<li onclick="accept_connect_request('+profile_id+', '+v.loop_id+','+status+');">';
                    	} else {
                    		var loop_entry = '<li onclick="request_connect('+profile_id+', '+v.loop_id+');">';
                    	}
                        var is_checked = (v.selected === '1') ? 'checked="checked"' : '';
                        loop_entry += '<input type="checkbox" name="loops[]" value="'+v.loop_name+'" '+is_checked+'> '+v.loop_name;
                        loop_entry += '</li>';
                        loop_list.append(loop_entry);
                    });
                }
            });
            
            $(this).parent().append(loop_list);
            $('#loop_list').css('top',$(this).position().top+'px').css('left',$(this).position().left+'px');
            if ($('#loop_list').data('open') !== '1') {
                $('#loop_list').slideDown('slow');
            }
        },
        mouseleave: function(e) {
            //console.log('request button exit '+$('#loop_list').data('open'));
            e.stopPropagation();
            timer = setTimeout(function() {
                if($('#loop_list').data('open') !== '1') {
                    $('#loop_list').fadeOut('fast');
                }
            }, 100);
        }
    });
    */    


    

});

