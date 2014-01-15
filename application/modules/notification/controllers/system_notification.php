<?php
class System_notification extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->lang->load('includes/includes_views', LANGUAGE);
	}
	
	public function get() {
		if (!$notification = $this->get_notification()) return ;
		
		$this->load->view('notification/system_notification', array(
			'notification' => $notification
		));
	}
	
	private function get_notification() {
		$notification = $this->system_notification_model->get_by(array('id >'=>$this->user->system_notification));
		if (! $notification) return ;
		
		if ($notification->id == 1 && !preg_match('/('.implode('|', $this->config->item('avatar_color')).')/i', $this->user->avatar)) {
			$this->user->system_notification =  1;
			$this->user->update(array('system_notification' => $this->user->system_notification));
			$notification = $this->system_notification_model->get_by(array('id >'=>$this->user->system_notification));
			if (! $notification) return ;
		}
		
		if ($notification->id == 3 && $this->user->password) {
			$this->user->system_notification = 3;
			$this->user->update(array('system_notification' => $this->user->system_notification));
			$notification = $this->system_notification_model->get_by(array('id >'=>$this->user->system_notification));
			if (! $notification) return ;
		}
		
		return $notification;
	}
	
	public function remove($id) {
		$this->user->system_notification = $id;
		$this->user->update(array('system_notification' => $this->user->system_notification));
		
		$content = '';
		if ($notification = $this->get_notification()) {
			$content = $this->load->view('notification/system_notification_'.$notification->template_name, array(
				'notification' => $notification
			), true);
		}
		
		die(json_encode(array(
			'status' => true,
			'content' => $content
		)));
	}
}