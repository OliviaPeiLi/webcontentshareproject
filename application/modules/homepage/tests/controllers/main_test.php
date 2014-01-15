<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class home_main_test extends Web_Test_Case
{

    /*
    **	The test function for page /followers/{user_id}
    */
    public function disabled_test_followers()
    {
        $user = $this->config['login'];
        $limit = 30;

        $this->login();
        $page = $this->get("/followers/{$user['id']}");

        $this->assertTitle('Followers');
        $this->assertPattern("#<h1>{$user['fist_name']} {$user['last_name']}</h1>#msi");
        $this->assertPattern("#<h6>Followers</h6>#msi");

        $followers = $this->db_interface->db
                     ->select('users.first_name, users.last_name, users.uri_name')
                     ->where('user2_id', $user['id'])
                     ->join('users', 'users.id = connections.user2_id', 'left')
                     ->limit($limit)
                     ->get('connections')
                     ->result();
        if($followers)
        {
            $followers_count = 0;
            foreach($followers as $row)
            {
                $profile_url = $this->config['base_url'].'collections/'.$row->uri_name;
                if( preg_match("#<a href=\"{$profile_url}\">{$row->first_name} {$row->last_name}</a>#msi", $page) )
                {
                    $followers_count++;
                }
            }
            $this->assertEqual(count($followers), $followers_count, "Number of followers found not match, db:".count($followers)." page:$followers_count");
        }
        else
        {
            $this->assertPattern('#<div id="no_follow">No Followers</div>#msi');
        }
    }

    /*
    **	The test function for page /followings/{user_id}
    */
    public function disabled_test_following()
    {
        $user = $this->config['login'];
        $limit = 30;

        $this->login();
        $page = $this->get("/followings/{$user['id']}");

        $this->assertTitle('Following');
        $this->assertPattern("#<h1>{$user['fist_name']} {$user['last_name']}</h1>#msi");
        $this->assertPattern("#<h6>Following</h6>#msi");

        $followings = $this->db_interface->db
                      ->select('users.first_name, users.last_name, users.uri_name')
                      ->where('user1_id', $user['id'])
                      ->join('users', 'users.id = connections.user1_id', 'left')
                      ->limit($limit)
                      ->get('connections')
                      ->result();
        if($followings)
        {
            $followings_count = 0;
            foreach($followings as $row)
            {
                $profile_url = $this->config['base_url'].'collections/'.$row->uri_name;
                if( preg_match("#<a href=\"{$profile_url}\">{$row->first_name} {$row->last_name}</a>#msi", $page) )
                {
                    $followings_count++;
                }
            }
            $this->assertEqual(count($followings), $followings_count, "Number of followings found not match, db:".count($followings)." page:$followings_count");
        }
        else
        {
            $this->assertPattern('#<div id="no_follow">You are not following anyone.</div>#msi');
        }
    }

    /**
     * The test functions cover all vars in the controller
     * this view is /views/home/home_main.php view
     */

    public function disabled_test_index()
    {
        $this->setMaximumRedirects(0);
        $this->db_interface->db->where('id', $this->config['login']['id'])->update('user_visits', array('home'=>'1'));
        $this->logout();

        // try to do the requrest while logged out
        $data = $this->get('');
        $this->assertFieldByName('email');
        $this->assertFieldByName('password');

        //login and check if the correct template is shown (/views/home/home_main.php)
        $this->login();

        /**
         * pages updated to page_users as the frontend query
         */
        $item = $this->db_interface->db->where('page_id_to IN (SELECT page_id FROM page_users WHERE user_id = '.$this->config['login']['id'].')')
                ->order_by('time','desc')->limit(1)->get('newsfeed')->row();

        if (!$item) $item = $this->db_interface->add_object('newsfeed');
        /**
         * When adding db objects this should be done before the get()
         *
         * Here also the query for retrieving activity is wrong and also for adding new if there is not any
         * you need to follow how the $ticker var is populated in home/home_main.php
         */
        $activity = $this->db_interface->db->where('activity_user_id IN (SELECT user2_id FROM connections WHERE user1_id = '.$this->config['login']['id'].') AND a_data != ""')->order_by('aid', 'desc')->limit(1)->get('newsfeed_activity')->row();
        //echo '@@@'; print_r($activity); echo '@@@';
        if (!$activity)
        {
            $connection = $this->db_interface->add_object('connections');
            $activity = $this->db_interface->add_object('newsfeed_activity');
        }
        //echo '###'; print_r($activity); echo '###';
        $data = $this->get('');
        $this->assertPattern('#<div id="main" class="main_home">#msi');
        $this->assertPattern('#<a href="/walkthrough/home"#msi');
        $this->assertTitle('Fandrop');

        $news_array=unserialize($item->data);

        $this->assertPattern('#<li class="photo newsfeed_entry" data-newsfeed_id="'.$item->newsfeed_id.'"#msi');

        $this->assertPattern('#<p class="posted_to" data-pageid="'.$item->page_id_to.'"#msi');

        $items = $this->db_interface->db
                 ->where(array('list_maker_id'=>$this->config['login']['id']))
                 ->order_by('time', 'desc')
                 ->get('lists')
                 ->result();
        foreach ($items as $item)
        {
            //$this->assertPattern('#<li id="list_tab_'.$item->list_id.'#msi');
        }

        $this->assertPattern('#<div id="activity_'.$activity->aid.'" class="post_info inlinediv">#msi');
        //TO-DO test the rest of the vars
    }

    /**
     * To test the inner pages use the link instead of get or from submit instead of post
     * example for get:  /list/tests/list_controller_test
     * example for post: /link/tests/link_controller_test
     */

    public function disabled_test_profile()
    {
        $this->db_interface->db->where('id', $this->config['login']['id'])->update('user_visits', array('home'=>'1'));
        $this->logout();
        $user = $this->db_interface->db->where('id', $this->config['objects']['test_profile']['id'])->get('users')->row();
        if(!isset($user))
        {
            $user = $this->db_interface->add_object('test_profile');
        }

        // try to do the requrest while logged out
        $this->get('');
        $this->assertFieldByName('email');
        $this->assertFieldByName('password');

        //login and check if the Profile page was loaded.
        $this->login();
        $this->get(sprintf('profile/%s/%s', $user->uri_name, $user->id));
        $this->assertTitle(sprintf('%s %s', $user->first_name, $user->last_name));

        // Testing #User details#
        $user_details = $this->db_interface->db
                        ->select('interests,follower,following')
                        ->from('users')
                        ->where('id', $user->id)
                        ->get()
                        ->row();
        $this->assertPattern('#<div id="user_info">#msi');
        $this->assertPattern('#<div class="info_item_title">Interests</div>[^<]*<div class="info_count">'.$user_details->interests.'</div>#msi');
        $this->assertPattern('#<div class="info_item_title">Following</div>[^<]*<div class="info_count">'.$user_details->following.'</div>#msi');
        $this->assertPattern('#<div class="info_item_title">Followers</div>[^<]*<div class="info_count">'.$user_details->follower.'</div>#msi');



        // Testing #Activity#
        $activity = $this->db_interface->db
                    ->select('aid')
                    ->from('newsfeed_activity')
                    ->where('activity_user_type', 'users')
                    ->where(array('a_data !='=>''))
                    ->where_in('activity_user_id', $user->id)
                    ->order_by('aid', 'DESC')
                    ->limit(30)
                    ->get()
                    ->result();
        $this->assertPattern('#<div id="profile_newsfeeds_placeholder" class="newsfeed newsfeed_placeholder">#msi');
        if($activity)
        {
            foreach($activity as $row)
            {
                $this->assertPattern('#<div id="activity_'.$row->aid.'"#msi');
            }
        }

        // Testing #Fav 5#
        $vibes = $this->db_interface->db
                 ->select('pages.page_id, uri_name')
                 ->where(array('user_id'=>$user->id, 'vibe >'=>'0'))
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

        // Testing #Folders#
        $folders_ids = array();
        $folders = $this->db_interface->db
                   ->select('folder_id, folder_name')
                   ->where('user_id', $user->id)
                   ->from('folder')
                   ->order_by("private")
                   ->order_by("folder_id")
                   ->get()
                   ->result();
        $this->assertPattern('#<div id="folders">#msi');
        if($folders)
        {
            foreach($folders as $row)
            {
                $folders_ids[] = $row->folder_id;
                $this->assertPattern('#<div class="folder expandable_folder" rel="'.$row->folder_id.'" rel_name="'.url_title($row->folder_name).'"#msi');
            }
        }

        // Testing #Newsfeed#
        if(count($folders_ids) > 0)
        {
            $news = $this->db_interface->db
                    ->select('newsfeed_id, activity_id, link_type')
                    ->from('newsfeed')
                    ->where_in('folder_id', $folders_ids)
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