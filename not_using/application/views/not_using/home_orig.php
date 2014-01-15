
<? //print_r($this->session->userdata('id'));?>
<? //print_r($this->session->userdata('name'));?>
<? 
$email = $this->session->userdata('email'); 
$id = $this->session->userdata('id');
//print_r($id);
//echo $id.','.$email;?>
<?
	$attributes = array('id' => 'csrf_form');
	echo form_open('page/post_comm', $attributes);
	echo form_hidden('dummy', 'dummy');
	echo form_close();
	$last_timestamp['friends'] = 0;
	$last_timestamp['interests'] = 0;
?>
<div class="dummy" style="display: none">
	<? echo form_open('dummy'); ?>
	<? echo form_hidden('dummy'); ?>
	<? echo form_close(); ?>
</div>
<!-- hello -->
<?
if ($stage === 'main') { ?>
<div id="main">
	<div class="container_24" id="home">
	   <div class="grid_14 alpha" id="left_column">
			<!--~~~~~~~~~~~ Homepage Tabs ~~~~~~~~~~~-->
			<div id="home_tabs" class="indexTabs home_tabs">
				<div style="vertical-align: bottom">
					<ul id="home_tabs_list">
						<li id="home_tab" class="inlinediv active_tab"><a href="/home_interests">Channel</a></li>
						<li id="events_tab" class="events_tab inlinediv"><a href="/home_events">Events</a></li>
					<!--	<li id="lists_tab" class="menu_activator inlinediv"><a href="#" onclick="return false" onmousedown="javascript:view_hide('lists');">Lists</a></li> --><!-- old list tab -->
						
						<? foreach($my_lists as $list_key=>$list_value) { ?>
							<li id="list_tab_<?=$list_value['list_id']?>" class="inlinediv">
								<a class="list_newsfeed" href="/list_newsfeed/<?=$list_value['list_id']?>"><?=$list_value['list_name']?></a> 
								<a href="/edit_list_add_pages/<?=$list_value['list_id']?>"> <img class="edit_icon" style="width:12px" src="/images/edit_icon.png" /></a> 
								<a href="/del_list/<?=$list_value['list_id']?>"> <img class="remove_icon" style="width:12px" src="/images/delete_icon.png" /></a>
							</li>	
						<? } ?>
						
						<? foreach($follow_lists as $follow_key=>$follow_value) { ?>
							<li id="list_tab_<?=$follow_value['list_id']?>" class="inlinediv">
								<a class="list_newsfeed" href="/list_newsfeed/<?=$follow_value['list_id']?>"><?=$follow_value['list_name']?>.</a>
								<a href="/unfollow_list/<?=$follow_value['list_id']?>/home"> Unfollow list</a>
							</li>	
						<? } ?>
						<li id="more_tabs" class="inlinediv menu_activator" style="display:none" onclick="javascript:open_list_menu(); return false;"><a href="#">More v</a></li>
                        <li id="create_new_list" class="inlinediv"><a href="/new_list">+</a></li>
                        <ul style="display:none" id="more_tabs_list" class="menu site_menu"></ul>
					</ul>
				</div>
			</div>
        	<ul id="lists" class="menu site_menu">				
				<? if($my_lists) { ?>
					<li class="heading">My Lists</li>
					<? foreach($my_lists as $list_key=>$list_value) { ?>
						<li>
							<a class="list_newsfeed" href="/list_newsfeed/<?=$list_value['list_id']?>"><?=$list_value['list_name']?></a> 
                            <a href="/del_list/<?=$list_value['list_id']?>"> <img class="remove_icon" style="width:12px" src="/images/delete_icon.png" /></a>
                            <a href="/edit_list_add_pages/<?=$list_value['list_id']?>"> <img style="width:12px" src="/images/edit_icon.png" /></a>

						</li>
					<? } ?>
				<? } ?>
                    <li id="create_new_list"><a href="/new_list">Create New List</a></li>
				
				<? if($follow_lists) { ?>
					<li class="heading">Followed Lists</li>
					<? foreach($follow_lists as $follow_key=>$follow_value) { ?>
						<li>
							<a class="list_newsfeed" href="/list_newsfeed/<?=$follow_value['list_id']?>"><?=$follow_value['list_name']?>.</a>
							<a href="/unfollow_list/<?=$follow_value['list_id']?>/home"> Unfollow list</a>
						</li>	
					<? } ?>
				<? } ?>
			</ul>
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~ Events from your Interests ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<!--<div id="show_events" class="home_left_newsfeed" style="display: none;">
				<div class="newsfeed_title">Events from your Interests</div>
			</div>-->
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~ Events from your Lists ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
           <!--<div class="newsfeed_title">Interests Channel</div>-->
           <?if($page_array) { ?>
                <div id="list_newsfeed" class="home_left_newsfeed newsfeed newsfeed_placeholder">
                    <div class="newsfeed_title">Interests Channel</div>
                    <!--<div class="newsfeed_title">News in your lists</div>-->
                    <? foreach ($page_array as $fkey => $fvalue)
                    {
                        $news_array=unserialize($fvalue['data']);
                        $last_timestamp['interests'] = $fvalue['time'];
                        //echo $last_timestamp['interests'];

                        $type = 'interests';
                        $view_type = 'home';
                        include('application/views/newsfeed/newsfeed.php');
                    } ?>
                    <? //print_r($page_array); ?>
                    <? //echo count($page_array); ?>
                    <? if(count($page_array) > 15) {?>
                        <div id="interests_feed_bottom" class="feed_bottom">
                            <? if (count($page_array) > 15) { ?>
                                <a class="more_news_link" href="#">Get More News</a>
                            <? } else { ?>
                            <? } ?>
                            <div class="last_timestamp" style="display: none;"><? echo $last_timestamp['interests']; ?></div>
                        </div>
                    <? } ?>
                </div>
			<? } ?>
		</div>
		<div class="grid_10 omega" id="right_column">
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~ Suggested Pages ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<div id="home_suggested_pages" style="display: none">
			   <div style="padding: 90px 140px 90px 140px;">Pages you may like</div>
				<?
				foreach ($suggested_pages as $suggested_page)
				{
					if (!$suggested_page['avatar'])
					{
						$suggested_page['avatar'] = 'example.jpg';
					}
					echo '<a href="?cntrl=future_page_cntrl&act=show_page&page_id='.$suggested_page['page_id'].'"><img src="/images/'.$suggested_page['avatar'].'" border="0" height="40px"></a>';
				}
				?>
			</div>
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~ Friends Newsfeed ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<div id="profile_post_action_box" class="post_section profile_post_action_box_home">
			<div style="height: 5px;"></div>
				<ul id="profile_loop_tabs" class="indexTabs">
						<li class="profile_loop_tab inlinediv <? if($this->session->userdata['loop_id'] == '' || $this->session->userdata['loop_id'] == '0'){ echo 'active_tab';}?>"><a id="loop_0_tab" href="/loop/0">Public Loop</a></li> 
						<? foreach($my_loops as $loop) {?>	
							<li class="profile_loop_tab inlinediv <? if($this->session->userdata['loop_id'] == $loop['loop_id']){ echo 'active_tab';}?>"><a id="loop_<?=$loop['loop_id']?>_tab" href="/loop/<?=$loop['loop_id']?>"><?=$loop['loop_name']?></a>
							<a href="/edit_loop/<?=$loop['loop_id']?>"> <img class="edit_icon" src="/images/edit_icon.png" /></a>
                            <? if($loop['loop_name'] != 'Main Loop') { ?>
								<a href="/rm_loop/<?=$loop['loop_id']?>"><img class="remove_icon" src="/images/delete_icon.png" /></a>
							<? }?>
							</li>
						<? } ?>
                        <li class="profile_loop_tab inlinediv menu_activator" id="more_loops" style="display:none" onclick="javascript:open_loop_menu(); return false;"><a href="#">More v</a></li>
						<li class="profile_loop_tab inlinediv" id="create_new_loop"><a href="/create_loop">+</a></li>
							   
					
                    <ul style="display:none" id="more_loops_list" class="menu site_menu"></ul>
				</ul>

				<div id="home_add_post_photo" class="inlinediv">
				<? $profile_id = $this->session->userdata['id'];?>
				
					<!---~~~~~~~ Upload photos box ~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!-- TODO: Needs to be integrated in profile_post_section -->
					<div id="profile_upload_photo" class="profile_post_box" style="display: none">
                        <?php echo form_open_multipart('upload_photo_profile/'.$profile_id);?>
                        <div class="inlinediv" id="post_data">
                            <? echo form_upload('userfile', '', 'size="20" class="post_box_add_photo"'); ?>
                            <? echo form_hidden('view', 'home'); ?>
                            <? echo Form_Helper::form_input('caption', set_value('caption', 'Add Caption'), 'id="profile_new_photo_caption" class="input_placeholder" style="display:none"');?>
                            <div class="autocomplete_input add_loops" style="display:none"><input type="text" name="loops" value="Select Loops" class="select_loops"></div>
                            <!--<div class="autocomplete_input add_topics" style="display:none"><input type="text" name="topics" value="Select Topics" class="select_topics"></div>-->
                        </div>
                        <div class="inlinediv" id="post_photo_submit">
                            <? echo form_submit('submit','upload', 'class="post_submit"'); ?>
                        </div>
						<? echo form_close(); ?>
					</div>

					<!--~~~~~~~~ Wallpost box ~~~~~~~~~~~~~~~~~~~~~~-->		
					<!-- TODO: Needs to be integrated in profile_post_section -->
					<div id="profile_add_post" class="profile_post_box">
						<? echo validation_errors(); ?>
						<? echo form_open('post_profile/'.$profile_id.'/'); ?>
                        <div class="inlinediv" id="post_data">
                            <? echo form_hidden('to', $profile_id); ?>
                            <? echo form_hidden('post_type', 'profile'); ?>
                            <? echo form_hidden('view', 'home'); ?>
                            <? echo Form_Helper::form_input('post_msg', set_value('post_msg', 'Leave a Post'), 'id="home_new_post" class="input_placeholder post_box_new_post"');?>
                        <div class="autocomplete_input add_loops" style="display:none"><input type="text" name="loops" value="Select Loops" class="select_loops"></div>
						<!--<div class="autocomplete_input add_topics" style="display:none"><input type="text" name="topics" value="Select Topics" class="select_topics"></div>-->
                        <? //echo form_dropdown('loop_id', $loop_dropdown, '0'); ?>
						</div>
                        <div class="inlinediv" id="post_post_submit">
                            <? echo form_submit('submit', 'Share', 'class="post_submit"'); ?>
                        </div>
						<? echo form_close(); ?>
					</div>

                    <!--~~~~~~~~~~~~~~~~~~ Links box ~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<div align="center" id="profile_add_link" class="profile_post_box" style="display:none">
						<? echo form_open('/share_link');?>
						<div class="box" align="left">
							<div class="close" align="right">
								<div class="closes"></div>
							</div>
							<input type="text" name="url" id="url" />
							<input type="button" class="button" id="url_submit" value="Fetch">
                            <input type="text" name="text" id="user_text" class="input_placeholder" value="What is special about this link?" style="display:none"/>
							<div id="loader">
								<div align="center" id="load" style="display:none"><img src="load.gif" /></div>
							</div>
                            <div id="link_user_id" style="display:none"><?=$profile_id?></div>
                            <div id="post_loop_info" style="display:none;">
                                <div class="inlinediv">
                                    <div class="autocomplete_input add_loops"><input type="text" name="loops" value="Select Loops" class="select_loops"></div>
                                    <!--<div class="autocomplete_input add_topics"><input type="text" name="topics" value="Select Topics" class="select_topics"></div>-->
                                </div>
                                <div class="inlinediv" id="post_link_submit">
                                    <input type="button" id="link_share" class="post_submit button one_line" value="Share">
                                </div>

                            </div>
						</div>
						<? echo form_close(); ?>
					</div>
				</div>
                <ul id="profile_post_tabs">
					<li id="profile_post_tab_post" class="profile_post_tab active_post_tab inlinediv"><a href="">Post</a></li>
					<li id="profile_post_tab_photo" class="profile_post_tab inlinediv"><a href="">Photo</a></li>
                    <li id="profile_post_tab_link" class="profile_post_tab inlinediv"><a href="">Link</a></li>
				</ul>
			</div>	
			<div class="newsfeed_title">Connections Channel</div>
			
			
			
			<!--~~~~~~~~~~~ Placeholder for wall/photo post input sections ~~~~~~~~~~~~~~~-->
			<div id="profile_post_section">
				<? $profile_id = $this->session->userdata['id']; ?>
	
			</div>

			
			<!--~~~~~~~~~~~~~~~~~~~~~~~~~~ wallpost from loop ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<div id="show_loop" style="display: none;">
				
			</div>
			
			
			<div id="home_friends_newsfeed" class="newsfeed newsfeed_placeholder">
				<? foreach ($profile_array as $fkey => $fvalue)
				{
					$news_array=unserialize($fvalue['data']);
					$last_timestamp['friends'] = $fvalue['time'];
                    //echo $last_timestamp['friends'];
					$type = 'profile';
					$view_type = 'home';
					include('application/views/newsfeed/newsfeed.php');
				}
				?>
                <?//print_r($profile_array); ?>
                <? //echo count($page_array[0]); ?>
                <? if(count($profile_array) > 15) {?>
                    <div id="friends_feed_bottom" class="feed_bottom">
                        <a class="more_news_link" href="#">Get More News</a>
                        <div class="last_timestamp" style="display: none;"><? echo $last_timestamp['friends']; ?></div>
                    </div>
                <? } ?>
				
				<!--
				<table border="1">
					<tr height="40">
					<?
						foreach($friends_actions as $friends_action)
						{
							if (!$friends_action['avatar'])
							{
								$friends_action['avatar'] = "example.jpg";
							}
							if ($friends_action['event'] == 'friend')
							{
								$action_message = 'befriended <a href="?cntrl=future_profile_cntrl&act=show_profile&user_id='.$friends_action['subj_id'].'">'.$friends_action[data1].' '.$friends_action[data2].'</a>';
								$photo = NULL;
							}
							elseif ($friends_action['event'] == 'page')
							{
								$action_message = 'now follows page <a href="?cntrl=future_profile_cntrl&act=show_profile&page_id='.$friends_action['subj_id'].'">'.$friends_action['data1'].'</a>';
								$photo =  ($friends_action['data2'])  ?  $friends_action['data2'] : NULL;
							}
							elseif ($friends_action['event'] == 'photo')
							{
								$action_message = 'uploaded photo <a href="?cntrl=future_profile_cntrl&act=show_profile&photo_id='.$friends_action['subj_id'].'">'.$friends_action['data1'].'</a>';
								$photo =  ($friends_action['data1'])  ?  $friends_action['data1'] : NULL;
							}
							elseif ($friends_action['event'] == 'post')
							{
								$action_message = 'made <a href="?cntrl=future_profile_cntrl&act=show_profile&page_post_id='.$friends_action['subj_id'].'">post</a><br>'.$friends_action['data2'];
								$photo =  ($friends_action['data3'])  ?  $friends_action['data3'] : NULL;
							}
							?>
						<td width="40">
							<a href="?cntrl=future_profile_cntrl&act=show_profile&user_id=<?= $friends_action['user_id']?>"><img src="/images/<?= $friends_action['avatar'] ?>" border="0" height="40px"></a>
						</td>
						<td width="320">
								<div class="user_name_container"><a href="?cntrl=future_profile_cntrl&act=show_profile&user_id=<?= $friends_action['user_id']?>"><?= $friends_action['firstname'].' '.$friends_action['lastname']?></a></div>
								<div class="text_post"><?= $action_message?></div>
								<div class="post_info"><?= "Time Lapsed: ".$friends_action['time'] ?> | comment | props</div>
						</td>
						<?=  ($photo)  ?  '<td width="40"><img src="/images/'.$photo.'" border="0" height="40px"></td>'  : ''; ?>
					</tr>
							<?
								}
							?>
			   </table>
			-->
			</div>
		</div>
	</div>
</div>
 
<?
} else if ($stage === 'newsfeed_update_interests') {
?>
    <? if ($initial === '1') { ?>
        <div class="newsfeed_title">Interests Channel</div>
    <? } ?>
    <? //print_r($page_array) ?>
        <?//count($page_array)?>
	<? foreach ($page_array as $fkey => $fvalue)
	{
        //print_r($fvalue);
		$news_array=unserialize($fvalue['data']);
		//print_r($news_array);
		$data['in'] = $this->page_model->user_in($news_array['page_id_to']);
		?>
		
		<? $last_timestamp['interests'] = $fvalue['time']; ?>
            <? //echo $last_timestamp['interests'] ?>
		<? include('application/views/newsfeed/newsfeed.php'); ?>
	<?}?>

    <? if (count($page_array) > 15) { ?>
        <div id="interests_feed_bottom" class="feed_bottom">
            <a class="more_news_link" href="#">Get More News</a>
            <div class="last_timestamp" style="display: none;"><? echo $last_timestamp['interests']; ?></div>
        </div>
    <? } else { ?>
        No more news
    <? } ?>
<?
} else if ($stage === 'newsfeed_update_friends') { ?>
	<? 
	foreach ($profile_array as $fkey => $fvalue)
	{
		$news_array=unserialize($fvalue['data']);?>
		<? $last_timestamp['friends'] = $fvalue['time']; ?>
        <? echo $last_timestamp['friends'] ?>
		<? include('application/views/newsfeed/newsfeed.php'); ?>
	<? }?>
    <? if (count($profile_array) > 15) { ?>
        <div id="friends_feed_bottom" class="feed_bottom">
            <a class="more_news_link" href="#">Get More News</a>
            <div class="last_timestamp" style="display: none;"><? echo $last_timestamp['friends']; ?></div>
        </div>
    <? } else { ?>
            No more news
    <? } ?>
<?
} else {
	echo 'unauthorized';
}
?>

