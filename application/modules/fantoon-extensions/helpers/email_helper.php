<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Email_helper extends Helper {
	/**
	 *SendEmail function
	 *function:send email from info@fandrop.com
	 *config file is include in /library/aws_sdk/config.inc.php
	 *input: receiver email address, message subject, message body
	 *output: response from aws sdk
	 */

	public static function SendEmail($to, $subject, $message) {
		$CI =& get_instance();
		$CI->load->library("aws");  
	
	    $amazonSes = new AmazonSES(AWS_KEY, AWS_SECRET_KEY);
	    try {
		    $response = $amazonSes->send_email(AWS_SES_FROM_EMAIL,
		        array('ToAddresses' => $to),
		        array(
		            'Subject.Data' => $subject,
		            'Body.Html.Data' => $message,
		        )
		    );
	    	if ($response->isOK()) return true;
	    } catch (Exception $e) {
	    	//For dev environments
	    }
	    
	    if (isset($response)) {
	    	//echo "Send email hrelper error: ";
	    	//print_r($response);
	    }
		
	    return false;
	}

}