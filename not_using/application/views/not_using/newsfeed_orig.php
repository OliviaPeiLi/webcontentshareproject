<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript" charset="utf-8"></script>-->
<? //print_r($fvalue); ?>
<?
// For this to work, the following variables must be set and be accessible:
//   $feeds_array: the array containing the newsfeed
?>
<? $this->load->helper('page_helper'); ?>
<? if (empty($view_type) || $view_type === null) {
    if($page_id || $post_type === 'page')
    {
        $view_type = 'page';
    }
    else if($profile_id)
    {
        $view_type = 'profile';
    }
    else
    {
        $view_type = 'home';
    }
}
?>
<? //print_r($fvalue); ?>
<? /*$border_class = ($view_type === 'page') ? 'newsfeed_border_top' : 'newsfeed_border_full';*/ ?>
<? $border_class = ($view_type === 'page') ? 'newsfeed_border_top' : 'newsfeed_border_top'; ?>
<?
$news_array=unserialize($fvalue['data']);
$link_id = (empty($news_array['page_id_from'])) ? $news_array['user_id_from'] : $news_array['page_id_from'];
$badge_type = (empty($news_array['page_id_from'])) ? 'user' : 'page';
?>

<?
if($fvalue['type']=='post' || $fvalue['type']=='post_comm' || $fvalue['type']=='post_like' || $fvalue['type']=='post_comm_like')
{
?>
	<!--~~~~~~~~~~ News Feed Entry: Post, Post comments, Post likes, Post like comments ~~~~~~~~~~~~~~-->
	<div class="profile_newsfeed_entry newsfeed_text_entry newsfeed_entry <?=$border_class?>">
		<?
			$is_from_page = ($news_array['page_id_from'] !== '0');
			$thumb = $news_array['thumbnail'];
			if($thumb == s3_url())
			{
				if ($is_from_page) 
				{
					$thumb = s3_url().'pages/default/defaultInterest/'.$news_array['category_id'].'.png';
				} 
				else 
				{	
					$thumb = s3_url().'users/default/defaultMale.png';			
				}
			}
			$can_comment = (($check_friends || $logger==true) || $in);
			//echo $page_id;
			$entity_id = ($page_id) ? $page_id : $this->uri->segment(2);
		?>
		<? $type = ($is_from_page) ? 'page' : 'user'; ?>
        <? // UPs column for pages ?>
        <? if ($view_type === 'page') { ?>
            <div class="post_entry_rank_placeholder inlinediv">
                <div class="post_entry_rank"><span class="num_ups"><?=count($news_array['likes'])?></span> ups</div>
                <img class="up_icon" src="images/up_icon.png" />
            </div>
        <? } ?>
        <div class="newsfeed_entry_avatar show_badge newsfeed_entry_avatar_<?=$type?> <?=$badge_type?>_avatar"><a href="<?=base_url().$news_array['url']?>"><img class="newsfeed_avatar_img" src="<?=$thumb?>" border="0"></a><div style="display:none" class="obj_id"><?=$link_id?></div></div>
        <div class="newsfeed_entry_content profile_newsfeed_entry_content">

            <? echo '<span class="newsfeed_poster">'.$news_array['link'].'</span> <span class="newsfeed_time">'.convert_datetime($news_array['ptime']).'</span>'?>
            <? if(isset($fvalue['page_name']) && $news_array['page_id_from'] != $news_array['page_id_to']){echo '<span class="newsfeed_postedfrom">to <a href="/interests/'.$fvalue['uri_name'].'/'.$fvalue['page_id_to'].'">'.$fvalue['page_name'].'</a></span>';}?>

            <? if(isset($news_array['receiver_link']))
            {
                if($news_array['user_id_to'] == $this->session->userdata['id'])
                {
                    echo '<span class="newsfeed_poster">to You</span>';
                }
                else
                {
                    echo '<span class="newsfeed_poster">to '.$news_array['receiver_link'].'</span>';
                }
            }?>
            <? if($fvalue['loop_name'])
            {
            echo 'From <a class="loop_'.$fvalue['loop_id'].'_tab" href="/loop/'.$fvalue['loop_id'].'">'.$fvalue['loop_name'].'</a>';
            } ?>
            <div class="newsfeed_entry_comment_text"><?=$news_array['post']?></div>

            <!--~~~~~~~~ List of Likes ~~~~~~~~~-->
            <?
                $for_post = 1;
                include('application/views/comment/up_view.php');
            ?>
            <? //$can_comment =true; ?>
            <!--~~~~~~~~~~~~~~~~~ Comments Options (Up, add comment, etc..) ~~~~~~~~~~~~~~~~~-->
            <div class="newsfeed_entry_options">
                <? if($can_comment && $fvalue['relation'] != 'followed') { ?>
                    <?  foreach ($news_array['likes'] as $like_key=>$like_value) {
                        if($like_value['user_id'] == $this->session->userdata['id']) {
                            $post_like =1;
                        }
                    } ?>

                        <?
                        $hide_up = '';
                        $hide_unup = '';
                        //echo $post_like;
                        if($post_like != 1)
                        {
                            $hide_unup = 'style="display:none"';
                        } else {
                            $hide_up = 'style="display:none"';
                        }
                            if($view_type == 'home')
                            {
                                if(isset($fvalue['page_name']))
                                { ?>
                                    <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['post_id']?>/post/0/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/home"><img class="up_icon" src="/images/up_icon.png"></a>
                                    <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/post/<?=$fvalue['newsfeed_id']?>/<?=$news_array['post_id']?>/page/home">undo Up</a> |
                            <?  }
                                else
                                {?>
                                    <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['post_id']?>/post/0/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/home"><img class="up_icon" src="/images/up_icon.png"></a>
                                    <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/post/<?=$fvalue['newsfeed_id']?>/<?=$news_array['post_id']?>/profile/home">undo Up</a> |
                            <?  }
                            }
                            else
                            { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['post_id']?>/post/<?=$entity_id?>/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                                <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/post/<?=$fvalue['newsfeed_id']?>/<?=$news_array['post_id']?>/<?=$view_type?>/<?=$entity_id?>">undo Up</a> |
                            <?
                            } ?>
                    <a href="" class="newsfeed_entry_add_comment_lnk" onclick="setTo('<?=$news_array['post_id']?>', 'post', '<? if($news_array['page_id_to']!=0){echo $news_array['page_id_to'];}else{echo $news_array['user_id_to'];}?>', '<? if($news_array['page_id_from']!=0){echo $news_array['page_id_from'];}else{echo $news_array['user_id_from'];}?>')">Add comment</a> |
                <? } ?>
                <a class="newsfeed_view_comments_lnk" href="">hide comments</a>
            </div>
			
            <!--~~~~~~~~ List of Comments ~~~~~~-->

            <? include('application/views/comment/comments.php'); ?>

        </div>
		<? 
		if($news_array['user_id_from']==$this->session->userdata['id'] || $news_array['user_id_to']==$this->session->userdata['id'])
		{
			if($view_type == 'profile')
			{
				echo '<div class="newsfeed_delete_icon"><a href="/del_post/'.$news_array['post_id'].'/'.$fvalue['newsfeed_id'].'/profile/'.$news_array['user_id_to'].'"><img src="/images/delete_icon.png" /></a></div>';
			}
			elseif($view_type == 'page')
			{
				echo '<div class="newsfeed_delete_icon"><a href="/del_post/'.$news_array['post_id'].'/'.$fvalue['newsfeed_id'].'/interests/'.$fvalue['page_id_to'].'"><img src="/images/delete_icon.png" /></a></div>';
			}
			else
			{
				echo '<div class="newsfeed_delete_icon"><a href="/del_post/'.$news_array['post_id'].'/'.$fvalue['newsfeed_id'].'/home'.'"><img src="/images/delete_icon.png" /></a></div>';
			}
		}
		?>
		<div class="clear"></div>
	</div>
<? } ?>