<div id="new_list_dialog" class="dialog" style="display: none">
	<ul>
		<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
		<? echo form_open('save_list/'); ?>
		<? echo form_hidden('from',$this->uri->segment(3)); ?>
		<li>
			<div class="form_label">List Name</div>
			<div class="form_field" style="width: 365px"><? echo Form_Helper::form_input('list_name');?></div>
		</li>
		<li>
			<div class="form_label">Description</div>
			<div class="form_field"><textarea name="description" rows="12" cols="70"></textarea></div>
		</li>
		<li>
			<div class="form_label">List Visibility</div>
			<div class="form_field">
				<div><? echo form_radio('privacy', 'public', TRUE); ?> Public</div>
				<div><? echo form_radio('privacy', 'private'); ?> Private</div>
			</div>
		</li>
		
			<ul id="my_pages">
				<li>Choose Pages</li>
				<? foreach($my_pages as $key=>$item) { ?>
					<li><? echo form_checkbox('check_pages[]', $item['page_id']); ?> <a href="/interests/uri_name/<?=$item['page_id']?>"><?=$item['page_name']?> </a></li>
				<? } ?>
			</ul>
		<li><div class="form_label"><? echo form_submit('submit', 'Submit'); ?></div></li>
		<? echo form_close();?>
	</ul>
