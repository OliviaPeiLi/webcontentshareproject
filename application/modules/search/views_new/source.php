<? $this->lang->load('newsfeed/newsfeed', LANGUAGE); ?>
<div id="source_top">
	<h1 class="primary_title">
		<?=$this->lang->line('source_title');?> <a href="http://<?=$source?>" target="_blank"><?=$source?></a>
	</h1>
</div>

<div id="sourceContainer" class="messagesContainer">
	<?=modules::run('newsfeed/newsfeed/source', $source)?>
</div>