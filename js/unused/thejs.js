
function show_and_hide(x,y) {
	$('#'+x).fadeIn(0);
	$('#'+y).fadeOut(0);
}

function accept_connection(e) {
	//console.log('accept_connection2');

	var status_text = $(e);
	//console.log(status_text)
	//alert('accepting…');
	//var user_id = $(this).attr('rel');
	var user_id = $(e).attr('rel');
    $.ajax({
        //url: "/accept/"+user_id+'/'+loop_id,
        //for connection without loops
        url: "/accept/"+user_id,
        type: 'GET',
        success: function(data) {
            //alert('ajax');
            //alert('accepted');
            var par = status_text.parent();
            status_text.hide().remove();
            par.text('Already Following');
        }
    });
    return false;
}


function go(url) {
	window.location.href = url;
}

var prev_click = null;
/*
$(function() {
	//Click events (for header notification and account options menus
	$(document).click(function(e){
		//console.log('click');
		var targ;
		if (e.target) targ = e.target;
		else if (e.srcElement) targ = e.srcElement;
		//alert(targ);
		
		if ($(targ).hasClass('menu_button')) {
			return;
		}
		
		if (!($(targ).is('#account_menu'))) {
			$('#options').hide();
			$('#account_menu').removeClass('hdrnav_clicked').removeClass('account_menu_clicked').addClass('account_menu');
			//$('#account_menu .dropdown_menu_arrow').css('border-top-color', 'white');
			$('#account_menu #account_link').removeClass('menu_active');
			$('#account_menu .dropdown_menu_arrow').removeClass('dropdown_menu_arrow_hovered');
		}
		if (!($(targ).is('#hdr_notifications'))) {
			$('#hdr_notifications').removeClass('hdrnav_clicked').removeClass('hdr_notifications_clicked').addClass('hdr_notifications');
			$('#notifications').hide();
		}
		if (!($(targ).is('#hdr_allfriends'))) {
			$('#hdr_allfriends').removeClass('hdrnav_clicked').removeClass('hdr_allfriends_clicked').addClass('hdr_allfriends');
			$('#requests').hide();
		}
		if(!($(targ).is('.menu_activator'))) {
			//alert('yo') ;
			//console.log('wwwwww');
			if (!$(targ).closest('.site_menu').is('.keep_menu_open')) {
				$('.site_menu').hide();
			}
		}
	});
	$('#account_menu').click(function (e) {
		e.stopPropagation();
	});
	$('#hdr_notifications').click(function(e) {
		e.stopPropagation();
	});
	$('#hdr_allfriends').click(function(e) {
		e.stopPropagation();
	});
    
	$('.menu_activator').click(function(e) {
		e.stopPropagation();
        //alert('menu_activator');
	});
	
	
	
	
});
*/