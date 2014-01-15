<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include( APPPATH.'controllers/operation_code/main_page.php' );

class Chat_controller extends CI_Controller {

	var $CI, $session;
	var $mainpage_obj;


	function get_operation_code()
	{
		$this->mainpage_obj = new Chat_operation();
	}

	function login_ok($userid)
	{

		$this->get_operation_code();

		$this->mainpage_obj->login($userid);
		$data = $this->mainpage_obj->show_page();
		$this->load->view('main_page', $data);
	}


	public function ajax_send_message() {
		$to = $this->input->post('friend');
		$message = $this->input->post('message');

		$this->get_operation_code();
		echo $this->mainpage_obj->ajax_send_message($to, $message);
	}

	public function ajax_get_message() {
		 $userid2 = $this->input->post('friend');
		 $unreadonly = ( $this->input->post('unread') == 0 ? false : true );

		 $this->get_operation_code();
		 echo $this->mainpage_obj->ajax_get_message($userid2, $unreadonly);
	}

	function ajax_change_active_friend()
	{
		$friendid = $this->input->post('friendid');

		$this->get_operation_code();
		echo $this->mainpage_obj->ajax_change_active_friend($friendid);
	}

	function ajax_get_buddy()
	{
		$second = $this->input->post('second');
		$friend = $this->input->post('chatwith');
		$get_all_message = $this->input->post('get_all_message');

		$this->get_operation_code();
		echo $this->mainpage_obj->ajax_get_buddy($second, $friend, $get_all_message);
	}

	function ajax_chat_with()
	{
		$friend = $this->input->post('friend');

		$this->get_operation_code();
		echo $this->mainpage_obj->ajax_chat_with($friend);
	}

	function ajax_stay_online()
	{
		$this->get_operation_code();
		echo $this->mainpage_obj->ajax_stay_online($friend);
	}
	
	function ajax_close_friend()
	{
		$friendid = $this->input->post('friendid');

		$this->get_operation_code();
		echo $this->mainpage_obj->ajax_close_friend($friendid);
	}

	function ajax_save_session()
	{
		 $selfonline = $this->input->post('selfonline');
		 $soundmute = $this->input->post('soundmute');

		$this->get_operation_code();
		echo $this->mainpage_obj->ajax_save_session($selfonline, $soundmute);

	}

	function ajax_typing()
	{
		$friendid = $this->input->post('chatwith');

		$this->get_operation_code();
		echo $this->mainpage_obj->ajax_typing( $friendid );
	}

	function logout()
	{
		$this->get_operation_code();
		$this->mainpage_obj->logout();
		$this->load->helper(array('form', 'url'));
		$this->load->view('welcome_message');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/business_code/welcome.php */
