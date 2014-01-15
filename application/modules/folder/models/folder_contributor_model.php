<?php

class Folder_contributor_model extends MY_Model
{

	protected $belongs_to = array(
								'folder',
								'user'
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
									'relation' => array('user_id' => 'user_id'),
                            		//@todo - what happens if user shares private collection?
									'fields' => array(
										'public_collections_count',
                            		),
								)
                            ),
							'active' => array(
								'folder_id' => 'folder_id', //this should be primary_key
								'user_to_field' => 'user_id',
								'type' => 'folder_contributor'
							),
							'notify' => array(
								'type' => 'folder_contributor',
								'primary_key' => 'id',
								'user_to_field' => 'user_id',
							)
						);
	
	protected $validate = array(
								'id'          => array( 'label' => 'ID',        'rules' => '' ),
								'folder_id'   => array( 'label' => 'Folder',    'rules' => '' ),
								'user_id'     => array( 'label' => 'From User', 'rules' => '' ),
						  );
						  
	/* ========================== FILTERS =========================== */
						  
	public function filter_folder($folder_id) {
		$this->db->where('folder_id', $folder_id);
		return $this;
	}

	/* ========================== SELECTS ============================= */
	public function dropdown($key='', $val='') {
		if ($key || $val) return parent::dropdown($key, $val);
		$this->join('users', 'users.id = user_id');
		return parent::dropdown('user_id', "CONCAT(first_name, ' ', last_name)");
	}
}