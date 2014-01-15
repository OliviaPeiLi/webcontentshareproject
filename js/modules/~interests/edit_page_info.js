/* *********************************************************
 * Edit Page Info JS
 *  JS Logic for handling dynamic changes to page info, 
 *   such as handling of category selection dropdown menu
 *  this page can be reached by going to interest page and clicking on
 *   Edit at top, or to info tab and clicking on edit link there.
 *
 * ******************************************************* */

define(['plugins/hoverintent', 'jquery'],function() {

	$('#edit_interest_category').live('click', function() {
	    $('#interest_category_dialog').dialog({
		modal: true,
		draggable: false,
		resizable: false,
		width: 300,
		autoOpen: true
		});
	    return false;
	    });
	//Page creation wizard (code to toggle animation inside 6 boxes) [NOT USED anymore]
	$('div.categoryImg2').hoverIntent({over: activateBox, timeout: 200, interval: 200, out: function(){}});
	    function activateBox() {
	        //$('div.active').removeClass('active').animate({width: '150'}, {duration: 1000, specialEasing: {width: 'easeInOutQuint'}});
	        //$(this).parent().addClass('active').animate({width: '270'}, {duration: 1000, specialEasing: {width: 'easeInOutQuint'}});
	        var catContainer = $(this);
	        if  (catContainer.css('display') !== 'none') {
		    if (cat !== null) {
		        cat.stop(true,true).toggle('slide', { direction: "right" }, 1000);
		    }
		    catContainer.stop(true,true).toggle('slide', { direction: "right"}, 1000);
		    cat = catContainer;
		}
	}
	
	/**
	Handles dropdown dialog for selecting interest category (saves dynamically via ajax on selection)
	*/
	$('#interest_pick_topic select').live('change', function() {
		var url = '/interest_category/'+php.segment2
	    var data = {
	        interest_category_id: $(this).val(),
	        ci_csrf_token: $("input[name=ci_csrf_token]").val()
	    };
	    $.ajax({
	        url: url,
	        type: 'POST',
	        data: data,
	        success: function(msg) {
				//saved
	        }
	    });
	});
	
	$('#exit_page_edit').live('click', function() {
		document.location.href = '/page_info/'+php.page_id;
		return false;
	});
});
