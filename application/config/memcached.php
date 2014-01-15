<?php
if (ENVIRONMENT == 'development') {	
	$config = array(
		'server_1' => array(
			'hostname'		=> '127.0.0.1',
			'port'		=> 21201,
			'weight'	=> 1
		)
	);
} elseif (ENVIRONMENT == 'staging') { //tessting server
	$config = array(
	    'server_1' => array(
	        'hostname'   => '127.0.0.1',
	        'port'   => 11211,
	        'weight' => 1
	    ),
	    //'server_2' => array(
	    //    'hostname'   => '173.255.253.241',
	    //    'port'   => 21201,
	    //    'weight' => 1
	    //),
	); 

} elseif (ENVIRONMENT == 'production') { //production server
	if (gethostname()=='app0') {
		$config = array(
		    'server_1' => array(
		        'hostname'   => 'localhost',
		        'port'   => 11211,
		        'weight' => 1
		    ),
		   'server_2' => array(
		        'hostname'   => 'hn2.fandrop.com',
		        'port'   => 21572,
		        'weight' => 1
		    ),
		); 
	} else {
		$config = array(
		   'server_1' => array(
		        'hostname'   => 'hn1.fandrop.com',
		        'port'   => 21573,
		        'weight' => 1
		    ),
			'server_2' => array(
		        'hostname'   => 'localhost',
		        'port'   => 11211,
		        'weight' => 1
		    ),
		); 	
	}
}