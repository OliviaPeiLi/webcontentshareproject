
<?
if ($stage === 'more_interests') {
    $this->load->helper('page_helper');
	foreach ($interests as $k => $v) {
		$page_name = substr($v['page_name'],0,14); 
		if (strlen($v['page_name']) > 14) {
			$page_name .= '...'; 
		}
		if (trim($page_name) == '') {
			$page_name = '...';
		}
		?> 
        <li class="item dHover tc_show_badge">
            <? $dir = ($k%5 > 2) ? 'left' : 'right'; ?>
            <div class="badge_style" style="display:none">badge_heavy</div>
            <div class="badge_direction" style="display:none"><?=$dir?></div>
            <div class="offset" style="display:none">
                <? if ($dir === 'left') { ?>
                    <div class="x">-320</div>
                <? } else { ?>
                    <div class="x">100</div>
                <? } ?>
                <div class="y">0</div>
            </div>
            <div class="obj_id" style="display:none"><?=$v['page_id']?></div>
			<? if($link_disable == 1){ ?>
				<h5><?=$page_name?></h5>
			<? } else { ?>
            <h5><a href="<?=base_url()?>interests/<?=$v['uri_name']?>/<?=$v['page_id']?>"><?=$page_name?></a></h5>
            <? } ?>
            <div class="interest_image inlinediv">
            	<img src="<?=s3_url().$v['thumbnail']?>" width="111" alt="pic1"/>
            </div>
            <span><a class="add_interest vote" rel="<?=$v['page_id']?>" href="/add_page/<?=$v['page_id']?>/interest_discovery/22">Add Vote</a></span>
        </li> 
        <? $page_name = $v['page_name']; ?>                  	
	<? } ?>
<? } else { ?>

	<!-- CSRF Token placeholder for AJAX calls -->
	<div style="display: none">
		<? 
			echo form_open('/');
			echo Form_Helper::form_input('dummy', 'dummy');
			echo form_close();
		?>
	</div>
	<div id="picks">
	<? 
	echo form_open('/tc_sort');
	echo form_close();
	?>
	</div>
	<div class="container_24" id="container">
        <div id="vertical_menu" class="grid_7 alpha">
            <div class="menu_top">
                <h2>Categories</h2>
            </div>
            <ul id="interests_categories">

                <li>
                    <a href="#">
                        <span>TechCrunch</span>
                        <span class="interest_id" style="display: none">22</span>
                    </a>
                </li>
            </ul>
            <div class="vertical_bot"></div>
        </div>
		<div class="grid_17 omega tc_discovery_main" id="discovery_main">
            <div class="discovery_top">
                <h2>
                    Make your startup picks!
                </h2>
                <div id="vote_count_placeholder"><b id="vote_count">5</b> votes left</div>
                <a id="tc_vote_to_sort" class="inactive" href="#">Next Step</a>
            </div>
            <div class="discovery_content tc_discovery_content">
                <p>
                    <label>Pick the top five startups that impressed you the most.</label>
                </p>
                
                <? //List of interests ?>
                <ul id="interests">
                	<? $this->load->helper('page_helper'); ?>
                	<? foreach ($interests as $k => $v) { ?>
						<? 
						$page_name = substr($v['page_name'],0,14); 
						if (strlen($v['page_name']) > 14) {
							$page_name .= '...'; 
						}
						if (trim($page_name) == '') {
							$page_name = '...';
						}
						if ($stage !== 'register') {
							$page_link = base_url().'interests/'.$v['uri_name'].'/'.$v['page_id'];
						} else {
							$page_link = '';
						}
						?> 
                        <li class="item dHover tc_show_badge">
				            <? $dir = ($k%5 > 2) ? 'left' : 'right'; ?>
				            <div class="badge_style" style="display:none">badge_heavy</div>
				            <div class="badge_direction" style="display:none"><?=$dir?></div>
				            <div class="offset" style="display:none">
				                <? if ($dir === 'left') { ?>
				                    <div class="x">-320</div>
				                <? } else { ?>
				                    <div class="x">100</div>
				                <? } ?>
				                <div class="y">0</div>
				            </div>
				            <div class="obj_id" style="display:none"><?=$v['page_id']?></div>
				            <? if($link_disable == 1){ ?>
				           	 	<h5><?=$page_name?></h5>
				            <? }else{ ?>
        						<h5><a href="<?=$page_link?>"><?=$page_name?></a></h5>
        					<? } ?>
                            <div class="interest_image inlinediv">
                            	<img src="<?=s3_url().$v['thumbnail']?>" width="111" alt="pic1"/>
                            </div>
                            <span><a class="add_interest vote" rel="<?=$v['page_id']?>" href="/add_page/<?=$v['page_id']?>/interest_discovery/22">Add Vote</a></span>
                        </li> 
                        <? $page_name = $v['page_name']; ?>                  	
                	<? } ?>
                </ul>
            </div>
            <div class="discovery_bot"></div>
			<div class="clear"></div>
			<div id="list_interests_bottom" class="interests_bottom"><div class="last_id" style="display: none"><?=$v['page_id']?></div></div>

		</div>
		<div class="clear"></div>
	</div>

	<script type="text/javascript">
	
		$(function() {
			
			var on_li = false;
	        var on_detail = false;
			var timer;
	
			//Hover over avatar should pop-up a badge
		    var hoverintent_config = {
		        over: tc_show_badge,
		        timeout:200,
		        interval: 300,
		        out: tc_hide_badge
		    };
		    $('.tc_show_badge').hoverIntent(hoverintent_config);
	
			$('#tc_vote_to_sort').live('click', function() {
				if ($(this).hasClass('inactive')) {
					alert('Please select at least one pick (up to five)');
				} else {
					$('#picks form').submit();
				}
				//return false;
			});
	
			$('.discovery_content #interests .add_interest.vote').live('click', function() {
				//console.log('adding…');
				var count = $('#picks input.pick').length;
				if (count >= 0) {
					$('#tc_vote_to_sort').removeClass('inactive').addClass('active');
				}
				if (count < 5) {
					var int_url = $(this).attr('href');
					var uri = $(this).parent().find('.page_uri').text();
					var pg_id = $(this).attr('rel');
					var button = $(this);
					var hiddenform = $('#picks form');
					button.removeClass('vote').addClass('unvote').text('Added');
					hiddenform.append('<input name="page_id[]" id="pick_'+pg_id+'" class="pick" type="hidden" value="'+pg_id+'">');
					$('#vote_count').text(5-count-1);

	            } else {
	            	alert('At most 5 picks allowed per vote. You have picked 5 items. You can either submit, or change your picks');
	            }
	            return false;
			});
			$('.discovery_content #interests .add_interest.unvote').live('click', function() {
				//console.log('removing…');
				var count = $('#picks input.pick').length;
				if (count > 0) {
					var int_url = $(this).attr('href');
					var uri = $(this).parent().find('.page_uri').text();
					var pg_id = $(this).attr('rel');
					var button = $(this);
					var input_to_delete = $('#picks form #pick_'+pg_id);
					button.removeClass('unvote').addClass('vote').text('Add Vote');
					input_to_delete.remove();
					$('#vote_count').text(5-count+1);
					if (count <= 1) {
						$('#tc_vote_to_sort').removeClass('active').addClass('inactive');
					}
					
	            } else {
	            	alert('At most 5 picks allowed per vote. You have picked 5 items. You can either submit, or change your picks');
	            }
	            return false;
			});
	
	    //Autoscroll (for news feeds)
	        var container = window;
	        var prev_id = 0
	        var last_id = 0
	
	        $(container).scroll(function(e) {
	            var element = $('.interests_bottom');
	            var element_id = element.attr('id');
	            var type = 'discover_interests';
	            var elem_clone = element.clone();
	            last_id= element.find('.last_id').text();
	            if(last_id){
	                //console.log(prev_id+' '+last_id);
	                if (!$.belowFold(element,container) && prev_id !== last_id) {
	
	                    setTimeout(function() {
	                        element.html('Loading...');
	                        var interests_data = {
	                            interest_id: 22,
	                            start_at:  last_id,
	                            type: type,
	                            limit: 24,
	                            ci_csrf_token: $("input[name=ci_csrf_token]").val()
	                        };
	                        $.ajax({
	                            url: '/more_interests/0',
	                            type: 'POST',
	                            data: interests_data,
	                            success: function(data) {
	                                var data = $(data);
	                                element.parent().find('#interests').append(data);
	                                prev_id = last_id;
	                                var con = element.closest('#interest_topic_search_results');
	                                element.remove();
	                                last_id = $('#interests').find('li').last().find('.page_id').text();
	                                elem_clone.find('.last_id').text(last_id);
	                                con.append(elem_clone);
	                                //$('html').trigger('execute_badge');
	                                var hoverintent_config = {
	                                    over: show_badge,
	                                    timeout:200,
	                                    interval: 300,
	                                    out: hide_badge
	                                };
	                                console.log($(data));
	                                $('.show_badge').hoverIntent(hoverintent_config);
	                            }
	                        });
	                    }, 100);
	                }
	            }else{
	                //$(this).html('Get more news');
	            }
	        });
	        $.belowFold = function(element,container) {
	            if (container === undefined || container === window) {
	                var pageFold = $(window).height() + $(window).scrollTop()+25;
	            } else {
	                var pageFold = $(container).offset().top + $(container).height();
	            }
	            return pageFold < $(element).offset().top;
	        };
	
	    });
	
	    $(window).load(function() {

	    });
	
		// Deals with displaying the list of your interests for the category
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
		    if ($(this).data('onmenu') === '0') {
		        $('.category_menu').hide();
		    }
		    return false;
		}
		$('.category_menu').hover(function() {
		    $(this).data('onmenu','1');
		}, function() {
		    $(this).data('onmenu','0');
		    $(this).hide();
		});
		
		// Deals with displaying the details of interest in interest discovery
		function display_interest_details() {
		    var detail = '<div>'+$(this).parent().find('.page_name').text()+'</div>';
		    detail = '<div id="page_detail">'+detail+'</div>';
		    var detail_left = $(this).offset().left;
		    var detail_top = $(this).offset().top-25;
		    $(detail)
		        .css('top', detail_top+'px')
		        .css('left', detail_left+'px')
		        .appendTo('body');
		    //$(detail).qtip();
		    $('#page_detail').fadeIn(500);
		    //$(this).unbind();
		    return false;
		}
		function hide_interest_details() {
		    $('#page_detail').hide();
		    $('#page_detail').remove();
		    return false;
		}
		
