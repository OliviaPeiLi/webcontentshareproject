<?php
class Comments_controller_test extends Web_Test_Case
{

    public function disabled_test_index()
    {

        $user_id = $this->config['login']['id'];

        //create comment
        $comment = $this->db_interface->add_object('comments', array('user_id_from'=>$user_id));

        //get newsfeed
        $newsfeed = $this->db_interface->db
                    ->where(array('activity_user_id' => $user_id, 'type'=>'link'))
                    ->order_by('newsfeed_id', 'desc')
                    ->limit(1)
                    ->get('newsfeed')
                    ->row();

        //extract data from newsfeed
        $data = $newsfeed->data;
        $newsfeed_data = unserialize($data);

        //create comment to update newsfeed
        $comment_newsfeed = array(
                                'comment_id' =>$comment->comment_id,
                                'parent_id' => $comment->parent_id,
                                'reply_user_id' => $comment->reply_user_id,
                                'reply_page_id' => $comment->reply_page_id,
                                'user_id_from' => $comment->user_id_from,
                                'user_id_to' => $comment->user_id_to,
                                'page_id_from' => $comment->page_id_from,
                                'page_id_to' => $comment->page_id_to,
                                'post_id' => $comment->post_id,
                                'photo_id' => $comment->photo_id,
                                'event_id' => $comment->event_id,
                                'pr_id' => $comment->pr_id,
                                'link_id' => $comment->link_id,
                                'ctime' => $comment->time,
                                'comment' => $comment->comment,
                                'link' => '<a href="/profile/Alexi-Ned/1">Alexi Ned</a>',
                                'url' => 'profile/Alexi-Ned/1',
                                'thumbnail' => 'https://s3.amazonaws.com/fantoon-dev/users/1/pics/thumbs/thumb.jpg'
                            );

        //add new comment to newsfeed data array
        $newsfeed_data['comments'][$comment->comment_id] = $comment_newsfeed;

        //update newsfeed database record
        $this->db_interface->db
        ->set('data', serialize($newsfeed_data))
        ->where('newsfeed_id',$newsfeed->newsfeed_id)
        ->update('newsfeed');


        $this->login();
        $data = $this->get('drop/'.$newsfeed->newsfeed_id.'/content');

        //check the comment is present
        $this->assertPattern('#<ul class="child_comments">(.*?)'.$comment->comment.'#msi');


        /*
        * delete comment from database and check the page
        */

        //delete from newsfeed commens array
        unset($newsfeed_data['comments'][$comment->comment_id]);

        //update newsfeed database record
        $this->db_interface->db
        ->set('data', serialize($newsfeed_data))
        ->where('newsfeed_id',$newsfeed->newsfeed_id)
        ->update('newsfeed');

        //delete comment from comments table
        $this->db_interface->db->delete('comments', array('comment_id'=>$comment->comment_id));

        //check the comment is not present
        $this->assertPattern('#<ul class="child_comments">(.*?)'.$comment->comment.'#msi');


    }



}