<?
if($fvalue['type']=='photo' || $fvalue['type']=='photo_comm' || $fvalue['type']=='photo_like' || $fvalue['type']=='photo_comm_like')
{ ?>
	<!--~~~~~~~~~~~~~~ News Feed Entry: Photos, Photo comments, Photo likes, Photo like comments ~~~~~~~~~~~-->
	<div class="profile_newsfeed_entry_photos newsfeed_photo_entry newsfeed_entry <?=$border_class?>">
		<?
			$is_from_page = ($news_array['page_id_from'] !== '0');
			$thumb = $news_array['thumbnail'];
			if($thumb == s3_url())
			{
				if ($is_from_page) 
				{
					$thumb = s3_url().'pages/default/defaultInterest/'.$news_array['category_id'].'.png';
				} 
				else 
				{	
					$thumb = s3_url().'users/default/defaultMale.png';			
				}
			}
			$can_comment = (($check_friends || $logger==true) || $in);
			$entity_id = ($page_id) ? $page_id : $profile_id;
		?>

		<? $type = ($is_from_page) ? 'page' : 'user'; ?>
        <? if ($view_type === 'page') { ?>
            <div class="post_entry_rank_placeholder inlinediv">
                <div class="post_entry_rank"><span class="num_ups"><?=count($news_array['likes'])?></span> ups</div>
                <img class="up_icon" src="images/up_icon.png" />
            </div>
        <? } ?>
		<div class="newsfeed_entry_avatar show_badge newsfeed_entry_avatar_<?=$type?> <?=$badge_type?>_avatar"><a href="<?=base_url().$news_array['url']?>"><img class="newsfeed_avatar_img" src="<?=$thumb?>" border="0"></a><div style="display:none;" class="obj_id"><?=$link_id?></div></div>
		<div class="newsfeed_entry_content <?=$view_type?>_newsfeed_entry_content">
			<div class="<?=$view_type?>_newsfeed_entry_photos_top newsfeed_entry_photos_top"> <!-- contains name/text -->
				<div class="newsfeed_entry_photos_titlepane"> <!-- contains Name of entry & actions -->
					
					<? echo '<span class="newsfeed_poster">'.$news_array['link'].'</span>'; ?>
					<? echo '<span class="newsfeed_time">'.convert_datetime($news_array['time']).'</span>'; ?>
					<? if(isset($fvalue['page_name']) && $news_array['page_id_from'] != $news_array['page_id_to']){echo 'to <span class="newsfeed_postedfrom"><a href="/interests/'.$fvalue['uri_name'].'/'.$fvalue['page_id_to'].'">'.$fvalue['page_name'].'</a></span>';}?>
					
					<? if(isset($news_array['receiver_link']))
					{ 
						if($news_array['user_id_to'] == $this->session->userdata['id'])
						{
							echo '<span class="newsfeed_poster">to You</span>';
						}
						else
						{
							echo '<span class="newsfeed_poster">to '.$news_array['receiver_link'].'</span>'; 
						}
					}?>
					<? if($fvalue['loop_name'])
					{
					echo 'From <a class="loop_'.$fvalue['loop_id'].'_tab" href="/loop/'.$fvalue['loop_id'].'">'.$fvalue['loop_name'].'</a>';
					} ?>				
					
				</div>
				<div class="newsfeed_entry_photos_text"> <!-- contains text of entry -->
					<?=$news_array['photo_caption']?>
				</div>

				<!--~~~~~~~~ List of Likes ~~~~~~~~~-->			
				<?
					$for_photo = 1; 
					include('application/views/comment/up_view.php'); 
				?>			

				<!--~~~~~~~~~~~~~~~~~ Comments Options (Up, add comment, etc..) ~~~~~~~~~~~~~~~~~-->
				<div class="newsfeed_entry_options"> <!-- options -->
					<? if($can_comment){?>
					
						<? 
						foreach ($news_array['likes'] as $like_key=>$like_value)
						{
							if($this->session->userdata['page_id'] && $like_value['type'] == 'page' && $like_value['page_id'] == $this->session->userdata['page_id'])
							{
								$post_like = 1;
							}
							if(!$this->session->userdata['page_id'] && $like_value['type'] == 'user' && $like_value['user_id'] == $this->session->userdata['id'])
							{
								$post_like = 1;
							}
							
						} 
						?>
						
                        <?
                        $hide_up = '';
                        $hide_unup = '';
                        if($post_like != 1)
						{
                            $hide_unup = 'style="display:none"';
                        } else {
                            $hide_up = 'style="display:none"';
                        }
                        if($view_type == 'home')
                        {
                            if(isset($fvalue['page_name']))
                            { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['photo_id']?>/photo/0/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                                <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/photo/<?=$fvalue['newsfeed_id']?>/<?=$news_array['photo_id']?>/page/home">undo Up</a> |
                            <? }
                            else
                            { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['photo_id']?>/photo/0/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                                <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/photo/<?=$fvalue['newsfeed_id']?>/<?=$news_array['photo_id']?>/profile/home">undo Up</a> |
                            <? }
                        }
                        else
                        { ?>
                            <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['photo_id']?>/photo/<?=$entity_id?>/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                            <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/photo/<?=$fvalue['newsfeed_id']?>/<?=$news_array['photo_id']?>/<?=$view_type?>/<?=$entity_id?>">undo Up</a> |
                        <? }
                        $post_like = 0;
						?>
						<a href="" class="newsfeed_entry_add_comment_lnk" onclick="setTo('<?=$news_array['photo_id']?>', 'photo', '<? if($news_array['page_id_from']!=0){echo $news_array['page_id_from'];}else{echo $news_array['user_id_from'];}?>', '<? if($news_array['page_id_from']!=0){echo $news_array['page_id_from'];}else{echo $news_array['user_id_from'];}?>')">Add comment</a> |
					<? } ?>
					<a class="newsfeed_view_comments_lnk" href="">hide comments</a> 
				</div>

				<!--~~~~~~~~ List of Comments ~~~~~~-->
				<? include('application/views/comment/comments.php'); ?>
			</div>
			<div class="newsfeed_entry_photo">
				<? if ($view_type === 'page') { ?>
					<a class="link_to_photo" target="_blank" href="/show_photo/<?=$news_array['photo_id']?>/page/<?=$news_array['page_id_to']?>/<?=$news_array['album_id']?>/<?=$news_array['album_id']?>">
                        <img class="newsfeed_entry_photos_pic" src='<?=s3_url()?>pages/<?=$news_array['page_id_to']?>/pics/<?=$news_array['album_id']?>/thumbs/<?=$news_array['photo_name']?>' style="position:relative;" >
                    </a>
				<? } 
				elseif($view_type == 'profile') { ?>
					<a class="link_to_photo" target="_blank" href="/show_photo/<?=$news_array['photo_id']?>/user/<?=$news_array['user_id_to']?>/<?=$news_array['album_id']?>/<?=$news_array['album_id']?>">
                        <img class="newsfeed_entry_photos_pic" src='<?=s3_url()?>users/<?=$news_array['user_id_to']?>/pics/<?=$news_array['album_id']?>/thumbs/<?=$news_array['photo_name']?>' style="position:relative;" >
					</a>
				<? } 
				else
				{
					if(isset($fvalue['page_name']))
					{ ?> 
						<a class="link_to_photo" target="_blank" href="/show_photo/<?=$news_array['photo_id']?>/page/<?=$news_array['page_id_to']?>/<?=$news_array['album_id']?>/<?=$news_array['album_id']?>"><img class="newsfeed_entry_photos_pic" src='<?=s3_url()?>pages/<?=$news_array['page_id_to']?>/pics/<?=$news_array['album_id']?>/thumbs/<?=$news_array['photo_name']?>' style="position:relative;" ></a>
					<? }
					else
					{
					?>
						<a class="link_to_photo" target="_blank" href="/show_photo/<?=$news_array['photo_id']?>/user/<?=$news_array['user_id_to']?>/<?=$news_array['album_id']?>/<?=$news_array['album_id']?>"><img class="newsfeed_entry_photos_pic" src='<?=s3_url()?>users/<?=$news_array['user_id_to']?>/pics/<?=$news_array['album_id']?>/thumbs/<?=$news_array['photo_name']?>' style="position:relative;" ></a>
					<?
					}
				}?>
			</div>
		</div>
		<? 
		if ($view_type == 'page') {
			if($news_array['page_id_from']==$this->session->userdata['page_id'] || $news_array['user_id_from']==$this->session->userdata['id'])
			{
				echo '<div class="page_newsfeed_entry_photos_action newsfeed_delete_icon inlinediv" ><a href="/del_photo/'.$news_array['photo_id'].'/'.$fvalue['newsfeed_id'].'/pages/'.$this->uri->segment(3).'/'.$this->uri->segment(2).'"><img src="/images/delete_icon.png" /></a></div>';
			}
		} 
		elseif($view_type == 'profile')
		{
			if($news_array['user_id_from']==$this->session->userdata['id'] || $news_array['user_id_to']==$this->session->userdata['id'])
			{
				echo '<div class="newsfeed_delete_icon inlinediv"><a href="/del_photo/'.$news_array['photo_id'].'/'.$fvalue['newsfeed_id'].'/users/'.$this->uri->segment(3).'/'.$uri_name.'/'.$loop_id.'"><img src="/images/delete_icon.png" /></a></div>';
			}
		}
		else
		{
			if($news_array['user_id_from']==$this->session->userdata['id'] || $news_array['user_id_to']==$this->session->userdata['id'])
			{
				echo '<div class="newsfeed_delete_icon inlinediv"><a href="/del_photo/'.$news_array['photo_id'].'/'.$fvalue['newsfeed_id'].'/home"><img src="/images/delete_icon.png" /></a></div>';
			}
		}
		?>	
		<div class="clear"></div>
	</div>
<? } ?>


