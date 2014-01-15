<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Comments extends ADMIN
{

	protected $model = 'comment_model';

	protected $list_fields = array(
								 'comment_id'	=> 'primary_key',
								 'parent_id'	=> 'string',
								 // 'reply_user_id'=>'string',
								 // 'reply_page_id'=>'string',
								 'user_from'	=> 'belongs_to',
								 'user_to'		=> 'belongs_to',
								 'comment'		=> 'string',
								 'time'			=> 'time'
							 );
	protected $form_fields = array(
								 'parent_id'=>'string',
								 // 'reply_user_id'=>'string',
								 // 'reply_page_id'=>'string',
								 'user_id_from'=>'string',
								 'user_id_to'=>'string',
								 'comment'=>'string',
								 'time'=>'time'
							 );

	protected $filters = array(
							 'comment_id' => 'primary_key',
							 'user_id_from' => 'string',
							 'user_id_to' => 'string'
						 );

	protected function filter($items)
	{
		if ($this->user->role != 2)
		{
			$items->_set_where(array("(user_id_from = {$this->user->id} OR user_id_to = {$this->user->id})"));
		}
		return parent::filter($items);
	}

	protected function check_access($item)
	{
		if ($this->user->role != 2)
		{
			if (!in_array($this->user->id, array($item->user_id_from,$item->user_id_to))) return false;
		}
		return parent::check_access($item);
	}

}