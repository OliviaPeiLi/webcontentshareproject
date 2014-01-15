<? $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<div class="bookmarklet-success" data-newsfeed_id="<?=@$newsfeed->newsfeed_id?>">
	<h1><?=$this->lang->line('bookmarklet_success_header');?></h1>
	<p class="folder-info">
    	<?=$this->lang->line('bookmarklet_success_message');?>
		<a href="{folder_url}" target="_blank">{folder_name}</a>
	</p>
	<div>
		<a data-url="{drop url}" data-text="{drop desc}" data-count="none" class="share_btn share_twt_app" href="http://ft/"><span class="ico"></span></a>
		<a class="share_fb_app" data-newsfeed_id="{drop id}" href="http://ft/"><span class="ico"></span></a>
		<a id="newsfeed_url" href="{folder url}" target="_blank">
			<?=$this->lang->line('bookmarklet_success_url_btn');?>
			<span id="newsfeed_url_span"></span>
		</a>
	</div>
	<div id="error_popup" style="display:none">
		<p class="content">{populated via js}</p>
	</div>
</div>

<?=Html_helper::requireJS(array('bookmarklet/success'))?> 
