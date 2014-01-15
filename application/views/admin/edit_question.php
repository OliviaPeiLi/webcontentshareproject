<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/admin/edit_question.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('admin/admin_views', LANGUAGE); ?>
<div>
	<div class="clear"></div>
	<div class="container_24">
		<? foreach($question as $k=>$v){ ?>
			<? echo Form_Helper::form_open('/save_question'); ?>
			<div class="grid_2"><?=$v['q_id']?></div>
			<? echo form_hidden('q_id', $v['q_id']); ?>
			<div class="grid_6"><?=$this->lang->line('admin_views_title_lbl');?>:<? echo Form_Helper::form_input('title', set_value('title', $v['title']));?></div>
			<div class="grid_6"><?=$this->lang->line('admin_views_question_lbl');?><? echo Form_Helper::form_input('question', set_value('question', $v['question']));?></div>
		<? 	echo Form_Helper::form_submit('submit', $this->lang->line('admin_views_submit_btn'));
        		echo form_close();?>
			<div class="clear"></div>
		<? } ?>
	</div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/admin/edit_question.php ) -->' . "\n";
} ?>
