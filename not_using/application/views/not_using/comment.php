<? //echo 'aaa'; ?>
<? //echo $this->session->userdata('id').'---'; ?>
<? 
if (!isset($this)) {
	$session = $ci->session;
	//$ci->load->helper('time_ago_helper');
} else {
	$session = $this->session;
}
//echo $session->userdata('id');
?>
<? //echo $ci->session->userdata('id').'^^^'; ?>
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
<? $for_comment = 0; ?>

<?
//if ($view_type === 'user') {
//	$badge_type = 'user';
//} else {
//	$badge_type = 'page';
//}
?>
<? $badge_type = 'user'; ?>



<?  ?>
<div class="newsfeed_entry_comment" rel="<?=$cv['comment_id']?>">
	<?
	if($cv['user_id_from']==$session->userdata['id'] || $cv['user_id_to']==$session->userdata['id'])
	{
        echo '<a class="close delete_comment" href="/del_comm/'.$cv['comment_id'].'/'.$fvalue['newsfeed_id'].'/'.$view_type.'/'.$entity_id.'">Delete</a>';
	}
	?>
	<div class="newsfeed_entry_comment_avatar inlinediv show_badge <?=$badge_type?>_avatar">
		<a href="#"><img class="newsfeed_comment_avatar_img" src="<?=$thumb?>"></a>
		<div style="display:none;" class="obj_id"><?=$cv['user_id_from']?></div>
	</div>

	<? if ($view_type === 'page' && $news_array['page_id_to'] && $cv['point']) { ?>
    	<!--<span title="Reputation Score" class="point"><?=$cv['point']?></span>-->
    <? } ?>

	<div class="comment_info">
		<span class="user_name">
			<? echo $cv['link']; ?>
		</span>
		<? echo '<span class="newsfeed_time">'.convert_datetime($cv['ctime']).'</span>'; ?>
	</div>

	<p class="comment_content">
		<?=$cv['comment']?>
	</p>
    <?
		$for_comment = 1;
		include('application/views/comment/up_view.php');
	?>

	<? //if($can_comment && $fvalue['relation'] != 'followed') { ?>

		<div class="newsfeed_entry_comment_options">
			<? //$session->userdata['page_id']; ?>
							<?  ?>
			<?
			foreach ($cv['likes'] as $l_key=>$l_value)
			{
				if($session->userdata['page_id'] && $l_value['type'] == 'page' && $l_value['page_id'] == $session->userdata['page_id'])
				{
					$comm_like = 1;
				}
				if(!$session->userdata['page_id'] && $l_value['type'] == 'user' && $l_value['user_id'] == $session->userdata['id'])
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
            
            
            if ($in || $view_type === 'topic') {
            
	            if($session->userdata['id'] && $view_type === 'home')
	            {
	            	
	                //if(isset($fvalue['page_name']) )
	                if($fvalue['page_id_to']>0)
	                {?>
	                <?  ?>
	                    <? if(isset($news_array['photo_id'])) { ?>
	                        <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/photo_comm/0/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/<?=$view_type?>">UP</a>
	                    <? } else if(isset($news_array['link_id'])) { ?>
	                        <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/link_comm/0/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/<?=$view_type?>">UP</a>
	                    <? } else { ?>
	                        <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/post_comm/0/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/page/<?=$view_type?>">UP</a>
	                    <? } ?>
	                    <a class="up undo_up_button" <?=$hide_unup?> href="/del_like/<?=$session->userdata['id']?>/photo_comm/<?=$fvalue['newsfeed_id']?>/<?=$cv['comment_id']?>/page/home/<?=$entity_id?>">Undo UP</a>
	                <?  ?>
	                <? }
	                else
	                { ?>
	                <?  ?>
	                    <? if(isset($news_array['photo_id'])) { ?>
	                        <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/photo_comm/0/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/<?=$view_type?>">UP</a>
	                    <? } else if(isset($news_array['link_id'])) { ?>
	                        <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/link_comm/0/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/<?=$view_type?>">UP</a>
	                    <? } else { ?>
	                        <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/post_comm/0/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/profile/<?=$view_type?>">UP</a>
	                    <? } ?>
	                    <a class="up undo_up_button" <?=$hide_unup?> href="/del_like/<?=$session->userdata['id']?>/photo_comm/<?=$fvalue['newsfeed_id']?>/<?=$cv['comment_id']?>/profile/home/<?=$entity_id?>">Undo UP</a>
	                <?  ?>
	                <? }
	                
	                
	            }
	            else
	            {  ?>
	            	<?  ?>
	                <? if(isset($news_array['photo_id'])) { ?>
	                    <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/photo_comm/<?=$entity_id?>/<? echo $news_array['photo_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>">UP</a>
	                <? } else if(isset($news_array['link_id'])) { ?>
	                    <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/link_comm/<?=$entity_id?>/<? echo $news_array['link_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>">UP</a>
	                <? } else { ?>
	                    <a class="up up_button" <?=$hide_up?> href="/like/<?=$cv['comment_id']?>/post_comm/<?=$entity_id?>/<? echo $news_array['post_id']; ?>/<?=$fvalue['newsfeed_id']?>/<?=$view_type?>">UP</a>
	                <? } ?>
	                <a class="up undo_up_button" <?=$hide_unup?> href="/del_like/<?=$session->userdata['id']?>/photo_comm/<?=$fvalue['newsfeed_id']?>/<?=$cv['comment_id']?>/<?=$view_type?>/<?=$entity_id?>">Undo UP</a>
	            	<?  ?>
	            <?  } ?>
	            <?  ?>
            <? } ?>
             <?  ?>
            <? $comm_like = 0; ?>
            <? //Reply ?>
            <? //if($view_type === 'interest') { ?>
            <? //Comments are not collapsible if not under threaded comment system ?>
            
            <? if ($hierarchial_comments)  { ?>
            	<? //print_r($in); ?>
            	<?  ?>
            	<? if ($in || $view_type === 'topic') { ?>
            		<a class="add reply_to_comment_lnk">Reply</a>
            	<? } ?>
            	<?  ?>
            	<? if ($num_children > 0) { ?>
            		<a class="hide see_children_comments_lnk">Hide Replies</a>
            	<? } ?>
            <? } ?>	
            
            <? //Different behavior of reply for a non-threaded comment system ?>
			<? //if(!$page_id && $cv['user_id_from'] != $session->userdata['id']){?>
			<? if(!$hierarchial_comments){?>
			    <? if ($fvalue['type'] === 'photo' || $fvalue['type'] === 'photo_comm' || $fvalue['type'] === 'photo_like' || $fvalue['type'] === 'photo_comm_like') {?>
			        <a id='aaa' class="add reply_button" onclick="setReply('<?=$news_array['photo_id']?>', 'photo', '<?if($cv['page_id_from']!=0){echo $cv['page_id_from'];}else{echo $cv['user_id_from'];}?>', '<? if($cv['page_id_from']!=0){ echo 'page';} else {echo 'profile';}?>')">Reply</a>
			    <? } else if ($fvalue['type'] === 'post' || $fvalue['type'] === 'post_comm' || $fvalue['type'] === 'post_like' || $fvalue['type'] === 'post_comm_like') { ?>
			        <a id='aaa' class="add reply_button" onclick="setReply('<?=$news_array['post_id']?>', 'post', '<?if($cv['page_id_from']!=0){echo $cv['page_id_from'];}else{echo $cv['user_id_from'];}?>', '<? if($cv['page_id_from']!=0){ echo 'page';} else {echo 'profile';}?>')">Reply</a>
			    <? } ?>
			<? } ?>
        	<?  ?>            
        </div>

	<? //} ?>

    <div class="clear"></div>
</div>
<div class="clear"></div>

<? if ($comment_added === '1' && $hierarchial_comments) {
	echo '<div class="add_comment_reply_box" style="display:none">';
	echo form_open('add_comment');
	echo Form_Helper::form_input('comm_msg','','class="reply_textbox"');
	echo form_submit('submit','Reply','class="add_child_comment blue_bg" rel="'.$cv['comment_id'].'"');
	echo form_close();
	echo '</div>';					

} ?>


<? //echo 'comment='.$comment_added; ?>
<? if ($comment_added === '1') { ?>
	<script type="text/javascript">
		$(function() {
			//alert('hoooo');
		    var hoverintent_config = {
		        over: show_badge,
		        timeout:200,
		        interval: 300,
		        out: hide_badge
		    };
			$('.show_badge').hoverIntent(hoverintent_config);
		});
	</script>
<? } ?>

<?  ?>