</div>

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

<script type="text/javascript">
function open_list_menu() {
    var l_left = $('#more_tabs').position().left;
    var l_top = $('#more_tabs').position().top+$('#more_tabs').height()+10;
    //alert(l_left+' '+l_top);
    $('#more_tabs_list').css('top',l_top+'px').css('left',l_left+'px');
    view_hide('more_tabs_list');
    return false;
}
function open_loop_menu() {
    var l_left = $('#more_loops').position().left;
    var l_top = $('#more_loops').position().top+$('#more_loops').height()+10;
    //alert(l_left+' '+l_top);
    $('#more_loops_list').css('top',l_top+'px').css('left',l_left+'px');
    view_hide('more_loops_list');
    return false;
}

$(window).load(function() {

});
$(function() {

    $('#profile_add_post .select_loops, #profile_upload_photo .select_loops').tokenInput(<?=$loops_json?>, {
        theme: "google",
        queryParam: "term",
        searchDelay: 50,
        searchingText: null,
        hintText: null,
        linkedText: true,
        placeholderText: "+ Add Loop",
        showDropdownOnFocus: true,
        onAdd: function(item) {
            $('#profile_add_post form').append('<input type="hidden" name="loop[]" class="ac_loop" id="loop_'+item.id+'" value="'+item.id+'">');
        },
        onDelete: function(item) {
            $('#profile_add_post').find('#loop_'+item.id).remove();
        }
    });
    $('#profile_add_post .select_topics, #profile_upload_photo .select_topics, #profile_add_link .select_topics').tokenInput('/get_topics', {
        theme: "google",
        preventDuplicates: true,
        singleTokenOnly: false,
        queryParam: "q",
        searchDelay: 50,
        linkedText: true,
        placeholderText: "+ Add Topic",
        allowInsert: true,
        showDropdownOnFocus: false,
        onAdd: function(item) {
            $('#profile_add_post form').append('<input type="hidden" name="topic_id['+item.id+']" class="ac_topic_id" id="topic_id_'+item.id+'" value="'+item.id+'">');
            $('#profile_add_post form').append('<input type="hidden" name="topic_name['+item.id+']" class="ac_topic_name" id="topic_name_'+item.id+'" value="'+item.name+'">');
        },
        onDelete: function(item) {
            $('#profile_add_post').find('#topic_id_'+item.id).remove();
            $('#profile_add_post').find('#topic_name_'+item.id).remove();
        }
    });
    $('#profile_add_link .select_loops').tokenInput(<?=$loops_json?>, {
        theme: "google",
        queryParam: "term",
        searchDelay: 50,
        searchingText: null,
        hintText: null,
        linkedText: true,
        placeholderText: "+ Add Loop",
        showDropdownOnFocus: true,
        onAdd: function(item) {
            $('#profile_add_link').append('<input type="hidden" name="loop[]" class="ac_loop" id="loop_'+item.id+'" value="'+item.id+'">');
        },
        onDelete: function(item) {
            $('#profile_add_link').find('#loop_'+item.id).remove();
        }
    });

	//$('.newsfeed_entry_options').hide();
	$('.newsfeed_entry_comments').hide();
    $('.newsfeed_entry_options').css('visibility','hidden').find('.newsfeed_view_comments_lnk').text('View comments');
    //Left side of home (interests)
    var excluded_tabs = ['create_new_list','more_tabs'];
    var tabs_width_limit = $('#home_tabs').outerWidth()-$('#create_new_tab').outerWidth(true)-$('#more_tabs').outerWidth(true)-25;
    init_tabs($('#more_tabs'), $('#home_tabs'), $('#more_tabs_list'), tabs_width_limit, excluded_tabs);
    //Right side of home (friends)
    excluded_tabs = ['create_new_loop','more_loops'];
    tabs_width_limit = $('#profile_loop_tabs').parent().width()-$('#create_new_loop').width()-$('#more_loops').width()-25;
    init_tabs($('#more_loops'), $('#profile_loop_tabs'), $('#more_loops_list'), tabs_width_limit, excluded_tabs);

/*
$("#more_tabs").live('click', function() {
    var l_left = $(this).offset().left;
    var l_top = $(this).offset().top+$(this).height()+10;
    $('#more_tabs_list').css('top',l_top+'px').css('left',l_left+'px');
    alert('hello');
    $('#more_tabs_list').show();
});
*/

$('#events_tab').live('click', function() {
	var event_data = {
		requestor: 'home',
		ci_csrf_token: $("input[name=ci_csrf_token]").val()
	};
	$.ajax({
		url: '/events',
		type: 'POST',
		data: event_data,
		success: function(data) {
            $('#left_column .newsfeed_title').hide();
			$('#list_newsfeed').html(data);
            $('#list_newsfeed').prepend('<div class="newsfeed_title">Events</div>');
			$('#home_tabs_list').find('.active_tab').removeClass('active_tab');
			$('#events_tab').addClass('active_tab');
		}
	});
	return false;
});
$('#home_tab').live('click', function() {
	//$('#show_newsfeeds .newsfeed_entry').each(function(indx) {
	//	$(this).remove();
	//});

	var page_data = {
		requestor: 'home',
        initial: '1',
		ci_csrf_token: $("input[name=ci_csrf_token]").val()
	};
	$.ajax({
		url: '/',
		type: 'POST',
		data: page_data,
		success: function(data) {
            $('#left_column .newsfeed_title').show();
			$('#list_newsfeed').html(data);
            $('#home_tabs_list .active_tab').removeClass('active_tab');
            $('#home_tab').addClass('active_tab');
		}
	});

	return false;
});
$('.edit_icon, .remove_icon').live('click',function(e){
	e.stopPropagation();
	return true;
})

<? $all_lists = array_merge((array)$my_lists, (array)$follow_lists); 
foreach($all_lists as $list)
{?>
	$('#list_tab_<?=$list['list_id']?>').live('click', function() {
        var tab = $(this);
		var list_data = {
			requestor: 'home',
			ci_csrf_token: $("input[name=ci_csrf_token]").val()
		};
		
		$.ajax({
			url: '/list_newsfeed/<?=$list['list_id']?>/home',
			type: 'POST',
			data: list_data,
			success: function(data) {
                $('#left_column .newsfeed_title').show();
				$('#list_newsfeed').html(data);
                $('#home_tabs_list .active_tab').removeClass('active_tab');
				$('#list_tab_<?=$list['list_id']?>').addClass('active_tab');
                if(tab.closest('ul').attr('id') === 'more_tabs_list') {
                    //alert('yooo');
                   $('#more_tabs').addClass('active_tab');
                } else {
                    $('#more_tabs').removeClass('active_tab');
                }

				//$('#lists_tab').addClass('active_home_tab');
			}
		});
		return false;
	});
<? } ?>

$('#create_new_list').live('click', function() {
	$('#new_list_dialog').dialog({
				modal: true, 
				draggable: false,
				resizable: false,
				width: 600,
				autoOpen: true
	});
	return false;
});
$('.view_list').live('click', function() {
	var list_data = {
		requestor: 'home',
		ci_csrf_token: $("input[name=ci_csrf_token]").val()
	};
	$.ajax({
		url: '/list_newsfeed',
		type: 'POST',
		data: list_data,
		success: function(data) {
			$('#list_newsfeed').html(data);
			//$('#show_newsfeeds').hide();
			//$('#show_events').hide();
		}
	});
	return false;
});
});
</script>

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
        var tab = $(this);
		var loop_data = {
			requestor: 'home',
			ci_csrf_token: $("input[name=ci_csrf_token]").val()
		};
		$.ajax({
			url: '/loop/<?=$loop['loop_id']?>/<?=$profile_id?>',
			type: 'POST',
			data: loop_data,
			success: function(data) {
                $('#home_friends_newsfeed').html(data);
                $('#profile_loop_tabs').find('.active_tab').find('.remove_icon').attr('src','/images/delete_icon.png');
                $('#profile_loop_tabs').find('.active_tab').removeClass('active_tab');
                if(tab.closest('ul').attr('id') === 'more_loops_list') {
                   $('#more_loops').addClass('active_tab');
                } else {
                    $('#more_loops').removeClass('active_tab');
                }
				$('#loop_<?=$loop['loop_id']?>_tab').parent().addClass('active_tab');
				$('#loop_<?=$loop['loop_id']?>_tab').parent().find('.remove_icon').attr('src','/images/remove_icon_w.png')
				$('.newsfeed_entry_photo img').hoverIntent({over: display_image_on_hover, timeout: 200, interval: 200, out: function(){}});
			}
		});
		return false;
	});
	</script>
	
