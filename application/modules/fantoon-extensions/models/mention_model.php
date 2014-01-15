<?php

class Mention_model extends MY_Model
{

	//relations
	protected $belongs_to = array(
								'newsfeed', 'folder',
								'user_to' => array('foreign_model'=>'user', 'foreign_column'=>'user_id_to'),
								'user_from' => array('foreign_model'=>'user', 'foreign_column'=>'user_id_from'),
							);
							
	public $behaviors = array(
							'countable' => array(
                            	array(
									'table' => 'user_stats',
									'relation' => array('user_id_to' => 'user_id'),
									'fields' => array('mentions_count'),
								)
                            ),
							'active' => array(
    							'primary_key' => 'id',
    							'user_from_field' => 'user_id_from',
    							'user_to_field' => 'user_id_to',
    							'type' => 'mention'
    						),
							'notify' => array(
								'type' => 'at_comm',
    							'primary_key' => 'id',
    							'user_to_field' => 'user_id_to',
							)
						);

}