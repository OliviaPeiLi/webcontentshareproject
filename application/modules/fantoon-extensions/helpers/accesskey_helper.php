<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Accesskey_helper extends Helper {
	
	//generates a random key based on the characters specified below
	//take an argument $ lenght, to specify the length of the generated key
	public static function generateAccessKey($length=10) {
		// start with a blank password
		$AccessKey = "";
		
		// define possible characters
		$possible = "0123456789bcdfghjkmnpqrstvwxyzaiyuoe"; 
				
		// set up a counter
		$i = 0; 
			
		// add random characters to $password until $length is reached
		while ($i < $length) {		
			// pick a random character from the possible ones
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
						
			// we don't want this character if it's already in the password
			if (!strstr($AccessKey, $char)) { 
				$AccessKey .= $char;
				$i++;
	    	}
	
	  	}
		
		return $AccessKey;
	
	}
	
}