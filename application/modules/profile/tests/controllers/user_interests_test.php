<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class user_interests_test extends Web_Test_Case
{



    public function disabled_test_index()
    {

        $limit = 50;
        $uid = $this->config['test_user']['user_id'];

        $this->login();

        //open user interests page
        $this->get('view_interests/'.$uid);

        //get user info from database
        $user = $this->db_interface->db
                ->where('id', $uid)
                ->get('users')->row();

        //check first name and last name
        $full_name = $user->first_name. ' '. $user->last_name;
        $this->assertPattern('#<h1>'.$full_name.'<\/h1>#msi');


        $interests = $this->db_interface->db
                     ->select('page_users.page_id, pages.page_name, pages.uri_name, pages.thumbnail, interest_id, role, points')
                     ->from('page_users')
                     ->join('points_system', 'points_system.page_id = page_users.page_id', 'left')
                     ->where('page_users.user_id', $uid)
                     ->limit($limit)
                     ->order_by('pages.page_name', 'ASC')
                     ->join('pages', 'pages.page_id = page_users.page_id')
                     ->group_by('page_id')
                     ->get()
                     ->result();



        foreach($interests as $interest)
        {
            $interest_name = substr($interest->page_name, 0, 20);
            $this->assertPattern('#<ul id=\"my_interests\">(.*?)'.preg_quote($interest_name).'#msi');
        }


        //check user info block
        $this->assertPattern('#<div id=\"user_info\">#msi');
        $this->assertPattern('#<div class="info_item_title">Interests</div>[^<]*<div class="info_count">'.$user->interests.'</div>#msi');
        $this->assertPattern('#<div class="info_item_title">Following</div>[^<]*<div class="info_count">'.$user->following.'</div>#msi');
        $this->assertPattern('#<div class="info_item_title">Followers</div>[^<]*<div class="info_count">'.$user->follower.'</div>#msi');

    }

}