<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . 'controllers/operation_code/main_page.php');

class Chat_operation extends Main_page {

	var $CI, $session;
	
	function __construct()
	{
		//parent::__construct();
		$this->CI = &get_instance();
		$this->CI->load->library("session");
		$this->session = $this->CI->session;
		$this->model = $this->CI->model;
	}

	public function index()
	{
		$this->CI = &get_instance();
		$this->CI->load->library("session");
		$this->session = $this->CI->session;
	}


	/**
	 * login 
	 * 	Store the session variables such as logged_in=true/false,
	 * 	userid account & who is chatting with (friend variable)
	 * 
	 * @access public
	 * @return void
	 */
	function login($userid) {
		$this->CI->load->library('session');
		$this->CI->load->helper(array('form', 'url'));

		// $userid = $this->input->post('userid');

		//
		$this->CI->load->model('Cometchat_database');
		echo 'aaa';
		$session = $this->CI->Cometchat_database->get_session($userid);
		$friendlist = array();
		if ($session->num_rows() == 1) {
			 foreach ($session->result() as $s) {
				 $friendlist = preg_split("/,/", $s->friendlistid);
			 }
		}

		// should load more configuration setting from database
		$this->session->set_userdata(array(
				'logged_in' => TRUE, 
				'userid' => $userid,
				'friend' => 0,
				'first_buddy_get' => true,
				'friendlist' => $friendlist
		));

		// clean offline status
		$cleantime = $this->CI->Cometchat_database->online_interval();
		$this->CI->Cometchat_database->clean_offline($cleantime);

//		redirect('main_page');
	}

	/**
	 * logout 
	 * 	Handle logout button pressed. User is set as offline
	 * 	and session is detroyed
	 * 
	 * @access public
	 * @return void
	 */
	public function logout()
	{
		$this->load->library('session');

		// update offline status to the cometchat_status table
		$this->load->database();
		$this->load->model('Cometchat_database');
		$this->Cometchat_database->set_offline($this->session->userdata('userid'));

		$this->load->helper('url');
		$this->session->sess_destroy();
		
//		redirect('main_page_not_login');
	}


	/**
	 * show_page 
	 * 	Show the main page & pass variable to the view
	 *		friendid = 0 when user did not click to any person for chatting yet
	 *		friendid <> 0: user is chatting with a person
	 *				in this case, $friend keeps the name of the person
	 *				for showing it in view
	 */
	function show_page() {
		//$this->load->library('session');
		//$this->load->helper(array('form', 'url'));
		$data = array('name'=>'aaa','id'=>'bbb');

		$this->CI->load->database();
		$this->CI->load->model('Cometchat_database');

		$userid = $this->session->userdata('id');

		$data['user'] =  $this->CI->Cometchat_database->get_name($userid);
		$data['friendid'] =  $this->CI->session->userdata('friend');
		$data['friend'] 	  =  $this->CI->Cometchat_database->get_name($this->session->userdata('friend'));

		$data['friendlist'] = array();
		$data['friendlistid'] = array();

		foreach ($this->session->userdata('friendlist') as $f) {
			$name = $this->CI->Cometchat_database->get_name($f);
			$data['friendlistid'][] = $f;
			$data['friendlist']["$f"] = $name;
		}

		if ($this->CI->Cometchat_database->is_online($data['friendid']) == true) {
			$data['online'] =  0;		// is online
		} else {
			$data['online'] =  1;		// is offline
		}

		// transfer previous login session for this time login
		$session = $this->CI->Cometchat_database->get_session($userid);
		$data['friendlistid'] = $this->session->userdata('friendlist');
		$data['havefriend'] = 0;
		if ($session->num_rows() == 1) {
			 foreach ($session->result() as $s) {
				 //restore session
				 $data['selfonline'] = $s->selfonline;
				 $data['soundmute'] = $s->soundmute;

				 if ($s->friendlistid != null) {
					  $data['havefriend'] = 1;
				 }

				 $data['friendlist_name'] = array();
				 foreach ($data['friendlistid'] as $d) {
				 	$data['friendlist_name'][] = $this->CI->Cometchat_database->get_name($d);
				 }

			 }
		} else {
			 //initial session
			 $data['selfonline'] = 1;
			 $data['soundmute'] = 0;
			 $data['friendlist_name'] = array();
		}

		$data['myname'] = $this->CI->Cometchat_database->get_name($userid);

		return $data;
		// testing
		// $this->load->view('main_page', $data);
	}

