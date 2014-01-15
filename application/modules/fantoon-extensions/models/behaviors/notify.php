<?php
 /**
  * Active behavior - should be added to all tables which add a record in activity and notification tables
  * @author radilr
  *
  */
class Notify_Behavior extends Behavior
{
	public static function _run_after_create(&$data, $config) {
		$CI = get_instance();
		
		if (is_array($config['type'])) {
			foreach ($config['type'] as $type => $condition) {
				if (self::check_cond($condition, $data)) {
					$config['type'] = $type;
					break;
				}
			}
			if (is_array($config['type'])) {
				return ;
				//die('Condition not met - using default type');
				//$config['type'] = $type;
			}
		}
		
		if($config['type'] == 'u_comm') {							
			//Notify other users which commented on the item that a new comment is added
			if (@$data['newsfeed_id']) {
				$comment_users = $CI->comment_model->_set_where(array(array('newsfeed_id' => $data['newsfeed_id'])))->dropdown('user_id_from','user_id_from');
			} else {
				$comment_users = $CI->comment_model->_set_where(array(array('folder_id' => $data['folder_id'])))->dropdown('user_id_from','user_id_from');
			}
			$comment_users[] = $data['user_id_to'];
			$comment_users = array_keys(array_flip($comment_users));
			
			foreach($comment_users as $user_id) {
				if ($user_id == $CI->user->id) continue;
				$CI->notification_model->insert(array(
						'user_id_from' => $CI->user->id,
						'user_id_to' => $user_id,
						'type' => $config['type'],
						'item_id' => isset($data[@$config['primary_key']]) ? $data[@$config['primary_key']] : 0,
				));
			}
		} else {
			$item_id = isset($data[@$config['primary_key']]) ? $data[@$config['primary_key']] : 0;
			$user_id_from = isset($config['user_from_field']) ? $data[$config['user_from_field']] : $CI->session->userdata('id');

			if (isset($config['user_to_field'])) {
				if (is_array($config['user_to_field'])) {
					$model = $CI->{$CI->notification_model->notification_types[$config['type']].'_model'};
					$item = $model->get($item_id);
					$remote_field = reset($config['user_to_field']);
					$field = key($config['user_to_field']);
					$user_id_to = $item->$field->$remote_field;
				} else {
					$user_id_to = $data[$config['user_to_field']];
				}
			} else {
				$user_id_to = 0;
			}

			if ( $user_id_from == $user_id_to ) return;
			$CI->notification_model->insert(array(
					'user_id_from'=> $user_id_from,
					'user_id_to'=> $user_id_to,
					'type' => $config['type'],
					'item_id' => $item_id,
				));
		}
		
	}
	
	public static function _run_after_delete($obj, $config) {
		$CI = get_instance();
		if (is_array($config['type'])) {
			foreach ($config['type'] as $type => $condition) {
				if (self::check_cond($condition, $obj)) {
					$config['type'] = $type;
					break;
				}
			}
		}
		$CI->notification_model->delete_by(array('type'=>$config['type'],'item_id'=>$obj->$config['primary_key']));
		return parent::_run_after_delete($obj, $config);
	}
}
