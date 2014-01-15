<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/admin/admin.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('admin/admin_views', LANGUAGE); ?>
<div>
	<div class="clear"></div>
	<div class="container_24">
		<div>
			1. <a href="/admin_page"><?=$this->lang->line('admin_views_manage_pages_btn');?></a>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			2. <a href="/page_aliases"><?=$this->lang->line('admin_views_process_aliases_btn');?></a>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			3. <?=$this->lang->line('admin_views_ppl_u_may_know_lexicon');?>
			<p><a href="/gen_similarity/full"><?=$this->lang->line('admin_views_full_update_btn');?></a></p>
			<p><a href="/gen_similarity/increase"><?=$this->lang->line('admin_views_mental_update_btn');?></a></p>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			4. <?=$this->lang->line('admin_views_ppl_u_should_know_lexicon');?>
			<p><a href="/peopleshouldknow/0"><?=$this->lang->line('admin_views_full_update_btn');?></a></p>
			<p><a href="/peopleshouldknow/1"><?=$this->lang->line('admin_views_mental_update_btn');?></a></p>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			5. <a href="/page_suggestion"><?=$this->lang->line('admin_views_page_suggestion_btn');?></a>
		</div>
		<div class="clear"></div>
		<div class="clear"></div> 
		<div>
			6. <a href="/transfer_page"><?=$this->lang->line('admin_views_transfer_wiki_btn');?></a><?=$this->lang->line('admin_views_transfer_wiki_text');?>
			 <br><br> 
			 <a href="/transfer_img/<?=$this->uri->segment(2)?>/<?=$this->uri->segment(3)?>"><?=$this->lang->line('admin_views_pics_wiki_btn');?></a><?=$this->lang->line('admin_views_pics_wiki_text');?>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			7. <?=$this->lang->line('admin_views_merge_interests_btn');?>
			<? echo Form_Helper::form_open('/merge_page'); ?>
			<? echo $this->lang->line('admin_views_merge_first_text'). Form_Helper::form_input('page1_id', '');?>
			<? echo $this->lang->line('admin_views_into_second_text'). Form_Helper::form_input('page2_id', '');?>
			<? echo Form_Helper::form_submit('submit', $this->lang->line('admin_views_merge_btn')); ?>
			<? echo form_close();?>
		</div> 
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			8. <?=$this->lang->line('admin_views_fix_fb_cat');?>
				<p><a href="/fix_categories"><?=$this->lang->line('admin_views_click_to_fix');?></a></p>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			9. <?=$this->lang->line('admin_views_transfer_ids_text');?>
				<p><a href="/t_fb_pageids"><?=$this->lang->line('admin_views_click2transfer_fb');?></a></p>
				<p><a href="/transfer_twitter_id"><?=$this->lang->line('admin_views_click2transfer_tw');?></a></p>
				
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			10. <?=$this->lang->line('admin_views_manage_fav_text');?>
				<p><a href="/manage_favorite"><?=$this->lang->line('admin_views_manage_fav_btn');?></a></p>				
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div>
			11. <?=$this->lang->line('admin_views_manage_alpha_text');?>
				<p><a href="/alpha"><?=$this->lang->line('admin_views_manage_alpha_btn');?></a></p>				
		</div>
		<div>
			12. <?=$this->lang->line('admin_views_delete_page_text');?>
			<? echo Form_Helper::form_open('/delete_page'); ?>
			<? echo $this->lang->line('admin_views_page_id_text'). Form_Helper::form_input('page_id', '');?>
			<? echo Form_Helper::form_submit('submit', $this->lang->line('admin_views_delete_lexicon')); ?>
			<? echo form_close();?>
		</div>
		<div>
			13. <?=$this->lang->line('admin_views_update_empty_pic_text');?>
			<div class="clear"></div>
			<a href="/update_empty_image"><?=$this->lang->line('admin_views_update_empty_pic_btn');?></a>
		</div>
		<b><a href="/admin_logout"><?=$this->lang->line('admin_views_logout_btn');?></a></b>
	</div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/admin/admin.php ) -->' . "\n";
} ?>