	/**
	 * _get_buddy 
	 * 	return below information to client
	 *			- friendlist (list of id of friend that is chatting with)
	 *			- friendlist_name (name of person who is chatting with)
	 *			- online_id/online_name (id/name of friends who are online)
	 *			- offline_id/offline_name (id/name of friends who are offline)
	 *			- new_message_in_second=true/false
	 *				true: there is a new message (for sound)
	 *			- typing:
	 *				1: the person who is chatting with, is typing a message
	 */
	public function _get_buddy($second, $friend, $get_all_message)
	{
//		 echo "time begin = " . time() . "<br />";
		 $this->CI->load->library('session');
		 $userid = $this->session->userdata('userid');

		 $this->CI->load->model('Cometchat_database');

		 $online = $this->CI->get_buddy_online();

		 $friendlistid = array();
		 $data['online_id'] = array();
		 $data['online_name'] = array();
		 foreach ($online as $b) {
		 	$friendlistid[] = $b;
 			$data['online_id'][] = (int)$b;
 			$data['online_name'][] = $this->CI->Cometchat_database->get_name($b);
 			$data['unread'][] = $this->CI->Cometchat_database->get_unread_messages($b, $userid);
		 }

		 if ($this->CI->Cometchat_database->is_online($userid) == true) {
			  $data['selfonline'] = 1;
		 } else {
			  $data['selfonline'] = 0;
		 }


		 $all = $this->CI->Cometchat_database->get_buddy($userid);

		 $data['offline_id'] = array();
		 $data['offline_name'] = array();
		 foreach ($all as $buddy) {
					$b = $buddy->user2_id;
				
			  if (!in_array($b, $friendlistid)) {
					$friendlistid[] = $b;
 					$data['offline_id'][] = (int)$b;
 					$data['offline_name'][] = $this->CI->Cometchat_database->get_name($b);
 					$data['unread'][] = $this->CI->Cometchat_database->get_unread_messages($b, $userid);
			  }
		 }

		 // new message withint 3s
		 $data['new_mess'] = $this->CI->Cometchat_database->is_new_message($userid, 5);

		 // check typing from friend
		 $data['typing'] = ($this->CI->Cometchat_database->is_typing($userid, $friend, time(), $second) == true ? 1 : 0 );
		 $data['new_message'] = array();
		 if ($friend <> 0) {
			 if ($get_all_message == 1) {
			 	$message = $this->CI->Cometchat_database->get_messages($userid, $friend, false);
			 } else {
			 	$message = $this->CI->Cometchat_database->get_messages($userid, $friend, true);
			 }

			 foreach ($message as $m) {
				 if ($m->from == $userid) {
			 		$data['new_message'][] = "<b  style=\"color:red\">" . $this->CI->Cometchat_database->get_name($m->from) . "</b>: ". $this->replace_link($m->message) . "<br />";
				 } else {
			 		$data['new_message'][] = "<b style=\"color:blue\">" . $this->CI->Cometchat_database->get_name($m->from) . "</b>: " . $this->replace_link($m->message) . "<br />";
				 }
			 }
		 }

		 $data['status'] = 1;
//		 echo "time end = " . time() . "<br />";

		 return json_encode($data);
		 exit();
	}

	public function get_buddy_online()
	{
		$this->load->library('session');
		$userid = $this->session->userdata('userid');
	
		$this->load->database();
		$this->load->model('Cometchat_database');

		// get all friends (online + offline)
		$friends = $this->Cometchat_database->get_buddy($userid);

		$data['code'] = array();
		$data['result'][] = array();

		foreach ($friends as $f) {
				if (!in_array($f->user2_id, $data['code'])) {
					 // online
					if ($this->Cometchat_database->is_online($f->user2_id)) {
			 			$data['code'][] = $f->user2_id;
			 			$data['result'][] = "<i><a href=\"main_page/chat_with/".$f->user2_id."\">" . $this->Cometchat_database->get_name($f->user2_id) . "</a></i><br />";
					}
				}
		}

		return $data['code'];
		exit();

	}

}

/* End of file main_page.php */
/* Location: ./application/controllers/operation_code/chat_operation.php */
