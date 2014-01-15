<?
if ($stage === 'page_detail') { ?>
	<? $this->load->helper('page_helper') ?>
	<div class="interest_detail_tooltip">
		<div>
			<div class="interest_img inlinediv">
				<!--<img src="<?=$page_info[0]['img']?> alt="" onError="javascript:this.src='<?=base_url()?>pages/default/defaultInterest/<?=$page_info[0]['interest_id']?>.png'"" style="width: 80px"/>-->
				<img src="<?=$page_info[0]['img']?> alt="" style="width: 80px"/>
			</div>
			<div class="interest_info inlinediv">
				<div class="interest_title">
					<a href="<?=base_url()?>interests/<?=$page_info[0]['uri_name']?>/<?=$page_info[0]['page_id']?>"><?=$page_info[0]['page_name']?></a>
				</div>
				<div>
					<?=$page_info[0]['type']?>
				</div>
				<div>
                	
					<? echo count($page_users); if(count($page_users) == '1'){?> person<? }else{?> people<? }?>
				</div>
			</div>
		</div>
	</div>
<? 
} else if ($stage === 'more_interests') {
    $this->load->helper('page_helper');
    foreach ($interests as $k => $v) { ?>
        <li class="item dHover show_badge" style="float: left">
            <? $dir = ($k%6 > 2) ? 'left' : 'right'; ?>
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
            <?
            $page_name = substr($v['page_name'],0,15);
            if (strlen($v['page_name']) > 15) {
                $page_name .= '...';
            }
            if (trim($page_name) == '') {
                $page_name = '...';
            }
            ?>
            <div class="interest_item_placeholder">
                <div class="page_name" style="display: none"><?=$v['page_name'] ?></div>
                <div class="page_name_abr"><?=$page_name ?></div>
                <a href="/add_page/<?=$v['page_id']?>/interest_discovery/<?=$this->uri->segment(2)?>/<? if($stage == 'register' || $this->uri->segment(3) == 'start'){ echo 'start'; }?>">
                    <div class="page_img"><img src="<?=$v['img']?>" alt="" onError="javascript:this.src='<?=base_url()?>pages/default/defaultInterest/<?=$v['interest_id']?>.png'"/></div>
                </a>
                <div class="page_id" style="display: none"><?=$v['page_id'] ?></div>
                <div class="page_uri" style="display: none"><?=$v['uri_name']?></div>
                <div class="interest_category_id" style="display: none"><?=$v['interest_id'] ?></div>
                <? $page_name = $v['page_name']; ?>
            </div>
        </li>
    <? } ?>
<? } else { ?>
	<? foreach($my_interests as $kkk => $vvv) { ?>
		<div id="int_cat_<?=$kkk?>" class="category_menu menu site_menu" style="display: none;">
			<ul>
				<li class="title">Your Interests</li>
				<?
				foreach($vvv as $key => $val) {
					if (trim($val) !== '') {
						echo '<li>'.$val.'</li>';
					}
				}
				?>
			</ul>
		</div>
	<? } ?>

	<!-- CSRF Token placeholder for AJAX calls -->
	<div style="display: none">
		<? 
			echo form_open('main/index/');
			echo Form_Helper::form_input('dummy', 'dummy');
			echo form_close();
		?>
	</div>
	<div class="clear"></div>
	<div class="dummy" style="display: none">
		<? echo form_open('dummy'); ?>
		<? echo form_hidden('dummy'); ?>
		<? echo form_close(); ?>
	</div>
	<div class="container_24">
        <div class="grid_5 alpha">
            <div id="interests_categories">
                <?
                //print_r($interest_categories);
                foreach($interest_categories as $key => $value) { ?>
                    <div class="interest_category" id="interest_category_<?=$value['id']?>">
                        <?
                        $count = ($interest_count[$value['id']]) ? $interest_count[$value['id']] : 0;
                        ?>
                        <div class="inlinediv category_name">
                            <a href="/discover_interests/<?=$value['id']?>/<? if($stage == 'register' || $this->uri->segment(3) == 'start'){ echo 'start'; }?>"><?=$value['type']?></a>
                        </div>
                        <div class="interest_count menu_activator"><?=$count?></div>
                        <div class="interest_id" style="display: none"><?=$value['id']?></div>
                    </div>
                <? } ?>
            </div>
        </div>
		<div class="grid_19 omega" id="interests_main_content">
            <div id="interests_title"><h1>Discover your interests</h1></div>
			<div id="interests_placeholder">
				<div>
					<div id="interest_category_question">What kind of art interests you? </div>
					<? echo form_open('interests', 'class="inline_form"'); ?>
					<!--<div class="autocomplete_input">-->
					<? echo Form_Helper::form_input('interest_choice_input', 'eg: painting', 'id="interest_choice_input" class="input_placeholder"');?>
					<!--</div>-->
					<? $submit = array ("name" => "submit", "value" => "Save", "id" => "save_interest_choices", "class" => "button"); ?>
					<? echo form_submit($submit); ?>
					<? echo form_close(); ?>
			   </div>
			   <div>
					<div id="interest_search_desc">Search topics in <?=ucwords($value['type'])?> </div>
					<? echo form_open('interests', 'class="inline_form"'); ?>
					<!--<div class="autocomplete_input">-->
					<? echo Form_Helper::form_input('topic_search_input', 'example: Frank Gehry', 'id="topic_search_input" class="input_placeholder"');?>
					<!--</div>-->
					<? $submit = array ("name" => "submit", "value" => "Search", "id" => "search_interest_topics", "class" => "button"); ?>
					<? echo form_submit($submit); ?>
					<? echo form_close(); ?>
			   </div>
			   <div id="interest_topic_search_results">
					<ul id="interests">
					<? $this->load->helper('page_helper'); ?>
					<? foreach ($interests as $k => $v) { ?>
						<li class="item dHover show_badge" style="float: left">
                            <? $dir = ($k%6 > 2) ? 'left' : 'right'; ?>
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
							<? 
							$page_name = substr($v['page_name'],0,15); 
							if (strlen($v['page_name']) > 15) {
								$page_name .= '...'; 
							}
							if (trim($page_name) == '') {
								$page_name = '...';
							}
							?>
							<div class="interest_item_placeholder">
								<div class="page_name" style="display: none"><?=$v['page_name'] ?></div>
								<div class="page_name_abr"><?=$page_name ?></div>
								<a href="/add_page/<?=$v['page_id']?>/interest_discovery/<?=$this->uri->segment(2)?>/<? if($stage == 'register' || $this->uri->segment(3) == 'start'){ echo 'start'; }?>">
									<div class="page_img"><img src="<?=$v['img']?>" alt="" onError="javascript:this.src='<?=base_url()?>pages/default/defaultInterest/<?=$v['interest_id']?>.png'"/></div>
								</a>
								<div class="page_id" style="display: none"><?=$v['page_id'] ?></div>
                                <div class="page_uri" style="display: none"><?=$v['uri_name']?></div>
                                <div class="interest_category_id" style="display: none"><?=$v['interest_id'] ?></div>
								<? $page_name = $v['page_name']; ?>
							</div>
						</li>
					<? } ?>
					</ul>
					<div class="clear"></div>
					<div id="list_interests_bottom" class="interests_bottom"><div class="last_id" style="display: none"><?=$v['page_id']?></div></div>
			   </div>
			</div>
		</div>
		<div class="clear"></div>
<? if ($stage === 'register' || $this->uri->segment(3) == 'start') { ?>
		<div class="prefix_19 grid_5">
			<a href="<?=base_url()?>personal_info/my_info"><div class="button">
			<!-- <div id="discover_interests_submit" class="button">-->
				Go to Next Step: Enrich your Profile
			</div></a>
		</div>
<? } ?>
	</div>
	<div id="my_category_interests" style="display: none">
	</div>
	<!--<div id="ihDesc" style="display:none"></div>-->	
	<script type="text/javascript">

	$(function() {
		var on_li = false;
        var on_detail = false;
		var timer;

        $('#interests').delegate('li', 'mouseenter',function(e) {
            $(this).find('.interest_item_placeholder').css('background-color', '#000').css('color', '#FFF').css('opacity', 0.7);
            $(this).find('.page_img').append('<div class="button add_page_btn" style="top: 30px; margin-left: 16px; width: 50px; position: absolute; ">Add</div>');
        });
		$('#interests').delegate('li','mouseleave',function(e) {
            $(this).find('.interest_item_placeholder').stop(true,true).css('background-color', '#FFF').css('color', '#000').css('opacity', 1);
            $(this).find('.add_page_btn').stop(true,true).remove();
		});


        $('')

		//For updating the 'my interests' count in the interest categories
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

    // Deals with clicking on interest categories
        //$('.interest_category').live('click', function() {
        $('.interest_category').live('click', function() {
            window.location.replace($(this).find('a').attr('href'));
            return false;
        });
        $('.interest_count').hoverIntent({over: display_my_interests_for_category, timeout: 200, interval: 200, out: hide_my_interests_for_category});

    //Autoscroll (for news feeds)
        var container = window;
        var prev_id = 0
        var last_id = 0

        $(container).scroll(function(e) {
            //alert($(window).scrollTop());
            /*
            if ($(window).scrollTop() > 70 && $('#interests_main_content').height() > $(window).height()) {
                $('#interests_categories').css('margin-top',$(window).scrollTop()-60+'px');
            } else {
                $('#interests_categories').css('margin-top','4px');
            }
            */
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
                            interest_id: <?=$this->uri->segment(2)?>,
                            start_at:  last_id,
                            type: type,
                            limit: 24,
                            ci_csrf_token: $("input[name=ci_csrf_token]").val()
                        };
                        $.ajax({
                            url: '/more_interests',
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
                                //console.log($(data));
                                $('.show_badge').hoverIntent(hoverintent_config);
                                /*
                                $(data).find('.show_badge').each(function() {
                                    $(this).hoverIntent(hoverintent_config);
                                });
                                */
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
        //alert($('#interests_categories').height()+' > '+$(this).height());
        /*
        if ($('#interests_categories').height()+20 > $(this).height()-75) {
            $('#interests_categories .interest_category').css('padding-top','0px !important')
                                                         .css('padding-bottom','0px !important')
                                                         .css('margin-bottom','2px !important');
        }
        */
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


	</script>

	
<? } ?>
<div class="debug"></div>
