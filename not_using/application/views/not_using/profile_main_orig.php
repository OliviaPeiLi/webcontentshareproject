
<? if($stage == 'photos') { ?>
<div id="show_newsfeeds">
    <div id="profile_interests_list_placeholder">
        <div id="is_open" isopen="0"  prev_height="" style="display: none;"></div>
        <div id="profile_user_name">Photo Albums of <? if($profile_id == $this->session->userdata('id'))
        {
            echo 'You';
        }
        else
        {
            echo $my_data['first_name'].' '.$my_data['last_name'];
        }?></div>
        <!--TODO: load photoalbums here -->
        <? include('application/views/photos/albums.php'); ?>
    </div>
</div>
<? }
elseif($stage == 'message' || $stage == 'msg_thread' || $stage == 'outbox')
{ ?>
<div id="show_newsfeeds">
    <? include('application/views/message/msg_box.php'); ?>
</div>
<?  }
elseif($stage == 'edit_info')
{ ?>
<div id="show_newsfeeds">
    <? include('application/views/profile/personal_info.php'); ?>
</div>
<?	}
elseif($stage == 'view_info')
{ ?>
<div id="show_newsfeeds">
	<? include('application/views/profile/user_info.php'); ?>
</div>
<? }
elseif($stage == 'get_connections')
{ ?>
<div id="show_newsfeeds">
	<? include('application/views/profile/connections_list.php'); ?>
</div>
<? }
    elseif($stage == 'view_interests')
{ ?>
<div id="show_newsfeeds">
    <? include('application/views/profile/view_interests.php'); ?>
</div>
<? }
else
{ ?>

    <!--~~~~~~~~~~~~ placeholder for List of Interests (top-most horizontal strip below the header) ~~~~~~~~~~~~~~~~~~-->
    <div id="profile_interests_list_placeholder">
        <div id="is_open" isopen="0"  prev_height="" style="display: none;"></div>


        <!-----------------Form for update the interests display order-------------------------------->
        <form method="post" action="/profile/<?=$my_data['uri_name']?>/<?=$profile_id?>" id="sort_form">
        <input type="submit" value="Save new order"/>
        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" >
        <input type="hidden" name="go" value="1">
        </form>

        <ul id="profile_interest_categories">
            <? foreach ($Categories as $cid=>$category) { ?>
                <? if (count((array)@$Interests[$cid]) > 0) { ?>
                    <li style="width: 226.667px;" rel="<?php echo $category['id'] ?>">
                        <div class="category_placeholder"><img src="/images/category_icons/<?php echo $category['short'] ?>.png" title="<?=$category['title']?>" border="0" height="20px">
                            <ul class="interests_list">
                                <?php
                                    $interest_count = 0;
                                    foreach ((array)@$Interests[$cid] as $iid => $interest) { ?>
                                        <?
                                        $thumb = $interest['thumbnail'];
                                        if (empty($thumb)) {
                                            $thumb = s3_url().'pages/default/defaultInterest/'.$interest['id_category'].'.png';
                                        } else {
                                            $thumb = s3_url().$thumb;
                                        }
                                        $disp = ($interest_count >= 5) ? 'none' : 'inline-block';
                                        //FAKE INTEREST HIGHLIGHTED IN PROFILE
                                        $highlight = '';
                                        ?>
                                        <li class="interests_item show_badge" rel="<?=$interest['id'] ?>" style="display: <?=$disp?>">
                                            <span style="display:none"><?=$interest['title']?></span>
                                            <a href="/interests/<?=$interest['uri_name']?>/<?=$interest['id']?>"><img class="<?=$highlight?>" src="<?=$thumb?>"  border="0" height="20px"/></a>
                                        </li>
                                        <? $interest_count++; ?>
                                    <?	} ?>
                            </ul>
                        </div>
                    </li>
                <? } ?>
            <? } ?>
            <div class="clear"></div>
        </ul>

        <? /* ?>
        <form method="post" action="" id="sort_form">
        <input type="submit" value="Save new order"/>
        <input type="hidden" name="go" value="1">
        </form>
        <ul id="profile_interest_categories">
            <?
            foreach($interests as $key => $value) {
                echo '<li>';
                echo '<div class="category_placeholder">';
                echo '<img src="/images/category_icons/'.$value['type'].'.png" title="'.$value['type'].'" border=0 height="20px">';
                for	($i=0; $i<rand(1,25); $i++) {
                    echo '<img src="/images/example1.jpg" border="0" height="20px">';
                }
                echo '</div>';
                echo '</li>';
            }
            ?>
            <div class="clear"></div>
        </ul>
        <? */ ?>
    </div>
    <div style="height: 50px; margin-left: 7px;">
        <? if($logger==true) { ?>
            <a href="/create_page">
            <div class="big_button unselectable">
                Create Interest
            </div>
            </a>
        <? } ?>
        <div id="profile_see_more_interests">
                See More...
        </div>
    </div>
    <!--~~~~~~~~~~~~~~~ placeholder for content below the list of interests ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div id="profile_below_interests_list_placeholder">
        <!--~~~~~~~~~~ placeholder for recommendations (left-most vertical strip) ~~~~~~~~~~~~~~~~~~~~~~-->
        <div class="grid_5 alpha" id="profile_recommendations_placeholder">
            <!-- TODO: Recommendations -->
            <div id="recInterests" class="recPages">
                <div style="padding: 3px 2px; text-align: center;">
                    <div class="rec_title">Recommended Interests</div>
                    <? $this->load->helper('page_helper'); ?>
                    <ul id="profile_friends_list">
                        <? foreach($recommend_page as $id=>$page) {
                            $page_info = page_info($id); ?>
                            <li class="">
                                <!--<div class="avatar user_avatar inlinediv show_badge" style="float: left;">-->
                                <a href="<?=$page_info['link']?>">
                                    <div class="recommended_friend page_avatar show_badge">
                                            <div class="recommended_avatar">
                                                <img class="avatar" src="<?=$page_info['thumbnail'] ?>" width="38" />
                                            </div>
                                            <div class="rec_friend_name inlinediv"><?=$page_info['page_name']?></div>
                                            <div class="obj_id" style="display:none;"><?=$id?></div>
                                    </div>
                                </a>
                            </li>
                        <? } ?>
                    </ul>




                    <? /* ?>
                    <ul id="profile_friends_list">
                    <?
                    foreach ($recommend_page as $k=>$v)
                    {?>
                        <li class="profile_friends_list_item">
                                <div class="avatar inlinediv">
                                    <? $this->load->helper('user_helper'); ?>
                                    <img src="<?=get_page_img($k) ?>" />
                                </div>
                                <div class="friend_name inlinediv">
                                    <?=id_to_page($k) ?>
                                    <a href="/add_page/<?=$k?>">Add Page</a>
                                </div>
                        </li>
                    <? } ?>
                    </ul>
                    <? */ ?>
                </div>
            </div>

            <div id="recFriends" class="recPages">
                <div style="padding: 3px 2px; text-align: center;">
                    <div class="rec_title">Recommended Friends</div>

                    <ul id="profile_friends_list">
                        <? foreach($people as $user) { ?>
                            <li class="">
                            <? {
                            if($user['thumbnail'] != '')
                            {
                                $user['img'] = s3_url().$user['thumbnail'];
                            }
                            else
                            {
                                if($user['gender'] == 'm')
                                {
                                    $user['img'] = s3_url()."users/default/defaultMale.png";
                                }
                                else
                                {
                                    $user['img'] = s3_url()."users/default/defaultFemale.png";
                                }
                            }
                            }?>
                                <!--<div class="avatar user_avatar inlinediv show_badge" style="float: left;">-->
                                <a href="/profile/<?=$user['uri_name']?>/<?=$user['id']?>">
                                    <div class="recommended_friend user_avatar show_badge">
                                        <div class="recommended_avatar">
                                            <img class="avatar" src="<?=$user['img'] ?>" width="38" />
                                        <!--	<div>
                                                <span class="mini_similarityBar">
                                                    <span class="minisimilaritybar" style="width: 0%;"></span>
                                                </span>
                                            </div> -->
                                        </div>
                                        <div class="rec_friend_name inlinediv"><?=$user['first_name'].' '.$user['last_name']?></div>
                                        <div class="obj_id" style="display:none;"><?=$user['id']?></div>
                                    </div>
                                </a>
                            </li>
                        <? } ?>
                    </ul>



                    <? /* ?>
                    <ul id="profile_friends_list">
                        <? foreach($friends_suggestion as $key=>$item) { ?>
                            <li class="profile_friends_list_item">
                                <div class="avatar inlinediv">
                                    <? $this->load->helper('user_helper'); ?>
                                    <img src="<?=get_avatar_img($item['user_id']) ?>" />
                                </div>
                                <div class="friend_name inlinediv">
                                    <?=id_to_link($item['user_id']) ?>
                                    <a href="#" onclick="return false" onmousedown="javascript:view_hide('mutul_<?=$item['user_id']?>');">(<?=count($item['mutul'])?>)</a>
                                    <ul id = "mutul_<?=$item['user_id']?>" style="display: none">
                                        <? foreach($item['mutul'] as $k=>$v)
                                        { ?>
                                        <li><a href="/profile/<?=$v['user2_id']?>"><?=$v['first_name'].' '.$v['last_name']?></a></li>
                                        <? }?>
                                    </ul>
                                    <a href="/request/<?=$item?>">Connect</a>
                                </div>
                            </li>
                        <? } ?>
                    </ul>
                    <? */ ?>
                </div>
            </div>
            
            <div id="recFriends" class="recPages">
                <div style="padding: 3px 2px; text-align: center;">
                    <div class="rec_title">People Should Know</div>

                    <ul id="profile_friends_list">
                        <? foreach($peopleshouldknow as $user) { ?>
                            <li class="">
                            <? {
                            if($user['thumbnail'] != '')
                            {
                                $user['img'] = s3_url().$user['thumbnail'];
                            }
                            else
                            {
                                if($user['gender'] == 'm')
                                {
                                    $user['img'] = s3_url()."users/default/defaultMale.png";
                                }
                                else
                                {
                                    $user['img'] = s3_url()."users/default/defaultFemale.png";
                                }
                            }
                            }?>
                                <!--<div class="avatar user_avatar inlinediv show_badge" style="float: left;">-->
                                <a href="/profile/<?=$user['uri_name']?>/<?=$user['user2_id']?>">
                                    <div class="recommended_friend user_avatar show_badge">
                                        <div class="recommended_avatar">
                                            <img class="avatar" src="<?=$user['img'] ?>" width="38" />
                                            <div id="similarityScore_<?=$user['user2_id']?>" style="display:none"><?=$user['similarity']?></div>
                                        	<div>
                                                <span class="mini_similarityBar">
                                                    <span id="minisimilaritybar_<?=$user['user2_id']?>" style="width: 0%;"></span>
                                                </span>
                                            </div> 
                                        </div>
                                        <div class="rec_friend_name inlinediv"><?=$user['first_name'].' '.$user['last_name']?></div>
                                        <div class="obj_id" style="display:none;"><?=$user['user2_id']?></div>
                                    </div>
                                </a>
                            </li>
                        <? } ?>
                    </ul>
                </div>
            </div>
            
        </div>

        <!--~~~~~~~~~~~~~ Main content of profile is here (posts, wall, albums) ~~~~~~~~~~~-->
        <div class="grid_14 omega" id="profile_wall">

            <!--~~~~~~~~~~~~~~~~~~~~~~~~~~ wallpost from loop ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            <div id="show_loop" style="display: none;">

            </div>


            <!--~~~~~~~Show NewsFeeds Placeholder ~~~~~~~~~~~~~~~~~~~-->
            <div id="show_newsfeeds">

                <? if($check_friends || $logger==true){?>

                    <!--~~~~~~~~~~~ Placeholder for wall/photo post input sections ~~~~~~~~~~~~~~~-->
                    <div id="profile_post_section" class="post_section">
                        <!-- TODO: needs to be redone to integrate Upload photos box and Add Wallpost Box -->
                        <?php echo $this->session->flashdata('validation_errors');?>
<!-- <? /*
                        <ul id="profile_loop_tabs" class="indexTabs">
                            <? if($this->session->userdata['id'] == $profile_id) { ?>
                                <li class="profile_loop_tab inlinediv <? if($this->uri->segment(4) == '' || $this->uri->segment(4) == '0'){ echo 'active_tab';}?>"><a id="loop_0_tab" href="/loop/<?=$loop['loop_id']?>">Public Loop</a></li>
                                <? foreach($my_loops as $loop) {?>
                                    <li class="profile_loop_tab inlinediv <? if($this->uri->segment(4) == $loop['loop_id']){ echo 'active_tab';}?>"><a id="loop_<?=$loop['loop_id']?>_tab" href="/loop/<?=$loop['loop_id']?>"><?=$loop['loop_name']?></a>
                                    <a href="/edit_loop/<?=$loop['loop_id']?>"> <img class="edit_icon" src="/images/edit_icon.png" /></a>
                                    <? if($loop['loop_name'] != 'Main Loop') { ?>
                                        <a href="/rm_loop/<?=$loop['loop_id']?>"><img class="remove_icon" src="/images/delete_icon.png" /></a>
                                    <? }?>
                                    </li>
                                <? } ?>
                                <li class="profile_loop_tab inlinediv menu_activator" id="more_loops" style="display:none" onclick="javascript:open_loop_menu()"><a href="#">More v</a></li>
                                <li class="profile_loop_tab inlinediv" id="create_new_loop"><a href="/create_loop">+</a></li>

                            <? }else { ?>
                                <li class="profile_loop_tab inlinediv <? if($this->uri->segment(4) == '' || $this->uri->segment(4) == '0'){ echo 'active_tab';}?>"><a id="loop_0_tab" href="/profile/<?=$my_data['uri_name']?>/<?=$profile_id?>">Public Loop</a></li>
                                <? foreach($loops as $loop) { ?>
                                    <li class="profile_loop_tab inlinediv <? if($this->uri->segment(4) == $loop['loop_id']){ echo 'active_tab';}?>"><a id="loop_<?=$loop['loop_id']?>_tab"><a id="loop_<?=$loop['loop_id']?>_tab" href="/loop/<?=$loop['loop_id']?>"><?=$loop['loop_name']?></a></li>
                                <? } ?>
                                <li class="profile_loop_tab inlinediv menu_activator" id="more_loops" style="display:none" onclick="javascript:open_loop_menu()"><a href="#">More v</a></li>
                            <? } ?>
                            <ul style="display:none" id="more_loops_list" class="menu site_menu"></ul>
                        </ul>
*/ ?> -->
                        <!--~~~~~~ Tabs for selecting post/photo sections ~~~~~~~~~~~-->
<?  ?>
				<div id="profile_post_action_box" class="inlinediv">
				<? 
				//echo 'PROFILE ID='.$profile_id;
				if (empty($profile_id)) {
					$profile_id = $this->session->userdata['id'];
				}
				//echo 'PROFILE ID='.$profile_id;
				?>

					<!---~~~~~~~ Upload photos box ~~~~~~~~~~~~~~~~~~~~~~~~-->
					<!-- TODO: Needs to be integrated in profile_post_section -->
					<div id="profile_upload_photo" class="profile_post_box" style="display: none">
                        <?php echo form_open_multipart('upload_photo_profile/'.$profile_id);?>
                        <div class="inlinediv" id="post_data">
                            <? echo form_upload('userfile', '', 'size="20" class="post_box_add_photo"'); ?>
                            <? echo form_hidden('view', 'profile'); ?>
                            <? echo Form_Helper::form_input('caption', set_value('caption', 'Add Caption'), 'id="profile_new_photo_caption" class="input_placeholder" style="display:none"');?>
                            <div class="autocomplete_input add_loops" style="display:none"><input type="text" name="loops" value="Select Loops" class="select_loops"></div>
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
                            <? echo form_hidden('view', 'profile'); ?>
                            <? echo Form_Helper::form_input('post_msg', set_value('post_msg', 'Leave a Post'), 'id="profile_new_post" class="input_placeholder post_box_new_post"');?>
                        <div class="autocomplete_input add_loops" style="display:none"><input type="text" name="loops" value="Select Loops" class="select_loops"></div>
                        <? //echo form_dropdown('loop_id', $loop_dropdown, '0'); ?>
						</div>
                        <div class="inlinediv" id="post_post_submit">
                            <? echo form_submit('submit', 'Share', 'class="post_submit"'); ?>
                        </div>
						<? echo form_close(); ?>
					</div>

                    <!--~~~~~~~~~~~~~~~~~~ Links box ~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
					<div align="center" id="profile_add_link" class="profile_post_box" style="display:none">
						<? echo form_open('/post_link'); ?>
						<div class="box" align="left">
							<div class="close" align="right">
								<div class="closes"></div>
							</div>
							<input type="text" name="url" id="url" value="http://"/>
							<input type="button" class="button" id="url_submit" value="Fetch">
                            <input type="text" name="text" id="user_text" class="input_placeholder" value="What is special about this link?" style="display:none"/>
							<div id="loader">
								<div align="center" id="load" style="display:none"><img src="load.gif" /></div>
							</div>
                            <div id="link_user_id" style="display:none"><?=$profile_id?></div>
                            <div id="post_loop_info" style="display:none;">
                                <div class="inlinediv">
                                    <div class="autocomplete_input add_loops"><input type="text" name="loops" value="Select Loops" class="select_loops"></div>
                                </div>
                                <div class="inlinediv" id="post_link_submit">
                                    <input type="button" id="link_share" class="post_submit button one_line" value="Share">
                                </div>

                            </div>
						</div>
						<? form_close(); ?>
					</div>
				</div>
                <ul id="profile_post_tabs">
					<li id="profile_post_tab_post" class="profile_post_tab active_post_tab inlinediv"><a href="">Post</a></li>
					<li id="profile_post_tab_photo" class="profile_post_tab inlinediv"><a href="">Photo</a></li>
                    <li id="profile_post_tab_link" class="profile_post_tab inlinediv"><a href="">Link</a></li>
				</ul>
<?  ?>
<? /* ?>
                        <div id="profile_post_action_box">
                            <ul id="profile_post_tabs" class="inlinediv">
                                <li id="profile_post_tab_post" class="profile_post_tab active_post_tab"><a href="">Share</a></li>
                                <li id="profile_post_tab_photo" class="profile_post_tab"><a href="">Photo</a></li>
                            </ul>
                            <div id="profile_add_post_photo" class="inlinediv">

                                <!---~~~~~~~ Upload photos box ~~~~~~~~~~~~~~~~~~~~~~~~-->
                                <!-- TODO: Needs to be integrated in profile_post_section -->
                                <div id="profile_upload_photo" class="profile_post_box" style="display: none">
                                    <?php echo form_open_multipart('upload_photo_profile/'.$profile_id);?>
                                    <? echo form_upload('userfile', '', 'size="20"'); ?>
                                    <? echo Form_Helper::form_input('caption', set_value('caption', 'Caption'), 'id="profile_new_photo_caption" class="input_placeholder"');?>

                                    <? if($this->uri->segment(3) == $this->session->userdata['id']){ ?>
                                        <div class="autocomplete_input"><input type="text" name="loops" value="Select Loops" class="select_loops"></div>
                                    <? } else {
                                        echo form_hidden('loop_id','0');
                                     } ?>
                                    <input type="hidden" name="uri_name" value=<?=$my_data['uri_name']?> />
                                    <? echo form_submit('submit','upload', 'class="post_submit"'); ?>
                                    <? echo form_close(); ?>
                                </div>

                                <!--~~~~~~~~ Wallpost box ~~~~~~~~~~~~~~~~~~~~~~-->
                                <!-- TODO: Needs to be integrated in profile_post_section -->
                                <div id="profile_add_post" class="profile_post_box">
                                    <? echo validation_errors(); ?>
                                    <? echo form_open('post_profile/'.$profile_id.'/'); ?>
                                    <? echo form_hidden('to', $profile_id); ?>
                                    <? echo form_hidden('post_type', $this->uri->segment(1)); ?>
                                    <? echo Form_Helper::form_input('post_msg', set_value('post_msg', ''), 'id="profile_new_post" class="input_placeholder"');?>

                                    <? if($this->uri->segment(3) == $this->session->userdata['id']){ ?>
                                        <div class="autocomplete_input"><input type="text" name="loops" value="Select Loops" class="select_loops"></div>
                                    <? } else {
                                        echo form_hidden('loop_id','0');
                                    } ?>
                                    <input type="hidden" name="uri_name" value=<?=$my_data['uri_name']?> />
                                    <? echo form_submit('submit', 'Share', 'class="post_submit"'); ?>
                                    <? echo form_close(); ?>
                                </div>
                            </div>
                        </div>
 <? */ ?>
                    </div>
                    
<script type="text/javascript">
$(function() {
	<? if($profile_id === $this->session->userdata('id')) { ?>
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
    <? } ?>
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

    /*
    $('.select_loops').tokenInput(<?=$loops_json?>, {
        theme: "google",
        queryParam: "term",
        searchDelay: 50,
        searchingText: null,
        hintText: null,
        linkedText: true,
        placeholderText: "+ Add Loop",
        showDropdownOnFocus: true,
        prePopulate: <?=$init_loops?>,
        onAdd: function(item) {
            $('#profile_add_post').append('<input type="hidden" name="loop[]" class="ac_loop" id="loop_'+item.id+'" value="'+item.id+'">');
        },
        onDelete: function(item) {
            $('#profile_add_post').find('#loop_'+item.id).remove();
        }
    });
    */
});
</script>
                <? } ?>


            <!--~~~~~~~ NewsFeeds Placeholder ~~~~~~~~~~~~~~~~~~~-->
                <div id="profile_newsfeeds_placeholder" class="newsfeed newsfeed_placeholder">
                    <? foreach ($feeds_array as $fkey => $fvalue)
                    {
                        $last_timestamp['friends'] = $fvalue['time'];
                        //echo $last_timestamp['friends'];
                        if($value['type']=='connection')
                        {
                            echo $news_array[0].' are connected with '.$news_array[1].'.';
                            echo '<br />';
                        }
                        $type= 'profile';
                        include('application/views/newsfeed/newsfeed.php');

                    }?>
                    <? if (count($feeds_array) > 15) { ?>
	                    <div id="friends_feed_bottom" class="feed_bottom">
	                        <a class="more_news_link" href="#">Get More News</a> 
	                        <div class="last_timestamp" style="display: none;"><? echo $last_timestamp['friends']; ?></div>
	                    </div>                    
                    <? } ?>

                </div>
            </div>
        </div>
    </div>

<? } ?>

<script type="text/javascript">

    /*Similarity Match Bar*/
    function get_psk_SimilarityMatch() {
        <? foreach($peopleshouldknow as $user){ ?>
        //alert('similarity match');
        var val=$('#similarityScore_<?=$user['user2_id']?>').text();
        if(val>=75){
            $('#minisimilaritybar_<?=$user['user2_id']?>').removeClass().addClass('q75').animate({width:val+'%'},1000);
        }
        else if(val>=50){
            $('#minisimilaritybar_<?=$user['user2_id']?>').removeClass().addClass('q50').animate({width:val+'%'},1000);
        }
        else if(val>=25){
            $('#minisimilaritybar_<?=$user['user2_id']?>').removeClass().addClass('q25').animate({width:val+'%'},1000);
        }
        else{
            $('#minisimilaritybar_<?=$user['user2_id']?>').removeClass().addClass('q1').animate({width:val+'%'},1000);
        }
        <? } ?>
    }
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
                            $.post("/fetch_link?url="+foundurl, { // make ajax request to the fetch.php with the foundurl
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