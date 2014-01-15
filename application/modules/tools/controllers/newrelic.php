<?php
class Newrelic extends MX_Controller {
	
	private $api_key = "0d57e7ac18299fd7d0088951207701d8dee7fc1772fda3d";
	
	public function post_error() {
		$post = $this->input->post();
		
		if (extension_loaded ('newrelic')) {
			ini_set('display_errors', 'On');
			error_reporting(E_ALL);
			define('MP_DB_DEBUG', true); 
			
			echo "new relic post errror";
			newrelic_notice_error("Javascript error");
			newrelic_add_custom_parameter("message", $post['errorMsg']);
			newrelic_add_custom_parameter("file", $post['file']);
			newrelic_add_custom_parameter("lineNumber", $post['lineNumber']);
		} else {
			echo "new relic extension is not loaded";
		//	throw $exception;
		}
	}
	
	public function post_deploy() {
		$ch = curl_init("https://rpm.newrelic.com/deployments.xml");
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key:'.$this->api_key));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
			'deployment[application_id]' => ENVIRONMENT == 'production' ? '1264760' : '1264656',
			'deployment[description]' => 'Regular deployment',
			'deployment[changelog]' => 'Some changes', //TO-DO generate a list of changed files
		)));
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
		$res = curl_exec($ch);
	}
}