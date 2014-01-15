<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Ban_site extends ADMIN
{

	protected $model = 'ban_site_model';

	protected $list_fields = array(
								 'id' => 'primary_key',
								 'name' => 'string',
								 'url' => 'string'
							 );
	protected $form_fields = array(
								 'name' => 'string',
								 'url' => 'string'
							 );

	protected $filters = array(
							 'id' => 'primary_key',
							 'name' => 'string',
							 'url' => 'string'
						 );	

}