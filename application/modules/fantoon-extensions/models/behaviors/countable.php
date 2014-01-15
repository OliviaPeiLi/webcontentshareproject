<?php
 /**
  * Active behavior - should be added to all tables which add a record in activity and notification tables
  * @author radilr
  *
  */
class Countable_Behavior extends Behavior
{
	public static function _run_after_create(&$data, $config) {
		foreach ($config as $table_conf) {
			$table = $table_conf['table'];
			$relation_stat = reset($table_conf['relation']);
			$relation_field = key($table_conf['relation']);
			if (!isset($data[$relation_field])) continue;
			foreach ($table_conf['fields'] as $field=>$cond) {
				if (is_int($field)) {$field = $cond; $cond = false; }
				if (!self::check_cond($cond, $data)) continue;
				
				mysql_query("UPDATE $table SET $field = $field + 1 WHERE $table.$relation_stat = ".$data[$relation_field]);
			}
		}
		return parent::_run_after_create($data, $config);
	}
	
	public static function _run_after_delete($obj, $config) {
		foreach ($config as $table_conf) {
			$table = $table_conf['table'];
			$relation_stat = reset($table_conf['relation']);
			$relation_field = key($table_conf['relation']);
			foreach ($table_conf['fields'] as $field=>$cond) {
				if (is_int($field)) {$field = $cond; $cond = false; }
				if (!self::check_cond($cond, $obj)) continue;
				
				mysql_query("UPDATE $table SET $field = $field - 1 WHERE $table.$relation_stat = ".$obj->$relation_field);
			}
		}
		return parent::_run_after_delete($obj, $config);
	}
	
}