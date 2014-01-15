<div id="landing_page">
	<?=modules::run('homepage/home_folders/ugc_top')?>
	
	<?=modules::run('homepage/home_folders/ugc_hashtags')?>
</div>
<?=Html_helper::requireJS(array("folder/folder_main_ugc"))?>