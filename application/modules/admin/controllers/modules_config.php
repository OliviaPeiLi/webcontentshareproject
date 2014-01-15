<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Modules_config extends ADMIN
{
	protected $model = 'modules_config_model';

	protected $list_actions = array('edit'=>'Edit','delete'=>'Delete');
	protected $list_fields = array(
								 'id'          => 'primary_key',
								 'name'        => 'string',
								 'development' => 'checkbox',
								 'staging'     => 'checkbox',
								 'production'  => 'checkbox',
								 'updated'     => 'time',
								 'custom'      => 'string',
							 );
	protected $form_fields = array(
								 'name'        => 'string',
								 'development' => 'checkbox',
								 'staging'     => 'checkbox',
								 'production'  => 'checkbox',
								 'custom'      => 'string',
								 'updated'     => 'readonly',
							);
	protected $filters = array(
								 'name' 			=> 'string',
								 'development' 		=> 'number',
								 'staging' 			=> 'number',
								 'production' 		=> 'number',
						 );

}