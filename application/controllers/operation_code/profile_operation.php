<?php

interface profileInfo
{
    public function get_user_info($user_id);
}

class Profile_operation extends CI_Controller implements profileInfo
{

    var $CI, $session;

    public function index()
    {
        $this->CI = &get_instance();
        $this->CI->load->library("session");
        $this->session = $this->CI->session;
    }

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('connection_model');
        //$this->load->model('page_model');
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function get_user_info($user_id)
    {
        $this->load->model('user_locations_model');
        $data['query'] = $this->user_locations_model->getPlace($user_id); //get locations

        $this->load->model('user_links_model');
        $condition = array('user_id'=>$user_id);
        $query = $this->user_links_model->getLinks($condition);
        $data['link_query'] = $query;

        $data['info'] = $this->user_model->user_info($user_id);
        $data['schools'] = $this->user_model->get_user_schools($user_id);
        $data['edit_info_url'] = base_url().'personal_info';
        return $data;
    }

    //display personal info
    public function get_personal_info($user_id)
    {
        return 'aaaaaaaaaaa';
        $data['info'] = $this->user_model->user_info($user_id);
        list($data['year'], $data['month'], $data['day']) = explode('-', $data['info'][0]['birthday']);
        if($data['info'][0]['gender'] == 'm')
        {
            $data['m_gender'] = TRUE;
        }
        else if($data['info'][0]['gender'] == 'f')
        {
            $data['f_gender'] = TRUE;
        }

        $years = range(date('Y')+10, 1900);
        $data['years'] = $years;
        $data['schools'] = $this->user_model->get_user_schools($user_id);

        $data['contact'] = $this->user_model->contact_info($user_id);
        $data['quotes'] = $this->user_model->get_quote();

        $info_array = array();
        foreach($data['add_info'] as $key => $value)
        {
            //echo $value['type'].'->'.$value['type_name'].' <a href="/index.php/user/remove_info/'.$value['type_id'].'">delete</a><br>';
            if ($info_array[$value['type']] == null)
            {
                $info_array[$value['type']]  = array();
            }
            array_push($info_array[$value['type']] , array("id" => $value['type_id'], "name" => $value['type_name']));
        }
        $data['add_info_groups'] = $info_array;


        $data['stage'] = 'edit_info';

        return $data;
    }

}