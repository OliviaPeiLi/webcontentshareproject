<?php

class Email_setting_model extends MY_Model
{
	//Relations
	protected $belongs_to = array(
								'user'
							);
							
	protected $validate = array(
								'user_id' => array('label'=>'user ID','rules'=>''),
								'message' => array('label'=>'Message','rules'=>''),
								'comment' => array('label'=>'comment','rules'=>''),
								'up_link' => array('label'=>'up_link','rules'=>''),
								'reply' => array('label'=>'reply','rules'=>''),
								'up_comment' => array('label'=>'up_comment','rules'=>''),
								'connection' => array('label'=>'connection','rules'=>''),
								'follow_folder' => array('label'=>'follow_folder','rules'=>''),
								'follow_list' => array('label'=>'follow_list','rules'=>''),
								'collaboration' => array('label'=>'collaboration','rules'=>''),
								'folder_like' => array('label'=>'folder_like','rules'=>''),
								'newsletter' => array('label'=>'newsletter','rules'=>''),
							);


}