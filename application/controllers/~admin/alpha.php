<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Alpha extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if($this->session->userdata['user'] != 'superadmin')
        {
            redirect(base_url(),'refresh');
        }
        $this->load->model('alpha_model');
        $this->load->helper('email_helper');
        $this->load->helper('accesskey_helper');
    }
    /*
      function __construct()
      {
    	parent::__construct();
    	$this->load->helper('url');
    	//redirect('/','refresh');
    	if (isset($_SERVER['REMOTE_ADDR'])) {
    		redirect('www.fandrop.com','refresh');
    		//die('unauthorized');
    		//echo 'unauthorized';
    	}
    	//parent::Controller();
    }
    */

    function index()
    {
        $users = $this->alpha_model->get_alpha_users();
        $all_users = $this->alpha_model->get_all_users();
        foreach($users as $k=>$user)
        {
            foreach($all_users as $key=>$val)
            {
                if($user['email'] == $val['email'])
                {
                    $users[$k]['status'] = 'registered';
                }
            }
        }
        $data['users'] = $users;
        $data['main_content'] = 'admin/alpha_users';
        $data['title'] = 'Alpha Users';
        $data['header'] = 'header_admin';
        $this->load->view('includes/template',$data);
    }

    function send_alpha_email()
    {
        $this->load->library('parser');

        $users = $this->input->post('alpha_users', true);

        foreach($users as $email)
        {
            $key = generateAccessKey('10');
            $id = $this->alpha_model->get_userid($email);
            if($id > 0)
            {
                $user_data = $this->alpha_model->get_alpha_users($id);
                $subject = 'You are invited';
                $alpha_link = register_url().'signup?a='.$id.'&b='.$key;
                $msg_data = array(
                                'alpha_link' => $alpha_link,
                                'full_name' => $user_data[0]['first_name']
                            );

                $msg = $this->parser->parse('alpha_invite_template', $msg_data);
                //$message = base_url().'signup?a='.$id.'&b='.$key;
                if(SendEmail($email, $subject, $msg))
                {
                    $this->alpha_model->email_sent_update($email);
                    $this->alpha_model->insert_key($id,$key);
                }
            }
        }

        redirect('/admin/alpha_user','refresh');
    }


}
?>