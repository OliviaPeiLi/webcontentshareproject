<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class System_notification extends ADMIN
{

	protected $model = 'system_notification_model';

	protected $list_fields = array(
								 'id' => 'primary_key',
								 'title' => 'string',
								 'content' => 'string',
								 'time' => 'time'
							 );
	protected $form_fields = array(
								 'title' => 'string',
								 'content' => 'string',
								 'time' => 'readonly'
							 );

	protected $filters = array(
							 'id' => 'primary_key',
							 'title' => 'string',
							 'time' => 'time'
						 );

}