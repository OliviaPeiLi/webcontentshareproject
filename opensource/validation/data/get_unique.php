<?php
	
	$q = $_GET['q'];

	$uniques = array(
		array("id"=>1,"username"=>"test"),
		array("id"=>1,"username"=>"radil")
	);

	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-type: application/json');

	foreach ($uniques as $key => $value) {
		# code...
		 if ($value['username'] == $q)	{
		 	die(json_encode(array('status'=>false)));
		 }
	}

	die(json_encode(array('status'=>true)));

?>