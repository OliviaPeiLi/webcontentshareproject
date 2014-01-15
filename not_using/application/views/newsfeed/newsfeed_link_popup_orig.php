<? $this->load->helper('page_helper'); ?>
<? $this->load->helper('user_helper'); ?>
<? if (empty($view_type) || $view_type === null) {
	$view_type = 'home';
}?>
<?
//$news_array=unserialize($fvalue['data']);
//$link_id = (empty($news_array['page_id_from'])) ? $news_array['user_id_from'] : $news_array['page_id_from'];
//$badge_type = (empty($news_array['page_id_from'])) ? 'user' : 'page';
$for_link = $for_phot = $for_comment = 0;
$obj_id = ($newsfeed->type=='photo' || $newsfeed->type=='photo_comm' || $newsfeed->type=='photo_like' || $newsfeed->type=='photo_comm_like' || $newsfeed->type=='collect_photo') ? $newsfeed->activity->photo_id : $newsfeed->activity->link_id;
$type = $newsfeed->type;
$link_type = ($newsfeed->type=='link' || $newsfeed->type=='link_comm' || $newsfeed->type=='link_like' || $newsfeed->type=='link_comm_like' || $newsfeed->type=='collect_link') ? $newsfeed->link_type : '';
?>


<? if($newsfeed->type=='link' || $newsfeed->type=='link_comm' || $newsfeed->type=='link_like' || $newsfeed->type=='link_comm_like' || $newsfeed->type=='collect_link') { ?>
    <? $for_link = 0; ?>
	<? //--~~~~~~~~~~ News Feed Entry: Link, Link comments, Link likes, Link like comments ~~~~~~~~~~~~~~ ?>
	<?		
		//Logic for likes (defining up/unup links here) 
        if(isset($newsfeed->page_id_to) && $newsfeed->page_id_to>0)
        {
            $up_link = '/like/'.$newsfeed->activity->link_id.'/link/0/'.$newsfeed->activity->link_id.'/'.$newsfeed->newsfeed_id.'/page/home';
            $unup_link = '/del_like/'.$this->session->userdata('id').'/link/'.$newsfeed->newsfeed_id.'/'.$newsfeed->activity->link_id.'/page/home';
    	}
        else
        {
            $up_link = '/like/'.$newsfeed->activity->link_id.'/link/0/'.$newsfeed->activity->link_id.'/'.$newsfeed->newsfeed_id.'/profile/home';
            $unup_link = '/del_like/'.$this->session->userdata('id').'/link/'.$newsfeed->newsfeed_id.'/'.$newsfeed->activity->link_id.'/profile/home';
    	}        

        if($can_comment) {
			$post_like = 0;
            if (isset($newsfeed->activity->likes))foreach ($newsfeed->activity->likes as $like) {
            	if($like->user_id == $this->session->userdata('id')) {
                    $post_like = 1;
                }
            }

      
            $hide_up = '';
            $hide_unup = '';
            if($post_like != 1)
            {
                $hide_unup = 'style="display:none"';
            } else {
                $hide_up = 'style="display:none"';
            }
		}

		//Logic for thumbnail avatar

        $is_from_page = ($newsfeed->page_id_from !== '0');
        $page_thumb = $newsfeed->page_to ? $newsfeed->page_to->thumb : s3_url()."pages/default/defaultInterest/1.png";//get_thumbnail($news_array['page_id_to'], $news_array['page_thumb'],'page',@$news_array['page_category_id']);
        $poster_thumb = $newsfeed->user_from->avatar_small;

		//echo $page_id;
		if(!isset($page_id)){ $page_id=null; }
		$entity_id = ($page_id) ? $page_id : $this->uri->segment(2);
		?>
		<? $type = ($is_from_page) ? 'page' : 'user'; ?>

	<? //echo 'TYPE='.$newsfeed->link_type; ?>
	<? //print_r($item); ?>
	<?php if ($newsfeed->link_type == 'text') { ?>
		<div id="newsfeed_link_content" data-type="text" style="min-width:200px;">
			<div id="newsfeed_link_content_text" class="textonly"><?=$newsfeed->activity->content?></div>
		</div>
	<? } else if ($newsfeed->link_type == 'screen') { ?>
		<div id="newsfeed_link_content" data-type="screen" style="width:66%; height: 100%">
			<div id="newsfeed_link_content_text"><?=$newsfeed->activity->content?></div>
			<img id="newsfeed_link_content_img" src="<?=$newsfeed->activity->image?>" style="max-width: 99%">
		</div>
	<? } else if ($newsfeed->link_type == 'image') { ?>
		<div id="newsfeed_link_content" data-type="image" style="">
			<img id="newsfeed_link_content_img" src="<?=$newsfeed->activity->thumb?>" style="">
			<div id="newsfeed_link_content_text"><?=$newsfeed->activity->content?></div>
		</div>
	<? } else if ($newsfeed->link_type == 'embed') { ?>
		<div id="newsfeed_link_content" data-type="embed" style="min-width:200px;">
			<div id="newsfeed_link_content_media">
				<?=$newsfeed->activity->media?>
			</div>
			<div id="newsfeed_link_content_text"><?=$newsfeed->activity->content?></div>
		</div>
	<? } else if ($newsfeed->link_type == 'content') { ?>
		<!-- <div style="display:none"><? print_r($newsfeed->activity); ?></div> -->
    <!-- <iframe id="newsfeed_link_popup_iframe" data-type="content" src="<?=$newsfeed->activity->link?>" style="width:1050px;"></iframe> -->
<?
			list($w, $h) = @getimagesize($newsfeed->activity->img); 
			//} catch (Exception $ex) {
			if (!$w) {
				$w=1050;
				$h=0;
      }
?>
		<img id="newsfeed_link_popup_screenshot_thumb" data-type="screen" src="<?=$newsfeed->activity->thumb?>" style="width:<?=$w?>px;height:auto !important">
		<img id="newsfeed_link_popup_screenshot" data-type="screen" data-imgw="<?=$w?>" data-imgh="<?=$h?>" src="<?=$newsfeed->activity->image?>" style="display:none; width:<?=$w?>px;height:auto !important">
	<? // Some apps like facebook do not provide embed tag, so we have to handle link to video inside iframe ?>
	<? } else if ($newsfeed->link_type == 'media_link') { ?>
		<iframe id="newsfeed_link_popup_iframe" class="youtube-player" type="text/html" frameborder="0"data-type="media_link" src="<?=$newsfeed->activity->media?>" style="width:1050px;"></iframe>
		<? /* ?>
		<img id="newsfeed_link_popup_screenshot_thumb" data-type="html" src="<?=$newsfeed->activity->thumb?>" style="width:<?=$w?>px;height:auto !important">
		<img id="newsfeed_link_popup_screenshot" data-type="html" data-imgw="<?=$w?>" data-imgh="<?=$h?>" src="<?=$newsfeed->activity->image?>" style="display:none; width:<?=$w?>px;height:auto !important">
		<? */ ?>
	<? } else { // html?>
		<? if (isset($newsfeed)) { ?>
			<?
			//try { 
			//print_r($newsfeed->activity->img);
			list($w, $h) = @getimagesize($newsfeed->activity->img); 
			//} catch (Exception $ex) {
			if (!$w) {
				$w=1050;
				$h=0;
			}
			?>
			<div style="display:none"><? print_r($newsfeed->activity); ?></div>
			<img id="newsfeed_link_popup_iframe_thumb" data-type="html" data-imgw="<?=$w?>" data-imgh="<?=$h?>" src="<?=$newsfeed->activity->thumb?>" style="width:<?=$w?>px;height:auto !important">
			<iframe id="newsfeed_link_popup_iframe" data-type="html" data-imgw="<?=$w?>" data-imgh="<?=$h?>" src="/bookmarklet/snapshot_preview/<?=$newsfeed->newsfeed_id?>" style="width:1050px;display:none;"></iframe>
		<? } else { ?>
			Invalid data
		<? } ?>
	<? } ?>

	<?php $title =
		'<a href="'.base_url().'drop/'.$newsfeed->newsfeed_id.'" class="pop_URL" target="_blank">'.base_url().'drop/'.$newsfeed->newsfeed_id.'</a>'.
		'<a href="'.$newsfeed->activity->link.'" target="_blank" id="go_to_site" class="blue_bg">Go to Site</a>'
	?>
	<? $for_link = 1; ?>

<? } else if ($newsfeed->type=='photo' || $newsfeed->type=='photo_comm' || $newsfeed->type=='photo_like' || $newsfeed->type=='photo_comm_like' || $newsfeed->type=='collect_photo') { ?>

    <? $for_photo = 0; ?>
	<? //--~~~~~~~~~~ News Feed Entry: Link, Link comments, Link likes, Link like comments ~~~~~~~~~~~~~~ ?>
	<?		
		//Logic for likes (defining up/unup links here) 
        if(isset($newsfeed->page_id_to) && $newsfeed->page_id_to>0)
        {
            $up_link = '/like/'.$newsfeed->activity->photo_id.'/photo/0/'.$newsfeed->activity->photo_id.'/'.$newsfeed->newsfeed_id.'/page/home';
            $unup_link = '/del_like/'.$this->session->userdata('id').'/photo/'.$newsfeed->newsfeed_id.'/'.$newsfeed->activity->photo_id.'/page/home';
    	}
        else
        {
            $up_link = '/like/'.$newsfeed->activity->photo_id.'/photo/0/'.$newsfeed->activity->photo_id.'/'.$newsfeed->newsfeed_id.'/profile/home';
            $unup_link = '/del_like/'.$this->session->userdata('id').'/photo/'.$newsfeed->newsfeed_id.'/'.$newsfeed->activity->photo_id.'/profile/home';
    	}        

        if($can_comment) {
			$post_like = 0;
            if (isset($newsfeed->activity->likes))foreach ($newsfeed->activity->likes as $like) {
            	if($like->user_id == $this->session->userdata('id')) {
                    $post_like = 1;
                }
            }

      
            $hide_up = '';
            $hide_unup = '';
            if($post_like != 1)
            {
                $hide_unup = 'style="display:none"';
            } else {
                $hide_up = 'style="display:none"';
            }
		}

		//Logic for thumbnail avatar

        $is_from_page = ($newsfeed->page_id_from !== '0');
        $page_thumb = $newsfeed->page_to ? $newsfeed->page_to->thumb : s3_url()."pages/default/defaultInterest/1.png";//get_thumbnail($news_array['page_id_to'], $news_array['page_thumb'],'page',@$news_array['page_category_id']);
        $poster_thumb = $newsfeed->user_from->avatar_small;

		//echo $page_id;
		if(!isset($page_id)){ $page_id=null; }
		$entity_id = ($page_id) ? $page_id : $this->uri->segment(2);
		?>
		<? $type = ($is_from_page) ? 'page' : 'user'; ?>

	<? //echo 'TYPE='.$newsfeed->link_type; ?>
	<? //print_r($item); ?>
		<div id="newsfeed_link_content" data-type="image" style="">
			<img id="newsfeed_link_content_img" src="<?=$newsfeed->activity->full_img?>" style="">
			<div id="newsfeed_link_content_text"><?=$newsfeed->activity->caption?></div>
		</div>

	<?php $title =
		'<a href="'.base_url().'drop/'.$newsfeed->newsfeed_id.'" class="pop_URL" target="_blank">'.base_url().'drop/'.$newsfeed->newsfeed_id.'</a>'
	?>
	<? $for_photo = 1; ?>

<? } ?>
<div id="notification_container" class="newsfeed_link_popup_comments newsfeed_entry" <? if ($link_type != 'screen') { echo 'style="min-width:400px;"'; }?>>
	<div id="popup_bar">
		<a id="close_link_popup"></a>
		<div id="toggle_comments" class="hide_comments"></div>
	</div>
	
	
	<? //print_r(unserialize($fvalue['data'])); ?>
	<div id="popup_comments" rel="<?=$newsfeed->newsfeed_id?>">
		<div id="permalinks"><?=$title?></div>
		<div class="link_poster">
			<!-- User's avatar (profile picture) -->
			<? //print_r($user_info); ?>
			<div class="avatar_col" style="display:inline-block; float:left;">
				<a href="<?=base_url()?>profile/<?=$newsfeed->user_from->uri_name?>/<?=$newsfeed->user_from->id?>"><img class="avatar" src="<?=$newsfeed->user_from->avatar_small?>" alt="" style="position:relative" /></a>
			</div>
			<!-- Username and Timestamp -->
			
			<div class="post_info inlinediv"> <? //var_dump($news_array); ?>
				<? if ($newsfeed->page_id_to) { ?>
					<p class="posted_to" data-pageid="<?=$newsfeed->page_id_to?>">Posted to <a href="/<? if($newsfeed->page_to->official_url != ''){ echo $newsfeed->page_to->official_url; }else{ echo '/interests/'.$newsfeed->page_to->uri_name.'/'.$newsfeed->page_to->page_id; } ?>"><?=$newsfeed->page_to->page_name?></a></p>
			    <? } else { ?>
					<p class="posted_to" data-pageid="<?=$newsfeed->page_id_to?>">Dropped in <a href="/collection/<?=$newsfeed->user_from->uri_name?>/<?=parse_url_string($newsfeed->folder->folder_name)?>/<?=@$newsfeed->folder->folder_id;?>"><?=@$newsfeed->folder->folder_name?><? if($newsfeed->folder->private == '1'){ echo ' (Draft)'; } ?></a></p>
			    <? } ?>
			    
			    <p class="posted_by_when">By <a href="<?=base_url()?>profile/<?=$newsfeed->user_from->uri_name?>/<?=$newsfeed->user_from->id?>"><?=$newsfeed->user_from->first_name?> <?=$newsfeed->user_from->last_name?></a><small class="posted_when"><?=@convert_datetime($newsfeed->activity->time)?></small></p>
			    <!-- Status -->
			    <? /* <p class="status"><a href="<?=@$news_array['link_url']?>"><?=$news_title?></a></p> */ ?>
			</div>
			<div class="clear"></div>
		</div>
	
	
		<? //Likes ?>
	    <div class="like_text_container">
		    <?
		        include('application/modules/comment/views/up_view.php');
		    ?>		    
	    </div>
	    <? //echo $can_comment; ?>
	    <? //Comment options ?>
	    <div id="newsfeed_link_popup_options">
	    	<? //echo $can_comment; ?>
	        <? if($can_comment) { ?>
	        	<div class="inlinediv">
		            <a class="up up_button" <?=@$hide_up?> href="<?=$up_link?>">Like</a>
		            <a class="up undo_up_button" <?=@$hide_unup?> href="<?=$unup_link?>">Liked</a>
				</div>
			<? } ?>
	    	<? if ($can_comment) { ?>
				<div id="newsfeed_link_popup_newcomment_lnk" class="inlinediv" style="display:none">
					<a href="">Add Comment</a>
				</div> 
			<? } ?>
			<div class="comm_count inlinediv">Comments (<?=$newsfeed->comment_count?>)</div>
			<div id="newsfeed_link_popup_collect_lnk" class="inlinediv">
				<a href="#collect_popup" class="newsfeed_collect_lnk" rel="popup" data-group="collect_dialog" data-type="link" data-id="<?=$obj_id?>" title="Redrop">Redrop</a>
			</div>
			
		</div>
		<? //comments ?>
		<div class="newsfeed_item_comments">
			<? include('application/modules/comment/views/comments.php'); ?>

		</div>
	</div>
</div>
<? include('application/modules/folder/views/collect.php'); ?>
<?=requireJS(array("jquery","newsfeed/newsfeed_link_popup"))?>