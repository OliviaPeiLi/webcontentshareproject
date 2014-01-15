<?php
/**
 * Base behavior class.
 */
class Behavior
{
    public static function _run_before_set(&$data, $config) { }
    public static function _run_after_set(&$data, $config) { }
    public static function _run_before_delete($obj, $config) { return $obj; }
    public static function _run_after_delete($obj, $config) { return true; }
    public static function _run_before_create(&$data, $config) { }
    public static function _run_after_create(&$data, $config) { }
    public static function _run_before_get($config) { }
    public static function _run_after_get($result, $config) { }
    
	protected function check_cond($cond, $obj) {
		$cond = trim($cond);
		if (!is_object($obj)) $obj = (Object) $obj;
		if (!$cond) return true;
		
		if (strpos($cond, '&&') !== false) {
			foreach (explode('&&', $cond) as $sub_cond) if (!self::check_cond($sub_cond, $obj)) {
				return false;
			}
			return true;
		} elseif (strpos($cond, '||') !== false) {
			foreach (explode('&&', $cond) as $sub_cond) if (self::check_cond($sub_cond, $obj)) {
				return true;
			}
			return false;
		}
				
		if (strpos($cond, '==') !== false) {
			list($check_field, $val) = explode('==', $cond);
			return isset($obj->$check_field) ? $obj->$check_field == $val : !$val;
		}
		if (strpos($cond, '!=') !== false) {
			list($check_field, $val) = explode('!=', $cond);
			return !isset($obj->$check_field) || $obj->$check_field != $val;
		}
		if (strpos($cond, '>=') !== false) {
			list($check_field, $val) = explode('>=', $cond);
			$check_field = trim($check_field); $val = trim($val);
			return isset($obj->$check_field) && $obj->$check_field >= $val;
		}
		if (strpos($cond, '>') !== false) {
			list($check_field, $val) = explode('>', $cond);
			$check_field = trim($check_field); $val = trim($val);
			return isset($obj->$check_field) && $obj->$check_field > $val;
		}
		if (strpos($cond, '<=') !== false) {
			list($check_field, $val) = explode('<=', $cond);
			$check_field = trim($check_field); $val = trim($val);
			return isset($obj->$check_field) && $obj->$check_field <= $val;
		}
		if (strpos($cond, '<') !== false) {
			list($check_field, $val) = explode('<', $cond);
			$check_field = trim($check_field); $val = trim($val);
			return isset($obj->$check_field) && $obj->$check_field < $val;
		}
		die("Condition not recognized: ".$cond);
	}
}