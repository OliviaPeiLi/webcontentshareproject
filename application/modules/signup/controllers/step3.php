<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Step3 extends MX_Controller {
	
	public function index() {
		$this->user_visit_model->update($this->session->userdata('id'), array('preview'=>'0'));
		
		return parent::template('signup/step3_walkthrough', array(), 'Categories');
	}
}