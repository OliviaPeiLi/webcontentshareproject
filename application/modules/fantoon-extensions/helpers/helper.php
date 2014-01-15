<?php
/**
 * Base helper class. All helper we use extends this one
 * @author radilr
 */
class Helper {
	
	public static function __callstatic($method, $args) {
		if (!function_exists($method)) {
			require_once BASEPATH.'helpers/'.strtolower(get_called_class()).'.php';
		}
		if (!function_exists($method)) {
			throw new Exception("Helper method: ".$method." in ".__CLASS__." not found");
		}
		return call_user_func_array($method, $args);
	}
	
	
}