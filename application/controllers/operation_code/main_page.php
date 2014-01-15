<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_page extends CI_Controller
{

    var $CI, $session;

    public function index()
    {
        $this->CI = &get_instance();
        $this->CI->load->library("session");
        $this->session = $this->CI->session;
    }

    function __construct()
    {
        //parent::__construct();
    }



    /**
     * login
     * 	Store the session variables such as logged_in=true/false,
     * 	userid account & who is chatting with (friend variable)
     *
     * @access public
     * @return void
     */
    public function login($userid)
    {
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));

        // $userid = $this->input->post('userid');

        //
        $this->load->model('Cometchat_database');
        $session = $this->Cometchat_database->get_session($userid);
        $friendlist = array();
        if ($session->num_rows() == 1)
        {
            foreach ($session->result() as $s)
            {
                $friendlist = preg_split("/,/", $s->friendlistid);
            }
        }

        // should load more configuration setting from database
        $this->session->set_userdata(array(
                                         'logged_in' => TRUE,
                                         'userid' => $userid,
                                         'friend' => 0,
                                         'first_buddy_get' => true,
                                         'friendlist' => $friendlist
                                     ));

        // clean offline status
        $cleantime = $this->Cometchat_database->online_interval();
        $this->Cometchat_database->clean_offline($cleantime);

