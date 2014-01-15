<?php
class Array_Helper extends Helper {

	/**
	 * Coverts object ot an assoc array
	 * 
	 * @param string $obj
	 */
	public function obj2arr($obj)
	{
		$ret = array();
		foreach ($obj as $key=>$val)
		{
			$ret[$key] = is_object($val) ? self::obj2arr($val) : $val;
		}
		return $ret;
	}
	
	public function array_merge_assoc($array1, $array2)
	{
		return array_combine(
			array_merge(array_keys($array1), array_keys($array2)),
			array_merge(array_values($array1), array_values($array2))
		);
	}
}