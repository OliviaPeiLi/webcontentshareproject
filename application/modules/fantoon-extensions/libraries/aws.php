<?php  
if (!defined('BASEPATH')) {exit('No direct script access allowed');}  

/************************************************************************
*AWS class
*function: require aws_sdk library
*should be call from Email_helper
************************************************************************/
  
class AWS  
{  
	function __construct($class = NULL)  
	{  
		ini_set('include_path', ini_get('include_path').';'.PATH_SEPARATOR . APPPATH . 'modules/fantoon-extensions/libraries/aws_sdk');  
		require_once('sdk.class.php');  
	}  
}  

?>