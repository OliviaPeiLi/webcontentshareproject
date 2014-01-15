<?
/* Needs these variables in order to work properly:
 *  $cv
 *  $news_array
 *  $fvalue
 *  $thumb
 *  $can_comment
 *  $view_type
 *  $entity_id
 */
?>
<? //print_r($cv); ?>
		<div class="newsfeed_entry_comment">
			<div class="newsfeed_entry_comment_avatar"><a href="#"><img src="<?=$thumb?>" border="0" height="40px"></a></div>
			<div>
				<span class="user_name">
					<? echo $cv['link']; ?>
				</span>
				<? echo '<span class="newsfeed_time">'.convert_datetime($cv['ctime']).'</span>'; ?>
			</div>
			<?
			if($cv['user_id_from']==$this->session->userdata['id'] || $cv['user_id_to']==$this->session->userdata['id'])
			{
				echo '<div class="delete_comment"><a href="/del_comm/'.$cv['comment_id'].'/'.$fvalue['newsfeed_id'].'/'.$view_type.'/'.$entity_id.'"><img src="/images/delete_icon.png" /></a></div>';
			}
			?>
			<div class="newsfeed_entry_comment_text">
				<?=$cv['comment']?>
				<? if($cv['user_id_from'] != $this->session->userdata['id']){?>
				    <? if ($fvalue['type'] === 'photo' || $fvalue['type'] === 'photo_comm' || $fvalue['type'] === 'photo_like' || $fvalue['type'] === 'photo_comm_like') {?>
				        <input type='button' id='aaa' class="reply_button button" onclick="setReply('<?=$news_array['photo_id']?>', 'photo', '<?if($cv['page_id_from']!=0){echo $cv['page_id_from'];}else{echo $cv['user_id_from'];}?>', '<? if($cv['page_id_from']!=0){ echo 'page';} else {echo 'profile';}?>')" value = 'reply'>
				    <? } else if ($fvalue['type'] === 'post' || $fvalue['type'] === 'post_comm' || $fvalue['type'] === 'post_like' || $fvalue['type'] === 'post_comm_like') { ?>
				        <input type='button' id='aaa' class="reply_button button" onclick="setReply('<?=$news_array['post_id']?>', 'post', '<?if($cv['page_id_from']!=0){echo $cv['page_id_from'];}else{echo $cv['user_id_from'];}?>', '<? if($cv['page_id_from']!=0){ echo 'page';} else {echo 'profile';}?>')" value = 'reply'>
				    <? } ?>
				<? } ?>
			</div>
            <?
				$for_comment = 1;
				include('application/views/comment/up_view.php');
			?>
			<? if($can_comment && $fvalue['relation'] != 'followed') { ?>
				<div class="newsfeed_entry_comment_options">
					<?
					foreach ($cv['likes'] as $l_key=>$l_value)
					{
						if($this->session->userdata['page_id'] && $l_value['type'] == 'page' && $l_value['page_id'] == $this->session->userdata['page_id'])
						{
							$comm_like = 1;
						}
						if(!$this->session->userdata['page_id'] && $l_value['type'] == 'user' && $l_value['user_id'] == $this->session->userdata['id'])
						{
							$comm_like = 1;
						}
					}
                    $hide_up = '';
                    $hide_unup = '';
                    if($comm_like != 1)
                    {
                        $hide_unup = 'style="display:none"';
                    } else {
                        $hide_up = 'style="display:none"';
                    }
                    if($view_type == 'home')
                    {
                        if(isset($fvalue['page_name']))
                        {?>
                            <? if(isset($news_array['photo_id'])) { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/photo_comm/0/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                            <? } else if(isset($news_array['link_id'])) { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/link_comm/0/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                            <? } else { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/post_comm/0/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                            <? } ?>
                            <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/photo_comm/<?=$fvalue['newsfeed_id']?>/<?=$cv['comment_id']?>/page/home/<?=$entity_id?>">undo Up</a>
                        <? }
                        else
                        { ?>
                            <? if(isset($news_array['photo_id'])) { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/photo_comm/0/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                            <? } else if(isset($news_array['link_id'])) { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/link_comm/0/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                            <? } else { ?>
                                <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/post_comm/0/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                            <? } ?>
                            <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/photo_comm/<?=$fvalue['newsfeed_id']?>/<?=$cv['comment_id']?>/profile/home/<?=$entity_id?>">undo Up</a>
                        <? }
                    }
                    else
                    {?>
                        <? if(isset($news_array['photo_id'])) { ?>
                            <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/photo_comm/<?=$entity_id?>/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                        <? } else if(isset($news_array['link_id'])) { ?>
                            <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/link_comm/<?=$entity_id?>/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                        <? } else { ?>
                            <a class="up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/post_comm/<?=$entity_id?>/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>"><img class="up_icon" src="/images/up_icon.png"></a>
                        <? } ?>
                        <a class="undo_up_button" <?=$hide_unup?> href="/del_like/<?=$this->session->userdata['id']?>/photo_comm/<?=$fvalue['newsfeed_id']?>/<?=$cv['comment_id']?>/<?=$view_type?>/<?=$entity_id?>">undo Up</a>
                    <? } ?>
                    <? $comm_like = 0; ?>
                </div>
			<? } ?>
            <div class="clear"></div>
		</div>
        <div class="clear"></div>
