<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class user_info_test extends Web_Test_Case
{



    public function disabled_test_index()
    {

        $limit = 20;
        $uid = $this->config['test_user']['user_id'];

        $this->login();

        $this->get('get_info/'.$uid);

        $user = $this->db_interface->db
                ->where('id', $uid)
                ->get('users')->row();

        //check first name and last name
        $full_name = $user->first_name. ' '. $user->last_name;
        $this->assertPattern('#<h1>'.$full_name.'<\/h1>#msi');


        //check birthday
        $this->assertPattern('#<div class=\"info_body\">(.*?)' . $user->birthday . '#msi');

        //check geneder
        $gender = ($user->gender=='m') ? 'Male': 'Female';
        $this->assertPattern('#<div class=\"info_body\">(.*?)' . $gender . '#msi');


        //check about
        $bio = $user->about;
        if($bio)
        {
            $this->assertPattern('#<div class=\"info_body\">(.*?)' . $bio . '#msi');
        }

        //check im
        $im = $user->im;
        if($im)
        {
            $this->assertPattern('#<div class=\"info_body\">(.*?)' . $im . '#msi');
        }

        //check schools
        //<h6>Education</h6>
        $schools = $this->db_interface->db
                   ->select('user_schools.id, school_id, name, year, major')
                   ->join('schools', 'user_schools.school_id = schools.id','left')
                   ->where('user_id', $uid)
                   ->get('user_schools')
                   ->result();

        foreach($schools as $school)
        {
            $this->assertPattern('#<h6>Education<\/h6>(.*?)' . $school->name.'#msi');
        }


        //check user locations
        //<h6>Location</h6>
        $locations = $this->db_interface->db
                     ->where('user_id',$uid)
                     ->get('user_locations')
                     ->result();
        foreach($locations as $location)
        {
            $this->assertPattern('#<h6>Location<\/h6>(.*?)' . $location->place_name.'#msi');
        }


        //check user links
        $links = $this->db_interface->db
                 ->where('user_id', $uid)
                 ->get('user_links')
                 ->result();
        foreach($links as $link)
        {
            $this->assertPattern('#<h6>Links<\/h6>(.*?)' . $link->url.'#msi');
        }

        //check user info block
        $this->assertPattern('#<div id="user_info">#msi');
        $this->assertPattern('#<div class="info_item_title">Interests</div>[^<]*<div class="info_count">'.$user->interests.'</div>#msi');
        $this->assertPattern('#<div class="info_item_title">Following</div>[^<]*<div class="info_count">'.$user->following.'</div>#msi');
        $this->assertPattern('#<div class="info_item_title">Followers</div>[^<]*<div class="info_count">'.$user->follower.'</div>#msi');

    }

}