function tc_show_badge() {
    if($('.badge').text()!='') {$('.badge').remove();return false;}
    //e.stopPropagation();
    var badge_style = $(this).find('.badge_style').text();

    var obj_id = $(this).find('.obj_id').text();
    var x = $(this).find('.offset .x').text();
    var y = $(this).find('.offset .y').text();
    console.log('['+x+','+y+']');
    var more_info = $(this).find('.more_info').html();

    if ($(this).hasClass('interests_item')) {
        //alert('aaa');
        th=$(this);
        th.append('<div class="badge"></div>');
        obj_id = $(this).attr('rel');
        $(this).find('.thumb').append('<div class="badge"></div>');
        var top = $(this).offset().top+$(this).height();
        var left = $(this).offset().left;
        $('.badge').offset({top: top,left: left});
    } else {
        th=$(this);
        th.append('<div class="badge"></div>');
    }
    console.log($(this).offset());
    if ($(this).find('.offset').length !== 0) {
        var top = parseInt($(this).offset().top)+parseInt(y);
        var left = parseInt($(this).offset().left)+parseInt(x);
        $('.badge').offset({top: top, left: left});
    }

    var type = ($(this).hasClass('user_avatar')) ? 'user' : 'page';
    //$('.badge').append('<div class="loading">Loading...</div>');
    if ($(this).find('.badge_style').length !== 0) {
        $('.badge').addClass(badge_style);
    } else {
        $('.badge').addClass('badge_light');
    }
    var badge_dir = $(this).find('.badge_direction').text();
    var link_disable = $('#link_disable').text();
    console.log(badge_dir);
    if ($(this).find('.badge_direction').length !== 0) {
        $('.badge').addClass(badge_style+'_'+badge_dir);
    }
    $('.badge').load('/tc_badge/'+type+'/'+obj_id+'/'+link_disable, function() {
        //var badge_src = $(this).closest('.show_badge');
        if (more_info !== null && more_info !== undefined) {
            more_info = $(more_info);
            $('.badge').prepend(more_info).css('padding-top','0px');
            more_info.show();
        }
        $('.badge').fadeIn(500);
    });
}
function tc_hide_badge() {
    $('.badge').fadeOut('fast',function(){
        $('.badge').remove();
    });
}

	
	
	</script>

	
<? } ?>
