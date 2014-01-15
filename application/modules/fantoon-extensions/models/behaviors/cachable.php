<?php
 /**
  * Active behavior - should be added to all tables which add a record in activity and notification tables
  * @author radilr
  *
  */
class Cachable_Behavior extends Behavior
{
	public static function _run_after_create($data, $config) {
		foreach ($config as $table_conf) {
			$item = self::get_item($table_conf, $data);
			$cached = self::get_data($table_conf, $item);
			
			if (isset($table_conf['skip_first']) && $table_conf['skip_first'] && isset($cached[0])) {
				$_cached = array($cached[0]);
				$_cached[] = self::create_item($table_conf['data'], $item);
				for ($i=1;$i<count($cached);$i++) $_cached[] = $cached[$i];
				$cached = $_cached;
			} else {
				array_unshift($cached, self::create_item($table_conf['data'], $item));
				$cached = array_slice($cached, 0, $table_conf['max']);
			}
			
			self::update_data($table_conf, $item, $cached);
		}
		return parent::_run_after_create($data, $config);
	}
	
	public static function _run_after_set($data, $config) {
		foreach ($config as $table_conf) {
			$item = self::get_item($table_conf, $data);
			$cached = self::get_data($table_conf, $item);
			
			foreach ($cached as &$cached_item) {
				if ($cached_item->{$item->_model->primary_key()} == $item->primary_key()) {
					$cached_item = self::create_item($table_conf['data'], $item);
				}
			}
			
			self::update_data($table_conf, $item, $cached);
		}
		return parent::_run_after_set($data, $config);
	}
	
	public static function _run_after_delete($obj, $config) {
		foreach ($config as $table_conf) {
			if (!isset($obj->_model)) continue;
			$model = get_instance()->{$table_conf['_model'].'_model'};
			$primary_key = $model->primary_key();
			$cached = self::get_data($table_conf, $obj);
			$found = false; $ids = array();
			foreach ($cached as $key=>$item) {
				$ids[] = $item->$primary_key;
				if ($item->$primary_key == $obj->$primary_key) {
					unset($cached[$key]);
					$found = true;
				}
			}
			if (!$found) continue;
			
			$relation_remote = reset($table_conf['relation']);
			$relation_local = key($table_conf['relation']);
			$item = $obj->_model->order_by($obj->_model->primary_key(),'desc')->get_by(array(
				$relation_local => $obj->$relation_local,
				$obj->_model->primary_key().' NOT' => $ids
			));
			if ($item) $cached[] = self::create_item($table_conf['data'], $item);
			
			self::update_data($table_conf, $obj, $cached);
		}
		return parent::_run_after_delete($obj, $config);
	}
	
	public function update_data($table_conf, $item, $items) {
		$field = reset($table_conf['update']);
		$table = key($table_conf['update']);
		
		$relation_remote = reset($table_conf['relation']);
		$relation_local = key($table_conf['relation']);
		
		$items = array_slice($items, 0, $table_conf['max']);
		$sql = "UPDATE $table SET $field = '".mysql_real_escape_string(json_encode($items))."' WHERE $table.$relation_remote = {$item->$relation_local}";
		mysql_query($sql);
	} 
	
	
	public function get_item($table_conf, $data) {
		$model = get_instance()->{$table_conf['_model'].'_model'};
		$primary_key = $model->primary_key();
		return $model->get($data[$primary_key]);
	}
	
	public  function create_item($fields, $item) {
		$ret = array($item->_model->primary_key() => $item->primary_key());
		foreach ($fields as $field) {
			$ret[$field] = $item->$field;
		}
		return $ret;
	}
	
	private function get_data($table_conf, $item) {
		$field = reset($table_conf['update']);
		$table = key($table_conf['update']);
		$relation_remote = reset($table_conf['relation']);
		$relation_local = key($table_conf['relation']);
		$cached = mysql_fetch_object(mysql_query("SELECT $field FROM $table WHERE $table.$relation_remote = {$item->$relation_local}"))->$field;
		return (Array) json_decode($cached);
	}
	
}