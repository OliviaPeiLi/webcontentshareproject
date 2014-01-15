<?php

class Privacy_controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }


    //display the privacy setting page
    function privacy_page()
    {
        $data['header'] = 'header';
        $data['title'] = 'privacy';
        $data['main_content'] = 'privacy/privacy';
        $this->load->view('includes/template', $data);
    }

}
?>