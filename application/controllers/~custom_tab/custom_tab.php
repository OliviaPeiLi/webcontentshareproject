<?php

class Custom_tab extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        

        $this->load->model('page_model');
        $this->load->model('custom_tab_model');
    }

    //creates new tab for a page
    function new_tab()
    {
        $page_id = $this->uri->segment(2);
        //$tab_id = $this->uri->segment(3);
        $uri_name = $this->uri->segment(3);

        $this->load->library('form_validation');

        // field name, error message, validation rules
        $this->form_validation->set_rules('tab_name', 'Tab Name', 'trim|required');


        if($this->form_validation->run() == FALSE)
        {
            $data['privilege'] = $this->page_model->privilege($page_id);
            //$this->load_tab($tab_id);
            redirect('interests/'.$uri_name.'/'.$page_id);
        }
        else
        {
            $tab_id = $this->custom_tab_model->insert_tab($page_id);
            redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
            //$this->load_tab($this->uri->segment(3));
        }

    }

    //tab activation
    function activate()
    {
        $page_id = $this->uri->segment(2);
        $tab_id = $this->uri->segment(3);
        $uri_name = $this->uri->segment(4);
        $tab = $this->custom_tab_model->get($tab_id);
        if ($tab->page->owner_id != $this->session->userdata['id']) redirect('/');

        $tab->update(array('activated' => '1'));
        //$this->custom_tab_model->act_deact($page_id,$tab_id,'1');
        //$this->load_tab($this->uri->segment(3));
        redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
    }

    //tab deactivation
    function dectivate()
    {
        $page_id = $this->uri->segment(2);
        $tab_id = $this->uri->segment(3);
        $uri_name = $this->uri->segment(4);

        $this->custom_tab_model->act_deact($page_id,$tab_id,'0');
        //$this->load_tab($this->uri->segment(3));
        redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
    }

    //edits tab name
    function edit_name()
    {
        $page_id = $this->uri->segment(2);
        $tab_id = $this->uri->segment(3);
        $uri_name = $this->uri->segment(4);

        $this->load->library('form_validation');

        // field name, error message, validation rules
        $this->form_validation->set_rules('new_tab_name', 'Tab Name', 'trim|required');


        if($this->form_validation->run() == FALSE)
        {
            $data['privilege'] = $this->page_model->privilege($page_id);
            //$this->load_tab($this->uri->segment(3));
            redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
        }
        else
        {
            $this->custom_tab_model->update_tab_name($page_id,$tab_id);
            //$this->load_tab($this->uri->segment(3));
            redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
        }
    }

    //deletes a tab
    function delete_tab()
    {
        $page_id = $this->uri->segment(2);
        $tab_id = $this->uri->segment(3);
        $uri_name = $this->uri->segment(4);

        $this->custom_tab_model->delete_tab($page_id,$tab_id);
        //$this->load_tab($this->uri->segment(3));
        redirect('interests/'.$uri_name.'/'.$page_id);
    }

    //addition of text component
    function add_text()
    {
        $page_id = $this->uri->segment(2);
        $tab_id = $this->uri->segment(3);
        $uri_name = $this->uri->segment(4);

        $this->load->library('form_validation');

        // field name, error message, validation rules
        $this->form_validation->set_rules('type_add', 'Type', 'trim|required');
        $this->form_validation->set_rules('new_content', 'Content', 'trim|required');


        if($this->form_validation->run() == FALSE)
        {
            $data['privilege'] = $this->page_model->privilege($page_id);
            //$this->load_tab($this->uri->segment(3));
            //$data['redirect'] = 'page/index/'.$this->uri->segment(2).'/'.$this->uri->segment(3);
            redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
        }
        else
        {
            $this->custom_tab_model->add_component($tab_id);
            //$this->load_tab($this->uri->segment(3));
            //$data['redirect'] = 'page/index/'.$this->uri->segment(2).'/'.$this->uri->segment(3);
            redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
        }
    }

    //edit component
    function edit_component()
    {
        $page_id = $this->uri->segment(2);
        $tab_id = $this->uri->segment(3);
        $component_id = $this->uri->segment(4);
        $uri_name = $this->uri->segment(5);

        $this->load->library('form_validation');

        // field name, error message, validation rules
        $this->form_validation->set_rules('new_tab_content', 'New Content', 'trim|required');
        if($this->form_validation->run() == FALSE)
        {
            $data['privilege'] = $this->page_model->privilege($page_id);
            //$this->load_tab($this->uri->segment(3));
            redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
        }
        else
        {
            $this->custom_tab_model->edit_piece($tab_id,$component_id);
            //$this->load_tab($this->uri->segment(3));
            redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
        }
    }

    //delete component
    function delete_component()
    {
        $page_id = $this->uri->segment(2);
        $tab_id = $this->uri->segment(3);
        $component_id = $this->uri->segment(4);
        $uri_name = $this->uri->segment(5);

        $this->custom_tab_model->delete_piece($tab_id,$component_id);
        //$this->load_tab($this->uri->segment(4));
        redirect('interests/'.$uri_name.'/'.$page_id.'/'.$tab_id);
    }

    //sort components
    function sort_components()
    {
        $page_id = $this->uri->segment(2);
        $tab_id = $this->uri->segment(3);
        echo 'aaa';
        foreach($this->input->post('order', true) as $k => $v)
        {
            $this->custom_tab_model->update_order($v, $tab_id, $k);
        }
        print_r($this->input->post('order', true));
    }
    /*
    	function load_tab($tab_id)
    	{
    		$data['current_tab']=$tab_id;
    		$data['tab']= $this->custom_tab_model->show_tab($this->uri->segment(3),$tab_id);
    		$data['tab_content'] = $this->custom_tab_model->show_components($tab_id);
    		$data['header'] = 'header';
    		$data['main_content'] = 'custom_tab/custom_tab';
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
    */
}
