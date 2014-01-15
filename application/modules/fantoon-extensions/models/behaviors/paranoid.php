<?php
/**
 * Paranoid behavior - uses "is_deleted" column from the table and overwrites get_* metthods not to show 
 *                     the records which has is_deleted=1. Also overwrites the delete method to set the field
 *                     to 1 and not delete it.
 * @author radilr
 *
 */
class Paranoid_Behavior extends Behavior
{
	
	public static function _run_before_get($config) {
		get_instance()->db->where('is_deleted', 0);
	}
	
	public static function _run_before_delete($obj, $config) {
		$table        = $config['table'];
		$primary_key  = $config['primary_key'];
		$field        = "is_deleted";

		mysql_query("UPDATE $table SET $field = 1 WHERE $table.$primary_key = ".$obj->$primary_key);

		return false;
	}
	
}
