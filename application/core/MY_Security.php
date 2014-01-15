<?php
class MY_Security extends CI_Security {
	
	public function csrf_verify()	{
		if (!isset($_POST['signed_request']))	{
			parent::csrf_verify();
		}	else {
			parent::_csrf_set_hash();
			parent::csrf_set_cookie();
		}
	return $this;
	}

	public function csrf_show_error()
	{
		show_error('Your browser\'s cookie functionality is turned off. Please turn it on.');
	}
	
	/**
	 * Set Cross Site Request Forgery Protection Cookie
	 *
	 * @return	object
	 */
	public function csrf_set_cookie() {
		$expire = time() + $this->_csrf_expire;

		setcookie($this->_csrf_cookie_name, $this->_csrf_hash, $expire, config_item('cookie_path'), config_item('cookie_domain'));

		log_message('debug', "CRSF cookie Set");

		return $this;
	}
}