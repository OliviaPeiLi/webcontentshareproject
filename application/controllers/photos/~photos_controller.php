<?php

class Photos_controller extends CI_Controller
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
        $this->load->helper('page_helper');
    }

    //get the album's list for display
    function get_albums()
    {
        //$data['view_page'] = 'photos';
        $this->session->set_flashdata('view_page', 'photos');
        //print_r($this->session->flashdata('view_page'));
        $noheader = $this->input->get('header',true);
        $is_ajax = ($this->input->get('ajax',true) === '1');
        $data['is_ajax'] = $is_ajax;
        if ($is_ajax)
        {
            $noheader = 'none';
        }
        $is_page = ($this->uri->segment(3) === 'page') ? true : false;
        if ($is_page)
        {
            $data = load_page_data();
            $page_id = $data['page_id'] = $this->uri->segment(2);
        }
        else
        {
            $data['stage'] = 'photos';
            //include('application/modules/profile/controllers/profile_info.php');
            $data['selected_tab'] = 'photos';
            $data['hide_main_border'] = "no_border";
        }

        $this->load->model('photos_model');
        $albums = $this->photos_model->get_album();

        foreach ($albums as $key => $value)
        {
            $num = $this->photos_model->count_album_photo($value['album_id']);
            if($num == '0')
            {
                unset($albums[$key]);
            }
            else
            {
                if($value['album_name'] == 'Profile')
                {
                    $photo_info = $this->photos_model->get_photos($value['album_id'], '', 'public');
                }
                else
                {
                    $photo_info = $this->photos_model->get_photos($value['album_id'], '', $page_id);
                }
                $albums[$key]['photo_count'] = count($photo_info);
                $pic_count = count($photo_info);
                // echo $value['album_name'].'=================';
                //print_r($photo_info);
                $albums[$key]['thumb'] = array();
                //this loop is there for the visual effects where images are rotated in a fan-open-style.
                //Decision to remove it because of effects overkill.
                //for($i=0; $i<3; $i++) {
                //if ($i < $pic_count) {
                if($value['album_name'] == 'Profile')
                {
                    if($is_page)
                    {
                        //array_push($albums[$key]['thumb'], s3_url().'pages/'.$value['page_id'].'/pics/profile/'.$photo_info[$i]['photo_name']);
                        $albums[$key]['thumb'] = s3_url().'pages/'.$value['page_id'].'/pics/profile/thumbs/'.$photo_info[0]['photo_name'];
                    }
                    else
                    {
                        //array_push($albums[$key]['thumb'], s3_url().'users/'.$value['user_id'].'/pics/profile/'.$photo_info[$i]['photo_name']);
                        $albums[$key]['thumb'] = s3_url().'users/'.$value['user_id'].'/pics/profile/thumbs/'.$photo_info[0]['photo_name'];
                    }
                }
                else
                {
                    if($is_page)
                    {
                        //array_push($albums[$key]['thumb'], s3_url().'pages/'.$value['page_id'].'/pics/'.$value['album_id'].'/thumbs/'.$photo_info[$i]['photo_name']);
                        $albums[$key]['thumb'] = s3_url().'pages/'.$value['page_id'].'/pics/'.$value['album_id'].'/thumbs/'.$photo_info[0]['photo_name'];
                    }
                    else
                    {
                        //array_push($albums[$key]['thumb'], s3_url().'users/'.$value['user_id'].'/pics/'.$value['album_id'].'/thumbs/'.$photo_info[$i]['photo_name']);
                        $albums[$key]['thumb'] = s3_url().'users/'.$value['user_id'].'/pics/'.$value['album_id'].'/thumbs/'.$photo_info[0]['photo_name'];
                    }
                }
                //}
                //}
                //if($pic_count == 0)
                //{
                //	unset($albums[$key]);
                //}
            }
        }
        //print_r($albums);
        $data['albums'] = $albums;
        //print_r($data['albums']);

        //logic for My Photos
        $user_id = $this->session->userdata('id');
        $my_photos = $this->photos_model->my_photos($user_id);
        //$count_my_photos = count($my_photos);
        //for ($ii=0; $ii<3; $ii++) {
        //if ($ii >= $count_my_photos) {
        //    $n = 0;
        //} else {
        //    $n = $ii;
        //}
        //if($my_photos[$n]['page_id'] != 0)
        if($my_photos[0]['page_id'] != 0)
        {
            //$user_path = 'pages/'.$my_photos[$n]['page_id'].'/';
            $user_path = 'pages/'.$my_photos[0]['page_id'].'/';
        }
        else
        {
            //$user_path = 'users/'.$my_photos[$n]['user_id'].'/';
            $user_path = 'users/'.$my_photos[0]['user_id'].'/';
        }
        $pics_path = $user_path.'pics/';
        //$file_path = $pics_path.$my_photos[$n]['album_id'].'/';
        //$myphotos_thumb[$n] = s3_url().$file_path.'thumbs/'.$my_photos[$n]['photo_name'];
        $file_path = $pics_path.$my_photos[0]['album_id'].'/';
        $myphotos_thumb = s3_url().$file_path.'thumbs/'.$my_photos[0]['photo_name'];
        //}
        $data['myphotos_thumb'] = $myphotos_thumb;
        $data['myphotos_count'] = count($my_photos);

        if (!$is_page)
        {
            //$data['main_content'] = 'photos/albums';
            //print_r($data);
            $data['main_content'] = 'profile/profile';
        }
        else
        {
            if ($data['page_info'][0]['avatar'] != '')
            {
                $data['main_pic'] = s3_url().$data['page_info'][0]['avatar'];
            }
            if ($data['page_info'][0]['avatar'] == '')
            {
                $data['main_pic'] = $this->photos_model->get_wiki_photo($page_id)->wiki_photo_url;
            }
            if (!$data['main_pic'])
            {
                //$data['main_pic'] = "/images/example1.jpg";
                $data['main_pic'] = s3_url()."pages/default/defaultInterest/".$data['page_info'][0]['interest_id'].".png";
                //should be "/paages/default/default_pic.jpg",but doesn't work
            }
            $data['main_content'] = 'interests/page';
            $data['stage'] = 'albums';

        }
        if ($is_page)
        {
            $data['title'] = $data['page_info'][0]['page_name'].': Photo Albums';
        }
        else
        {
            $data['title'] = 'Photo Albums';
        }
        $data['header'] = 'header';

        $data['id'] = $this->uri->segment(2);
        if ($noheader === 'none')
        {
            $this->load->view($data['main_content'],$data);
        }
        else
        {
            $this->load->view('includes/template',$data);
        }
    }

    //Get 3 images for the album cover ui effect
    function get_album_cover()
    {
        $this->load->model('photos_model');
        $album_id = $this->uri->segment(2);
        //$data['album'] = $album_name;
        $photos = $this->photos_model->get_photos($album_id,3);
        print_r($photos);
        foreach($photos as $k => $v)
        {

        }
    }

    //get the photos from one album for display
    function get_photos()
    {
        $this->load->model('photos_model');
        $album_id = $this->uri->segment(3);
        //$data['album'] = $album_name;
        $data['album'] = $this->photos_model->get_album_info($album_id);
        $album = $this->photos_model->get_album_info($album_id);

        if($album[0]['page_id'] > 0)
        {
            $type = 'page';
        }

        $data['photos'] = $this->photos_model->get_photos($album_id,'',$type);
        //print_r($data['photos']);
        $data['main_content'] = 'photos/photos';
        $data['title'] = '';
        $data['header'] = 'header';
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
    function get_profile_photos()
    {
        $this->load->model('photos_model');
        $profile_id = $this->uri->segment(2);
        //echo 'profile_id='.$profile_id;
        $type = 'user';
        $profile_type = $this->input->get('type',true);
        if ($profile_type === 'page')
        {
            $type = 'page';
        }
        $album_id = $this->photos_model->get_albumid($profile_id,'Profile',$type);
        //echo 'albumid='.$album_id;
        //$data['album'] = $album_name;
        $data['album'] = $this->photos_model->get_album_info($album_id);
        $data['photos'] = $this->photos_model->get_photos($album_id,'','public');
        //$photo_info = $this->photos_model->get_photos($value['album_id'], '', 'public');
        $data['main_content'] = 'photos/photos';
        $data['title'] = '';
        $data['ajax'] = ($this->input->get('ajax',true) === '1') ? '1' : '0';
        $data['header'] = 'header';
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

    //get the photos from one album for display
    function my_photos()
    {
        $this->load->model('photos_model');
        $user_id = $this->session->userdata('id');
        $data['photos'] = $this->photos_model->my_photos($user_id);
        //print_r($data['photos']);
        $data['album'][0]['album_name'] = 'My Photos';
        $data['main_content'] = 'photos/photos';
        $data['title'] = 'Photos';
        $data['header'] = 'header';
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

    //display one photo from one album, the next one of the last photo will be the first one in the album, and the previous photo of the first one will be the last one of the album. the loop
    function show_photo()
    {
        $this->load->model('photos_model');
        $album_id = $this->uri->segment(6);
        $data['photo_info'] = $this->photos_model->photo_info($this->uri->segment(2));
        $data['album_info'] = $this->photos_model->get_album_info($album_id);
        //print_r($data['photo_info']);
        $data['main_content'] = 'photos/show_photo';
        $data['title'] = '';
        $data['header'] = 'header';
        $data['role'] = 0;
        $data['tag_time'] = date("Y-m-d H:i:s");
        if($data['photo_info'][0][0]['user'] == $this->session->userdata['id'])
        {
            $data['role'] = 1;
        }
        if($this->uri->segment(3)=='user')
        {
            $data['user_type'] = 'profile';
            $user_type = 'user';
        }
        else
        {
            $data['user_type'] = 'page';
            $user_type = 'page';
        }
        $data['redirect_url'] = '/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6).'/'.$this->uri->segment(7).'/'.$this->uri->segment(8);
        if($this->uri->segment(7) == 'my_photos')
        {
            $user_id = $this->uri->segment(8);
            $photos_array = $this->photos_model->my_photos($user_id);
        }
        else
        {
            $album_id = $this->uri->segment(6);
            if($user_type == 'page')
            {
                $album_type = 'page';
            }
            $photos_array = $this->photos_model->get_photos($album_id,'', $album_type);
            //not sure album_photos is useful anymore.
            //$data['album_photos'] = $this->photos_model->get_photos($album_id, '', $album_type);
        }
        $data['photos_array'] = $photos_array;
        //echo $album_id;
        //print_r($photos_array);
        $n = count($photos_array);
        //echo $n;
        foreach($photos_array as $k => $v)
        {
            if($this->uri->segment(2) == $v['photo_id'])
            {
                if($photos_array[$k+1]['user_id'] != 0)
                {
                    $next = $photos_array[$k+1]['photo_id'].'/user/'.$photos_array[$k+1]['user_id'].'/'.$photos_array[$k+1]['album_id'].'/'.$photos_array[$k+1]['album_id'];
                }
                else
                {
                    $next = $photos_array[$k+1]['photo_id'].'/page/'.$photos_array[$k+1]['page_id'].'/'.$photos_array[$k+1]['album_id'].'/'.$photos_array[$k+1]['album_id'];
                }
                if($k+1 >= $n)
                {
                    if($photos_array[0]['user_id'] == '0')
                    {
                        $next_type = 'page';
                    }
                    else
                    {
                        $next_type = 'user';
                    }
                    $next = $photos_array[0]['photo_id'].'/'.$next_type.'/'.$photos_array[0][$next_type.'_id'].'/'.$photos_array[0]['album_id'].'/'.$photos_array[0]['album_id'];
                }

                if($photos_array[$k-1]['user_id'] != 0)
                {
                    $last = $photos_array[$k-1]['photo_id'].'/user/'.$photos_array[$k-1]['user_id'].'/'.$photos_array[$k-1]['album_id'].'/'.$photos_array[$k-1]['album_id'];
                }
                else
                {
                    $last = $photos_array[$k-1]['photo_id'].'/page/'.$photos_array[$k-1]['page_id'].'/'.$photos_array[$k-1]['album_id'].'/'.$photos_array[$k-1]['album_id'];
                }
                if($k-1 < 0)
                {
                    if($photos_array[$n-1]['user_id'] == '0')
                    {
                        $last_type = 'page';
                    }
                    else
                    {
                        $last_type = 'user';
                    }
                    $last = $photos_array[$n-1]['photo_id'].'/'.$last_type.'/'.$photos_array[$n-1][$last_type.'_id'].'/'.$photos_array[$n-1]['album_id'].'/'.$photos_array[$n-1]['album_id'];
                }
            }
        }
        $data['next_url'] = '/show_photo/'.$next.'/'.$this->uri->segment(7).'/'.$this->uri->segment(8);
        $data['last_url'] = '/show_photo/'.$last.'/'.$this->uri->segment(7).'/'.$this->uri->segment(8);
        $noheader = $this->input->get('header',true);
        $data['hide_header'] = '1';
        $data['hide_footer'] = '1';
        if ($noheader === 'none')
        {
            $this->load->view($data['main_content'],$data);
        }
        else
        {
            $this->load->view('includes/template',$data);
        }

    }

    function get_photo_info()
    {
        $this->load->model('photos_model');
        $data['photo_info'] = $this->photos_model->photo_info($this->uri->segment(2));
        $this->load->view('comment/photo_comments', $data);
    }

}
?>
