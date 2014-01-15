	<script type="text/javascript" src="/js/tests/qunit.js"></script>
	<script type="text/javascript" src="/js/tests/quint.utils.js"></script>
	<?php
		$test_filename = str_replace('/', '_', $this->router->uri->uri_string ? $this->router->uri->uri_string : ($this->user ? 'home' : 'landing')).'.js';
	
		if (preg_match('#drop_[0-9]*.js#', $test_filename)) $test_filename = 'drop.js';
		if (preg_match('#test_user1_bookmarklet_folder#', $test_filename)) $test_filename = 'collection.js';
		if (preg_match('#view_msg_[0-9]+.js#', $test_filename)) $test_filename = 'view_msg.js';
		if (preg_match('#manage_lists_[a-zA-Z0-9_-]+_edit.js#', $test_filename)) $test_filename = 'edit_list.js';
		if (preg_match('#manage_lists_[a-zA-Z0-9_-]+.js#', $test_filename)) $test_filename = 'manage_list.js';
		if (preg_match('#winsxsw_[a-zA-Z0-9_-]+.js#', $test_filename)) $test_filename = 'winsxsw_folder.js';
		if (preg_match('#demo_windemomobile[a-zA-Z0-9_-]+.js#', $test_filename)) $test_filename = 'demo_folder.js';
		if (in_array($test_filename, array('testuser1.js','test_user1.js','test_user2.js'))) $test_filename = 'profile.js';
		if (strpos($test_filename, 'source_') !== false) $test_filename = 'search_source.js';
		
		if ($this->is_mod_enabled('design_ugc')) $test_filename = str_replace('.', '_ugc.', $test_filename);
	    $test_pathname = BASEPATH.'../application/modules/'.$this->router->fetch_module().'/js/tests/'
	?>
	<?php if (file_exists($test_pathname.$test_filename)) {?>
		<script type="text/javascript" src="/js/modules/tests/<?=$test_filename?>"></script>
	<?php } else {?>
		<script type="text/javascript">console.warn('NO TEST FOUND FOR: <?=$test_pathname.$test_filename?>');</script>
	<?php } ?>