//		redirect('main_page');
    }

    /**
     * logout
     * 	Handle logout button pressed. User is set as offline
     * 	and session is detroyed
     *
     * @access public
     * @return void
     */
    public function logout()
    {
        $this->load->library('session');

        // update offline status to the cometchat_status table
        $this->load->database();
        $this->load->model('Cometchat_database');
        $this->Cometchat_database->set_offline($this->session->userdata('userid'));

        $this->load->helper('url');
        $this->session->sess_destroy();

//		redirect('main_page_not_login');
    }


    /**
     * show_page
     * 	Show the main page & pass variable to the view
     *		friendid = 0 when user did not click to any person for chatting yet
     *		friendid <> 0: user is chatting with a person
     *				in this case, $friend keeps the name of the person
     *				for showing it in view
     */
    function show_page()
    {
        //$this->load->library('session');
        //$this->load->helper(array('form', 'url'));
        $data = array('name'=>'aaa','id'=>'bbb');
        return $data;
        die();
        $this->load->database();
        $this->load->model('Cometchat_database');

        $userid = $this->session->userdata('id');

        $data['user'] =  $this->Cometchat_database->get_name($userid);
        $data['friendid'] =  $this->session->userdata('friend');
        $data['friend'] 	  =  $this->Cometchat_database->get_name($this->session->userdata('friend'));

        $data['friendlist'] = array();
        $data['friendlistid'] = array();

        foreach ($this->session->userdata('friendlist') as $f)
        {
            $name = $this->Cometchat_database->get_name($f);
            $data['friendlistid'][] = $f;
            $data['friendlist']["$f"] = $name;
        }

        if ($this->Cometchat_database->is_online($data['friendid']) == true)
        {
            $data['online'] =  0;		// is online
        }
        else
        {
            $data['online'] =  1;		// is offline
        }

        // transfer previous login session for this time login
        $session = $this->Cometchat_database->get_session($userid);
        $data['friendlistid'] = $this->session->userdata('friendlist');
        $data['havefriend'] = 0;
        if ($session->num_rows() == 1)
        {
            foreach ($session->result() as $s)
            {
                //restore session
                $data['selfonline'] = $s->selfonline;
                $data['soundmute'] = $s->soundmute;

                if ($s->friendlistid != null)
                {
                    $data['havefriend'] = 1;
                }

                $data['friendlist_name'] = array();
                foreach ($data['friendlistid'] as $d)
                {
                    $data['friendlist_name'][] = $this->Cometchat_database->get_name($d);
                }

            }
        }
        else
        {
            //initial session
            $data['selfonline'] = 1;
            $data['soundmute'] = 0;
            $data['friendlist_name'] = array();
        }

        $data['myname'] = $this->Cometchat_database->get_name($userid);

        return $data;
        // testing
        // $this->load->view('main_page', $data);
    }

    /**
     * ajax_send_message
     *		This function is to communication with Javascript chat.js
     *		it gets the message from form & save to database by
     *		requesting to the function _send_message.
     *
     *		Result is returned to javascript chat.js, data.result='ok' when
     *		properly work.
     *
     */
    public function ajax_send_message($to, $message)
    {
        $this->load->library('session');
        $from = $this->session->userdata('userid');									//get userid of person who sends message

        $sent = time();											//time of sent message
        $read = 1; 													//sender already got it
        $direction = 0;

        return $this->_send_message($from, $to, $message, $sent, $read, $direction);
    }

    /**
     * _send_message
     * 	Insert the message to database
     * 	Return 'ok'
     */
    function _send_message($from, $to, $message, $sent, $read, $direction)
    {
        $this->load->database();
        $this->load->model('Cometchat_database');

        // insert to chat database
        if ($to == 0)
        {
            // did not select the person who chat with
            $data['status'] = 0;
        }
        else
        {
            $this->Cometchat_database->insert_chat_item($from, $to, $message, $sent, $read, $direction);
            $data['status'] = 1;
        }

        return json_encode($data);
        exit();
    }

    /**
     * ajax_get_message
     *		It access to database to get the current chat messages between 2 users
     *		then, return the result to javascript chat.js. Result is updated to
     *		the '#chatbox'
     *
     */
    public function ajax_get_message($userid2, $unreadonly)
    {
        $this->load->library('session');

        $userid1 = $this->session->userdata('userid');
//		 $userid2 = $this->input->post('friend');
//		 $unreadonly = ( $this->input->post('unread') == 0 ? false : true );

        return $this->_get_message($userid1, $userid2, $unreadonly);
    }

    /**
     * _get_message
     *		get message from database
     *		format the message with different highlight for user & friend
     *
     */
    function _get_message($userid1, $userid2, $unreadonly)
    {
        $this->load->database();
        $this->load->model('Cometchat_database');

        // get messages from database
        $message = $this->Cometchat_database->get_messages($userid1, $userid2, $unreadonly);

        // depend on sender, generate basic color for easy looking
        $data['result'] = array();
        $date = 0;
        $line = 0;
        foreach ($message as $m)
        {
            if ($m->from == $userid1)
            {
                $data['result'][] = "<b  style=\"color:red\">" . $this->Cometchat_database->get_name($m->from) . "</b>: ". $this->replace_link($m->message) . "<br />";
                $line++;
            }
            else
            {
                $data['result'][] = "<b style=\"color:blue\">" . $this->Cometchat_database->get_name($m->from) . "</b>: " . $this->replace_link($m->message) . "<br />";
                $line++;
            }
        }

        $data['status'] = 1;

        $this->load->library('session');

        if ($line < $this->maxline())
        {
            $data['size'] = $line;
        }
        else
        {
            $data['size'] = $this->maxline();
        }

        return json_encode($data);
        exit();
    }

    /**
     * maxline
     * 	maximum line of history message
     */
    function maxline()
    {
        return 50;
    }

    /**
     * ajax_change_active_friend
     * 	when change the window to another friend
     */
    public function ajax_change_active_friend($friendid)
    {

        return $this->_change_active_friend($friendid);
    }

    /**
     * _change_active_friend
     * 	if change chat window to another person, update the session varible
     * 	if click on chatting person, -> toggle the window
     */
    function _change_active_friend($friendid)
    {
        $this->load->library('session');
        $current_f = $this->session->userdata('friend');

        if ($current_f <> $friendid)
        {
            $this->session->set_userdata('friend', $friendid);

            $data['status'] = 1;
        }
        else
        {
            $data['status'] = 'toggle';
        }

        $data['friendid'] = $friendid;

        echo json_encode($data);
        exit();
    }


    /**
     * replace_link
     * 	If a link is contained in a string, return the
     * 	<a href="$link">$link</a>
     *		Other text is not changed
     *
     * 	Note:
     * 		A link is formatted as below
     *			"(file|gopher|news|nntp|telnet|http|ftp|https|ftps|sftp)(:\/\/)([^\s]*)		<- space is used for separating links if any
     */
    public function replace_link($str)
    {
        if (preg_match_all("/(.*)(file|gopher|news|nntp|telnet|http|ftp|https|ftps|sftp)(:\/\/)([^\s]*)(.*)/", $str, $matches, PREG_SET_ORDER))
        {
            foreach ($matches as $match)
            {
                $str = $match[4];
                $str5 = $match[5];

                // when it is a link, remove the last character if it is '.' , ','
                // because it may be separator of sentence, not actual a character of link
                if (substr($str, -1) == '.' || substr($str, -1) == ',')
                {
                    $str5 = substr($str, -1) . $str5;
                    $str = substr($str, 0, -1);
                }
                $link = $match[2].$match[3].$str;
                $result = '<a href="' . $link. '">' . $link . '</a>' ;
                return $this->replace_link($match[1]) . $result . $str5;
            }
        }
        else
        {
            return $str;
        }
    }

    /**
     * ajax_get_buddy
     * 	get online/offline buddy
     *		return to client
     */
    public function ajax_get_buddy($second, $chatwith, $get_all_message)
    {
//		$second = $this->input->post('second');
//		$friend = $this->input->post('chatwith');
//		$get_all_message = $this->input->post('get_all_message');
        return $this->_get_buddy($second, $chatwith, $get_all_message);
    }

    /**
     * _get_buddy
     * 	return below information to client
     *			- friendlist (list of id of friend that is chatting with)
     *			- friendlist_name (name of person who is chatting with)
     *			- online_id/online_name (id/name of friends who are online)
     *			- offline_id/offline_name (id/name of friends who are offline)
     *			- new_message_in_second=true/false
     *				true: there is a new message (for sound)
     *			- typing:
     *				1: the person who is chatting with, is typing a message
     */
    public function _get_buddy($second, $friend, $get_all_message)
    {
//		 echo "time begin = " . time() . "<br />";
        $this->load->library('session');
        $userid = $this->session->userdata('userid');

        $this->load->model('Cometchat_database');

        $online = $this->get_buddy_online();

        $friendlistid = array();
        $data['online_id'] = array();
        $data['online_name'] = array();
        foreach ($online as $b)
        {
            $friendlistid[] = $b;
            $data['online_id'][] = (int)$b;
            $data['online_name'][] = $this->Cometchat_database->get_name($b);
            $data['unread'][] = $this->Cometchat_database->get_unread_messages($b, $userid);
        }

        if ($this->Cometchat_database->is_online($userid) == true)
        {
            $data['selfonline'] = 1;
        }
        else
        {
            $data['selfonline'] = 0;
        }


        $all = $this->Cometchat_database->get_buddy($userid);

        $data['offline_id'] = array();
        $data['offline_name'] = array();
        foreach ($all as $buddy)
        {
            if ($buddy->userid1 == $userid)
            {
                $b = $buddy->userid2;
            }
            else
            {
                $b = $buddy->userid1;
            }

            if (!in_array($b, $friendlistid))
            {
                $friendlistid[] = $b;
                $data['offline_id'][] = (int)$b;
                $data['offline_name'][] = $this->Cometchat_database->get_name($b);
                $data['unread'][] = $this->Cometchat_database->get_unread_messages($b, $userid);
            }
        }

        // new message withint 3s
        $data['new_mess'] = $this->Cometchat_database->is_new_message($userid, 5);

        // check typing from friend
        $data['typing'] = ($this->Cometchat_database->is_typing($userid, $friend, time(), $second) == true ? 1 : 0 );
        $data['new_message'] = array();
        if ($friend <> 0)
        {
            if ($get_all_message == 1)
            {
                $message = $this->Cometchat_database->get_messages($userid, $friend, false);
            }
            else
            {
                $message = $this->Cometchat_database->get_messages($userid, $friend, true);
            }

            foreach ($message as $m)
            {
                if ($m->from == $userid)
                {
                    $data['new_message'][] = "<b  style=\"color:red\">" . $this->Cometchat_database->get_name($m->from) . "</b>: ". $this->replace_link($m->message) . "<br />";
                }
                else
                {
                    $data['new_message'][] = "<b style=\"color:blue\">" . $this->Cometchat_database->get_name($m->from) . "</b>: " . $this->replace_link($m->message) . "<br />";
                }
            }
        }

        $data['status'] = 1;
//		 echo "time end = " . time() . "<br />";

        return json_encode($data);
        exit();
    }

    public function get_buddy_online()
    {
        $this->load->library('session');
        $userid = $this->session->userdata('userid');

        $this->load->database();
        $this->load->model('Cometchat_database');

        // get all friends (online + offline)
        $friends = $this->Cometchat_database->get_buddy($userid);

        $data['code'] = array();
        $data['result'][] = array();

        foreach ($friends as $f)
        {
            if ($f->userid1 == $userid)
            {
                if (!in_array($f->userid2, $data['code']))
                {
                    // online
                    if ($this->Cometchat_database->is_online($f->userid2))
                    {
                        $data['code'][] = $f->userid2;
                        $data['result'][] = "<i><a href=\"main_page/chat_with/".$f->userid2."\">" . $this->Cometchat_database->get_name($f->userid2) . "</a></i><br />";
                    }
                }
            }
            else
            {
                if (!in_array($f->userid1, $data['code']))
                {
                    // online
                    if ($this->Cometchat_database->is_online($f->userid1))
                    {
                        $data['code'][] = $f->userid1;
                        $data['result'][] = "<i><a href=\"main_page/chat_with/".$f->userid1."\">" . $this->Cometchat_database->get_name($f->userid1) . "</a></i><br />";
                    }
                }
            }
        }

        return $data['code'];
        exit();

    }

    /**
     * chat_with
     * 	This function is called when user clicks on the name
     *		of the friend that expected to make a chat.
     *		It updates to 'friend' session variable
     *		Add the person to friendlist (who is chatting with array)
     *
     */
    function ajax_chat_with( $friend )
    {
        return $this->_chat_with($friend);
    }

    public function _chat_with($friend)
    {
        $this->load->library('session');
        $friendlist = $this->session->userdata('friendlist');
        // $data['before_friendlist'] = $this->session->userdata('friendlist');

        if (!in_array($friend, $friendlist))
        {
            if ( count ($friendlist) < 5 )
            {
                $friendlist[] = $friend;
                $data['maxreach'] = 0;
            }
            else
            {
                $data['maxreach'] = 1;
            }
        }

        $this->session->set_userdata('friend', $friend);
        $this->session->set_userdata('friendlist', $friendlist);

        $data['friend'] = $friend;

        $this->load->model('Cometchat_database');
        $data['friendlist'] = array();
        $data['friendlist_name']=array();
        foreach ($friendlist as $f)
        {
            if ($f <> 0)
            {
                $data['friendlist'][] = (int)$f;
                $data['friendlist_name'][] = $this->Cometchat_database->get_name($f);
            }
        }

        //save session
        $this->Cometchat_database->save_session(
            $this->session->userdata('userid'),
            $this->session->userdata('selfonline'),
            $this->session->userdata('soundmute'),
            $this->session->userdata('friendlist'));


        $data['status'] = 1;
        return json_encode($data);
        exit();
    }

    /**
     * ajax_stay_online
     *		After interval of time (10s), the javascript sends request to keep online
     *		status on database.
     *
     *		In the meantime, all old connections (>10s) are removed from online table
     */
    public function ajax_stay_online()
    {
        return $this->_stay_online();
    }

    public function _stay_online()
    {
        $this->load->library('session');
        $this->load->model('Cometchat_database');

        $userid = $this->session->userdata('userid');

        // update online status
        $this->Cometchat_database->set_online($userid);

        // clean offline status
        $cleantime = $this->Cometchat_database->online_interval();
        $this->Cometchat_database->clean_offline($cleantime);

        $data = array();

        return json_encode($data);
        exit();
    }

    /**
     * ajax_close_friend
     * 	close chat window of a friend
     *		-> remove friendid from friendlist
     */
    public function ajax_close_friend($friendid)
    {
        return $this->_close_friend($friendid);
    }

    public function _close_friend($friendid)
    {
        $this->load->library('session');
        $this->load->model('Cometchat_database');
        $data['current_friend'] = $this->session->userdata('friend');

        $friend = $this->session->userdata('friend');

        $friendlist = $this->session->userdata('friendlist');
        $data['before_session'] = $this->session->userdata('friendlist');

        // is remove the person who is ON chatting ?
        if ($friend == $friendid)
        {
            $this->session->set_userdata('friend', 0);
            $data['same'] = 1;
        }
        else
        {
            $data['same'] = 0;
        }

        // remove friendid from current chatting friendlist id
        $this->load->model('Cometchat_database');
        $friendlist_tmp = array();
        $friendlist_name_tmp = array();
        foreach ($friendlist as $id)
        {
            if (($id <> $friendid) && ($id <> 0))
            {
                $friendlist_tmp[] = $id;
                $friendlist_name_tmp[] = $this->Cometchat_database->get_name($id);
            }
        }

        $this->session->set_userdata('friendlist', $friendlist_tmp);
        $data['after_session'] = $this->session->userdata('friendlist');

        //save session
        $this->Cometchat_database->save_session(
            $this->session->userdata('userid'),
            $this->session->userdata('selfonline'),
            $this->session->userdata('soundmute'),
            $this->session->userdata('friendlist'));

        $data['status'] = 1;
        $data['friendlist'] = $friendlist_tmp;
        $data['friendlist_name'] = $friendlist_name_tmp;

        return json_encode($data);
        exit();
    }

    /**
     * ajax_save_session
     * 	save current session variable to database
     *		session information contains
     *			- userid
     *			- selfonline: next time, login as online/offline
     *			- soundmute:
     *				0=enable sound when having new message
     *				1=disable sound
     *			- friendlist (array)
     *				who are chatting with
     */
    function ajax_save_session($selfonline, $soundmute)
    {
        $this->load->library('session');
        $userid = $this->session->userdata('userid');

        $this->session->set_userdata('selfonline', $selfonline);

        $this->session->set_userdata('soundmute', $soundmute);

        // use current session for friendlistid
        $friendlist_id = $this->session->userdata('friendlist');

        return $this->_save_session($userid, $selfonline, $soundmute, $friendlist_id);
    }

    function _save_session($userid, $selfonline, $soundmute, $friendlistid)
    {
        $this->load->model('Cometchat_database');
        $this->Cometchat_database->save_session($userid, $selfonline, $soundmute, $friendlistid);
        $data['status'] = 1;
        return json_encode($data);
        exit();
    }

    /**
     * ajax_typing
     * 	update typing status to database
     * 	the person who is on CHATTING will realize it
     */
    function ajax_typing( $friendid )
    {
        return $this->_typing($friendid);
    }

    function _typing($friendid)
    {
        $this->load->model('Cometchat_database');
        $time = time();

        $this->load->library('session');
        $userid = $this->session->userdata('userid');
        $this->Cometchat_database->set_typing($userid, $friendid, $time);

        $data = array();

        return json_encode($data);
        exit();
    }

}

/* End of file main_page.php */
/* Location: ./application/controllers/operation_code/main_page.php */
