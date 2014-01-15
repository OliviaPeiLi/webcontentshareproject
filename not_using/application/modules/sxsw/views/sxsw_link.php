<? $this->load->helper('page_helper'); ?>
<? $this->load->helper('user_helper'); ?>

<?
$link_id = $item->id;
?>


	<?  ?>
    <? $for_link = 0; ?>
	<? //--~~~~~~~~~~ News Feed Entry: Link, Link comments, Link likes, Link like comments ~~~~~~~~~~~~~~ ?>
<?
	$link_like = 0;
	$likes = $item->sxsw_likes;
	if (isset($likes)) {
		$likes = $item->sxsw_likes;
    	foreach ($likes as $like_key=>$like_value) {
            if($this->session->userdata('sxsw_id') && $like_value->user_id == $this->session->userdata('sxsw_id')) {
                $link_like = 1;
            }
        }
    }
    $hide_up = '';
    $hide_unup = '';
    if($link_like != 1)
    {
        $hide_unup = 'style="display:none"';
    } else {
        $hide_up = 'style="display:none"';
    }
?>

<? //echo 'TYPE='.$item->link_type; ?>
<? //print_r($item); ?>
<? if (!isset($item->link_url) || $item->link_url === '') { ?>
	<div id="newsfeed_link_content" data-type="image" style="">
		<img id="newsfeed_link_content_img" src="<?=$item->image?>" style="">
		<div id="newsfeed_link_content_text"><?=stripslashes($item->title)?></div>
	</div>
<? } else { ?>
	<iframe id="newsfeed_link_popup_iframe" data-type="content" src="<?=$item->link_url?>" style="width:1050px;"></iframe>
<? } ?>


<?  ?>

<?php $title =
	'<a href="'.base_url().'sxsw/extlink/'.$item->uniqid.'" class="pop_URL" target="_blank">'.base_url().'sxsw/extlink/'.$item->uniqid.'</a>';
?>
<?  ?>
<? //$title=''; ?>

