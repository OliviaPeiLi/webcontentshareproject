<? $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<div class="bookmarklet-success" data-newsfeed_id="<?=@$newsfeed->newsfeed_id?>">
	<h1><?=$this->lang->line('bookmarklet_success_header');?></h1>
	<p class="folder-info">
    	<?=$this->lang->line('bookmarklet_success_message');?>
		<a href="" target="_blank"></a>
	</p>
	<ul class="controls">
		<li>
			<a id="newsfeed_url" href="<?=@$newsfeed->url?>" target="_blank">
				<?=$this->lang->line('bookmarklet_success_url_btn');?>
				<span id="newsfeed_url_span"></span>
			</a>
		</li>
		<li>
			<a href="https://twitter.com/share?url={DROP_URL}" id="share_on_twitter_btn"><span id="share_on_twitter_span"></span></a>
		</li>
		<li>
			<a href="" class="share_fb_app drop"><span id="share_fb_app_span"></span></a>
		</li>
		
	</ul>
	<div id="error_popup" style="display:none">
		<p class="content">{populated via js}</p>
	</div>
</div>

<?=Html_helper::requireJS(array('bookmarklet/success'))?> 