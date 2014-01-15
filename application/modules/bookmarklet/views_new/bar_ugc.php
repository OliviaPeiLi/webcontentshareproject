<? $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<div id="bar">
	<a href="/" target="_blank" class="home first" help="tooltip-1">
		<?=Html_helper::img('bookmarklet/logo2.png', array('alt'=>'Home'))?>
	</a>
	<?php if ($this->input->get('has_images') == 'true') { ?>
		<a href="#image_mode" title="" class="image_mode mode_button">
			<span class="ico"></span><?=$this->lang->line('image');?>
		</a>
	<? }?>
	<? if ($this->input->get('has_videos') == 'true') { ?>
		<a href="#video_mode" title="" class="video_mode mode_button">
			<span class="ico"></span><?=$this->lang->line('video');?>
		</a>
	<? } ?>
	<a href="#drop_page" title="" class="drop_page mode_button">
		<span class="ico"></span><?=$this->lang->line('url');?>
	</a>
	<a href="<?=$this->user->url?>" target="_blank" class="avatar">
		<?=Html_helper::img($this->user->avatar_25, array('height'=>"25px", 'alt'=>"avatar"))?>
	</a>
	<a href="#close" class="close last" help="tooltip-5">
		<span class="ico"></span>
	</a>
</div>
<script type="text/javascript">
	php.bar_options = <?=json_encode($options)?>;
</script>
<?=Html_helper::requireJS(array('bookmarklet/bar_ugc'))?>