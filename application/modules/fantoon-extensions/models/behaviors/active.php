<?php
 /**
  * Active behavior - should be added to all tables which add a record in activity and notification tables
  * @author radilr
  *
  */
class Active_Behavior extends Behavior
{
	public static function _run_after_create(&$data, $config) {
		$CI = get_instance();
		$data['_activity_id'] = $CI->activity_model->insert(array(
										'activity_id' => isset($config['primary_key']) ? $data[$config['primary_key']] : 0,
										'type' => is_array($config['type']) ? $data[$config['type'][0]] : $config['type'],
										'user_id_from' => isset($config['user_from_field']) ? $data[$config['user_from_field']] : $CI->user->id,
										'user_id_to' => isset($config['user_to_field']) ? $data[$config['user_to_field']] : 0,
										'folder_id' => isset($config['folder_id']) && isset($data[$config['folder_id']]) ? $data[$config['folder_id']] : 0,
										'collect' => is_array($config['type']) && in_array($data[$config['type'][0]], array('link')) && isset($data['parent_id']) ? '1' : '0',
										'time' => date('Y-m-d H:i:s')
								));
	}
}