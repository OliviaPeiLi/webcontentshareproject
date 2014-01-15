<? $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<div id="bar">
	<a href="/" target="_blank" class="home first" help="tooltip-1">
		<?=Html_helper::img('bookmarklet/logo2.png', array('alt'=>'Home'))?>
	</a>
	<a href="#drop_page" title="" class="drop_page" help="tooltip-2">
		Drop Page
	</a>
	<?php if ($this->is_mod_enabled('bookmarklet_image_mode') && $this->input->get('has_images') != 'false') { ?>
		<a href="#image_mode" title="" class="image_mode" help="tooltip-3">
			Image
		</a>
	<?php } ?>
	<a href="<?=$this->user->url?>" target="_blank" class="avatar" help="tooltip-4">
		<?=Html_helper::img($this->user->avatar_small, array('height'=>"25px", 'alt'=>"avatar"))?>
	</a>
	<a href="#close" class="close last" help="tooltip-5">
		<?=Html_helper::img('bookmarklet/close.png', array('alt'=>"Close"))?>
	</a>
</div>
<div class="ui-tooltip tooltip-1" style="left: 0px; top: 40px; width: 153px;" tracking="false" role="alert" aria-live="polite" aria-atomic="false" aria-describedby="ui-tooltip-1-content" aria-hidden="false">
	<div class="ui-tooltip-content" aria-atomic="true"><?=$this->lang->line('bookmarklet_go_home_msg');?></div>
	<div class="ui-tooltip-tip"></div>
</div>
<div class="ui-tooltip tooltip-2" style="left: 20px; top: 40px; width: 251px;" tracking="false" role="alert" aria-live="polite" aria-atomic="false" aria-describedby="ui-tooltip-1-content" aria-hidden="false">
	<div class="ui-tooltip-content" aria-atomic="true"><?=$this->lang->line('bookmarklet_drop_as_bookmark_msg');?></div>
	<div class="ui-tooltip-tip" style="left: 100px"></div>
</div>
<div class="ui-tooltip tooltip-3" style="left: 85px; top: 40px; width: 185px;" tracking="false" role="alert" aria-live="polite" aria-atomic="false" aria-describedby="ui-tooltip-1-content" aria-hidden="false">
	<div class="ui-tooltip-content" aria-atomic="true"><?=$this->lang->line('bookmarklet_image_mode_msg');?></div>
	<div class="ui-tooltip-tip" style="left: 123px"></div>
</div>
<div class="ui-tooltip tooltip-4" style="right: 13px; top: 40px; width: 165px;" tracking="false" role="alert" aria-live="polite" aria-atomic="false" aria-describedby="ui-tooltip-1-content" aria-hidden="false">
	<div class="ui-tooltip-content" aria-atomic="true"><?=$this->lang->line('bookmarklet_go_profile_msg');?></div>
	<div class="ui-tooltip-tip" style="left: 123px;"></div>
</div>
<div class="ui-tooltip tooltip-5" style="right: 5px; top: 40px; width: 185px;" tracking="false" role="alert" aria-live="polite" aria-atomic="false" aria-describedby="ui-tooltip-1-content" aria-hidden="false">
	<div class="ui-tooltip-content" aria-atomic="true"><?=$this->lang->line('bookmarklet_close_msg');?></div>
	<div class="ui-tooltip-tip" style="left: 165px"></div>
</div>
<script type="text/javascript">
	php.bar_options = <?=json_encode($options)?>;
</script>
<?=Html_helper::requireJS(array('bookmarklet/bar'))?>