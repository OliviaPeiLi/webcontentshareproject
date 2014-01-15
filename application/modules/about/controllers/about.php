<?php

class About extends MX_Controller
{

	public function __construct() {
		parent::__construct();
		$this->lang->load('about/footer', LANGUAGE);
		
	}
	
	public function presskit() {
		Url_helper::redirect("https://www.dropbox.com/sh/r2wmr4mrgtj5anj/yiruRaAZUs/fandrop%20presskit");
	}
	
	protected function template($page) {
		$template = $this->is_mod_enabled('design_ugc') ? 'about/about_ugc' : 'about/about';
		return parent::template($template, array(
			'disable_landing_top' => true,
			'page' => $page,
		), $this->lang->line('footer_'.$page.'_title'));
	}

	public function docontactus()	{

		$this->load->library('form_validation');

		$name = $this->input->post("name");
		$email = $this->input->post("email");
		$msg_body = $this->input->post("msg_body");

        // field name, error message, validation rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'End Time', 'required|email');
        $this->form_validation->set_rules('msg_body', 'Message Body', 'required');

      if($this->form_validation->run() == FALSE)
        {
        	die(json_encode(array("status"=>false,"msg"=>"Email was not sent")));
        } else {
        	
			$parser = $this->load->library('Parser');
			
			$data = array(
				"from"=>$name,
				"email"=>$email,
				"message"=>$msg_body,
				"base_url"=>Url_helper::base_url()
			);
			
			$preview_html = $this->parser->parse_objs('about/about_email_template', $data, TRUE);        	

			if (Email_helper::SendEmail( "info@fandrop.com" , "You have new message sent via fandrop contact form", $preview_html))	{
				die(json_encode(array("status"=>true)));
			}
			die(json_encode(array("status"=>false,"msg"=>"Email was not sent")));
        }

	}

	public function index($page = NULL) {
		if ($this->is_mod_enabled('design_ugc')) {
			return parent::template('about/about_main_ugc',array(), 'Fandrop','header',TRUE);
		} else {
			return self::template('main');
		}
	}
	
	public function contactus() {
		return self::template($this->is_mod_enabled('design_ugc') ? 'contactus_ugc' : 'contactus');
	}
	
	public function jobs() {
		return self::template($this->is_mod_enabled('design_ugc') ? 'jobs_ugc' : 'jobs');
	}
	
	public function team() {
		return self::template($this->is_mod_enabled('design_ugc') ? 'team_ugc' : 'team');
	}
	
	public function team2() {
		return self::template($this->is_mod_enabled('design_ugc') ? 'team2_ugc' : 'team2');
	}
	
	public function privacy() {
		return self::template($this->is_mod_enabled('design_ugc') ? 'privacy_ugc' : 'privacy');
	}
	
	public function copyright() {
		return self::template($this->is_mod_enabled('design_ugc') ? 'copyright_ugc' : 'copyright');
	}
	
	public function partners() {
		if ($this->is_mod_enabled('design_ugc')) {
			return parent::template('about/about_partners_ugc',array(), 'Fandrop','header',TRUE);
		} else {
			return self::template('partners');
		}
	}
	
	public function terms() {
		return self::template($this->is_mod_enabled('design_ugc') ? 'terms_ugc' : 'terms');
	}
	
	public function drop_it_button() {
		return self::template($this->is_mod_enabled('design_ugc') ? 'drop_it_button_ugc' : 'drop_it_button');
	}

	public function promoters() {
		if ($this->input->post()) {

			if (!$data = $this->promoters_model->validate($this->input->post())) {
				die(json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors())));
			}
			$this->promoters_model->insert($data);
			die(json_encode(array('status'=>true)));
		}
		return parent::template('promoters_ugc');
	}
	
	public function publishers() {
		if ($this->input->post()) {

			if (!$data = $this->publishers_model->validate($this->input->post())) {
				die(json_encode(array('status'=>false,'error'=>Form_Helper::validation_errors())));
			}
			
			$this->publishers_model->insert($data);
			die(json_encode(array('status'=>true)));
		}
		return parent::template('publishers_ugc',array(), 'Fandrop','header',TRUE);
	}
}