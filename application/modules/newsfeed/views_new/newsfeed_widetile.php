<? 
$this->lang->load('newsfeed/newsfeed_views', LANGUAGE);

//$this->output->enable_profiler(true);
if ($newsfeed->type == 'link') {
	$this->load->model('link_model');
	$select_fields = array('link_id', 'title', 'user_id_from', 'link', 'img', 'img_width', 'img_height', 'text', 'media', 'source', 
							'users.id as `users:id`, users.avatar as `users:avatar`, users.uri_name as `users:uri_name`',
							'users.first_name as `users:first_name`, users.last_name as `users:last_name`'
					);
    if ($newsfeed->link_type=='text') $select_fields[] = 'content';
	$newsfeed->activity = $this->link_model->select_fields($select_fields)
			->with('user_from')
			->get($newsfeed->activity_id);
}
?>

<? 
$add_help = '';
if ($view === 'page' || $view === 'interest') {
	$add_help = 'title="" help="'.$this->lang->line('newsfeed_views_interest_add_help').'" pos_my="left bottom" pos_at="right center" helpgroup="interesttile"'; 
} else if ($view === 'folder') {
	$add_help = 'title="" help="'.$this->lang->line('newsfeed_views_folder_add_help').'" pos_my="bottom left" pos_at="top right" helpgroup="foldertile"';
}
?>	 

<? //$for_link = $for_photo = $for_comment = 0; ?>
<? $like_type = ''; ?>


<? //print_r($newsfeed); ?>
<? //INIT CONFIG VARS ?>
<? //print_r($newsfeed->type); ?>
<? $link_type = ($newsfeed->type === 'link' || $newsfeed->type === 'link_comm' || $newsfeed->type === 'link_like' || $newsfeed->type === 'link_comm_like' || $newsfeed->type === 'collect_link') ? $newsfeed->link_type : ''; ?>
<? $type = $newsfeed->type; ?>
<? $obj_id = $newsfeed->activity_id ?>
<? //END ?>



<? //print_r($newsfeed); ?>
<li class="widetile newsfeed_entry <?=$this->newsfeed_model->is_liked($newsfeed->newsfeed_id, $this->user) ? 'liked' : ''?>" rel="<?=$newsfeed->newsfeed_id?>" type="<?=$newsfeed->link_type?>" data-post_type="<?=$type?>" alt="<?=$newsfeed->activity_id?>" data-newsfeed_id="<?=$newsfeed->newsfeed_id?>" <?=$add_help?>>
	<div class="widetile_contents" rel="popup" class="pop_URL link-popup" <?=Html_helper::link_preview_popup_data($newsfeed)?>>
		<? if ($newsfeed->type === 'link' || $newsfeed->type == 'link_comm' || $newsfeed->type == 'link_like' || $newsfeed->type == 'link_comm_like' || $newsfeed->type == 'collect_link') { ?>			    
			<? if($newsfeed->link_type == 'embed') { ?>
				<div data-media="<?=$newsfeed->link_type=='embed' ? htmlentities($newsfeed->activity->media) : ''?>">
					<?=Html_helper::img("play_button.png", array('class'=>"play_button"))?>
				</div>
			<? } ?>
			<?=Html_helper::img($newsfeed->img_thumb, array('class'=>"thumb", 'data-width'=>$newsfeed->img_width, 'data-height'=>$newsfeed->img_height))?>
		<? } else if ($newsfeed->type == 'photo' || $newsfeed->type == 'photo_comm' || $newsfeed->type == 'photo_like' || $newsfeed->type == 'photo_comm_like' || $newsfeed->type == 'collect_photo') { ?>
			<?=Html_helper::img($newsfeed->img_thumb, array('class'=>"thumb", 'data-width'=>$newsfeed->img_width, 'data-height'=>$newsfeed->img_height))?>
		<? } ?>
	
		<div class="item_bottom">
			<div class="item_info">
				<?
				$caption = @$newsfeed->activity->text;
				$poster_name = (@$newsfeed->activity->user_from->id == @$profile_id) ?  @$newsfeed->activity->user_from->first_name : @$newsfeed->activity->user_from->first_name.' '.@$newsfeed->activity->user_from->last_name;
				$poster_name = '<a href="'.$newsfeed->user_from->url.'">'.$poster_name.'</a>';
				$middle_part = $this->lang->line('newsfeed_views_dropped_this_in_lexicon');
				if(isset($newsfeed->folder->folder_name)){ $folder_name=$newsfeed->folder->folder_name; }else{ $folder_name=null; }
				$folder_name = '<a href="'.@$newsfeed->folder->get_folder_url().'" class="folder-url" >'.$folder_name.'</a>';
				$full_str = $poster_name.' '.$middle_part.' '.$folder_name;
				?>
				<div class="post_caption"><?=$caption?></div>
				<div class="post_detail"><?=$full_str?></div>
				
			</div>
			<div class="item_actions">
				<div class="stat stat_comment inlinediv"><span class="num"><?=$newsfeed->comment_count?></span><span class="icon"></span></div>
				<div class="stat stat_redrops inlinediv"><span class="num"><?=$newsfeed->collect_count?></span><span class="icon"></span></div>
				<div class="stat stat_likes inlinediv"><span class="num"><?=$newsfeed->up_count?></span><span class="icon"></span></div>
			</div>
		</div>
	</div>
</li>
<?=Html_helper::requireJS(array("newsfeed/newsfeed"))?> 