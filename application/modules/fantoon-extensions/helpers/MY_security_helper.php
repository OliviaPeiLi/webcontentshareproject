<?php
/**
 * General security helper. This file holds base and extended security check functions
 */
class Security_helper extends Helper {
	
	public static function security_check($value, $user) {
		if (!$value) {
			return $user && isset($user->id);
		} elseif ($value == 'public') {
			return true;
		} else {
			return call_user_func(array('Security_helper', 'security_check_'.$value), $user);
		}
		return FALSE;
	}
	
	public static function security_check_friends($user) {
		
	}
	
	public static function security_check_bookmarklet($user) {
		if ($user && isset($user->id)) return true; 
		return 'bookmarklet/external_login?referrer='.get_instance()->router->fetch_method().'&url='.get_instance()->input->get('url');
	}
	
	public static function security_check_cli($user) {
		if (isset($_SERVER['REMOTE_ADDR'])) return false;
		return true;
	}
}