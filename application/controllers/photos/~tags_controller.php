<?php

class Tags_controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        

        if(!$this->session->userdata['id'])
        {
            redirect(base_url(),'refresh');
        }
        $this->load->model('page_model');
        $this->load->model('custom_tab_model');
        $this->load->model('photos_model');
    }

    function get_tags()
    {
        $loop_id = $this->uri->segment(2);
        if($loop_id == '0')
        {
            $this->load->model('connection_model');
            $friends = $this->connection_model->connection_list($this->session->userdata['id']);
        }
        else
        {
            $this->load->model('loops_model');
            $friends = $this->loops_model->loop_member($loop_id);
        }
        $i = 0;
        foreach($friends as $k => $v)
        {
            $data['friends'][] = array('name' => $v['first_name'].' '.$v['last_name'], 'id' => $v['user2_id']);
            $i++;
        }

        $this->load->model('photos_model');
        $photo_id = $this->uri->segment(3);
        $tags = $this->photos_model->get_tags($photo_id, 'friends');
        if($tags)
        {
            $tags_array = unserialize($tags);
            $data['tags'] = $tags_array;
        }
        $json_data = json_encode($data);
        print($json_data);
    }

    function get_all_tags()
    {
        $this->load->model('photos_model');
        $photo_id = $this->uri->segment(3);
        $tags = $this->photos_model->get_tags($photo_id);
        if($tags)
        {
            $tags_array = unserialize($tags);
        }
        //$tags_data = json_decode($tags_array[0]);

        $data['tags'] = $tags_array;
        //print_r($data['tags']);
        //echo '<br>';
        //Get friends and loops
        $loop_id = $this->uri->segment(2);
        if($loop_id == '0')
        {
            $this->load->model('connection_model');
            $friends = $this->connection_model->connection_list($this->session->userdata['id']);
        }
        else
        {
            $this->load->model('loops_model');
            $friends = $this->loops_model->loop_member($loop_id);
        }

        //Get Pages
        $this->load->model('page_model');
        $pages = $this->page_model->all_pages();


        foreach($tags_array as $key => $val)
        {
            foreach($friends as $k => $v)
            {
                if($v['uid'] == $val->id && $val->type == 'user')
                {
                    //print_r($val->id);echo '<br>'.$k;
                    unset($friends[$k]);
                }
            }

            foreach($pages as $pk => $pv)
            {
                if($pv['id'] == $val->id && $val->type == 'page')
                {
                    //print_r($val->id);echo '<br>'.$k;
                    unset($pages[$pk]);
                }
            }
        }
        //print_r($friends);

        $i = 0;
        foreach($friends as $k => $v)
        {
            $data['items'][] = array('name' => $v['first_name'].' '.$v['last_name'], 'id' => $v['uid'], 'type' => 'user');
            $i++;
        }

        $j = 0;
        foreach($pages as $k => $v)
        {
            $data['items'][] = array('name' => $v['name'], 'id' => $v['id'], 'type' => 'page');
        }

        $json_data = json_encode($data);
        print($json_data);
    }

    function post_all_tags()
    {
        $this->load->model('photos_model');
        $this->load->model('post_model');

        $this->photos_model->del_tags($this->uri->segment(2), 'friends');
        $this->photos_model->del_tags($this->uri->segment(2), 'pages');
        $this->photos_model->add_tags($this->uri->segment(2));

        $tags_data = $this->input->post('tag_items', true);
        $tags_obj = json_decode($tags_data);

        //unset($tags_obj[0]);

        foreach($tags_obj as $k => $v)
        {
            if($v->type == 'page')
            {
                $this->photos_model->add_tags_info('page', $this->uri->segment(2), $v->id, date("Y-m-d H:i:s", $v->tag_time));
                //insert into newsfeed table
                $this->post_model->insert_newsfeed('pages_tags',$this->uri->segment(2), '', '', '', $v->id, date("Y-m-d H:i:s", $v->tag_time));
            }
            else
            {
                $this->photos_model->add_tags_info('user', $this->uri->segment(2), $v->id, date("Y-m-d H:i:s", $v->tag_time));
                //insert into newsfeed table
                $this->post_model->insert_newsfeed('friends_tags', $this->uri->segment(2), '', '', '', $v->id, date("Y-m-d H:i:s", $v->tag_time));
            }
        }
    }

    function post_tags()
    {
        $this->load->model('photos_model');
        $this->load->model('post_model');

        $this->photos_model->del_tags($this->uri->segment(2), 'friends');
        $this->photos_model->add_tags($this->uri->segment(2), 'friends');
        $tags_data = $this->input->post('tag_friends', true);
        $tags_obj = json_decode($tags_data);

        foreach($tags_obj as $k => $v)
        {
            $this->photos_model->add_tags_info('user', $this->uri->segment(2), $v->id, date("Y-m-d H:i:s", $v->tag_time));
            //insert into newsfeed table
            $this->post_model->insert_newsfeed('friends_tags', $this->uri->segment(2), '', '', '', $v->id, date("Y-m-d H:i:s", $v->tag_time));
        }
    }


    //get item tags
    function get_item_tags()
    {
        $this->load->model('page_model');
        $data['pages'] = $this->page_model->all_pages();

        $this->load->model('photos_model');
        $photo_id = $this->uri->segment(3);
        $tags = $this->photos_model->get_tags($photo_id, 'pages');
        if($tags)
        {
            $tags_array = unserialize($tags);
            $data['tags'] = $tags_array;
        }
        $json_data = json_encode($data);
        print($json_data);
    }



    //add item tags
    function post_item_tags()
    {
        $this->load->model('photos_model');
        $this->load->model('post_model');

        $this->photos_model->del_tags($this->uri->segment(2), 'pages');
        $this->photos_model->add_tags($this->uri->segment(2), 'pages');
        $tags_data = $this->input->post('tag_pages', true);
        $tags_obj = json_decode($tags_data);

        foreach($tags_obj as $k => $v)
        {
            $this->photos_model->add_tags_info('page', $this->uri->segment(2), $v->id, date("Y-m-d H:i:s", $v->tag_time));
            //insert into newsfeed table
            $this->post_model->insert_newsfeed('pages_tags',$this->uri->segment(2), '', '', '', $v->id, date("Y-m-d H:i:s", $v->tag_time));
        }
    }



}
?>
