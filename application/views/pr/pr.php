<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/pr/pr.php ) --> ' . "\n";
} ?>
<? $this->lang->load('pr/pr_views', LANGUAGE); ?>
<!-- <link rel="stylesheet" href="<?=base_url()?>css/facealike.css" type="text/css"> -->

<script type="text/javascript">
	php.segment2 = '<?=$this->uri->segment(2)?>';
</script>
<?=requireJS(array("jquery","pr/pr"))?>

<div id="pr_main">
	<div class="clear"></div>

	<h2><?=$this->lang->line('pr_views_press_release_title');?></h2>

	<div align="center">
		<div class="clear"></div>

		<? if(!$this->uri->segment(3)) {?>
			<? if($owner_priv == 1 || $admin_priv == 1) {
				if($pr_lock == 0) { ?>
					<div><a href="/pr_lock/<?=$this->uri->segment(2);?>/1"><?=$this->lang->line('pr_views_lock_prs_btn');?></a></div>
				<? } else { ?>
					<div><a href="/pr_lock/<?=$this->uri->segment(2);?>/0"><?=$this->lang->line('pr_views_unlock_prs_btn');?></a></div>
				<? } ?>
			<? }?>

			<? if($pr_lock == 0) { ?>
				<div class="box" align="left">
					<div class="head"><?=$this->lang->line('pr_views_link_lexicon');?></div>
					<div class="close" align="right">
						<div class="closes"></div>
					</div>
					<? if($pr_error){echo $pr_error;}?>
					<input type="text" name="url" size="64" id="url">&nbsp;&nbsp;&nbsp;<input type="button" id="submit" value="<?=$this->lang->line('pr_views_submit_lexicon');?>">
					<? echo form_open('new_pr'); ?>
					<div id="loader">
						<div align="center" id="load" style="display:none"><img src="/images/load.gif" /></div>
					</div>
					<? echo form_close();?>
				</div>
			<? } ?>
		<? }?>
	</div>

	<ul>
		<? 
		$i=1;
		foreach($prs as $key => $value) {
			$news_array=unserialize($value['data']);
			?>
		
			<div class="newsfeed_entry_content">
				<li>
					<?=$i?>. <a href="<?=$value['source_link'];?>"><?=$value['source_title'];?></a> 
					(<?=$this->lang->line('pr_views_by_lexicon');?> <a href="/profile/<?=$value['uri_name']?>/<?=$value['user_id']?>"> <?=$value['first_name'].' '.$value['last_name'];?></a>) <?=$value['source_date'];?>
					<a href="/pr_page/<?=$value['page_id']?>/<?=$value['newsfeed_id']?>"> <?=$this->lang->line('pr_views_detail_btn');?></a>
				</li>
				<? if($owner_priv == 1 || $admin_priv == 1){?><li><a href="/del_pr/<? echo $this->uri->segment(2).'/'.$value['pr_id']?>"><?=$this->lang->line('pr_views_delete_pr_btn');?></a></li><? }?> 
		
				<div class="newsfeed_entry_options">
				
				<? 
				foreach ($news_array['likes'] as $like_key=>$like_value)
				{
					if($like_value['user_id'] == $this->session->userdata['id'])
					{
						$pr_like =1;
					}
				}
				?>
				
				<? if($pr_like != 1)
				{ ?>
					<a href="/like/<?=$news_array['pr_id']?>/pr/<?=$news_array['page_id_from']?>/<? echo $news_array['pr_id']; ?>/<?=$value['newsfeed_id']?>/<? echo 'page';?>/<?=$view_type;?>"><?=$this->lang->line('pr_views_like_btn');?></a> |
				<? }else{ ?>
					<a href="/del_like/<?=$this->session->userdata['id']?>/pr/<?=$value['newsfeed_id']?>/<?=$news_array['pr_id']?>/<? echo 'page';?>/<?=$view_type;?>/<?=$news_array['page_id_from']?>/<? echo $news_array['pr_id']; ?>"><?=$this->lang->line('pr_views_delete_like_btn');?></a> 
				<? } ?> 
					<a class="newsfeed_view_comments_lnk" href=""><?=$this->lang->line('pr_views_view_comm_btn');?></a> | 
					<a href="" class="newsfeed_add_comment_lnk newsfeed_entry_option" onclick="setTo('<?=$news_array['pr_id']?>', 'pr', '<? echo $news_array['page_id_from'];?>', '<? echo $news_array['page_id_from'];?>')"><?=$this->lang->line('pr_views_add_comment_btn');?></a> | 
					<?=$this->lang->line('pr_views_props_lexicon');?>
				</div>
				<? 
				foreach ($news_array['likes'] as $like_key=>$like_value)
				{
					echo'<div class="like_text">'.$like_value['link'].$this->lang->line('pr_views_likes_this_lexicon').'</div>';
				}
				?>
		
				<div class="newsfeed_entry_comments" style="display: none;"> <!-- contains comments -->
					<div class="newsfeed_add_comment" style="display: none;">
						<?php echo validation_errors(); ?>
						<?php echo form_open('comment'); ?>
						
						<b id="reply_pr_<?=$news_array['pr_id']?>"> </b>
						<b id="reply_type_pr_<?=$news_array['pr_id']?>"> </b>
						<b id="to_pr_<?=$news_array['pr_id']?>"> </b>
						<?php echo form_hidden('view_type', $view_type); ?>
						<?php echo form_hidden('page_type', 'page'); ?>
						<?php echo form_hidden('comm_type', 'pr_comm'); ?>
						<?php echo form_hidden('pr_id', $news_array['pr_id']); ?>
						<?php echo form_hidden('newsfeed_id', $value['newsfeed_id']); ?>
						<? echo Form_Helper::form_input('comm_msg', set_value('comm_msg', 'Comm_msg'), 'class="reply_comm"');?>
						<? echo form_submit('submit', $this->lang->line('pr_views_post_submit')); ?>
						<?  echo form_close();?>
					</div>

					<? foreach ($news_array['comments'] as $ck=>$cv)
					{ ?>
						<div class="newsfeed_entry_comment">
							<div class="newsfeed_entry_comment_avatar"><a href="#"><img src="/images/example.jpg" border="0" height="40px"></a></div>
							<div>
								<span class="user_name">	
									<? echo $cv['link']; ?>
								</span>
								<? echo convert_datetime($cv['ctime']);?>
							</div>
								
							<? 
							if($cv['user_id_from']==$this->session->userdata['id'] || $cv['user_id_to']==$this->session->userdata['id'])
							{
								echo '<div><a href="/del_comm/'.$cv['comment_id'].'/'.$value['newsfeed_id'].'/pr_page/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'">'.$this->lang->line('pr_views_delete_btn').'</a></div>';
							}
							?>
								
							<div class="newsfeed_entry_comments_text">
								<?=$cv['comment']?>
								<input type='button' id='aaa' class="reply_button" onclick="setReply('<?=$news_array['pr_id']?>', 'pr', '<? if($cv['page_id_from']!=0){ echo $cv['page_id_from'];} else {echo $cv['user_id_from'];}?>', '<? if($cv['page_id_from']!=0){ echo 'page';}else{echo 'profile';}?>')" value = 'reply'>
							</div>
							<div class="post_info">
								<a href="/like/<?=$cv['comment_id']?>/pr_comm/<?=$news_array['page_id_from']?>/<? echo $news_array['pr_id']; ?>/<?=$value['newsfeed_id']?>/<? echo 'page';?>/<?=$view_type;?>"><?=$this->lang->line('pr_views_like_btn');?></a> |
								<a href="/del_like/<?=$this->session->userdata['id']?>/pr_comm/<?=$value['newsfeed_id']?>/<?=$cv['comment_id']?>/<? echo 'page';?>/<?=$view_type;?>/<?=$news_array['page_id_from']?>/<? echo $news_array['pr_id']; ?>"><?=$this->lang->line('pr_views_delete_like_btn');?></a>  
								<?=$this->lang->line('pr_views_props_lexicon');?>
							</div>
							<? 
							foreach ($cv['likes'] as $l_key=>$l_value)
							{
								echo '<div>'.$l_value['link'].' '.$this->lang->line('pr_views_likes_lexicon').'</div>';
							} 
							?>
						</div>
					<? } ?>
				</div>
			</div>
			<div class="clear"></div>
		<? $i++; }?>
	</ul>
</div>
 <?  ?> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo ' <!-- end of ( application/views/pr/pr.php ) -->' . "\n";
} ?>
