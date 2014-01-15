<?php
/** 
 * gmail invite
 * @author ray
 *
 */

class Auth_api extends MX_Controller {

	function gmail() {
		if ($this->user->email == 'radilr13@gmail.com' && @$_GET['code']) {
			$analytics = $this->load->library('google/analytics');
			$this->analytics->login(null,true);
		}
		
		$client = $this->load->library('google/apiClient', array());

		$client->setApplicationName("Google PHP Starter Application");
		$client->setScopes("http://www.google.com/m8/feeds/");

		if ($this->session->userdata('access_token_gmail')) {
			$client->setAccessToken($this->session->userdata('access_token_gmail'));
		} else if (isset($_GET['code'])) { //google callback url
			$client->authenticate();
			$this->session->set_userdata('access_token_gmail', $client->getAccessToken());
		}
				
		if( $client->getAccessToken() ) {
			echo '<script type="text/javascript">opener.gmail_success(); close();</script>';
			return ;
		}
		
		if ($this->input->get('error') == 'access_denied') {
			echo '<script type="text/javascript">opener.gmail_error(); close();</script>';
			return ;
		}
		
		header('Location: '.$client->createAuthUrl());
	}
	
	function yahoo() {
		$this->load->library('yahoo/yahoo');
		
		//this will redirect to login page if needs to login
		$this->yahoo->getAccessToken();
		
		echo '<script type="text/javascript">opener.yahoo_success(); close();</script>';
		return ;
	}
	
}