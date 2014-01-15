<?php

class Beanstalk_job_model extends MY_Model {

	protected function _run_after_set($row=null) {
		if(isset($row['id'])) {
			$this->load->model('beanstalk_job_model');
			$job = $this->beanstalk_job_model->get($row['id']);
			if($job->started_at != '0000-00-00 00:00:00' && $job->finished_at != '0000-00-00 00:00:00') {
				if(strtotime($job->finished_at) - strtotime($job->started_at) > 60) {
					$users = $this->user_model->get_many_by(array('role'=>'2'));
					foreach($users as $user) {
						Email_helper::SendEmail($user->email,'','');
					}
				}
			}
		}
	}

	/**
	 * USed in the Admin
	 */
	public function get_jobs_type() {
		$query = $this->db->select('DISTINCT type', FALSE)
				 ->get($this->_table);
		return $query->result();
	}

	/**
	 * USed in the admin
	 */
	public function get_graph_data($job_type, $limit=30) {
		$this->db->select('job_id, ROUND(AVG(UNIX_TIMESTAMP(created_at)*1000)) AS created_at, started_at-created_at AS start_delay, finished_at-started_at AS processing_time, job_id, type', FALSE)
		->where('type', $job_type)
		->where('started_at >', 0)
		->order_by('created_at', 'desc')
		->group_by('created_at')
		->limit($limit);
		$query = $this->db->get($this->_table);

		return $query->result();
	}

}