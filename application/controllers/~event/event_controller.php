<?php

class Event_controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        

        $this->load->model('page_model');
        $this->load->model('event_model');
        $this->load->helper('page_helper');
    }

    // TODO: refactor the event model to list invitees inside each event
    //rather than generating an event for each invitee in the list
    //idea: create invitees column in events table
    function events()
    {
        $data['header'] = 'header';
        $noheader = $this->input->get('header',true);
        $requestor = $this->input->post('requestor',true);

        if ($requestor === 'home')
        {
            $this->load->model('page_model');
            $page_list = $this->page_model->list_pages($this->session->userdata('id'));
            $j=0;
            foreach ($page_list as $pkey => $pvalue)
            {
                $my_page[$j] = $pvalue['page_id'];
                $j++;
            }

            $data['events'] = $events = $this->event_model->show_event($my_page);
            foreach($events as $event)
            {
                if($event['user_id'] == $this->session->userdata('id'))
                {
                    if($event['response'] == 'yes')
                    {
                        $response[$event['event_id']] = 'Yes, you are\'re coming';
                    }
                    if($event['response'] == 'no')
                    {
                        $response[$event['event_id']] = 'No, you\'re not coming';
                    }
                    //echo $event['event_id'].'->'.$event['response'].'++';
                }
            }
            //print_r($response);
            $data['response'] = $response;
            $data['view_type'] = 'events';
            $data['main_content'] = 'event/events';
            $noheader = 'none';
            echo 'hello here is a list of events';
        }
        else
        {
            $data = load_page_data();
            $data['title'] = $data['page_info'][0]['page_name'].': Events';
            if($this->uri->segment(3) != 0)
            {
                $events = $this->event_model->show_event($this->uri->segment(2), $this->uri->segment(3));
                //print_r($events);
                $data['view_type'] = 'show_event';
                $data['display_type'] = 'show_event_details';
                $data['main_content'] = 'event/events';
                $events_response_info = $this->event_model->check_invitee($this->session->userdata['id'], $events[0]['event_id']);

                if($events_response_info['response'] == 'yes')
                {
                    $data['show_comments'] = TRUE;
                }
                else
                {
                    $data['show_comments'] = FALSE;
                }
            }
            else
            {
                $events = $this->event_model->show_event($this->uri->segment(2));
                $data['view_type'] = 'events';
                $data['stage'] = 'events';
                $data['main_content'] = 'interests/page';
            }
            $data['events'] = $events;

            $data['owner_priv'] = $this->page_model->privilege($this->uri->segment(2),'OWNER');
            $data['admin_priv'] = $this->page_model->privilege($this->uri->segment(2),'ADMIN');
            $data['page_users'] = $this->page_model->num_users($this->uri->segment(2));
            $data['header'] = 'header';
            //$data['main_content'] = 'event/events';

            $this->load->model('post_model');
            foreach($data['events'] as $key => $value)
            {
                $invitees = $this->event_model->invitees_list($value['event_id']);
                $data['events'][$key]['more'] = $data['page_users'];

                foreach($invitees as $ik => $iv)
                {
                    foreach($data['events'][$key]['more'] as $k => $v)
                    {
                        if($v['user_id'] == $iv['user_id'])
                        {
                            unset($data['events'][$key]['more'][$k]);			//unset the user's name who already in invitees list
                        }
                    }
                    if($value['user_id'] == $iv['user_id'])
                    {
                        $data['events'][$key]['attend'] = $iv['response'];		//set the response value into array
                        if($iv['response'] == 'yes' || $iv['response'] == 'no')
                        {
                            //if the response is "yes" then set the user's profile link
                            $user_link_info = $this->post_model->user_link($iv['user_id']);
                            $data['events'][$key]['user_link'] = $user_link_info['link'];
                            $data['events'][$key]['thumbnail'] = $user_link_info['thumbnail'];
                        }
                    }
                }
            }
            //print_r($data['events']);
        }
        if ($noheader === 'none')
        {
            $this->load->view($data['main_content'],$data);
        }
        else
        {
            $page_id = $data['page_id'] = $this->uri->segment(2);
            $this->load->view('includes/template',$data);
        }
    }

    //shoe particular event
    function show_event()
    {

    }

    //for validation for event's start and end time
    function end_time_check($str)
    {
        $start_time = $this->input->post('start_time', true);
        if($start_time > $str)
        {
            $this->form_validation->set_message('end_time_check', 'The %s can not be earlier than Starting time');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    //generation of new event
    function new_event()
    {
        $page_id = $this->uri->segment(2);
        $this->load->library('form_validation');

        // field name, error message, validation rules
        $this->form_validation->set_rules('event_name', 'Event Title', 'trim|required');
        $this->form_validation->set_rules('end_time', 'End Time', 'callback_end_time_check');
        $this->form_validation->set_rules('location', 'Location', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('city', 'City', 'trim');
        $this->form_validation->set_rules('zip_code', 'Zip Code', 'trim|numeric|min_length[5]|max_length[8]');
        $this->form_validation->set_rules('description', 'Details', 'trim');
        $this->form_validation->set_rules('check_guests[]', 'Guests', 'trim|numeric');




        if($this->form_validation->run() == FALSE)
        {
            /*	$data['owner_priv'] = $this->page_model->privilege($this->uri->segment(2),'OWNER');
            	$data['admin_priv'] = $this->page_model->privilege($this->uri->segment(2),'ADMIN');
            	$data['header'] = 'header';
            	$data['main_content'] = 'event/events';
            	$data['title'] = '';
            	$noheader = $this->input->get('header',true);
            	if ($noheader === 'none') {
            		$this->load->view($data['main_content'],$data);
            	} else {
            		$this->load->view('includes/template',$data);
            	}
            */	$this->events();
        }
        else
        {
            //$img_file = $this->input->post('userfile', true);
            //echo
            if (isset($_FILES) && @$_FILES['userfile']['error'] == '0' )
            {
                $page_path = 'pages/'.$page_id.'/';
                $file_path = $page_path.'events/';
                if(!file_exists($page_path))
                {
                    mkdir($page_path,0777);
                }
                if(!file_exists($file_path))
                {
                    mkdir($file_path, 0777);
                }
                //$pic_count = count(glob($file_path.'*.jpg'));
                //$next_pic = $pic_count+1;
                //$newname = "image$next_pic.jpg";
                $newname = time().'.jpg';

                $config['file_name'] = $newname;
                $config['upload_path'] = $file_path;
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']	= '1024';
                $this->load->library('upload', $config);
                $this->upload->do_upload();

                $new_file = $file_path.$newname;
                $this->load->library('s3');
                $input = S3::inputFile($new_file);
                $bucket = s3_bucket();
                $uri = $new_file;
                if (S3::putObject($input, $bucket, $uri, S3::ACL_PUBLIC_READ))
                {
                    //echo "File uploaded.";
                    unlink($new_file);
                }
                else
                {
                    //echo "Failed to upload file.";
                }

                $data = array('upload_data' => $this->upload->data());
                $img = $newname;
            }
            else
            {
                $img = '';
            }
            $event_id = $this->event_model->insert_event($this->uri->segment(2),$img,$newsfeed_id);
            $this->load->model('post_model');
            $newsfeed_id = $this->post_model->insert_newsfeed('event', $event_id, 'page','','','',$this->uri->segment(2));
            $this->event_model->newsfeed_event($event_id, $newsfeed_id);
            redirect('events/'.$this->uri->segment(2));
        }
    }

    //cancelation of event
    function delete_event()
    {
        $data['owner_priv'] = $this->page_model->privilege($this->uri->segment(2),'OWNER');
        $data['admin_priv'] = $this->page_model->privilege($this->uri->segment(2),'ADMIN');
        $page_id = $this->uri->segment(2);
        $event_id = $this->uri->segment(3);

        if($data['owner_priv'] == 1 || $data['admin_priv'] == 1)
        {
            $this->event_model->delete_event($event_id,$page_id);
            $img = $this->event_model->get_img($event_id);
            //unlink('pages/'.$page_id.'/events/'.$img);
            $this->load->library('s3');
            $bucket = s3_bucket();
            $uri = 'pages/'.$page_id.'/events/'.$img;
            if (S3::deleteObject($bucket, $uri))
            {
                echo "Deleted file.";
            }
        }

        //redirect('events/'.$this->uri->segment(2));
        /*
        		$data['events'] = $this->event_model->show_event($this->uri->segment(2));
        		$data['page_users'] = $this->page_model->num_users($this->uri->segment(2));
        		$data['header'] = 'header';
        		$data['main_content'] = 'event/events';
        		$data['title'] = '';
        		$noheader = $this->input->get('header',true);
        		if ($noheader === 'none') {
        			$this->load->view($data['main_content'],$data);
        		} else {
        			$this->load->view('includes/template',$data);
        		}
        */
    }

    //user response to invitation to event
    function event_response()
    {
        $this->event_model->response($this->uri->segment(3),$this->uri->segment(4));
        redirect('/events/'.$this->uri->segment(2).'/'.$this->uri->segment(3));
    }

    //forward the event to page with editting form
    function edit_event()
    {
        $data['event'] = $this->event_model->show_event($this->uri->segment(2),$this->uri->segment(3));

        $data['page_users'] = $this->page_model->num_users($this->uri->segment(2));
        foreach($data['event'] as $key => $value)
        {
            $invitees = $this->event_model->invitees_list($value['event_id']);
            $data['event'][$key]['more'] = $data['page_users'];

            foreach($data['event'][$key]['more'] as $k => $v)
            {
                foreach($invitees as $ik => $iv)
                {
                    if($v['user_id'] == $iv['user_id'])
                    {
                        unset($data['event'][$key]['more'][$k]);
                    }
                }
            }
        }
        //print_r($data['event']);

        $data['header'] = 'header';
        $data['main_content'] = 'event/edit_event';
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

    //submits the values for validation and updating
    function event_edition()
    {
        $this->load->library('form_validation');
        $page_id = $this->uri->segment(2);
        $event_id = $this->uri->segment(3);

        // field name, error message, validation rules
        $this->form_validation->set_rules('event_name', 'Event Title', 'trim|required');
        $this->form_validation->set_rules('location', 'Location', 'trim');
        $this->form_validation->set_rules('address', 'Address', 'trim');
        $this->form_validation->set_rules('city', 'City', 'trim');
        $this->form_validation->set_rules('zip_code', 'Zip Code', 'trim|numeric|min_length[5]|max_length[8]');
        $this->form_validation->set_rules('description', 'Details', 'trim');
        //$this->form_validation->set_rules('check_guests[]', 'Guests', 'trim|numeric');

        if($_FILES['userfile']['tmp_name']!='')
        {
            $page_path = 'pages/'.$page_id.'/';
            $file_path = $page_path.'events/';
            if(!file_exists($user_path))
            {
                mkdir($user_path,0777);
            }
            if(!file_exists($file_path))
            {
                mkdir($file_path, 0777);
            }
            //$pic_count = count(glob($file_path.'*.jpg'));
            //$next_pic = $pic_count+1;
            //$newname = "image$next_pic.jpg";
            $newname = 'event'.$event_id.'.jpg';

            $config['file_name'] = 'event'.$event_id.'.jpg';
            $config['upload_path'] = $file_path;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['overwrite'] = TRUE;
            $config['max_size']	= '1024';
            $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload())
            {
                $error = array('error' => $this->upload->display_errors());

                $this->load->view('upload_form', $error);
            }
            else
            {
                $new_file = $file_path.$newname;
                $this->load->library('s3');
                $input = S3::inputFile($new_file);
                $bucket = s3_bucket();
                $uri = $new_file;
                if (S3::putObject($input, $bucket, $uri, S3::ACL_PUBLIC_READ))
                {
                    //echo "File uploaded.";
                    unlink($new_file);
                }
                else
                {
                    //echo "Failed to upload file.";
                }
            }
            $img = $config['file_name'];

            if($this->input->post('img', true) != $img)
            {
                //unlink('pages/'.$page_id.'/events/'.$this->input->post('img', true));
                $uri = $file_path.$this->input->post('img', true);
                if (S3::deleteObject($bucket, $uri))
                {
                    echo "Deleted file.";
                }
            }
            unlink('pages/'.$page_id.'/events/tmp/tmp_img.jpg');
        }
        else
        {
            $img = $this->input->post('img', true);
        }

        if($this->form_validation->run() == FALSE)
        {
            $data['owner_priv'] = $this->page_model->privilege($this->uri->segment(2),'OWNER');
            $data['admin_priv'] = $this->page_model->privilege($this->uri->segment(2),'ADMIN');
            //redirect('edit_event/'.$this->uri->segment(2).'/'.$this->uri->segment(3), 'refresh');
            echo 'validation failed';
        }
        else
        {
            $this->event_model->update_event($this->uri->segment(2),$this->uri->segment(3), $img);
            $this->event_model->update_invitees($event_id);

            redirect('events/'.$this->uri->segment(2).'/'.$this->uri->segment(3),'refresh');
        }
    }

    //delete the image of the event
    function del_img()
    {
        $img_path = '/home/ray/public_html/images/upload/page_'.$this->uri->segment(2).'/event_img/'.$this->uri->segment(4);
        if(unlink($img_path))
        {
            $this->load->model('event_model');
            $this->event_model->del_img($this->uri->segment(2),$this->uri->segment(3));
            redirect('edit_event/'.$this->uri->segment(2).'/'.$this->uri->segment(3));
        }
    }
}
