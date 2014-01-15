<? if($stage === 'return_img_location') { ?>
		<? echo $img_location; ?>
<? } elseif($stage ==='crop_success') { ?>
		<? echo $thumb_path; ?>
<? } else { ?>
	<? //FAKE PROFILE HIGHLIGHTED INTERESTS	 	
	$shared_221 = array(430,428,448,449,451,450,469,468,460,432,472,424,484,441,483,420,421,419,444);	
	$shared_222= array(430,428,448,449,451,488,469,468,460,432,472,424,484,441,483,420,421,419,444);	
	?>

    <?
    if ($is_ajax) {echo $is_ajax;
        include('application/views/profile/profile_main.php');
    } if(!$is_ajax || $this->session->flashdata('view_page') == 'photos') { 
    ?>
    <div id="p_id" style="display:none"><?=$profile_id?></div>
	<div id="main" class="container_24">
		<div id="profile_left" class="grid_19">
            <div id="profile_usernamevibes">
                <div id="profile_user_name">
                    <?= $my_data['first_name'].' '.$my_data['last_name']?>

                    <!-----for open graph button---->
                     <!--<span id="open_graph" class="medium_button" style="cursor: pointer">Open Graph</span>-->
                    <img id="open_graph" style="cursor: pointer;" src="/images/interestGraph.png">
                    <div class="connect_links">
                         <img id="fb_connect" style="float: right;" src="/images/fb_icon.gif">
                         <img id="twtr_connect" style="float: right;" src="/images/twtr_icon.ico">
                    </div>
	                    <? if ($this->uri->segment(1) === 'view_interests') { ?>
	                    <a href="/manage_my_interests">
	                        <div class="button inlinediv" style="float:right">
	                            Edit Interests
	                        </div>
	                    </a>
                    <? }?>
                    <!-----for request status----->
                    <? if($request_status == 'no_connection')
                    {?>
                        <!--<a id="request_status" onclick="javascript:view_hide('loop_list'); ">Connect</a>-->
                        <button id="request_status" class="button">Connect</button>
                    <? }
                    if($request_status == 'receiver')
                    { ?>
                        <button id="request_status" class="button accept_connection">
                        <a href="/accept/<?=$profile_id?>">Accept Connection Request</a>
                        </button>
                    <? }
                    if($request_status == 'sender')
                    { ?>
                        <span id="request_status">
                            <a>Connection Request Pending</a>
                        </span>
                    <? } ?>
                    <? //print_r($my_loops) ?>
                    <!--
                    <ul id="loop_list" style="display:none">
                        Please select a loop
                        <? foreach($my_loops as $loop){ ?>
                        <li onclick="request_connect(<?=$profile_id?>, <?=$loop['loop_id']?>);">
                            <? echo form_checkbox('loops[]', $loop['loop_id']); ?> <? echo $loop['loop_name'];?>
                        </li>
                        <? } ?>
                    </ul>-->
                </div>
                <div id="profile_user_vibes">
                    vibe list
                </div>
            </div>
            <div id="profile_main">
                <? include('application/views/profile/profile_main.php') ?>
            </div>
		</div>
		<!--~~~~~~~~~~ Placeholder for Profile Pic, profile tabs, and list of friends (right-most vertical strip) ~~~~~~~~~~~~~~~~~~-->
		<div class="grid_5">
			<!--~~~~~~~~ Placeholder for Profile Picture ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<div id="profile_profilePic">
				<? 
				if ($logger === true) {
					echo '<a href="#" id="link_to_edit_photo" >Change Profile Picture</a>';
					echo '<a href="#" id="link_to_profile_album"><img id="profilePic_auth" src="'.$profile_pic.'" border="0" width="190px"></a>';
				} else {
					echo '<a href="#" id="link_to_profile_album"><img id="profilePic" src="'.$profile_pic.'" border="0" width="190px"></a>';
				}
				?>
				<div class="clear"></div>
			</div>
			<!--~~~~~~~~~~ Similarity Bar (Fake data for now ~~~~~~~~~~~~~~~~~~~-->
			<?
			/*	//this is there as a joke to show that you are 100% similar to yourself
				if ($profile_id === $this->session->userdata['id']) {
					//$similarityValue = 100;
				}
				else if ($profile_id === '222')
					$similarityValue = 76;
				else if ($profile_id === '221')
					$similarityValue = 50;
				else if ($profile_id === '229')
					$similarityValue = 66;
		*/	?>
			<? if ($profile_id != $this->session->userdata('id')) { ?>
				<div id="similarityScore" style="display:none"><?=$similarityValue?></div>
				<div class="matchmeter">
					<a href="">Your similarity to <?=$my_data['first_name']?>
						<span class="similarityBar"><span id="similarityBar" style="width: 0%;"></span>
					</span></a>
				</div> 
			<? } ?>
			<!--~~~~ Placeholder for Profile section links (Wall/info/photos/friends) ~~~~~~~~~~~~~~~-->
			<div id="profile_section_links">
				<ul id="profileTabs">
                    <li><a href="#" onclick="get_profile_main(<?=$profile_id?>,'<?=$my_data['uri_name']?>')">Main</a></li>
					<li><a href="#" onclick="get_info(<?=$profile_id?>)">Info</a></li>
                    <li><a href="#" onclick="get_photos(<?=$profile_id?>,'user')">Photos</a></li>
				    <li><a href="/view_interests">Interests</a></li>
				
				<ul id="my_pages">
					<? foreach($list_my_pages as $key=>$item)  {
						echo '<li><a href="/interests/'.$item['uri_name'].'/'.$item['page_id'].'">'.$item['page_name'].'</a></li>';	
					}
					?>
				</ul>
				
				
			</div>
			<!--~~~~~~ Placeholder for List of Friends ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<div id="profile_friends">			
			<!--	<div class="title">Has <a href="#" onclick="get_connections('connections')"><?=$my_data['connections']?> connections</a></div> -->
				<div class="title">Has <a href="/get_connections/<?=$profile_id?>"><?=$my_data['connections']?> connections</a></div>
				
				<ul id="profile_friends_list">
					<? foreach($connection_list as $key=>$item) { ?>
						<li>
						<!--<li class="profile_friends_list_item">-->
							<div class="avatar inlinediv show_badge user_avatar badge_left"style="float: left;">
								<!--<img src="/users/<?=$item['user2_id'] ?>/pics/thumbs/thumb.jpg" />-->
								<? $this->load->helper('user_helper'); ?>
								<a href="/profile/<?=$item['uri_name']?>/<?=$item['uid']?>">
									<div class="img_tight_wrapper">
										<img src="<?=get_avatar_img($item['uid']) ?>" title="<? echo $item['first_name'].' '.$item['last_name'];?>" width="30" />
									</div>
								</a>
								<div class="obj_id" style="display:none;"><?=$item['uid']?></div>
							</div>
							<!--
							<div class="friend_name inlinediv">
								<a href="<?=$item['user2_id']?>"><? echo $item['first_name'].' '.$item['last_name'];?></a>
								<? if($logger == true){echo '(<a href="/disconnect/'.$item['user2_id'].'">x</a>)';}?>
							</div>
							-->
						</li>
					<? } ?>
				</ul>
			</div>
			<div class="clear"></div>
			<!--~~~~~~ Placeholder for List of Interests ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<div id="profile_interests">			
			<!--	<div class="title">Has <a href="#" onclick="get_connections('interests')"><?=$my_data['interests']?> interests</a></div> -->
				<div class="title">Has <a href="/get_interests/<?=$profile_id?>"><?=$my_data['interests']?> interests</a></div>
				<!-------------------------------------dummy number here-------------------------------------------
				<? /* ?>
				<ul id="profile_interests_list">
					<? $count=0; ?>
					<? foreach($list_my_pages as $key=>$item) { ?>
						<? if ($count < 6) { ?>
							<li class="profile_friends_list_item">
								<div class="avatar inlinediv page_avatar show_badge badge_left">
									<? $this->load->helper('user_helper'); ?>
									<a href="/interests/<?=$item['uri_name']?>/<?=$item['page_id']?>"><img src="<?=get_page_img($item['page_id']) ?>" width="30" /></a>
									<div class="obj_id" style="display:none;"><?=$item['page_id']?></div>
								</div>
								<div class="friend_name inlinediv">
									<a href="/interests/<?=$item['uri_name']?>/<?=$item['page_id']?>"><?=$item['page_name']?></a>
								</div>
							</li>
						<? } ?>
						<? $count++; ?>
					
						<li>
							<div class="avatar inlinediv page_avatar show_badge badge_left"style="float: left;">
								<? $this->load->helper('page_helper'); ?>
								<a href="/interests/<?=$item['uri_name']?>/<?=$item['page_id']?>">
									<img src="<?=get_page_avatar_img($item['page_id']) ?>" title="<?=$item['page_name']?>" width="30" />
								</a>
								<div class="obj_id" style="display:none;"><?=$item['page_id']?></div>
							</div>
						</li>
					
					<? } ?>
				</ul>
				<? */ ?>
				------------------------------------------dummy data display----------------------------------------->
				<ul id="profile_friends_list">
					<? $count=0; ?>
					<? foreach($list_my_pages as $key=>$item) { 
						$thumb = $item['thumbnail'];
						if (empty($thumb)) {
							$thumb = s3_url().'pages/default/defaultInterest/'.$item['interest_id'].'.png';
						} else {
							$thumb = s3_url().$thumb;
						}
					?>
							<li>
								<div class="avatar inlinediv show_badge page_avatar badge_left"style="float: left;">
									<!--<img src="/users/<?=$item['user2_id'] ?>/pics/thumbs/thumb.jpg" />-->
									<a href="/interests/<?=$item['uri_name']?>/<?=$item['page_id']?>">
									<img src="<?=$thumb ?>" title="<? echo $item['page_name'];?>" width="30" /></a>
									</a>
									<div class="obj_id" style="display:none;"><?=$item['page_id']?></div>
								</div>
							</li>
						<? $count++; ?>
					<? } ?>
				</ul>
				<!--------------------------------------------dummy data display---------------------------------------->
			</div>
		</div>
	</div>

	<!--~~~~~~~~~~~~~~~ Dialog for Uploading Profile Pictures (hidden) ~~~~~~~~~~~~~~~~~~~~~~~~~~-->
	<div id="upload_profilepic_dlg">

		<div id="imgupload_preview">
			<iframe id="postframe" name="postframe" style="display: none;"></iframe> 
			<div id="preview" style="width: 300px; margin-right: 20px;">
				<img src="<? echo s3_url().'users/default/default_pic.jpg'; ?>" style="float: left; margin-right: 100px; width: 300px;" >
				<div id="act_width" style="display: none"></div>
				<div id="act_height" style="display: none"></div>
			</div>
		</div>
		<div id="imgupload_options">
			<? $attrs = array('name' => 'thumbnail', 'id' => 'thumb_form'); ?>
			<? echo form_open('profile_pic', $attrs); ?>
			<input type="hidden" name="user" id="user" value=<? echo $profile_id ?>/>
			<input type="hidden" name="src_img" id="src_img" />
			<? echo form_submit('upload_thumbnail', 'Save', 'id="save_preview" style="display: none;"'); ?>
			<? echo form_close(); ?>	
			<div>
				<a id="upload_newimg_lnk" style="display:block;" href="">Upload new image</a>
				<div id="imgupload_newimg_pane" style="display: none;">
					<?php echo form_open_multipart('upload_photo_profile/'.$profile_id, 'id="orig_img_upload_form"'); ?>
					<?php echo form_hidden('album','profile'); ?>
					<?php echo form_hidden('ajax','1'); ?>
					<?php echo form_upload('userfile','','size="20"'); ?>
					<?php echo form_close(); ?>
				</div>
			</div>
			<div>
				<a id="upload_editthumb_lnk" style="display: block;" href="">Edit Thumbnail</a>
				<div id="imgupload_thumb_pane" style="display: none;">
					<div id="thumbnail" style="width:30px; height: 30px; border:1px #e5e5e5 solid; overflow:hidden; ">
						<img src="<? echo s3_url().'users/default/default_pic.jpg'; ?>" style="vertical-align: top; position: relative; width: 30px; height: 30px;"/>
					</div>
					<? $attrs = array('name' => 'thumbnail', 'id' => 'thumb_form'); ?>
					<? echo form_open('cropped_image', $attrs); ?>
					<input type="hidden" name="x1" id="x1" />
					<input type="hidden" name="y1" id="y1" />
					<input type="hidden" name="w" id="w" />
					<input type="hidden" name="h" id="h" />
					<input type="hidden" name="user" id="user" value=<? echo $profile_id ?>/>
					<input type="hidden" name="src_img" id="src_img" />
					<? echo form_submit('upload_thumbnail', 'Save Thumbnail', 'id="upload_thumb"'); ?>
					<? echo form_close(); ?>
					<div id="thumb_saved" style="display:none; font-color: green; font-weight: bold;">New thumb saved</div>
					<div>Please Select an area on the picture for cropping</div>
				</div>
			</div>
		</div>
	</div>
    <? } ?>

<div id="loop_dialog" class="dialog" style="display: none">
	<h2>Create Loop</h2>
    
    <ul>
        <li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
        <li><? echo form_open('/new_loop'); ?></li>
        <li><? echo 'Loop Name'. Form_Helper::form_input('loop_name');?></li>
            <ul>
                <li>Choose Connections</li>
                <? foreach($connection_list as $key=>$item)
                {
                ?>
                    <li><? echo form_checkbox('loop_member[]', $item['uid']); ?> <a href="/profile/<?=$item['uri_name']?>/<?=$item['uid']?>"><?=$item['first_name'].' '.$item['last_name']?> </a></li>
                <?
                }
                ?>
            </ul>
        <li><? echo form_submit('submit', 'Sumbit');
        echo form_close();?></li>
    </ul>
</div>

<? if(isset($my_loops))
	{
		$loop_tabs = $my_loops;
	}
	else
	{
		$loop_tabs = $loops;
	}
?>

<? $loop_tabs[]['loop_id'] = '0'; 
foreach($loop_tabs as $loop)
{?>
	<script type="text/javascript">
	
	$('#loop_<?=$loop['loop_id']?>_tab').live('click', function() {
		var loop_data = {
			requestor: 'profile',
			ci_csrf_token: $("input[name=ci_csrf_token]").val()
		};
		$.ajax({
			url: '/loop/'+<?=$loop['loop_id']?>+'/'+<?=$profile_id?>,
			type: 'POST',
			data: loop_data,
			success: function(data) {
				$('#show_loop').html(data);
				$('#show_loop').show();
				$('#show_newsfeeds').hide();
				//$('#loop_<?=$loop['loop_id']?>_tab').parent().removeClass('active_tab');
				//alert($(this).html());
				$('#loop_<?=$loop['loop_id']?>_tab').parent().addClass('active_tab');
				$('#loop_<?=$loop['loop_id']?>_tab').parent().find('.remove_icon').attr('src','/images/remove_icon_w.png')
				$('.newsfeed_entry_photo img').hoverIntent({over: display_image_on_hover, timeout: 200, interval: 200, out: function(){}});
			}
		});
		return false;
	});
	</script>
	
<? }?>

	<script type="text/javascript">
$(function() {
	getSimilarityMatch();
	get_psk_SimilarityMatch();
	init_sortable();
    var excluded_tabs = ['create_new_loop','more_loops'];
    var tabs_width_limit = $('#profile_loop_tabs').width()-$('#create_new_loop').width()-$('#more_loops').width()-30;
    init_tabs($('#more_loops'), $('#profile_loop_tabs'), $('#more_loops_list'), tabs_width_limit, excluded_tabs);

    function open_loop_menu() {
        var l_left = $('#more_loops').offset().left;
        //var l_top = $('#more_loops').offset().top+$('#more_loops').height()+10;
        //alert(l_left+' '+l_top);
        $('#more_loops_list').css('left',l_left+'px');
        view_hide('more_loops_list');
        return false;
    }

	//$('.newsfeed_entry_options').hide();
	$('.newsfeed_entry_add_comment').hide();
	
	$('#main_wall').live('click', function() {
		$('#show_newsfeeds .newsfeed_entry').each(function(indx) {
			$(this).remove();
		});
		var page_data = {
			requestor: 'profile',
			ci_csrf_token: $("input[name=ci_csrf_token]").val()
		};
		$.ajax({
			url: '/profile'+<?=$profile_id?>,
			type: 'POST',
			data: page_data,
			success: function(data) {
				$('#show_newsfeeds').html(data);
				$('#show_newsfeeds').show();
				$('#show_loop').hide();
			}
		});
		return false;
	});

    var timer;
    $("#request_status").live({
        mouseenter:  function(e) {
            //console.log('request button enter '+$('#loop_list').data('open'));
            e.stopPropagation();
            clearTimeout(timer);
            /*
                    <ul id="loop_list" style="display:none">
                        Please select a loop
                        <? foreach($my_loops as $loop){ ?>
                        <li onclick="request_connect(<?=$profile_id?>, <?=$loop['loop_id']?>);">
                            <? echo form_checkbox('loops[]', $loop['loop_id']); ?> <? echo $loop['loop_name'];?>
                        </li>
                        <? } ?>
                    </ul>
                    */
            var loop_list = $('<ul id="loop_list" style="display:none;" />');
            loop_list.append('Please select a loop');
            $.ajax({
                url: '/loops_user_is_in?profile_id=<?=$profile_id?>',
                type: 'GET',
                success: function(data) {
                    //alert('ajax');
                    var loops = $.parseJSON(data);
                    $.each(loops, function(k,v) {
                        var loop_entry = '<li onclick="request_connect(<?=$profile_id?>, '+v.loop_id+');">';
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
    $('#loop_list').live({
        mouseenter:  function(e) {
            clearTimeout(timer);
            //console.log('loop_list enter '+$('#loop_list').data('open'));
            e.stopPropagation();
            $(this).data('open','1');
        },
        mouseleave: function(e) {
            //console.log('loop_list exit '+$('#loop_list').data('open'));
            $(this).data('open','0');
            e.stopPropagation();
            timer = setTimeout(function() {
                $('#loop_list').fadeOut('fast');
                $('#loop_list').remove();
                //$(this).data('open','0');
            },100);
        }
    });
    

	/* For animating the interests pane in user profile */
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
			$(this).text('see more...');
			setup.attr('isopen', '0');
		}
		return false;
	});
	*/

	$('.user_avatar').each(function() {
		//getMiniSimilarityBar($(this).find('.minisimilaritybar'),50);
		var userid = $(this).find('.obj_id').text();
		var score = 45;
		if (userid === '222') {
			score = 76;
		} 
		else if (userid === '221') {
			score = 50;
		}
		else if (userid === '229') {
			score = 66;
		}
		getMiniSimilarityBar($(this).find('.minisimilaritybar'),score);
	});
    $('#link_to_profile_album').live('click', function() {
        $.ajax({
            url: '/view_profile_photos/<?=$profile_id?>?header=none&type=profile&ajax=1',
            type: 'GET',
            success: function(data) {
                //alert('ajax');
                $('#profile_main').html(data);
            }
        });
        return false;
    });

});
</script>

<script>
$('#create_new_loop').live('click', function() {
	//$('#new_list_dialog').dialog();

	$('#loop_dialog').dialog({
				modal: true, 
				draggable: false,
				resizable: false,
				width: 600,
				autoOpen: true
	});
	return false;
});
</script>
<div id="graph_dialog" style="display: none; position: relative"></div>
<script type="text/javascript">

	$(function() {
		var source = null;
		$('#graph_dialog').dialog({
			modal: true, 
			draggable: false,
			resizable: false,
			width: $(window).width()-100,
			height: $(window).height()-100,
			autoOpen: false,
			close: function() {}
		});
		

		
		
		$('#open_graph').click(function() {
			$('<iframe id="graph_iframe" name="graph_iframe"></iframe>').appendTo('#graph_dialog');
			$('#graph_iframe').css('width','100%').css('height','100%');
			$('#graph_dialog').find('#graph_iframe').attr('src', '/get_graph/<?=$profile_id?>');
			$('#graph_dialog').dialog('open');
		});
	});
</script>
<? } ?>