<? }?>

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


<script type="text/javascript" src="<?=base_url()?>js/jquery.watermarkinput.js"></script>
<script type="text/javascript">
	// <![CDATA[	
	$(document).ready(function(){var key_typing=0; // thats sets variable called typing - it is needed to see how much times a keyboard button is clicked - if it is 1 
		$('#url_submit').live('click', function(){
            var foundurl=$('#url').val(); // button clicked - parse the value to a var foundurl
		    if(foundurl!=''){ // make sure that there is foundurl
		        if(!isValidURL(foundurl)) // isValidURL is external function,which is at the bottom of the page. It checks if the found url looks like real url
			    {return false;} // do nothing if the url is bad
			    else
			    {
                    $.post("/application/views/test_url.php?url="+foundurl,function(alive){
                        //if(alive==='works' && $('.url').length==0){ // so here we use test_url.php ! That tests if the foundurl is alive by using ajax.There is also a small rule that checks if previous url has already loaded.
                        if(alive==='works'){ // so here we use test_url.php ! That tests if the foundurl is alive by using ajax.There is also a small rule that checks if previous url has already loaded.
                            $('#user_text').show('blind');
                            $('#load').show(); // show loading image
                            //alert('about to fetch link');
                            $.post("/application/views/tools/fetch_link?url="+foundurl, { // make ajax request to the fetch.php with the foundurl
                            }, function(response){ // ajax have returned a content
                                $('#loader').html($(response).fadeIn('slow')); //show the ajax returned content
                                $('.images img').hide(); //hide all images found
                                $('#load').hide(); //content already loaded - no need of loading bar anymore
                                $('img#1').load(function(){$('img#1').fadeIn().onerror(function(){$('img#1').hide();});}); // when the first image loads see if it doesn't give error or actually exists.If there is a problem just hide the image field
                                var totimg=$('.images img').length; // all images count
                                $('#total').html(totimg); // show the count of images
                                var currentimg=1; //set the first image to 1
                                if(totimg>0){$('#current').html('1');}else{$('#current').html('0');} // some small fix for showing if there is 1 or 0 images available
                                if(totimg==1){$('#navi').hide()} // thats remove the navigation (next and prev images) if the image is only 1

                                $('#next').click(function(){if((currentimg)<totimg){currentimg=currentimg+1; $('img#'+(currentimg-1)).hide(); $('img#'+currentimg).fadeIn(); $('#current').html(currentimg);}}); // just change the id to +1 if the next button is clicked.Also apply fade effects.
                                $('#prev').click(function(){if(currentimg!=1 || currentimg==2){currentimg=currentimg-1; $('img#'+(currentimg+1)).hide(); $('img#'+currentimg).fadeIn(); $('#current').html(currentimg);}}); // just change the id to -1 if the next button is clicked.Also apply fade effects.
                                $('#post_loop_info').show();

                            });
	                    }
		            });
		        }
            }
        });
	
		// watermark input fields (you know the yellow hover- thats left from the 99 site example)
		jQuery(function($){
		   
		   $("#url").Watermark("http://");
		});
		jQuery(function($){

		    $("#url").Watermark("watermark","#369");
			
		});	
		function UseData(){
		   $.Watermark.HideAll();
		   $.Watermark.ShowAll();
		}

	});	
	
function isValidURL(url){
		var RegExp = /(\.){1}\w/;
	
		if(RegExp.test(url)){
			return true;
		}else{
			return false;
		}
	}

	// ]]>
</script>

<? include_once('application/views/chat/chat.php'); ?>
