<?php

class Connection_model extends MY_Model
{

    //Relations
    protected $belongs_to = array(
                                'user1' => array('foreign_column'=>'user1_id', 'foreign_model'=>'user'),
                                'user2' => array('foreign_column'=>'user2_id', 'foreign_model'=>'user')
                            );

    protected $polymorphic_has_one = array(
                                         'ticker' => array(
                                                 'foreign_model' => 'activity',
                                                 'model_column' => 'type',
                                                 'item_column' => 'activity_id',
                                                 'on_delete_cascade' => true
                                         )
                                     );
    
    //Behaviors
    public $behaviors = array(
    						'countable' => array(
                            	array(
									'table' => 'user_stats',
									'relation' => array('user1_id' => 'user_id'),
									'fields' => array('followings_count'),
								),
                            	array(
									'table' => 'user_stats',
									'relation' => array('user2_id' => 'user_id'),
									'fields' => array('followers_count'),
								),
                            ),
    						'active' => array(
    							'primary_key' => 'id',
    							'user_from_field' => 'user1_id',
    							'user_to_field' => 'user2_id',
    							'type' => 'connection'
    						),
                            'notify' => array(
                            	'type' => 'follow',
    							'primary_key' => 'id',
    							'user_to_field' => 'user2_id',
                            )
                        );
                        
	protected $validate = array(
								'id' => array('label' => 'ID', 'rules' => ''),
								'user1_id' => array('label' => 'User following user2', 'rules' => 'required'),
								'user2_id' => array('label' => 'User followed by user1', 'rules' => 'required'),
						  );
						  
	/* ========================= EVENTS ====================== */
						  
	protected function _run_before_create($data) {
		if (!isset($data['user1_id'])) $data['user1_id'] = get_instance()->user->id;
		return parent::_run_before_create($data);
	}

    protected function _run_after_create($data) {
        
        if (!mysql_query("DELETE FROM folder_user WHERE user_id='".$data['user1_id']."' AND folder_id in (SELECT folder_id FROM folder WHERE user_id='".$data['user2_id']."')")){
	        throw new Exception( 'MySql error: ' . mysql_error() );
        }
        
        //Follow user folders to
        mysql_query("INSERT INTO folder_user (user_id, folder_id) (
        				SELECT '".$data['user1_id']."', folder_id FROM folder 
        				WHERE user_id = '".$data['user2_id']."'AND private!='1'
        				ORDER BY folder_id ASC
        			)");
        
        //Follow folder in which the user contributes too
        mysql_query("INSERT INTO folder_user (user_id, folder_id) (
        				SELECT '".$data['user1_id']."', folder_id 
        				FROM folder_contributors WHERE user_id = '".$data['user2_id']."'
        				ORDER BY folder_id ASC
        			)");
        return parent::_run_after_create($data);
    }
    
    protected function _run_after_set($data) {
     	$this->refresh_cache((Object) $data);
        return parent::_run_after_set($data);
    }

    protected function _run_before_delete($obj) {
		
    	//Unfollow user folders too
        mysql_query("DELETE FROM folder_user 
        			WHERE user_id='{$obj->user1_id}' AND folder_id IN (
        				SELECT folder_id FROM folder WHERE user_id='{$obj->user2_id}'
        			)");
        
        $this->refresh_cache($obj);
        return parent::_run_before_delete($obj);
    }
    
    private function refresh_cache($obj) {
    	$ci = get_instance();
        if (isset($obj->user1_id)) {
            $ci->cache->delete('following_list_'.$obj->user1_id);
        }
        if (isset($obj->user1_id)) {
            // remove following list of user1_id
            $ci->cache->delete('user_model_'.$obj->user1_id.'_follow');
        }
        if (isset($obj->user2_id)) {
            // remove follower list of user2_id
            $ci->cache->delete('user_model_'.$obj->user2_id.'_follow');
        }
    }
    
    /**
     * Filters
     */
    public function filter_followers($user_id) {
    	$this->db->where('user1_id', $user_id);
    	return $this;
    } 
    
    public function filter_followings($user_id) {
    	$this->db->where('user2_id', $user_id);
    	return $this;
    }

    public function search($q, $user) {
    	$user_id = is_object($user) ? $user->id : $user;
    	$this->db->join('users', 'users.id = connections.user2_id AND user1_id = '.$user_id);
    	$this->user_model->search($q);
    	return $this;
    }

    public function unique_users()  {
        $this->db->group_by('users.id');
    return $this;
    }

    public function add_users()  {
        $this->db->join('users', 'users.id = connections.user2_id');
    return $this;
    }

    /* =================== End filters ==================== */
    
    public function dropdown($key=null, $val=null, $token=false) {
    	return parent::dropdown('user2_id', "CONCAT(users.first_name,' ', users.last_name)", $token);
    }

}
