/* *********************************************************
 * Discover Interests
 * Logic handling autoscroll, following new interests
 *  such as repositioning items on autoscroll, on load, on any changes
 *  as well as comments
 *
 * ******************************************************* */

define(['plugins/hoverintent', 'jquery'],function() {

	$(function() {
		
		var on_li = false;
        var on_detail = false;
		var timer;

		//User clicks to add interest
		$('.discovery_content #interests .add_interest').live('click', function() {
			//console.log('adding…');
			var buttn = $(this);
			var int_url = $(this).attr('href');
			var uri = $(this).parent().find('.page_uri').text();
			var pg_id = $(this).parent().find('.page_id').text();
			var button = $(this);
            $.ajax({
                url: int_url,
                type: 'GET',
                success: function(data) {
                	buttn.closest('.item_container').append('<div class="interest_added"><div>Added</div></div>');
                	buttn.attr('title', buttn.attr('href'));
                	buttn.text('').attr('href','#');
					//button.css('background','#ccc').css('color','#777').text('Added');
                }
            });
            return false;
		});
		
		//Follow/Unfollow interest
		$('.badge_detail .follow_page').live('click', function(e) {
			var int_id = $(this).closest('.badge_detail').attr('rel');
			var item = $('.discovery_content #interests li.item[rel='+int_id+']');
			//console.log('follow clicked');
			item.find('.item_container').append('<div class="interest_added"><div>Added</div></div>');
			add_btn = item.find('.add_interest');
			add_btn.text('').attr('href', '#');
		});
		$('.badge_detail .unfollow_page').live('click', function(e) {
			var int_id = $(this).closest('.badge_detail').attr('rel');
			var item = $('.discovery_content #interests li.item[rel='+int_id+']');
			//console.log('unfollow clicked');
			item.find('.item_container .interest_added').hide().remove();
			add_btn = item.find('.add_interest');
			add_btn.text('+').attr('href', add_btn.attr('title'));
		});
		
		//For fixing the placement of badges
		function correct_badges() {
			var window_width = $('#interests').width();
			var row_num = Math.floor(window_width/173);
			//console.log(row_num);
			$('li.item').each(function(indx){
				//console.log(indx+'%'+row_num+'='+Math.floor(indx%row_num));
				if (Math.floor(indx%row_num) < Math.floor(row_num/2)) {
					$(this).find('.badge_direction').text('left');
					$(this).find('.offset .x').text(140);
					$(this).find('.offset .y').text(0);
				} else {
					$(this).find('.badge_direction').text('right');
					$(this).find('.offset .x').text(-320);
					$(this).find('.offset .y').text(0);
				}
			});
		}
		correct_badges();

		$(window).resize(function() {
			correct_badges();
		});		


		//For updating the 'my interests' count in the interest categories
		/*
	    $('.interest_item_placeholder a').live('click', function() {
            var int_url = $(this).attr('href');
            var name = $(this).parent().find('.page_name').text();
            var pg_id = $(this).parent().find('.page_id').text();
            var uri = $(this).parent().find('.page_uri').text();
            var int_cat_id = $(this).parent().find('.interest_category_id').text();
            var int_cat = $('#interests_categories').find('#interest_category_'+int_cat_id);
            var int_count = int_cat.find('.interest_count').text();
            var item = $(this).closest('.item');
            var int_cat_menu = $('#int_cat_'+int_cat_id);
            $.ajax({
                url: int_url,
                type: 'GET',
                success: function(data) {
                    int_cat.find('.interest_count').text(parseFloat(int_count)+1);
                    int_cat_menu.find('ul').append('<li><a href="/interests/'+uri+'/'+pg_id+'>'+name+'</a></li>');
                    item.remove();
                    //alert(name+' added');
                }
            });
            return false;
        });
        */

		//alert('hey');
    // Deals with clicking on interest categories
    /*
        //$('.interest_category').live('click', function() {
        $('.interest_category').live('click', function() {
            window.location.replace($(this).find('a').attr('href'));
            return false;
        });
        $('.interest_count').hoverIntent({over: display_my_interests_for_category, timeout: 200, interval: 200, out: hide_my_interests_for_category});
    */


        var container = window;
        var prev_id = 0;
        var last_id = 0;
        //var excluded = new Array();
        //$('li.item').each(function() {
        //	excluded.push($(this).attr('rel'));
        //});
        
        //var excluded = eval(<?=json_encode($page_ids)?>);

		
		/**
		Autoscroll only for loading new interests for the given category on the left.
		*/
		var dia_timer = null;
		
        $(container).scroll(function(e) {
        	$.run_dia_autoscroll(false);
        });
        
        $.belowFold = function(element,container) {
            if (container === undefined || container === window) {
                var pageFold = $(window).height() + $(window).scrollTop();
            } else {
                var pageFold = $(container).offset().top + $(container).height();
            }
            return pageFold <= $(element).offset().top;
        };

		$.run_dia_autoscroll = function(bypass) {
            var element = $('.interests_bottom');
            var element_id = element.attr('id');
            var type = 'discover_interests';
            
            clearTimeout(dia_timer);
            
            var elem_clone = element.clone();
            last_id= element.find('.last_id').text();
            
            if (!bypass) {
                bypass = prev_id !== last_id;
                //console.log('prev:'+prev_id+', next:'+last_id);
            }
            
            if(last_id){
                //console.log(prev_id+' '+last_id);
                if (!$.belowFold(element,container) && bypass) {
                	//console.log('---');
                    dia_timer = setTimeout(function() {
                    	var last_id = element.find('.last_id').text();
                        element.html('Loading...');
                        
                        var page_ids = $.parseJSON($('#interests').find('.page_ids').last().text());
                        
                        var interests_data = {
                            interest_id: php.segment2,
                            start_at:  last_id,
                            type: type,
                            page_ids: eval(page_ids),
                            limit: 16,
                            ci_csrf_token: $("input[name=ci_csrf_token]").val()
                        };
                        var link_disable = php.link_disable;
						
                        $.ajax({
                            url: '/more_interests/'+link_disable,
                            type: 'POST',
                            data: interests_data,
                            success: function(data) {
                                var data = $(data);
                                
                                //data.each(function() {
                                //	if ($(this).hasClass('item')) {
                                //		excluded.push($(this).attr('rel'));
                                //	}
                                //});
                                //console.log(excluded);

                                element.parent().find('#interests').append(data);

                                var con = element.closest('#discovery_main');
                                element.remove();
                                //last_id = $('#interests').find('li').last().attr('rel');
                                prev_id = last_id;
                                
                                last_id = $('#interests').find('li').last().attr('rel');
                                //console.log('getting last_id= '+last_id);
                                elem_clone.find('.last_id').text(last_id);
                                con.append(elem_clone);
                                //$('html').trigger('execute_badge');
                                var hoverintent_config = {
                                    over: show_badge,
                                    timeout:200,
                                    interval: 300,
                                    out: hide_badge
                                };
                                //console.log($(data));
                                $('.show_badge').hoverIntent(hoverintent_config);
                                correct_badges();
								wait = false;
                            }
                        });
                    }, 200);
                    //console.log(excluded);
                }
            }else{
                //$(this).html('Get more news');
            }
        }
	});
    


    $(window).load(function() {
        //alert($('#interests_categories').height()+' > '+$(this).height());
        /*
        if ($('#interests_categories').height()+20 > $(this).height()-75) {
            $('#interests_categories .interest_category').css('padding-top','0px !important')
                                                         .css('padding-bottom','0px !important')
                                                         .css('margin-bottom','2px !important');
        }
        */
    });

// Deals with displaying the list of your interests for the category (no longer used)
function display_my_interests_for_category() {
    var int_id = $(this).parent().find('.interest_id').text();
    var top = $(this).parent().offset().top;
    var left = $(this).parent().offset().left + $(this).parent().width();
    $('#int_cat_'+int_id).css('top', top+'px').css('left', left+20+'px');
    $('#int_cat_'+int_id).show('blind', { direction: "vertical" });
    //$('#int_cat_'+int_id).animate({ width: 'auto' }).animate({height: 'auto'});
    return false;
}
function hide_my_interests_for_category() {
    //setTimeout(function() {
        //var int_id = $(this).parent().find('.interest_id').text();
        //$('#int_cat_'+int_id).hide();
    if ($(this).data('onmenu') === '0') {
        $('.category_menu').hide();
    }
    //},500);
    return false;
}
$('.category_menu').hover(function() {
    $(this).data('onmenu','1');
}, function() {
    $(this).data('onmenu','0');
    $(this).hide();
});




});