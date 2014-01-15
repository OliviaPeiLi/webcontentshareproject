<?php

	$base_path = dirname(__FILE__);

	if ( strpos( $base_path, "C:\\" ) == 0 )	{
		// windows system
		$modules_path = $base_path . "\application\modules\\";
	}	else {
		$modules_path = $base_path . "/application/modules/";
	}

	$modules = scandir($modules_path);

	$sauce_path = array();
	$all_keys  = array();

	foreach ($modules as $k=>$v) {
		# code...
		if ($v == '.' || $v == '..') continue;
		
		$sp = $modules_path . $v ."/tests/sauce";

		if ( file_exists($sp) && is_dir($sp) )	{
			$files_to_execute = scandir($sp);

			foreach ($files_to_execute as $key => $value) {
				# code...
				if ( $value == '.' || $value == '..' ) continue;

				$lines = file($sp . "/" . $value);
				if (strpos($lines[1],"//#") == 0 )	{
					preg_match('/\/\/# (.*?) @(.*?)$/',$lines[1],$matches);
					$key = trim($matches[2]);
					$sauce_path[$v][$key] = $sp . "/" . $value;
					$all_keys[] = "{$v}:{$key}";
				}	else {
					$sauce_path[$v][] = $sp . "/" . $value;
				}
			}
		}
	}

	$execute_modules = array();

	// separate
	 if ( isset($_SERVER['argv']) && count($_SERVER['argv']) > 1 )	{
	 	$arg_1 = trim($_SERVER['argv'][1]);
	 	if (!in_array($arg_1,$all_keys))	{
	 		foreach ($all_keys as $key => $value) {
	 			# code...
	 			echo "\n\t {$value} \n";
	 		}
	 		exit;
	 	}	else {
	 		// execute code step by step
	 		unset($_SERVER['argv'][0]);

	 		foreach ($_SERVER['argv'] as $k=>$v) {
	 			# code...
	 			if( in_array($v, $all_keys) )	{
	 				$execute_modules[] = $v;
	 			}
	 		}
	 	}
	 }

	// execute scripts
	foreach ($sauce_path as $k => $v) {
		# code...
		foreach ($v as $key => $test_script_path) {
			# code...
			if (count($execute_modules) == 0 || in_array("{$k}:{$key}", $execute_modules) )	{
				echo "\n\n" .'Running test - ' . $k . ":" . $key ."\n\n";
				$return = system( $base_path . "\sauce\\vendor\bin\phpunit {$test_script_path}" );											
			}
		}
	}

?>