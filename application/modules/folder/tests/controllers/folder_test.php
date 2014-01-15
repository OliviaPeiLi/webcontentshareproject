<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class folder_test extends Web_Test_Case
{

    /**
     * The test functions cover all vars in the controller
     * this view is /application/modules/folder/views/folder_main.php view
     */

    public function disabled_test_folder()
    {
        $this->setMaximumRedirects(0);
        $folder_id = $this->config['objects']['test_folder']['folder_id'];
        $this->db_interface->db->where('id', $this->config['login']['id'])->update('user_visits', array('home'=>'1'));

        $folder = $this->db_interface->db
                  ->select('folder_id, folder_name, user_id, uri_name, first_name, last_name, private')
                  ->from('folder')
                  ->where('folder_id', $folder_id)
                  ->join('users', 'users.id = folder.user_id')
                  ->get()
                  ->row();
        if(!isset($folder))
        {
            $folder = $this->db_interface->add_object('test_folder');
        }

        $this->logout();

        // try to do the requrest while logged out
        $data = $this->get('');
        $this->assertFieldByName('email');
        $this->assertFieldByName('password');

        $this->login();
        $this->get(sprintf('folder/%s/%s/%s', $folder->uri_name, $folder->folder_name, $folder->folder_id));
        if($folder->private == 0)   //If the folder is Public
        {
            $this->assertTitle(sprintf('%s - Fandrop',$folder->folder_name));
            $this->assertPattern('#<h1>'.sprintf('%s %s',$folder->first_name, $folder->last_name).'</h1>#msi');
            $this->assertPattern('#<h2>'.$folder->folder_name.'</h2>#msi');

            // Testing #User details#
            $user_details = $this->db_interface->db
                            ->select('interests,follower,following')
                            ->from('users')
                            ->where('id', $folder->user_id)
                            ->get()
                            ->row();
            $this->assertPattern('#<div id="user_info">#msi');
            $this->assertPattern('#<div class="info_item_title">Interests</div>[^<]*<div class="info_count">'.$user_details->interests.'</div>#msi');
            $this->assertPattern('#<div class="info_item_title">Following</div>[^<]*<div class="info_count">'.$user_details->following.'</div>#msi');
            $this->assertPattern('#<div class="info_item_title">Followers</div>[^<]*<div class="info_count">'.$user_details->follower.'</div>#msi');

            // Testing #Fav 5#
            $vibes = $this->db_interface->db
                     ->select('pages.page_id, uri_name')
                     ->where(array('user_id' => $folder->user_id, 'vibe >'=>'0'))
                     ->from('page_users')
                     ->join('pages', 'pages.page_id = page_users.page_id')
                     ->order_by('vibe')
                     ->get()
                     ->result();

            //$this->assertPattern('#<div id="fav_5_container">#msi');
            if($vibes)
            {
                foreach($vibes as $row)
                {
                    $this->assertPattern('#<a href="'.$this->config['base_url'].'interests/'.$row->uri_name.'/'.$row->page_id.'">#msi');
                }
            }

            // Testing #Newsfeed#
            $news = $this->db_interface->db
                    ->select('newsfeed_id, activity_id, link_type')
                    ->from('newsfeed')
                    ->where('folder_id', $folder->folder_id)
                    ->order_by('time DESC')
                    ->limit(20)
                    ->get()
                    ->result();
            if($news)
            {
                foreach($news as $row)
                {
                    $this->assertPattern('#<div class="pin" rel="'.$row->newsfeed_id.'" type="'.$row->link_type.'" alt="'.$row->activity_id.'"#msi');
                }
            }
        }
        $this->logout();
    }

}