<? if($fvalue['type']=='album' || $fvalue['type']=='album_comm') { ?>											
	<!--~~~~~~ News Feed Entry: Album & Album comments ~~~~~~~~-->
	<? 	if($news_array['page_info']['thumbnail'] == s3_url())
		{ 
			$thumb = s3_url().'pages/default/defaultInterest/'.$news_array['page_info']['category_id'].'.png'; 
		}
		else
		{ 
			$thumb = $news_array['page_info']['thumbnail']; 
		} ?>
	<div class="profile_newsfeed_entry_photos newsfeed_entry <?=$border_class?>">
		<div class="newsfeed_entry_avatar show_badge newsfeed_entry_avatar_page page_avatar"><a href="<?=base_url().$news_array['page_info']['url']?>"><img class="newsfeed_avatar_img" src="<?=$thumb?>" border="0"></a><div style="display:none;" class="obj_id"><?=$news_array['page_id']?></div></div>
			<div class="newsfeed_entry_content <?=$view_type?>_newsfeed_entry_content">
                <div class="newsfeed_entry_photos_titlepane"> <!-- contains Name of entry & actions -->
                    <? echo '<span class="newsfeed_poster">'.$news_array['page_info']['link'].'</span>'; ?>
                    <? echo '<span class="newsfeed_time">'.convert_datetime($news_array['time']).'</span>'; ?>
                </div>

                <div class="newsfeed_entry_photos_text"> <!-- contains text of entry -->
                    uploaded <?=count($news_array['photos'])?> new photos to the album <a href="/view_photos/<?=$news_array['album_id']?>/<?=$news_array['album_id']?>/page/<?=$news_array['page_id']?>"><?=$news_array['album_name']?></a>
                </div>
                <div>

                    <? $i = 0;
                    foreach($news_array['photos'] as $photo){
                        if($i < 3) {?>
                            <div class="page_album_img inlinediv">
                                <a class="link_to_photo" href="/show_photo/<?=$photo['photo_id']?>/page/<?=$news_array['page_id']?>/<?=$news_array['album_id']?>/<?=$news_array['album_id']?>">
                                    <img src="<?=s3_url().$photo['photo_url']?>" style="position:relative" >
                                </a>
                            </div>
                    <?	}
                    $i++;
                    } ?>
                    
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
	

	
<? } ?>
<? if($fvalue['type']=='link' || $fvalue['type']=='link_comm' || $fvalue['type']=='link_like' || $fvalue['type']=='link_comm_like') { ?>
        <? //print_r($fvalue) ?>
	<!--~~~~~~~~~~ News Feed Entry: Link, Link comments, Link likes, Link like comments ~~~~~~~~~~~~~~-->
	<div class="profile_newsfeed_entry newsfeed_text_entry newsfeed_entry <?=$border_class?>">
		<?
			$is_from_page = ($news_array['page_id_from'] !== '0');
			$thumb = $news_array['thumbnail'];
			if($thumb == s3_url())
			{
				if ($is_from_page)
				{
					$thumb = s3_url().'pages/default/defaultInterest/'.$news_array['category_id'].'.png';
				}
				else
				{
					$thumb = s3_url().'users/default/defaultMale.png';
				}
			}
			$can_comment = (($check_friends || $logger==true) || $in);
			//echo $page_id;
			$entity_id = ($page_id) ? $page_id : $this->uri->segment(2);
		?>
		<? $type = ($is_from_page) ? 'page' : 'user'; ?>
        <? // UPs column for pages ?>
        <? if ($view_type === 'page') { ?>
            <div class="post_entry_rank_placeholder inlinediv">
                <div class="post_entry_rank"><span class="num_ups"><?=count($news_array['likes'])?></span> ups</div>
                <img class="up_icon" src="images/up_icon.png" />
            </div>
        <? } ?>
        <div style="position: relative">
            <div class="newsfeed_entry_avatar show_badge newsfeed_entry_avatar_<?=$type?> <?=$badge_type?>_avatar"><a href="<?=base_url().$news_array['url']?>"><img class="newsfeed_avatar_img" src="<?=$thumb?>" border="0"></a><div style="display:none" class="obj_id"><?=$link_id?></div></div>
            <div class="newsfeed_entry_content profile_newsfeed_entry_content">

                <? echo '<span class="newsfeed_poster">'.$news_array['link'].'</span> <span class="newsfeed_time">'.convert_datetime($fvalue['time']).'</span>'?>

                <? if(isset($fvalue['page_name']) && $news_array['page_id_from'] != $news_array['page_id_to']){echo '<span class="newsfeed_postedfrom">to <a href="/interests/'.$fvalue['uri_name'].'/'.$fvalue['page_id_to'].'">'.$fvalue['page_name'].'</a></span>';}?>

                <? if(isset($news_array['receiver_link']))
                {
                    if($news_array['user_id_to'] == $this->session->userdata['id'])
                    {
                        echo '<span class="newsfeed_poster">to You</span>';
                    }
                    else
                    {
                        echo '<span class="newsfeed_poster">to '.$news_array['receiver_link'].'</span>';
                    }
                }?>
                <? if($fvalue['loop_name'])
                {
                echo 'From <a class="loop_'.$fvalue['loop_id'].'_tab" href="/loop/'.$fvalue['loop_id'].'">'.$fvalue['loop_name'].'</a>';
                } ?>

                <div class="newsfeed_entry_comment_text"><?=$news_array['text']?></div>

                <!--~~~~~~~~ List of Likes ~~~~~~~~~-->
                <?
                    $for_post = 1;
                    include('application/views/comment/up_view.php');
                ?>


                <div>
                    <div class="info link_info">
                        <div class="title">
                            <a href="<?=$news_array['link_url']?>"><?=$news_array['title'] ?></a>
                        </div>
                        <div class="desc">
                            <?=$news_array['content']?>
                        </div>
                    </div>
                    <div class="image link_image">
                        <img src="<?=$news_array['link_img']?>" style="max-width:260px;vertical-align:top">
                    </div>
                </div>

                <!--~~~~~~~~~~~~~~~~~ Comments Options (Up, add comment, etc..) ~~~~~~~~~~~~~~~~~-->
                <div class="newsfeed_entry_options">
                    <? if($can_comment && $fvalue['relation'] != 'followed') { ?>
                        <?  foreach ($news_array['likes'] as $like_key=>$like_value) {
                            if($like_value['user_id'] == $this->session->userdata['id']) {
                                $post_like =1;
                            }
                        } ?>

                            <?
                            $hide_up = '';
                            $hide_unup = '';
                            if($post_like != 1)
                            {
                                $hide_unup = 'style="display:none"';
                            } else {
                                $hide_up = 'style="display:none"';
                            }
                                if($view_type == 'home')
                                {
                                    if(isset($fvalue['page_name']))
                                    { ?>
                                        <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['link_id']?>/link/0/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/home"><img class="up_icon" src="/images/up_icon.png"></a>
                                        <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/link/<?=$fvalue['newsfeed_id']?>/<?=$news_array['link_id']?>/page/home">undo Up</a> |
                                <?  }
                                    else
                                    {?>
                                        <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['link_id']?>/link/0/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/home"><img class="up_icon" src="/images/up_icon.png"></a>
                                        <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/link/<?=$fvalue['newsfeed_id']?>/<?=$news_array['link_id']?>/profile/home">undo Up</a> |
                                <?  }
                                }
                                else
                                { ?>
                                    <a class="up_button" <?=$hide_up?> href="/like/<?=$news_array['link_id']?>/link/<?=$entity_id?>/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                                    <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/link/<?=$fvalue['newsfeed_id']?>/<?=$news_array['link_id']?>/<?=$view_type?>/<?=$entity_id?>">undo Up</a> |
                                <?
                                } ?>
                        <a href="" class="newsfeed_entry_add_comment_lnk" onclick="setTo('<?=$news_array['link_id']?>', 'link', '<? if($news_array['page_id_to']!=0){echo $news_array['page_id_to'];}else{echo $news_array['user_id_to'];}?>', '<? if($news_array['page_id_from']!=0){echo $news_array['page_id_from'];}else{echo $news_array['user_id_from'];}?>')">Add comment</a> |
                    <? } ?>
                    <a class="newsfeed_view_comments_lnk" href="">hide comments</a>
                </div>
                <!--~~~~~~~~ List of Comments ~~~~~~-->
                <? include('application/views/comment/comments.php'); ?>



            </div>

            <?
            if($news_array['user_id_from']==$this->session->userdata['id'] || $news_array['user_id_to']==$this->session->userdata['id'])
            {
                if($view_type == 'profile')
                {
                    echo '<div class="newsfeed_delete_icon"><a href="/del_link/'.$fvalue['newsfeed_id'].'/profile/'.$news_array['user_id_to'].'"><img src="/images/delete_icon.png" /></a></div>';
                }
                elseif($view_type == 'page')
                {
                    echo '<div class="newsfeed_delete_icon"><a href="/del_link/'.$fvalue['newsfeed_id'].'/interests/'.$fvalue['page_id_to'].'"><img src="/images/delete_icon.png" /></a></div>';
                }
                else
                {
                    echo '<div class="newsfeed_delete_icon"><a href="/del_link/'.$fvalue['newsfeed_id'].'/home'.'"><img src="/images/delete_icon.png" /></a></div>';
                }
            }
            ?>
        </div>

		<div class="clear"></div>
	</div>

<? } ?>

<script type="text/javascript">
    //This resizes the Rank column to fit the entire height of the newsfeed entry
$('.newsfeed_entry_add_comment').hide();
    $(window).load(function() {
        $('.post_entry_rank_placeholder').each(function() {
            $(this).height($(this).closest('.newsfeed_entry').height());
        });
    });
</script>

