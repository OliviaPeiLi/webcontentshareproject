<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Newsletters extends ADMIN {

	//protected $model = 'newsletter_model';
	protected $list_actions = array();

	protected $list_fields = array(
								'id' => 'primary_key',
								'subject' => 'string',
								'newsletter_time' => 'datetime',
								'precent_sent' => 'precent' 
							 );
	protected $form_fields = array(
								 
							 );
	private $templates_path = 'application/views/email_templates/';
	
	public function create_get($success = FALSE) {
		$this->load->view('admin/layout', array(
			'view' => 'newsletters/create',
			'templates' => $this->get_templates(),
			'success'=>$success
		));
	}
	
	public function create_post() {
		$parser = $this->load->library('Parser');
		
		$data = $this->input->post();
		$data['base_url'] = Url_helper::base_url();
		
		$template = $data['template']; unset($data['template']);
		
		$preview_html = $this->parser->parse_objs('email_templates/'.$template, $data, TRUE);		
		
		$data['template'] = $template;
		
		$newsletter_id = $this->newsletter_model->insert(array(
			'subject'=>$data['subject'], 
			'msg'=>$preview_html,
			//'json_data_tmpl'=>json_encode($data), //no migration is created for this field
			'newsletter_time'=>date("Y-m-d H:i:s")
		));
		
		if ($newsletter_id)	{
			$this->create_get(true);
		}
	
	}
	
	public function preview_get() {

		$template = $this->input->get('template');
		
		if (!in_array($template, $this->get_templates())) die('Template: '.$template.' not found...');
		
		$parser = $this->load->library('Parser');
	
		$data = $parser->get_vars('email_templates/'.$template);
		
		$e_return = '
			<p>
				<label for="subject">Email Subject</label>
				<input type="text" name="subject" value="" />
			</p>		
		';
		
		$e_return .= $this->generate_template_form($data);
		
		$e_return .= '
			<p>
				<label for="test_email">Test Email</label>
				<input type="text" name="sentto" value="" placeholder="Send test email to."/>
			</p>		
		';		
		
		echo $e_return;
	}
	
	public function preview_post() {
		
		$data = $this->input->post();
		$data['base_url'] = Url_helper::base_url();
		$parser = $this->load->library('Parser');
		
		$template = $data['template']; unset($data['template']);
		$preview_html = $this->parser->parse_objs('email_templates/'.$template, $data, TRUE);
		
		if (isset($data['sentto']) && !empty($data['sentto']) && Email_helper::valid_email($data['sentto']))	{
			 Email_helper::SendEmail( $data['sentto'], $data['subject'], $preview_html);
		}
		
		echo $preview_html;
	}
	
	private function generate_template_form($data, $basename='', $create_row = false) {
		
		$ret = array();
		
		foreach ($data as $name=>$type) {
			if ($name == 'base_url') continue;
			if (is_array($type) && strpos($name, ':') === false) {
			
				$ret[] = '<fieldset>'
							.$this->generate_template_form($type, ($basename ? $basename."[0][$name]" : $name) , true).'
							</fieldset>';
			
			} elseif (strpos($name, ':') !== false) {
			
				list($label, $model) = explode(':', $name);
				
				//$convert_rows = str_replace(array("collection_row","drop_column"),array("add_collection_column","add_drop_column"),$name);

				$ret[] = '<label>'.$label.' '.($create_row ? '<a href="" class="create_row">Add '.$basename.'</a>' : '').'</label>
							<br/>
							<span class="input_wrap">
								<input type="text" name="'.($basename ? $basename."[0][$name][]" : $name.'[]').'" placeholder="'.ucfirst($model).' ID" value=""/>
							</span>
							<a href="" class="create_column">+'.$label.'</a>
						    
				';
			
			} else {
			
				$ret[] = '<label>'.$name.'</label>
						  <br/>
						  <span class="input_wrap">
						  	<input type="text" name="'.($basename ? $basename."[0][$name]" : $name).'" placeholder="'.$name.'" value=""/>
						  </span>';
			
			}
		
		}
		
		$class = $basename ? 'column' : '';
		return '<p class="'.$class.'">'.implode('</p><p class="'.$class.'">', $ret)."</p>";
	}
	
	private function get_templates() {
		if (!is_dir(BASEPATH.'../'.$this->templates_path)) die('Email templates dir not found');
		$templates = array();
		foreach (glob(BASEPATH.'../'.$this->templates_path.'*.php') as $template) $templates[] = str_replace('.php', '', basename($template)); 
		return $templates;
	}
}
