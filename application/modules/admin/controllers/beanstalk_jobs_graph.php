<?php

require_once 'admin.php';

class Beanstalk_jobs_graph extends ADMIN
{

	public function index_get()
	{
		$this->load->helper('flot');
		$this->load->model('beanstalk_job_model');
		$jobs = $this->beanstalk_job_model->get_jobs_type();
		$limit = 40;

		$flots = array();
		foreach($jobs as $i => $job)
		{
			$rows = $this->beanstalk_job_model->get_graph_data($job->type, $limit);
			if($rows)
			{
				$options = array('label'=>$job->type, 'color'=>$i);
				$flots[] = array(
							   'start_delay'	=> get_plot_row($rows, 'created_at', 'start_delay', $options),
							   'processing_time'=> get_plot_row($rows,'created_at', 'processing_time', $options)
						   );
			}
		}
		$data['flots'] = $flots;
		$data['limit'] = $limit;
		$data['view'] = 'beanstalk_jobs';

		$this->load->view('admin/layout', $data);
	}

}



