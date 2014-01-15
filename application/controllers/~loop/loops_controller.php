<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loops_controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        

        $this->load->model('loops_model');
        $this->load->model('connection_model');
        $this->load->model('post_model');
    }

    /********ONLY FOR NOW*************Buils Main Loop for all users******************
    	function build_mainloop()
    	{
    		$users = $this->loops_model->all_users();
    		foreach($users as $user)
    		{
    			$loop_id = $this->loops_model->new_loop($user['id'], 'Main Loop');
    			$members = $this->connection_model->connection_list($user['id']);
    			foreach($members as $member)
    			{
    				$this->loops_model->update_member($loop_id,$member['user2_id']);
    			}
    		}
    		echo 'all set';
    	}
    ***********************************************************************************/

    //old loop forwarding remove
    /*
    function create_loop()
    {
    	$data['main_content'] = 'create_loop';
    	$data['title'] = 'Create Loop';
    	$data['header'] = 'header';
    	$data['my_connections'] =$this->connection_model->connection_list($this->session->userdata('id'));
    	$this->load->view('includes/template',$data);
    }*/


    function new_loop()
    {
        $this->load->library('form_validation');
        // field name, error message, validation rules
        $this->form_validation->set_rules('loop_name', 'Loop Name', 'trim|required');
        $this->form_validation->set_rules('loop_member[]', 'Loop Member', 'trim|numeric');
        if($this->form_validation->run() == FALSE)
        {
            $data['header'] = 'header';
            $data['main_content'] = 'create_loop';
            $data['title'] = '';
            $this->load->view('includes/template',$data);
        }
        else
        {
            $user_id = $this->session->userdata['id'];
            $loop_name = $this->input->post('loop_name', true);
            $name_check = $this->loops_model->check_loopid($user_id, $loop_name);
            if($name_check == '')
            {
                $loop_id = $this->loops_model->new_loop($user_id, $loop_name);
                $loop_member = $this->input->post('loop_member', true);
                foreach($loop_member as $user)
                {
                    $this->loops_model->update_member($loop_id,$user);
                }
                //need to create a album for loop wall post
                $this->loops_model->new_album($loop_id, $loop_name, $user_id);
                redirect('/', 'refresh');
            }
        }
    }


    function edit_loop()
    {
        $loop_id = $data['loop_id'] = $this->uri->segment(2);
        $user_id = $this->session->userdata('id');
        $data['loop_info'] = $this->loops_model->loop_info($loop_id, $user_id);
        $data['loop_member'] = $this->loops_model->loop_member($loop_id);
        $data['main_content'] = 'loop/edit_loop';
        $data['title'] = 'Edit Loop';
        $data['header'] = 'header';
        $my_connections =$this->connection_model->connection_list($user_id);
        $my_follow = $this->connection_model->follow_list($user_id);
        foreach($data['loop_member'] as $k => $v)
        {
            foreach($my_connections as $key => $value)
            {
                if($v['uid'] == $value['uid'])
                {
                    $my_connections[$key]['check'] = TRUE;
                }
            }
            foreach($my_follow as $fk => $fv)
            {
                if($v['uid'] == $fv['uid'])
                {
                    $my_follow[$fk]['check'] = TRUE;
                }
            }
        }
        $data['my_connections'] = $my_connections;
        $data['my_follow'] = $my_follow;
        $this->load->view('includes/template',$data);
    }


    function update_loop()
    {
        $this->load->library('form_validation');
        // field name, error message, validation rules
        $this->form_validation->set_rules('loop_name', 'Loop Name', 'trim|required');
        $this->form_validation->set_rules('loop_member[]', 'Loop Member', 'trim|numeric');

        if($this->form_validation->run() == FALSE)
        {
            $data['header'] = 'header';
            $data['main_content'] = 'loop/edit_loop';
            $data['title'] = '';
            $this->load->view('includes/template',$data);
        }
        else
        {
            $user_id = $this->session->userdata['id'];
            $loop_id = $this->input->post('loop_id', true);
            $loop_name = $this->input->post('loop_name', true);
            $loop_member = $this->input->post('loop_member', true);
            $old_member = $this->loops_model->loop_member($loop_id);
            $loop_info = $this->loops_model->loop_info($loop_id, $user_id);
            $name_check = $this->loops_model->check_loopid($user_id, $loop_name);
            if($loop_info != '')  //need to make sure this loop is belong to this user
            {
                if($name_check != $loop_id && $name_check == '')
                {
                    $this->loops_model->update_loop($loop_id, $loop_name);
                    //also need update tha wall post photo album name
                    $this->loops_model->update_albumname($user_id, $loop_info['loop_name'], $loop_name);
                }

                //get the member got removed
                foreach($old_member as $k=>$old_member)
                {
                    foreach($loop_member as $new_member)
                    {
                        if($old_member['user_id'] == $new_member)
                            unset($old_member[$k]);
                    }
                }
                //check if the removed member still exists in any other loop, if no save them into user's main loop
                $main_loop_id = $this->loops_model->check_loopid($user_id, 'Main Loop');
                foreach($old_member as $k=>$v)
                {
                    $rm_check = $this->loops_model->rm_check($user_id, $v['user_id']);
                    if($rm_check)
                    {
                        $this->loops_model->update_member($main_loop_id,$v['user']);
                    }
                }

                $this->loops_model->rm_member($loop_id);
                foreach($loop_member as $user)
                {
                    $this->loops_model->update_member($loop_id,$user);
                }
            }
            redirect('profile/'.$this->session->userdata['uri_name'].'/'.$this->session->userdata('id'), 'refresh');
        }
    }


    function rm_loop()
    {
        $loop_id = $this->uri->segment(2);
        $user_id = $this->session->userdata['id'];
        $loop_info = $this->loops_model->loop_info($loop_id, $user_id);
        $main_loop_id = $this->loops_model->check_loopid($user_id, 'Main Loop');
        //check and make sure this loop is belong to this user and also not the Main Loop
        if($loop_info != '' && $loop_info['loop_name'] != 'Main Loop')
        {
            $old_member = $this->loops_model->loop_member($loop_id);
            foreach($old_member as $k=>$v)
            {
                $rm_check = $this->loops_model->rm_check($user_id, $v['user_id']);
                if($rm_check)
                {
                    $this->loops_model->update_member($main_loop_id,$v['user']);
                }
            }
            $this->loops_model->rm_loop($loop_id);
            $this->loops_model->rm_member($loop_id);
            $this->loops_model->rm_newsfeed($loop_id);
            $this->loops_model->rm_album($loop_id, $user_id);
        }
        redirect('/', 'refresh');
    }


    function loop_page()
    {
        $this->load->model('connection_model');
        $loop_id = $data['loop_id'] = $this->uri->segment(2);
        if($loop_id == '0')
        {
            $this->session->unset_userdata('loop_id');
        }
        else
        {
            $this->session->set_userdata($data);
        }
        $user_id = $this->uri->segment(3);
        $loops[] = $loop_id;
        if($user_id == $this->session->userdata['id'])
        {
            $data['my_loops'] = $loop_array = $this->loops_model->loops_list($user_id);
        }
        else
        {
            $data['my_loops'] = $this->loops_model->get_loop($this->session->userdata['id'], $user_id);
        }

        //get connection list
        $this->load->model('connection_model');
        $connection_list =$this->connection_model->connection_list($this->session->userdata('id'));
        //get following list
        $this->load->model('connection_model');
        $follow_list = $this->connection_model->follow_list($this->session->userdata['id']);

        if($loop_id == '0')
        {
            $my_connection[0] = $this->session->userdata['id'];
            foreach ($connection_list as $ckey => $cvalue)
            {
                $my_connection[] = $cvalue['uid'];
            }
            $this->load->model('post_model');
            if(count($my_connection)>0)
            {
                $connection_newsfeed = $this->post_model->get_newsfeed('profile',$my_connection,'connection');
            }
            foreach($connection_newsfeed as $k=>$v)
            {
                $key = strtotime($v['time']);
                $connection_feeds[$key] = $v;
            }
            $data['loop_feeds'] = $connection_feeds;

            foreach ($follow_list as $fkey => $fvalue)
            {
                $my_follow[] = $fvalue['uid'];
            }
            //get the newsfeed from all my following
            if(count($my_follow)>0)
            {
                $follow_newsfeed = $this->post_model->get_newsfeed('profile_follow',$my_follow,'connection');
            }
            foreach($follow_newsfeed as $k=>$v)
            {
                //$follow_newsfeed[$k]['relation'] = 'followed';
                $key = strtotime($v['time']);
                $follow_feeds[$key] = $v;
                $follow_feeds[$key]['relation'] = 'followed';
                $data['loop_feeds'][$key] = $follow_feeds[$key];
            }
            $data['feeds_array'] = $data['loop_feeds'];
            krsort($data['feeds_array']);
        }
        else
        {
            //get the loop's member list
            $this->load->model('loops_model');
            $loop_member = $this->loops_model->loop_member($loop_id);
            foreach($loop_member as $k=>$v)
            {
                $loop_user[] = $v['uid'];
                foreach($connection_list as $connection)
                {
                    if($v['uid'] == $connection['uid'])
                    {
                        $loop_connection[] = $v['uid'];
                    }
                }
                foreach($follow_list as $following)
                {
                    if($v['uid'] == $following['uid'])
                    {
                        $loop_followed[] = $v['uid'];
                    }
                }
            }
            if(count($loop_connection)>0)
            {
                $connection_newsfeed = $this->loops_model->loop_feed($loop_id, $user_id, $loop_connection);
            }
            //print_r($connection_newsfeed);
            foreach($connection_newsfeed as $k=>$v)
            {
                $key = strtotime($v['time']);
                $connection_feeds[$key] = $v;
            }
            $loop_feeds = $connection_feeds;
            if(count($loop_followed)>0)
            {
                $followed_newsfeed = $this->loops_model->loop_feed($loop_id, $user_id, $loop_followed, '', '', 'follow');
                foreach($followed_newsfeed as $k=>$v)
                {
                    $key = strtotime($v['time']);
                    $followed_feeds[$key] = $v;
                    $followed_feeds[$key]['relation'] = 'followed';
                    $loop_feeds[$key] = $followed_feeds[$key];
                }
            }
            $data['feeds_array'] = $loop_feeds;
            krsort($data['feeds_array']);
            //print_r($loop_feeds);
        }
        $profile_info = $this->loops_model->get_userid($loop_id);
        $data['profile_id'] = $user_id;
        $data['uri_name'] = $profile_info['uri_name'];

        /*		//for loop drop down menu
        		if($loop_id == '0')
        		{
        			foreach($loop_array as $loop)
        			{
        				$loop_dropdown[$loop['loop_id']] = $loop['loop_name'];
        			}
        			$loop_dropdown[0] = 'Public Loop';
        			$data['loop_dropdown'] = $loop_dropdown;
        			//$this->load->model('post_model');
        			//$loop_feed = $this->post_model->get_newsfeed('profile',array(0=>'4'),'user');
        			$data['profile_id'] = $profile_id = $user_id;
        		}
        */
        if ($r = $this->input->post('requestor',true))
        {
            $data['view_type'] = $r;
        }

        $data['profile_id'] = $profile_id = $user_id;
        $data['header'] = 'header';
        $noheader = 'none';
        $data['main_content'] = 'loop/loop';
        $data['type'] = 'profile';
        if($profile_id == $this->session->userdata('id'))
        {
            $data['logger'] = true;
        }
        else
        {
            $data['check_friends'] = $this->connection_model->count_by(array('user1_id'=>$this->session->userdata('id'), 'user2_id'=>$profile_id));
            $data['logger'] = false;
        }

        if ($noheader === 'none')
        {
            $this->load->view($data['main_content'],$data);
        }
        else
        {
            $this->load->view('includes/template',$data);
        }
    }

    function get_loops()
    {
        $q = $this->input->get('term',true);
        $loop_array = $this->loops_model->loops_list($this->session->userdata['id']);
        //print_r($loop_array);
        $loops = array();
        foreach($loop_array as $key => $value)
        {
            $loops[] = array("id" => $value['loop_id'], "name" => $value['loop_name']);
        }
        echo json_encode($loops);
    }

    //This function is different from get_loops above in the way that
    //it also tells which loops the current user has been put into
    function get_user_loops()
    {
        $user_id = $this->input->get('profile_id');
        $loops_list = $this->loops_model->loops_list($this->session->userdata['id']);
        foreach($loops_list as $k => $v)
        {
            $loop_id[] = $v['loop_id'];
        }
        $result = $this->loops_model->loops_user_is_in($user_id, $loop_id);
        foreach($result as $k => $v)
        {
            $loops[] = $v['loop_id'];
        }
        foreach ($loops_list as $k => $v)
        {
            $loops_list[$k]['selected'] = (in_array($v['loop_id'],$loops)) ? '1' : '0';
        }
        echo json_encode($loops_list);
    }


}
?>
