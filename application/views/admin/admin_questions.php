<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/admin/admin_questions.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('admin/admin_views', LANGUAGE); ?>
<div>
	<div class="clear"></div>
	<div class="container_24">
		<? foreach($questions as $k=>$v){ ?>
			<div class="grid_2"><?=$v['q_id']?></div>
			<div class="grid_6"><b><?=$v['title']?></b></div>
			<div class="grid_6"><?=$v['question']?></div>
			<? if($v['display'] == '0'){ ?>
			<div class="grid_2"><a href="question_display/display/<?=$v['q_id']?>"><?=$this->lang->line('admin_views_display_btn');?></a></div>
			<? } ?>
			<? if($v['display'] == '1'){ ?>
			<div class="grid_2"><a href="question_display/hide/<?=$v['q_id']?>"><?=$this->lang->line('admin_views_hide_btn');?></a></div>
			<? } ?>
			<div class="grid_2"><a href="question_rm/<?=$v['q_id']?>"><?=$this->lang->line('admin_views_remove_btn');?></a></div>
			<div class="grid_2"><a href="edit_question/<?=$v['q_id']?>"><?=$this->lang->line('admin_views_edit_btn');?></a></div>
			<div class="clear"></div>
		<? } ?>
	</div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/admin/admin_questions.php ) -->' . "\n";
} ?>
