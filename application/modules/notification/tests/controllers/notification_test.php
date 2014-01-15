<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class notification_test extends Web_Test_Case
{

    /*
    ** The test functions cover all vars in the controller
    ** this view is /views/notification/notification.php view
    */

    public function disabled_test_notification()
    {
        $this->setMaximumRedirects(3);
        $this->db_interface->db->where('id', $this->config['login']['id'])->update('user_visits', array('home'=>'1'));
        $user = $this->db_interface->db->where('id', $this->config['login']['id'])->get('users')->row_array();
        if(!isset($user))
        {
            $user = $this->config['login'];
        }

        //Login and check if the correct template is shown (/views/home/home_main.php)
        $this->login();
        $data = $this->get('');
        $this->assertTitle('Fandrop');
        $this->assertPattern('#<div id="account_name" class="inlinediv">'.sprintf('%s %s', $user['first_name'], $user['last_name']).'</div>#msi');

        //Testing #Notifications#
        $notifications = array();
        $unreaded_n = $this->db_interface->db
                      ->select('notifications.type, m_id, a_id, id, user_id_from')
                      ->from('notifications')
                      ->where(array('user_id_to'=>$user['id'], 'read'=>'0', 'page_id_to'=>0))
                      ->order_by('id','DESC')
                      ->limit(5)
                      ->get()
                      ->result();
        if($unreaded_n)   //If unreaded notifications retrieved
        {
            foreach($unreaded_n as $row)
            {
                $notifications[] = $row;
            }
        }
        if(count($unreaded_n)<5)   //If unreaded notifications less then 5
        {
            $limit=5-count($unreaded_n);
            $readed_n = $this->db_interface->db
                        ->select('notifications.type, m_id, a_id, id, user_id_from, read')
                        ->from('notifications')
                        ->where(array('user_id_to'=>$user['id'], 'read'=>'1', 'page_id_to'=>0))
                        ->order_by('id','DESC')
                        ->limit($limit)
                        ->get()
                        ->result();
            if($readed_n)   //If readed notifications retrieved
            {
                foreach($readed_n as $row)
                {
                    $notifications[] = $row;
                }
            }
        }
        if($notifications)   //If notifications were retrieved
        {
            foreach($notifications as $key => $row)
            {
                $who = '';
                $class = @$row->read == 0 ? 'unread_notification' : '';
                $user_from = $this->db_interface->db
                             ->select('id, uri_name, first_name, last_name')
                             ->where(array('id' => $row->user_id_from))
                             ->get('users')
                             ->row();
                if($user_from)
                {
                    $who = sprintf('<a href="%scollections/%s">%s %s</a>', $this->config['base_url'], $user_from->uri_name, $user_from->first_name, $user_from->last_name);
                }
                switch($row->type)
                {
                case 'follow':
                    $pattern = sprintf('%s is following you.', $who);
                    break;
                case 'message':
                    $pattern = sprintf('%s sent you a message.', $who);
                    break;
                case 'link_like':
                    $pattern = sprintf('%s likes your drop.', $who);
                    break;
                case 'link_comm_like':
                    $pattern = sprintf('%s likes your comment.', $who);
                    break;
                case 'comm':
                    $pattern = sprintf('%s commented on a drop.', $who);
                    break;
                case 'u_comm':
                    $pattern = sprintf('%s commented on the drop.', $who);
                    break;
                case 'reply_comm':
                    $pattern = sprintf('%s replied to your comment.', $who);
                    break;
                case 'link':
                    $pattern = sprintf('%s shared a link.', $who);
                    break;
                default:
                    $pattern = false;
                    break;
                }
                if($pattern)
                {
                    $notifications[$key]->pattern = $pattern;
                    $this->assertPattern('#'.preg_quote('<li class="'.$class.'" >'.$pattern).'#msi');
                }
            }
        }

        $unreaded_n_all = $this->db_interface->db
                          ->where(array('user_id_to'=>$user['id'], 'read'=>'0', 'page_id_to'=>0))
                          ->count_all_results('notifications');
        $this->assertPattern('#rel="notifications">[[:space:]]*'.$unreaded_n_all.'[[:space:]]*</a>#msi');

        //Testing #Show All# Page
        $this->get('show_all');
        $this->assertTitle('Your Notifications');
        $this->assertPattern('#<h1>Your Notifications</h1>#msi');
        if($notifications)
        {
            foreach($notifications as $row)
            {
                $this->assertPattern('#'.preg_quote($row->pattern).'#msi');
            }
        }
    }

    /*
    ** DISABLED
    */
    public function disabled_check_page_owner_notifications()
    {
        $this->setMaximumRedirects(3);
        $this->db_interface->db->where('id', $this->config['login']['id'])->update('user_visits', array('home'=>'1'));
        $user = $this->db_interface->db->where('id', $this->config['login']['id'])->get('users')->row_array();
        if(!isset($user))
        {
            $user = $this->config['login'];
        }

        //Login and check if the correct template is shown (/views/home/home_main.php)
        $this->login();
        $data = $this->get('');
        $this->assertTitle('Fandrop');
        $this->assertPattern('#<div id="account_name" class="inlinediv">'.sprintf('%s %s', $user['first_name'], $user['last_name']).'</div>#msi');

        //Search for page to switch
        $page = $this->db_interface->db
                ->select('page_id, page_name, uri_name, uri_name, official_url')
                ->from('pages')
                ->join('notifications', 'notifications.page_id_to = pages.page_id')
                ->where(array('pages.owner_id' => $user['id']))
                ->having('COUNT(notifications.id) > 0')
                ->limit(1)
                ->get()
                ->row();
        if($page)
        {
            //Switching to Page Owner MODE
            $this->get('switch_to_page/'.$page->page_id);
            $this->get('');

            //Check if the correct page loaded
            $this->assertTitle('Fandrop');
            $this->assertPattern('#<a href="/page_form/'.$page->page_id.'"><li>Page Info</li></a>#msi');

            //Testing #Notifications#
            $notifications = array();
            $unreaded_n = $this->db_interface->db
                          ->select('notifications.type, m_id, a_id, id, user_id_from')
                          ->from('notifications')
                          ->join('newsfeed_activity','newsfeed_activity.aid = notifications.a_id')
                          ->where(array('read'=>'0', 'page_id_to'=>$page->page_id))
                          ->order_by('id DESC')
                          ->limit(5)
                          ->get()
                          ->result();
            if($unreaded_n)   //If unreaded notifications retrieved
            {
                foreach($unreaded_n as $row)
                {
                    $notifications[] = $row;
                }
            }
            if(count($unreaded_n)<5)   //If unreaded notifications less then 5
            {
                $limit=5-count($unreaded_n);
                $readed_n = $this->db_interface->db
                            ->select('notifications.type, m_id, a_id, id, user_id_from')
                            ->from('notifications')
                            ->join('newsfeed_activity','newsfeed_activity.aid = notifications.a_id')
                            ->where(array('read'=>'1', 'page_id_to'=>$page->page_id))
                            ->order_by('id DESC')
                            ->limit($limit)
                            ->get()
                            ->result();
                if($readed_n)   //If readed notifications retrieved
                {
                    foreach($readed_n as $row)
                    {
                        $notifications[] = $row;
                    }
                }
            }
            if($notifications)   //If notifications were retrieved
            {
                foreach($notifications as $key => $row)
                {
                    switch($row->type)
                    {
                    case 'follow':
                        $f_user = $this->db_interface->db
                                  ->select('id, uri_name, first_name, last_name')
                                  ->where(array('id' => $row->user_id_from))
                                  ->get('users')
                                  ->row();
                        if($f_user)
                        {
                            $pattern = sprintf('<a href="%sprofile/%s/%s">%s %s</a>', $this->config['base_url'], $f_user->uri_name, $f_user->id, $f_user->first_name, $f_user->last_name);
                        }
                        break;

                    case 'message':
                        $pattern = sprintf('<a href="%sview_msg/%s" class="read">Read</a>', $this->config['base_url'], $row->m_id);
                        break;

                    default:
                        $newsfeed_id = $this->db_interface->db
                                       ->select('newsfeed_id')
                                       ->from('newsfeed_activity')
                                       ->where(array('aid' => $row->a_id))
                                       ->get()
                                       ->row();
                        $pattern = sprintf('<a rel="popup" href="%sdrop/%s" class="read" data-width="95%%">Read</a>', $this->config['base_url'], $newsfeed_id->newsfeed_id);
                        break;
                    }
                    $notifications[$key]->pattern = $pattern;
                    $this->assertPattern('#'.preg_quote($pattern).'#msi');
                }
            }
            $unreaded_n_all = $this->db_interface->db
                              ->where(array('read'=>'0', 'page_id_to'=>$page->page_id))
                              ->count_all_results('notifications');
            $this->assertPattern('#rel="notifications">[[:space:]]*'.$unreaded_n_all.'[[:space:]]*</a>#msi');

            //Testing #Show All# Page
            $this->get('show_all');
            $this->assertTitle('Your Notifications');
            $this->assertPattern('#<h1>Your Notifications</h1>#msi');
            if($notifications)
            {
                foreach($notifications as $row)
                {
                    $this->assertPattern('#'.preg_quote($row->pattern).'#msi');
                }
            }
        }
        $this->logout();

    }

}

/* notification_test.php */