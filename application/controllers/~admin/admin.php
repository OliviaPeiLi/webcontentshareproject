<?php

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if($this->session->userdata('user') != 'superadmin')
        {
            redirect('/', 'refresh');
        }
        $this->load->model('admin_model');
        $this->load->model('page_model');
        $this->load->model('topic_model');
        $this->load->model('topic_page_model');
        $this->load->model('page_info_model');

        $this->load->library('session');
    }

    function index()
    {
        //print_r($this->session->userdata);
        $data['header'] = 'header_admin';
        $data['main_content'] = 'admin/admin';
        $data['title'] = 'Admin';
        /*		if($this->uri->segment(2) != '1314501425')
        		{
        			redirect('admin/1314501425', 'refresh');
        		}
        */
        $this->load->view('includes/template',$data);
    }

    function logout()
    {
        $this->session->sess_destroy();
        //$this->session->unset_userdata('user');
        //print_r($this->session->userdata);
        redirect('/','refresh');
    }

    function manage_pages()
    {
        $data['pages'] = $pages = $this->admin_model->get_official_requests();
        $data['header'] = 'header_admin';
        $data['main_content'] = 'admin/admin_pages';
        $data['title'] = 'Manage Pages';
        $this->load->view('includes/template',$data);
    }

    function make_official()
    {
        $page_id = $this->uri->segment(2);
        $type = $this->uri->segment(3);
        if($type == 1)
        {
            $page_info = $this->admin_model->get_all_pages($page_id);
            $official_url = $page_info[0]['uri_name'];
        }
        else
        {
            $official_url = '';
        }
        $this->admin_model->update_official($page_id, $official_url);
        $this->admin_model->rm_official_request($page_id);
        redirect('admin_page', 'refresh');
    }

    function peopleshouldknow()
    {
        $type = $this->uri->segment(2);
        $this->load->library('Peopleshouldknow');
        $rec = new Peopleshouldknow();
        $rec->index($type);
    }

    function page_aliases()
    {
        $data['aliases'] = $aliases = $this->admin_model->get_page_aliases();
        $data['header'] = 'header_admin';
        $data['main_content'] = 'admin/admin_aliases';
        $data['title'] = 'Page Aliases Process';
        $this->load->view('includes/template',$data);
    }

    function proc_aliases()
    {
        $r_id = $this->uri->segment(2);
        $proc = $this->uri->segment(3);
        if($proc == 'approve')
        {
            $alias_info = $this->admin_model->get_page_aliases($r_id);
            $page_id = $alias_info[0]['page_id'];
            $page_thumb = $alias_info[0]['page_thumb'];
            $page_name = $alias_info[0]['page_name'];
            $interest_id = $alias_info[0]['interest_id'];
            if($alias_info[0]['official_url'] == '')
            {
                $redirect_url = 'interests/'.$alias_info[0]['page_uri'].'/'.$page_id;
            }
            else
            {
                $redirect_url = $alias_info[0]['official_url'];
            }
            $new_alias = array('page_name'=>$alias_info[0]['alias'], 'thumbnail'=>$page_thumb, 'alias_name'=>$page_name, 'alias_id'=>$page_id, 'interest_id'=>$interest_id, 'redirect_url'=>$redirect_url);
            $this->admin_model->new_alias($new_alias);
        }
        $this->admin_model->rm_alias_request($r_id);
        redirect('page_aliases','refresh');
    }

    function merge_interests()
    {
        $page1_id = $this->input->post('page1_id', true);
        $page2_id = $this->input->post('page2_id', true);
        $people1 = $this->page_model->num_users($page1_id,0,'','out');
        $people2 = $this->page_model->num_users($page2_id,0,'','out');
        //print_r($people2);
        foreach($people1 as $a=>$b)
        {
            foreach($people2 as $c=>$d)
            {
                if($b['user_id'] == $d['user_id'])
                {
                    unset($people1[$a]);
                }
            }
        }
        foreach($people1 as $user)
        {
            $this->page_model->assign_user($page2_id, $user['user_id'], 'FAN');
            echo 'user';
            echo $user['user_id'].'+++'.$page2_id;
        }
        echo 'user done';
        $topic_list1 = $this->topic_model->show_topics($page1_id, 'page'); //list of topics
        foreach($topic_list1 as $k=>$v)
        {
            if($v['merge'] == '1')
            {
                $topic_info1 = $this->topic_model->get_topic_name($v['topic1_id']);
                $topic1_array[$v['topic1_id']] = $topic_info1['topic_name'];
            }
            else
            {
                $topic1_array[$v['topic_id']] = $v['topic_name'];
            }
        }
        $topic_list2 = $this->topic_model->show_topics($page2_id, 'page'); //list of topics
        foreach($topic_list2 as $k=>$v)
        {
            if($v['merge'] == '1')
            {
                $topic_info2 = $this->topic_model->get_topic_name($v['topic1_id']);
                $topic2_array[$v['topic_id']] = $topic_info2['topic_name'];
            }
            else
            {
                $topic2_array[$v['topic_id']] = $v['topic_name'];
            }
        }
        foreach($topic1_array as $e=>$f)
        {
            foreach($topic2_array as $g=>$h)
            {
                if($e == $g)
                {
                    unset($topic1_array[$e]);
                }
            }
        }
        foreach($topic1_array as $k=>$v)
        {
            $this->topic_page_model->insert(array('topic_id'=>$k, 'page_id'=>$page2_id));
            $this->topic_model->insert_topic_page($k, $page2_id, 'page');
            echo 'topic';
            echo $k.'---'.$page2_id;
        }
        echo 'topic done';
        redirect('admin','refresh');
    }

    function manage_questions()
    {
        $this->load->model('favorite_question_model');
        $data['questions'] = $this->favorite_question_model->get_questions('all');
        //print_r($questions);
        $data['header'] = 'header_admin';
        $data['main_content'] = 'admin/admin_questions';
        $data['title'] = 'Manage Favorite Questions';
        $this->load->view('includes/template',$data);
    }

    function question_display()
    {
        $action = $this->uri->segment(2);
        $q_id = $this->uri->segment(3);
        $this->admin_model->update_question_display($q_id, $action);
        redirect('/manage_favorite','refresh');
    }

    function question_rm()
    {
        $q_id = $this->uri->segment(2);
        $this->admin_model->rm_question($q_id);
        redirect('/manage_favorite','refresh');
    }

    function edit_question()
    {
        $q_id = $this->uri->segment(2);
        $this->load->model('favorite_question_model');
        $question = $this->favorite_question_model->get_questions('all', $q_id);
        $data['question'] = $question;
        $data['header'] = 'header_admin';
        $data['main_content'] = 'admin/edit_question';
        $data['title'] = 'Edit Favorite Questions';
        $this->load->view('includes/template',$data);
    }

    function save_question()
    {
        $q_id = $this->input->post('q_id', true);
        $title = $this->input->post('title', true);
        $question = $this->input->post('question', true);
        $update = array('title'=>$title, 'question'=>$question);
        $this->admin_model->update_question($q_id, $update);
        redirect('/manage_favorite','refresh');
    }

    function add_topics()
    {
        $this->db->select('type');
        $this->db->from('interest_category');
        $query = $this->db->get();
        $row = $query->result_array();

        $i = 0;
        foreach($row as $k=>$v)
        {
            $this->db->select('topic_id');
            $this->db->where('topic_name', $v['type']);
            $this->db->from('topics');
            $query = $this->db->get();
            $topic_check = $query->result_array();

            if($topic_check[0]['topic_id'] == '')
            {
                $this->db->insert('topics', array('topic_name'=>$v['type']));
                echo 'added '.$v['type'].'<br>';
                $i++;
            }
        }
        echo $i;
    }

    function transfer_topic()
    {
        $this->db->select('id, type');
        $this->db->from('interest_category');
        $query = $this->db->get();
        $row = $query->result_array();

        foreach($row as $k=>$v)
        {
            $this->db->select('topic_id');
            $this->db->where('topic_name', $v['type']);
            $this->db->from('topics');
            $query = $this->db->get();
            $topic_info = $query->result_array();

// 			$this->db->select('topic_id, page_id');
// 			$this->db->where(array('topic_id'=>$topic_info[0]['topic_id']));
// 			$this->db->from('topic_page');
// 			$query = $this->db->get();
// 			$check = $query->result_array();
            $check = $this->topic_page_model->get_many_by_array(
                         array('topic_id'=>$topic_info[0]['topic_id'])
                     );

            foreach($check as $ck=>$cv)
            {
                $page_in[$cv['page_id']] = $cv['page_id'];
            }

            $this->db->select('page_id');
            $this->db->where('interest_id', $v['id']);
            $this->db->from('pages');
            $query = $this->db->get();
            $pages = $query->result_array();

            $i = 0;
            foreach($pages as $key=>$val)
            {
                if(!in_array($val['page_id'], $page_in))
                {
                    $i++;
                    $this->topic_page_model->insert( array('topic_id'=>$topic_info[0]['topic_id'], 'page_id'=>$val['page_id']));
                }
            }

            $this->db->set('hits', 'hits+'.$i, FALSE);
            $this->db->where('topic_id', $topic_info[0]['topic_id']);
            $this->db->update('topics');
            echo 'added '.$i.' pages from category '.$v['id'].$v['type'].' to topic '.$topic_info[0]['topic_id'].'<br>';
        }
    }


    //remove page based on id
    function delete_page()
    {
        //remove almubs

        $page_id = $this->input->post('page_id', true);
        $this->db->select('album_id');
        $this->db->from('albums');
        $this->db->where('page_id', $page_id);
        $q = $this->db->get();
        $album_id = $q->result_array();

        $this->load->library('s3');
        $bucket = s3_bucket();
        $folder = 'pages/'.$page_id.'/';
        $response = s3::getBucket($bucket,$folder
                                 );

        foreach ($response as $k=>$v)
        {
            s3::deleteObject($bucket, $k);
        }


        foreach($album_id as $row)
        {
            //echo $row[album_id];
            $this->db->delete('photos', array('album_id' =>  $row[album_id]));
        }

        //remove events
        $this->db->select('event_id');
        $this->db->from('events');
        $this->db->where('page_id', $page_id);
        $q = $this->db->get();
        $event_id = $q->result_array();

        foreach($event_id as $row)
        {
            $this->db->delete('invitees', array('event_id' => $row[event_id]));
        }

        $this->db->delete('pages', array('page_id' => $page_id));

        $this->db->where('page1_id',$page_id);
        $this->db->or_where('page2_id', $page_id);
        $this->db->delete('page_feature');

        $this->db->where('page1_id',$page_id);
        $this->db->or_where('page2_id', $page_id);
        $this->db->delete('pages_similarity');

        $this->db->where('page1_id',$page_id);
        $this->db->or_where('page2_id', $page_id);
        $this->db->delete('page_merge');

        $this->db->delete('prs', array('page_id'=>$page_id));
        $this->db->delete('page_user_relation', array('page_id' => $page_id));
        $this->page_info_model->delete_by(array('page_id' => $page_id));
        $this->db->delete('page_official_requests', array('page_id' => $page_id));
        $this->db->delete('page_thread', array('page_id' => $page_id));
        $this->db->delete('custom_tabs', array('page_id' => $page_id));
        $this->db->delete('events', array('page_id' => $page_id));
        $this->db->delete('favorite_answer', array('page_id' => $page_id));
        $this->db->delete('likes', array('page_id' => $page_id));

        $this->db->select('user_id');
        $this->db->where('page_id', $page_id);
        $query = $this->db->get('page_users');
        $row = $query->result_array();
        foreach($row as $k=>$v)
        {
            $this->db->set('interests', 'interests-1', FALSE);
            $this->db->where('id',$v['user_id']);
            $this->db->update('users');
            $this->db->delete('page_users', array('user_id'=>$v['user_id'], 'page_id'=>$page_id));
        }

// 		$this->db->select('topic_id');
// 		$this->db->where('page_id', $page_id);
// 		$query = $this->db->get('topic_page');
// 		$row = $query->result_array();
        $row = $this->topic_page_model->get_many_by_array(
                   array('page_id' => $page_id)
               );

        foreach($row as $k=>$v)
        {
            $this->db->set('hits', 'hits-1', FALSE);
            $this->db->where('topic_id',$v['topic_id']);
            $this->db->update('topics');
            $this->topic_page_model->delete_by(array('topic_id'=>$v['topic_id'], 'page_id'=>$page_id));
        }

        $this->db->where('page_id_from',$page_id);
        $this->db->or_where('page_id_to', $page_id);
        $this->db->delete('links');

        $this->db->delete('list_page', array('page_id' => $page_id));

        $this->db->select('newsfeed_id');
        $this->db->where('page_id_from', $page_id);
        $this->db->or_where('page_id_to', $page_id);
        $query = $this->db->get('newsfeed');
        $row = $query->result_array();

        foreach($row as $k=>$v)
        {
            $this->db->delete('newsfeed_activity', array('newsfeed_id'=>$v['newsfeed_id']));
            $this->db->delete('newsfeed', array('newsfeed_id'=>$v['newsfeed_id']));
        }

        $this->db->delete('photo_tags', array('page_id' => $page_id));
        $this->db->delete('points_system', array('page_id' => $page_id));

        $this->db->where('page_id_from',$page_id);
        $this->db->or_where('page_id_to', $page_id);
        $this->db->delete('posts');

        echo 'Page_'.$page_id.' has been deleted.</br>';
        echo '<a href="/admin">Go Back</a>';

    }


}
?>
