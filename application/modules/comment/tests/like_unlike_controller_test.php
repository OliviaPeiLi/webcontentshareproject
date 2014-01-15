<?php
class Like_unlike_controller_test extends Web_Test_Case
{

    public function disabled_test_index()
    {

        $user_id = $this->config['login']['id'];


        //get a newsfeed
        $newsfeed = $this->db_interface->db
                    ->where(array('type'=>'link'))
                    ->order_by('newsfeed_id', 'desc')
                    ->limit(1)
                    ->get('newsfeed')
                    ->row();

        $this->login();
        $page_html = $this->get('drop/'.$newsfeed->newsfeed_id);

        //get activity id
        $link_id = $newsfeed->activity_id;

        //unserialize data field
        $data = $newsfeed->data;

        $newsfeed_data = unserialize($data);



        //at least one like is present
        if(isset($newsfeed_data['likes']))
        {
            $this->assertPattern('#<p class=\"like_text\">#msi');

            //check current user like this page
            $liked = false;
            foreach($newsfeed_data['likes'] as $like)
            {
                if($like['user_id'] = $user_id)
                {
                    $liked = true;
                    break;
                }
            }
            if($liked)
            {
                $this->assertPattern('#<p class=\"like_text\">(.*?)You#msi');
            }

        }
        else
        {
            //user don't like this page

            //check the like is not present
            $this->check_not_like($page_html);

            //insert like in database
            $newlike = $this->db_interface->add_object('likes', array('user_id'=>$user_id, 'link_id'=>$link_id));


            //update newsfeed like
            $newfeed_like['like_id']  = $newlike->like_id;
            $newfeed_like['user_id']  = $newlike->user_id;
            $newfeed_like['page_id']  = $newlike->page_id;
            $newfeed_like['like_time']  = $newlike->time;
            $newfeed_like['link'] = '<a href="/profile/Alexi-Ned/1">Alexi Ned</a>';
            $newfeed_like['url'] = 'profile/Alexi-Ned/1';
            $newfeed_like['thumbnail'] = 'https://s3.amazonaws.com/fantoon-dev/users/1/pics/thumbs/thumb.jpg';
            $newfeed_like['type'] = 'user';

            $newsfeed_data['likes'][] = $newfeed_like;

            //update newsfeed database record
            $this->db_interface->db
            ->set('data', serialize($newsfeed_data))
            ->where('newsfeed_id',$newsfeed->newsfeed_id)
            ->update('newsfeed');

            //get updated page
            $page_html = $this->get('drop/'.$newsfeed->newsfeed_id);



            //check the like is present on page
            $this->assertPattern('#<p class=\"like_text\">(.*?)You#msi');



            /*
            * delete like from database and check the page
            */

            //delete from newsfeed like data
            unset($newsfeed_data['likes']);

            //update newsfeed database record
            $this->db_interface->db
            ->set('data', serialize($newsfeed_data))
            ->where('newsfeed_id',$newsfeed->newsfeed_id)
            ->update('newsfeed');

            //delete like from likes table
            $this->db_interface->db->delete('likes', array('like_id'=>$newlike->like_id));

            //get updated page, refresh page
            $page_html = $this->get('drop/'.$newsfeed->newsfeed_id);
            $this->check_not_like($page_html);

        }

    }
    //check the like content is not present
    function check_not_like($page_html)
    {

        preg_match('#<div class=\"like_text_container\">(.*?)<\/div>#msi', $page_html, $match);

        $position = strpos($match[0], '<p class="like_text">');

        $this->assertEqual(false, $position, 'In database like is not present but Page has like');

    }



}