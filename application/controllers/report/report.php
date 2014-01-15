<?php

class Report extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('report_model');
    }

    function report()
    {
        $user_id = $this->session->userdata('id');
        $type = $this->input->post('type');
        $id = $this->input->post('id', true);
        $insert_data = array('user_id'=>$user_id, $type.'_id'=>$id);
        $r_id = $this->report_model->check_report($insert_data);
        if($r_id)
        {
            $this->report_model->update_report($insert_data);
        }
        else
        {
            $this->report_model->new_report($insert_data);
        }
    }
}
?>
