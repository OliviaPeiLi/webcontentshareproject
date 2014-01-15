<?php

class publishers_model extends MY_Model {
	
	protected $validate = array(
		'url' => array('label' => 'URL','rules' => 'trim|required|valid_url|is_unique[publishers.url]'),
	);
	

}