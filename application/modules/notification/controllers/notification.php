<?php

class Notification extends MX_Controller
{

	function __construct()
	{
		parent::__construct();
		
		$this->lang->load('notification/notification', LANGUAGE);
	}

	public function get_all() {
		
	    $per_page = $this->config->item('notification_number');
	    $per_page = $per_page ? $per_page : 12;
		$page = $this->input->get('page')+1;

		//make all notification as read
		$this->notification_model->update_by(array('user_id_to'=>$this->session->userdata('id')),array('read'=>'1'));
		$groups = $this->notification_model->paginate($page, $per_page)->get_grouped($this->user->id);

		if ( $this->input->is_ajax_request() || $this->input->get('json') == 'true' ) {

			$ret = array();

			foreach ($groups as $key=>$group) {

				$group = array( 'notifications' => $this->notification_model->jsonfy($group) );

				if ( $key == date( 'Y-m-d' ) ) {
					$group['date_string'] = $this->lang->line( 'notification_today_lexicon' );
				}
				else if ( $key == date( 'Y-m-d', strtotime( 'yesterday' ) ) ) {
					$group['date_string'] = $this->lang->line( 'notification_yesterday_lexicon' );
				}
				else {
					$group['date_string'] = date( 'F d, Y', strtotime( $key ) );
				}
				$ret[] = $group;
			}

			header( 'Content-Type: application/json' );
			echo json_encode( $ret );
		
		} else {

			$cnt = 0;
			foreach ($groups as $nk => $nv) {
				foreach ($nv as $k => $v) {
					if ($v->type == 'badge')	{
						unset($groups[$nk][$k]);
					}
				
					if ($v->type == 'follow') {
					  $groups[$nk][$k]->is_following = $this->user_model->is_following($this->user,$v->user_id_from);
					}

				}
				if (count($nv) == 0)	{
					unset($groups[$nk]);
				}
			}

			return parent::template($this->is_mod_enabled('design_ugc') ? 'notification/notifications_list_ugc' : 'notification/notifications_list', array(
				'per_page' => $per_page,
				'grouped_notifications' => $groups
			), $this->lang->line('notification_all_notification_title'));

		}
	}

	public function get_read() {

	    $per_page = $this->config->item('notification_number');
	    $per_page = $per_page ? $per_page : 12;
		$page = $this->input->get('page')+1;

		//make all notification as read
		$notifications = $this->notification_model->order_by('id','desc')->paginate($page, $per_page)
			->get_many_by(array('user_id_to'=>$this->user->id));

		
		if ( $this->input->is_ajax_request() || $this->input->get( 'json' ) == 'true' ) {
			header( 'Content-Type: application/json' );
			echo json_encode( $this->notification_model->jsonfy($notifications) );
		} else {

			$notification_count = $this->notification_model->count_by(array('user_id_to'=>$this->user->id));

			$this->load->view($this->is_mod_enabled('design_ugc') ?  'notification/notifications_ugc' : 'notification/notifications', array( 
				'notifications' => array( $this->notification_model->sample() ),
				'notifications_color' => false,
				'notification_count'=>$notification_count,
				'per_page'=>$per_page
			));

			$this->load->view($this->is_mod_enabled('design_ugc') ?  'notification/notifications_ugc' : 'notification/notifications', array(
				'notifications' => $notifications,
				'notifications_color' => true,
			), false);

		}
	}
	
	public function update_mark_read() {
		$ids = $this->input->post('ids');
		if ($ids) {
			$this->notification_model->update_by('id IN ('.implode(',', $ids).')', array('read'=>'1'));
		}
		die(json_encode(array('status'=>true)));
	}

}
?>
