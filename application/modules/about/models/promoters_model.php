<?php

class Promoters_model extends MY_Model {
	
	protected $validate = array(
		'email' => array('label' => 'Email','rules' => 'trim|required|valid_email|is_unique[promoters.email]'),
	);

}