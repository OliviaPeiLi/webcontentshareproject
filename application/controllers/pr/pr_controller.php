<?php

class Pr_controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        

        $this->load->model('page_model');
        $this->load->model('pr_model');
        $this->load->model('post_model');
        $this->load->helper('page_helper');
    }

    function index()
    {
        $data = load_page_data();
        //print_r($data['uri_name']);
        //print_r($data['page_info']);
        //print_r($data['main_pic']);
        $page_id = $data['page_id'] = $this->uri->segment(2);
        $data['prs'] = $this->pr_model->show_pr($page_id,$this->uri->segment(3));
        $data['pr_lock'] = $this->pr_model->get_pr_lock($page_id);
        $data['owner_priv'] = $this->page_model->privilege($page_id,'OWNER');
        $data['admin_priv'] = $this->page_model->privilege($page_id,'ADMIN');
        $data['header'] = 'header';
        $data['view_type'] = 'pr';
        if($this->uri->segment(3))
        {
            $data['view_type'] = 'pr_detail';
        }
        //$data['title'] = 'pr';
        $data['title'] = $data['page_info'][0]['page_name'].': PR';
        //$data['main_content'] = 'pr/pr';
        $data['main_content'] = 'interests/page';
        $data['stage'] = 'pr';
        $noheader = $this->input->get('header',true);
        if ($noheader === 'none')
        {
            $this->load->view($data['main_content'],$data);
        }
        else
        {
            $this->load->view('includes/template',$data);
        }
    }


    function lock_pr()
    {
        $this->pr_model->pr_lock($this->uri->segment(2),$this->uri->segment(3));
        redirect('pr_page/'.$this->uri->segment(2),'refresh');
    }


    function new_pr()
    {
        $this->load->library('form_validation');

        // field name, error message, validation rules
        $this->form_validation->set_rules('pr_title', 'Title', 'trim|required');

        if($this->form_validation->run() == FALSE)
        {
            $data['owner_priv'] = $this->page_model->privilege($this->uri->segment(3),'OWNER');
            $data['admin_priv'] = $this->page_model->privilege($this->uri->segment(3),'ADMIN');
            $data['header'] = 'header';
            $data['main_content'] = 'pr/pr';
            $data['title'] = '';
            $noheader = $this->input->get('header',true);
            if ($noheader === 'none')
            {
                $this->load->view($data['main_content'],$data);
            }
            else
            {
                $this->load->view('includes/template',$data);
            }
        }
        else
        {
            if($this->pr_model->check_pr())
            {
                $pr_id = $this->pr_model->insert_pr();
                $newsfeed_id = $this->post_model->insert_newsfeed('pr', $pr_id, 'page','','','',$this->uri->segment(2));
                $this->pr_model->newsfeed_pr($pr_id, $newsfeed_id);

                if(!$this->session->userdata['page_id'])
                {
                    //for points system
                    $this->load->helper('points_helper');
                    update_points($this->input->post('page_id', true), $this->session->userdata('id'), 1, 'pr');
                }

                redirect('pr_page/'.$this->input->post('page_id', true));
            }
            else
            {
                $data['pr_error'] = 'PR exists';
                echo $data['pr_error'];
                redirect('pr_page/'.$this->input->post('page_id', true));
            }
        }

    }

    function delete_pr()
    {
        $data['owner_priv'] = $this->page_model->privilege($this->uri->segment(2),'OWNER');
        $data['admin_priv'] = $this->page_model->privilege($this->uri->segment(2),'ADMIN');

        if($data['owner_priv'] == 1 || $data['admin_priv'] == 1)
        {
            $this->pr_model->delete_pr($this->uri->segment(2),$this->uri->segment(3));

            if(!$this->session->userdata['page_id'])
            {
                //for points system
                $this->load->helper('points_helper');
                update_points($this->uri->segment(2), $this->session->userdata('id'), -1, 'del_pr');
            }
        }
        redirect('pr_page/'.$this->uri->segment(2));
    }

}
