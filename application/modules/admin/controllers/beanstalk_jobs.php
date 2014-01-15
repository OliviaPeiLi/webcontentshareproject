<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Beanstalk_jobs extends ADMIN
{

	protected $model = 'beanstalk_job_model';

	protected $list_actions = array('delete'=>'Delete');

	protected $list_fields = array(
								 'id' => 'primary_key',
								 'job_id' => 'number',
								 'type' => 'string',
								 'data' => 'serialized_array',
								 'created_at' => 'time',
								 'started_at' => 'time',
								 'finished_at' => 'time'
							 );
	protected $form_fields = array(
								 'job_id' => 'number',
								 'type' => 'string',
								 'data' => 'serialized_array',
								 'created_at' => 'time',
								 'started_at' => 'time',
								 'finished_at' => 'time'
							 );
							 
	protected $filters = array(
							 'id' => 'primary_key',
							 'job_id' => 'number',
							 'type' => array(
	 									'' => 'NONE',
	 									'ght' => 'generate html thumbnail',
	 									'scr' => 'screenshots',
	 									'cl-bm' => 'clean_html',
	 								)
						 );

}