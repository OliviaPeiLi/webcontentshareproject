<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/privacy/privacy.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('privacy/privacy_views', LANGUAGE); ?>
<div id="main">
	<div class="container_24">
		<?=$this->lang->line('privacy_views_privacy_page_heading');?>
    </div>
    <div class="container_24">
    	<ul>
    		<li><? echo form_open('set_privacy/'); ?></li>
    		<li><?=$this->lang->line('privacy_views_photo_setting_lbl');?></li>
    		<li><? echo form_radio('photo_privacy', 'strict'); ?> <?=$this->lang->line('privacy_views_strict_lexicon');?></li>
    		<li><? echo form_radio('photo_privacy', 'open'); ?> <?=$this->lang->line('privacy_views_open_lexicon');?></li>
    		<li><?=$this->lang->line('privacy_views_post_setting_lbl');?></li>
    		<li><? echo form_radio('post_privacy', 'strict'); ?> <?=$this->lang->line('privacy_views_strict_lexicon');?></li>
    		<li><? echo form_radio('post_privacy', 'open'); ?> <?=$this->lang->line('privacy_views_open_lexicon');?></li>
    	</ul>
    </div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/privacy/privacy.php ) -->' . "\n";
} ?>
