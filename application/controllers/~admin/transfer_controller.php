<?php
/*
Thigs that has to be adjusted for the proper category
category_id on line 44
interest_id on line 45
topic id on line 62
*/
class Transfer_controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        if($this->session->userdata('user') != 'superadmin')
        {
            redirect('/', 'refresh');
        }
        $this->load->model('page_model');
        $this->load->model('topic_model');
        $this->load->model('page_info_model');

    }

    function index()
    {
        $data['header'] = 'header';
        $data['main_content'] = 'admin/transfer';
        $this->load->view('includes/template',$data);
    }

    //get raw wikipedia info
    function get_wiki_pages()
    {
        $this->db->select('*');
        $this->db->from('wiki');

        $query = $this->db->get();
        return $query->result_array();
    }

    function transfer_data()
    {
        $wiki = $this->get_wiki_pages();
        $time = time();

        $this->db->select('page_name');
        $this->db->from('pages');
        $query = $this->db->get();
        $row = $query->result_array();
        foreach($row as $k=>$v)
        {
            $pages_check[] = $v['page_name'];
        }

        foreach($wiki as $key => $value)
        {
            $url_str = str_replace (' ', '_', $value['page_name']);
            $url_str = str_replace ('_(', '_', $url_str);
            $url_str = str_replace ('(', '_', $url_str);
            $url_str = str_replace (':', '_', $url_str);
            $url_str = str_replace ('?', '', $url_str);
            $url_str = str_replace ('/', '_', $url_str);
            //$url_str = str_replace ('*)', '_', $url_str);
            $url_name = str_replace (')', '_', $url_str);
            $url_name = rtrim($url_name, '_');
            //$url_name = preg_replace("/\([^\)]+\)/","",$url_str);
            //$url_name1 = preg_replace("~_[^_]+?$~i", "", $url_name);
            //$url_data = preg_grep("/(^\s*)|(\s*$)/","",$url_name);
            $new_url = preg_replace_callback('/[^a-z0-9-_\/\.:%=&\?]+/i', create_function('$matches', 'return "";'), $url_name);

            //category id and interest_id has to be modified according to the inputed data
            $new_pages = array(
                             'page_name' => $value['page_name'],
                             'uri_name' => $new_url,
                             'official_url' => $new_url,
                             'category_id' => '',
                             'interest_id' => $value['main_topic'],
                             'sign_up_date'=> date("Y-m-d H:i:s"),
                             'wiki_time' => $time,						//need to be set a value for uploading the photos to s3 server
                         );

            if(!in_array($value['page_name'], $pages_check))
            {
                $pages_check[] = $value['page_name'];
                //insert data page_name, category_id, interest_id and generates page_id
                $insert = $this->db->insert('pages', $new_pages);

                //gets page_id from previous query
                $id = mysql_insert_id();

                //inserts the first paragraph of wiki page to a description field in page_info table
                $this->page_info_model->insert(array('page_id' => $id, 'description'=> $value['abstract']));

                //assign the logged user as owern to the created page.
                //$user_id = 4;
                //$this->page_model->assign_user($id,$user_id,'OWNER');

                //inserts a topic to the page based on topic_id, which needs to be chanaged when new categories is used
                //$this->topic_model->insert_topic_page('26',$id,'page');

                //generates album name and adds page_id
                $this->db->insert('albums', array('page_id'=> $id,
                                                  'album_name'=>'Profile',
                                                  'time'=> date("Y-m-d H:i:s")));
                //album_id generated in the previous query
                $album_id = mysql_insert_id();

                //inserts in the wiki image url along with amlbum_id from previous query
                $this->db->insert('photos', array('album_id'=> $album_id,
                                                  'wiki_photo_url'=> $value['image_url']));
                if($value['category_name'] != '')
                {
                    //insert topic from category name
                    $topic_name = str_replace ('_', ' ', $value['category_name']);
                    if($topic_name == $last_topic_name)
                    {
                        $topic_id = $last_topic_id;
                    }
                    else
                    {
                        $last_topic_name = $last_topic_id = '';
                        $topic_id = $this->topic_model->check_topic_name($topic_name);
                    }
                    if($topic_id == '')
                    {
                        $topic_id = $this->topic_model->insert(array('topic_name'=>$topic_name));
                        $last_topic_name = $topic_name;
                        $last_topic_id = $topic_id;
                    }
                    if(!$page_check)
                    {
                        $this->topic_page_model->insert(array('topic_id'=>$topic_id, 'page_id'=>$page_id));
                        $this->topic_model->insert_topic_page($topic_id, $id, 'page');
                    }
                }
            }
        }

        redirect('admin/'.$time,'refresh');
    }

    function get_page_from_wiki($time, $start_page)
    {
        $this->db->select('pages.page_id, wiki_photo_url');
        $this->db->from('pages');
        $this->db->join('albums','albums.page_id = pages.page_id');
        $this->db->join('photos','photos.album_id = albums.album_id');
        $this->db->where(array('wiki_time'=>$time, 'album_name'=>'Profile', 'pages.page_id >='=>$start_page));				//need to set a value for wiki_time to upload photos
        //$this->db->where_in('pages.page_id', $pages);
        $query = $this->db->get();
        $row = $query->result_array();
        //print_r($row);
        return $row;
    }


    /********************************************************************
    *WARNING: Before you run this function please contact with Ray!!
    ********************************************************************/
    function upload_wiki_photo()
    {
        //die();
        $time = $this->uri->segment(2);
        if($this->uri->segment(3) != '')
        {
            $start_page = $this->uri->segment(3);
        }
        else
        {
            $start_page = 1;
        }
        $wiki = $this->get_page_from_wiki($time, $start_page);
        //print_r($wiki);
        //die();
        $start_page_id = $wiki[0]['page_id'];
        //echo $time;

        //print_r($wiki);
        foreach($wiki as $k=>$v)
        {
            if($v['wiki_photo_url'] != '' && $v['page_id'] >= $start_page_id)
            {
                echo time();
                $tmp_path = 'tmp/';
                $thumb_tmp = $tmp_path.'thumb/';
                $thumb_name = 'thumb.jpg';

                $album = 'Profile';
                $page = $v['page_id'];
                $user_path = 'pages/'.$page.'/';
                $src_img = $v['wiki_photo_url'];
                $pics_path = $user_path.'pics/';
                $file_path = $pics_path.$album.'/';
                $thumb_path = $pics_path.'thumbs/';
                $newname = time().'.jpg';
                $avatar_img = $file_path.$newname;
                $thumbnail_img = $thumb_path.$thumb_name;

                $image = $tmp_path.$newname;
                $img = $_SERVER['DOCUMENT_ROOT'].'/'.$image;
                $thumb_img = $thumb_tmp.$thumb_name;
                file_put_contents($img, file_get_contents($src_img));

                $type = strtolower(substr(strrchr($src_img,"."),1));
                if($type == 'jpeg')
                {
                    $type = 'jpg';
                }
                //$type = 'png';
                switch($type)
                {
                case 'bmp':
                    $source = imagecreatefromwbmp($img);
                    break;
                case 'gif':
                    $source = imagecreatefromgif($img);
                    break;
                case 'jpg':
                    $source = imagecreatefromjpeg($img);
                    break;
                case 'png':
                    $source = imagecreatefrompng($img);
                    break;
                default :
                    return "Unsupported picture type!";
                }

                // remove dupplicated, replace by common function
                $this->avatarfile_upload($img, $source, $thumb_img, $image, $file_path, $newname, $thumb_path, $thumb_name, $avatar_img, $v, $thumbnail_img);

                $this->output->enable_profiler(TRUE);
            }

        }
        //redirect('admin','refresh');


    }

    function avatarfile_upload($img, $source, $thumb_img, $image, $file_path, $newname, $thumb_path, $thumb_name, $avatar_img, $v, $thumbnail_img)
    {
        //echo $source.'<br>'.$src_img.'<br>'.$type;
        //die();
        list($img_w, $img_h, $type, $attr) = getimagesize($img);
        $x = ($img_w/2)-100;
        $y = ($img_h/2)-100;
        if ($x<0)
        {
            $x=0;
        }
        if ($y<0)
        {
            $y=0;
        }
        $src_w = $img_w;
        $src_h = $img_h;
        $crop = 1;
        if ($crop === 1)
        {
            if ($src_w < $src_h)
            {
                $src_h = $src_w;
            }
            else
            {
                $src_w = $src_h;
            }
            $crop_w = $crop_h = 150;
            if($src_w < 150 || $src_h < 150)
            {
                if($src_w < $src_h)
                {
                    $crop_w = $crop_h = $thumb_size = $src_w;
                }
                else
                {
                    $crop_w = $crop_h = $thumb_size = $src_h;
                }
            }
            else
            {
                $thumb_size = 150;
            }
            $newImage = imagecreatetruecolor($thumb_size,$thumb_size);
            imagesavealpha($newImage, true);
            //$trans_colour = imagecolorallocate($newImage, 0, 0, 0, 127);
            $white = imagecolorallocate($newImage, 255, 255, 255);
            imagefill($newImage, 0, 0, $white);
        }

        if (!imagecopyresampled($newImage,$source,0,0,$x,$y,$crop_w,$crop_h,$src_w,$src_h))
        {
            echo 'imagecopyresampled failed'.'+++'.$img.$crop_h;
        }
        else
        {
            echo $source;
        }

        if (!imagejpeg($newImage,$thumb_img,100))
        {
            echo 'imagejpeg failed';
        }

        $this->load->library('s3');
        $thumb_input = S3::inputFile($thumb_img);
        $img_input = S3::inputFile($image);
        $bucket = s3_bucket();
        $img_uri = $file_path.$newname;
        $thumb_uri = $thumb_path.$thumb_name;
        if (S3::putObject($img_input, $bucket, $img_uri, S3::ACL_PUBLIC_READ))
        {
            echo "File uploaded.";
            $avatar = array('avatar' => $avatar_img);
            $this->db->where('page_id',$v['page_id']);
            $this->db->update('pages', $avatar);
            unlink($image);
        }
        else
        {
            echo "Failed to upload avatar file.";
        }
        if (S3::putObject($thumb_input, $bucket, $thumb_uri, S3::ACL_PUBLIC_READ))
        {
            echo "File uploaded.";
            $thumbnail = array('thumbnail' => $thumbnail_img, 'sign_up_date' => date("Y-m-d H:i:s"));
            $this->db->where('page_id',$v['page_id']);
            $this->db->update('pages', $thumbnail);
            unlink($thumb_img);
        }
        else
        {
            echo "Failed to upload avatar file.";
        }
    }

    function update_abstract()
    {
        $this->db->select('page_name, abstract, main_topic');
        $this->db->from('wiki');
        $query = $this->db->get();
        $row = $query->result_array();

        foreach($row as $k=>$v)
        {
            $this->db->select('page_id');
            $this->db->where('page_name', $v['page_name']);
            $this->db->from('pages');
            $query = $this->db->get();
            $new_row = $query->result_array();

            $update_data = array('interest_id' => $v['main_topic']);
            //echo $v['mian_topic'];
            $this->db->where('page_id', $new_row[0]['page_id']);
            $this->db->update('pages', $update_data);

            $update_info = array('description' => $v['abstract']);
            // $this->db->where('page_id', $new_row[0]['page_id']);
            // $this->db->update('page_info', $update_info);
            $this->page_info_model->update_by(
                array('page_id' => $new_row[0]['page_id']),
                $update_info
            );
        }

        echo 'done';
        $this->output->enable_profiler(TRUE);
    }

    //remove '/' and whatever is after the slash from facebook page categories
    function fix_categories()
    {

        $this->db->select('fb_pageid,fb_category');
        $this->db->from('fb_pages');
        $this->db->like('fb_category','/');
        $query = $this->db->get();
        $row = $query->result_array();
//		print_r($row);
        foreach($row as $k => $v)
        {
            echo $v['fb_pageid'].'<br>';
            $fb_category = substr($v['fb_category'], 0, stripos($v['fb_category'], "/") );
            echo $fb_category.'<br>';
            $this->db->where('fb_pageid', $v['fb_pageid']);
            $this->db->update('fb_pages', array('fb_category' => $fb_category));
        }

        redirect('admin','refresh');
    }

    //transfer Facebook page id to 'page' table
    //transfer page categories
    function transfer_fb_pageids()
    {
        $this->db->select('fb_pageid,fb_pagename,fb_category');
        $this->db->from('fb_pages');
        $query = $this->db->get();
        $row = $query->result_array();

        foreach($row as $k => $v)
        {
            $new_row[$v['fb_pagename']] = $v;
        }

        foreach($new_row as $k => $v)
        {
            echo $v['fb_pagename'].'<br>';
            echo $v['fb_pageid'].'<br>';
            $this->db->where('page_name', $v['fb_pagename']);
            $this->db->update('pages', array('fb_pageid' => $v['fb_pageid']));

            $this->db->select('page_id');
            $this->db->from('pages');
            $this->db->where('page_name', $v['fb_pagename']);
            $query = $this->db->get();
            $row1 = $query->result_array();
            //echo $this->db->last_query();
            print_r($row1);
            //echo('<b>'.$row1[0]['page_id']).'</b><br>';

            if($v['fb_category'] != '' && $row1[0]['page_id']>0)
            {
                //insert topic from category name
                $topic_name = str_replace ('_', ' ', $v['fb_category']);
                if($topic_name == $last_topic_name)
                {
                    $topic_id = $last_topic_id;
                }
                else
                {
                    $last_topic_name = $last_topic_id = '';
                    $topic_id = $this->topic_model->check_topic_name($topic_name);
                }
                if($topic_id == '')
                {
                    $topic_id = $this->topic_model->insert(array('topic_name'=>$topic_name));
                    $last_topic_name = $topic_name;
                    $last_topic_id = $topic_id;
                }
                echo 'topic ID HERE:'.$topic_id.'<br>';
                if(!$page_check)
                {
                    $this->load->model('topic_page_model');
                    $this->topic_page_model->insert(array('topic_model'=>$topic_id, 'page_id'=>$row1[0]['page_id']));
                    $this->topic_model->insert_topic_page($topic_id, $row1[0]['page_id'], 'page');
                }
            }

        }
    }

    //transfer twitter ids
    function transfer_twitter_id()
    {
        $this->db->select('follow_id,follow_name');
        $this->db->from('twitter_follow');
        $query = $this->db->get();
        $row = $query->result_array();

        foreach($row as $k => $v)
        {
            echo $v['follow_name'].'<br>';
            echo $v['follow_id'].'<br>';
            $this->db->where('page_name', $v['follow_name']);
            $this->db->update('pages', array('twitter_id' => $v['follow_id']));
        }
    }



    //get all the pages form db
    function get_all_pages()
    {
        $this->db->select('page_id, page_name');
        $this->db->like('avatar', 'pages/default/', 'after');
        $this->db->from('pages');
        $this->db->order_by('page_id', 'ASC');
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }




    function show_update_empty_img_progress()
    {
        $id1 = $this->uri->segment(2);
        $id2 = $this->uri->segment(3);
        //echo 'Start from Page '.$id1.' to '.$id2;
        echo 'finished Page '.$id1;

        $page = '/update_empty_image';
        $sec = "1";
        header("Refresh: $sec; url=$page");
    }


    function update_empty_img()
    {
        $pages = $this->get_all_pages();


        include ('/google_image_search/gis.php');
        $img_size = 'm';
        $img_type = '';
        $img_page = 1;

        $num = 1;
        $count = 1;
        foreach($pages as $k=>$v)
        {
            $results = googleImageSearchOne($v['page_name'], $img_page, constant($img_size), constant($img_type));
            $img = $results->img;
            echo $v['page_id'].' '.$v['page_name'].' '.$img;

            if(count($pages) != $num)
            {
                //$id_num = count($ids);
                //if($id_num == 500)
                if($count == 500)
                {
                    $count = 0;
                    //$page = '/show_update_empty_image_progress/123';
                    redirect('/show_update_empty_image_progress/'.$v['page_id'],'refresh');
                }
            }
            $num++;
            $count++;
            $ids[] = $v['page_id'];

            if($img == '')
            {
                echo 'no image. <br>';
            }
            else
            {
                $tmp_path = 'tmp/';
                $thumb_tmp = $tmp_path.'thumb/';
                $thumb_name = 'thumb.jpg';

                $album = 'Profile';
                $page = $v['page_id'];
                $user_path = 'pages/'.$page.'/';
                $src_img = $img;
                $pics_path = $user_path.'pics/';
                $file_path = $pics_path.$album.'/';
                $thumb_path = $pics_path.'thumbs/';
                $newname = time().'.jpg';
                $avatar_img = $file_path.$newname;
                $thumbnail_img = $thumb_path.$thumb_name;

                $image = $tmp_path.$newname;
                $img = $_SERVER['DOCUMENT_ROOT'].'/'.$image;
                $thumb_img = $thumb_tmp.$thumb_name;
                file_put_contents($img, file_get_contents($src_img));

                $type = strtolower(substr(strrchr($src_img,"."),1));
                $type = substr($type, 0, 3);
                //echo $type;

                /*
                //$type = 'png';
                  switch($type){
                    case 'bmp': $source = imagecreatefromwbmp($img); break;
                    case 'gif': $source = imagecreatefromgif($img); break;
                    case 'jpg': $source = imagecreatefromjpeg($img); break;
                    case 'png': $source = imagecreatefrompng($img); break;
                    default : return "Unsupported picture type!";
                  }
                */

                if ($image_type == 'jpe' || $image_type == 'jpg')
                {
                    $source = imagecreatefromjpeg($img);
                }
                elseif ($image_type == 'gif')
                {
                    $source = imagecreatefromgif($img);
                }
                elseif ($image_type == 'png')
                {
                    $source = imagecreatefrompng($img);
                }
                elseif ($image_type == 'bmg')
                {
                    $source = imagecreatefromwbmp($img);
                }

                if($source == '')
                {
                    $source = imagecreatefromjpeg($img);
                }
                if($source == '')
                {
                    $source = imagecreatefromgif($img);
                }
                if($source == '')
                {
                    $source = imagecreatefrompng($img);
                }
                if($source == '')
                {
                    $source = imagecreatefromwbmp($img);
                }

                // remove dupplicated, replace by common function
                $this->avatarfile_upload($img, $source, $thumb_img, $image, $file_path, $newname, $thumb_path, $thumb_name, $avatar_img, $v, $thumbnail_img);

                echo ' Done<br>';
            }

        }
        echo '<a href="/admin">BACK</a>';
        $this->output->enable_profiler(TRUE);
    }














}
