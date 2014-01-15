<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class message_test extends Web_Test_Case
{

    /**
     * The test functions cover all vars in the controller
     * this view is message/msg_inbox view
     */

    public function disabled_test_message()
    {
        $this->setMaximumRedirects(0);
        $this->db_interface->db->where('id', $this->config['login']['id'])->update('user_visits', array('home'=>'1'));

        $this->logout();

        // try to do the requrest while logged out
        $data = $this->get('/signin');
        $this->assertFieldByName('email');
        $this->assertFieldByName('password');

        $this->login();
        $data = $this->get('messages');
        $this->assertTitle('Message');
        $this->assertPattern('#<h2>Messages</h2>#msi');
        $this->assertPattern('#<div class="profile_pic">#msi');
        $this->assertPattern('#<a id="new_message"#msi');

        // Testing #Thread#
        $threads = $this->db_interface->db
                   ->select_max('msg_id')
                   ->select('msg_info.thread_id as thread_id, users, msg_info.display_status')
                   ->from('msg_info')
                   ->join('msg_thread', 'msg_info.thread_id = msg_thread.thread_id')
                   ->join('users', 'users.id = msg_info.from')
                   ->where(array('to' => $this->config['login']['id'], 'erase_type !=' => '1'))
                   ->or_where(array('from' => $this->config['login']['id']))
                   ->where(array('erase_type !=' => '2'))
                   ->group_by('thread_id')
                   ->get()
                   ->result();

        if($threads)
        {
            foreach($threads as $key => $row)
            {
                if($key == 0)
                {
                    $first_thread = array('id' => $row->thread_id);
                }
                $exist = $this->assertPattern('#<a href="/view_msg/'.$row->thread_id.'">#msi');
                if($exist !== true)
                {
                    echo "Thread id #".$row->thread_id.";  Status:".$row->display_status."\n";
                }
                // Testing #Last Message of the Thread#
                $last_msg = $this->db_interface->db
                            ->select('msg_body')
                            ->from('msg_content')
                            ->where(array('msg_id' => $row->msg_id))
                            ->get()
                            ->row();
                if($last_msg)
                {
                    if($key == 0)
                    {
                        $first_thread['text'] = $last_msg->msg_body;
                    }
                    if(strlen($last_msg->msg_body)>70)
                    {
                        $msg_body = substr($last_msg->msg_body, 0, 70).'...';
                    }
                    else
                    {
                        $msg_body = $last_msg->msg_body;
                    }
                    $pattern = '#<p>'.preg_quote($msg_body).'<\/p>#msi';
                    $this->assertPattern($pattern);
                }
            }
            $this->assertPattern('#<a href="'.sprintf('/view_msg/%s', $first_thread['id']).'">#msi');
            $this->get(sprintf('view_msg/%s', $first_thread['id']));
            $this->assertTitle('Your Messages');
            $this->assertPattern('#<h2>Conversation</h2>#msi');
        }

        // Testing #Messages of the Thread#
        $current_messages = $this->db_interface->db
                            ->select('msg_body')
                            ->from('msg_content')
                            ->join('msg_info', 'msg_content.msg_id = msg_info.msg_id')
                            ->where(array('msg_content.thread_id' => $first_thread['id']))
                            ->where(array('msg_info.erase_type !=' => '2'))
                            ->where(array('msg_info.erase_type !=' => '1'))
                            ->order_by('msg_info.time DESC')
                            ->get()
                            ->result();
        if($current_messages)
        {
            foreach($current_messages as $row)
            {
                $this->assertPattern('#<p>'.preg_quote($row->msg_body).'</p>#msi');
            }
        }
        $this->logout();
    }

}