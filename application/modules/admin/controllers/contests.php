<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Contests extends ADMIN
{

	//protected $model = 'alpha_user_model';
	protected $model = 'contest_model';

	protected $list_fields = array(
								'id'		 => 'primary_key',
								'user'		 => 'belongs_to',
								'name' 		 => 'string',
								'url' 		 => 'link',
								'logo' 		 => 'image_thumb',
								'is_simple'  => 'checkbox',
								'created_at' => 'datetime',
							 );

	protected $form_fields = array(
								'user'		 => 'readonly',
								'name' 		 => 'string',
								'url' 		 => 'url',
								'logo' 		 => 'img',
								'is_simple'  => 'boolean',
								'created_at' => 'readonly',
							 );

	protected $filters = array(
							 'id' 			 => 'primary_key',
							 'name'  		 => 'string',
							 'created_at' 	 => 'time'
						 );
							 
	protected function filter($items)
	{
		if ($this->user->role != 2)
		{
			$items->_set_where(array("(user = {$this->user->id})"));
		}
		return parent::filter($items);
	}

	protected function check_access($item)
	{
		if ($this->user->role != 2)
		{
			if (!in_array($this->user->id, array($item->user))) return false;
		}
		return parent::check_access($item);
	}
}