<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/error.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('error', LANGUAGE); ?>
<div class="container container_24">
    <div class="grid_24 alpha omega">
		<div id="fourOhFour_container">
		    <h1><?php echo $error?></h1>
		    <div id="fourOhFour_text"><?php echo $descripion?></div>
		    <div id="fourOhFour_goBack"><a href="/"><?=$this->lang->line('error_view_go_back_btn');?></a></div>
		</div>
    </div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/error.php ) -->' . "\n";
} ?>