<div id="notification_container" class="newsfeed_link_popup_comments newsfeed_entry" style="min-width:400px;">
	<div id="popup_bar">
		<a id="close_link_popup"></a>
		<div id="toggle_comments" class="hide_comments"></div>
	</div>
	
	
	<? //print_r(unserialize($fvalue['data'])); ?>
	<div id="popup_comments">
		<div id="item_title"><?=stripslashes($item->title)?></div>
		<div id="permalinks"><?=$title?></div>
		<div class="link_poster">
			<!-- User's avatar (profile picture) -->
			<? //print_r($user_info); ?>
			<div class="avatar_col" style="display:inline-block; float:left;">
				<a href="<?=get_external_user_link($item->sxsw_user->id)?>" ><img class="avatar" src="<?=$item->sxsw_user->thumbnail?>" alt="" style="position:relative" /></a>
			</div>
			<!-- Username and Timestamp -->
			
			<div class="post_info inlinediv"> <? //var_dump($news_array); ?>
			    <p class="posted_by_when">By <a href="<?=get_external_user_link($item->sxsw_user->id)?>" ><?=$item->sxsw_user->first_name.' '.$item->sxsw_user->last_name?></a><small class="posted_when"><?=@convert_datetime($item->time)?></small></p>
			</div>
			<div class="clear"></div>
		</div>

	
	
		<? //Likes ?>
	    <div class="like_text_container">
		<? 
		//print_r($item->sxsw_likes);
		if (isset($likes) && count($likes) > 0) {
	        $for_link = 1;
	        include('application/modules/sxsw/views/sxsw_up_view.php');
		}
		?>	    
	    </div>
	    <? //echo $can_comment; ?>
	    <? //Comment options ?>
	    <div id="newsfeed_link_popup_options">
        	<div class="inlinediv">
	            <a class="up up_button" data-ispopup="1" <?=@$hide_up?> href="/sxsw_like">Like</a>
	            <a class="up undo_up_button" data-ispopup="1" <?=@$hide_unup?> href="/sxsw_del_like">Liked</a>
			</div>
			<div id="newsfeed_link_popup_newcomment_lnk" class="inlinediv" style="display:none">
				<a href="">Add Comment</a>
			</div> 
			<div rel="<?=$item->comment_count?>" class="comm_count inlinediv">Comments (<?=$item->comment_count?>)</div>
		</div>
		
		<? //New Comment ?>
		
		<?  ?>
		 <div class="newsfeed_entry_add_comment" style="display: block;">
			<?php echo validation_errors(); ?>
			<?php echo form_open('sxsw_comment'); ?>
				<?php echo form_hidden('link_id', $item->id); ?>
				<? if($this->session->userdata('sxsw_id')) {
					$thumb = $this->sxsw_user_model->get($this->session->userdata('sxsw_id'))->thumbnail;
				} else {
					$thumb = 'https://s3.amazonaws.com/fantoon-dev/users/default/defaultMale.png';
				}
				?>
					<div class="inlinediv new_comment_avatar">
						<img src="<?=$thumb?>" width="25" height="25">
					</div>
					<div class="comment_input_container">
						<? echo Form_Helper::form_input('comm_msg', set_value('comm_msg', ''), 'class="reply_comm input_placeholder" placeholder="Write a Comment..."');?>
					</div>
					<div class="comment_submit_container">
						<? echo form_submit('submit', 'Comment', 'class="comment_submit blue_bg" data-ispopup="1" style="display:none"'); ?>
					</div>

			<?  echo form_close();?>
			    
		</div>
		<?  ?>
		
		<? //comments ?>
		<div class="newsfeed_item_comments">
			
			<? 
			$comments = $item->get('sxsw_comments')->order_by('time', 'DESC')->get_all();;
			foreach($comments as $ck => $cv) { ?>
				<? 
				$for_comment = 0; 

				$comm_like = 0;
				$comm_likes = $cv->sxsw_comment_likes;
				
				if (isset($comm_likes)) {
			    	foreach ($comm_likes as $comm_like_key=>$comm_like_value) {
			    		//print_r($comm_like_value->user_id);
			            if($this->session->userdata('sxsw_id') && $comm_like_value->user_id == $this->session->userdata('sxsw_id')) {
			                $comm_like = 1;
			            }
			        }
			    }
			    $comm_hide_up = '';
			    $comm_hide_unup = '';
			    if($comm_like != 1)
			    {
			        $comm_hide_unup = 'style="display:none"';
			    } else {
			        $comm_hide_up = 'style="display:none"';
			    }

				?>

				<div class="newsfeed_entry_comment" rel="<?=$cv->id?>">
					<?
					$uid = $this->session->userdata('sxsw_id') ? $this->session->userdata('sxsw_id') : 0;
					
					if($cv->user_id === $uid)
					{
						
				        echo '<a class="close delete_comment" href="/sxsw_del_comm">Delete</a>';
					}
					?>
					<div class="newsfeed_entry_comment_avatar inlinediv">
						<a href="<?=get_external_user_link($cv->sxsw_user->id)?>" ><img class="newsfeed_comment_avatar_img" src="<?=$cv->sxsw_user->thumbnail?>"></a>
						<div style="display:none;" class="obj_id"><?=$cv->user_id?></div>
					</div>
				
					<div class="comment_info">
						<span class="user_name">
							<a href="<?=get_external_user_link($cv->sxsw_user->id)?>" >
								<? echo $cv->sxsw_user->first_name.' '.$cv->sxsw_user->last_name; ?>
							</a>
						</span>
						<? echo '<span class="newsfeed_time">'.convert_datetime($cv->time).'</span>'; ?>
					</div>
				
					<p class="comment_content">
						<?=stripslashes($cv->comment)?>
					</p>
				    <?
						$for_comment = 1;
						$for_link = NULL;
						include('application/modules/sxsw/views/sxsw_up_view.php');
						
					?>
				
				
					<div class="newsfeed_entry_comment_options">
		                <a class="up up_button" data-ispopup="1" <?=$comm_hide_up?> href="/sxsw_comm_like">Like</a>
		                <a class="up undo_up_button" data-ispopup="1" <?=$comm_hide_unup?> href="/sxsw_comm_del_like">Liked</a>
			        </div>
				
				
				    <div class="clear"></div>
				</div>
				<div class="clear"></div>
				
			<? } ?>

		</div>


	</div>
</div>
<?  ?>

<script type="text/javascript">
	require(["jquery","sxsw/sxsw_link_popup"], function ($) {
	});
</script>
