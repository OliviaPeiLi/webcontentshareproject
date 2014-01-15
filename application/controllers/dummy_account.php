<?php

/**
 * info
 *  echo message with a line break
 */
function info($msg)
{
    echo $msg . '<br />';
}

require_once(APPPATH . "modules/comment/controllers/comment_update_controller.php");
require_once(APPPATH . "modules/folder/controllers/folder_update_controller.php");

class Dummy_account extends MX_Controller
{
    // dummy account creating
    protected $_password     = "@fantoon";  // default password
    protected $_avatar_url   = "";          // default avatar
    protected $_thumb_url    = "";          // default thumb
    protected $_gender       = "m";         // default gender
    protected $_current_city = "";          // default location
    protected $_role         = 9;           // dummy account
    protected $_fb_id        = 0;           // default facebook id
    protected $_twitter_id   = 0;           // default twiter_id

    // collection creating
    protected $_private      = '0';         // default private (when creating new collection)

    // database setting
    //protected $db_hostname   = $this->db->hostname;
    //protected $db_username   = $this->db->username;
    //protected $db_password   = $this->db->password;
    //protected $db_database   = $this->db->database;
    protected $db_link;

    // temporary database for keeping table of dummy account
    protected $temp_database = 'fantoon_ci';
    protected $temp_table    = 'facebookusers';

    //
    protected $sending_email = true;        // send email after making a like or not

    /**
     * __construct
     *  Create data connection to mysql
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        //$this->load->library('session');

        parent::__construct();
        $this->db_link = $this->db->conn_id;

        //$this->db_link = mysql_connect($this->db_hostname, $this->db_username, $this->db_password);
        //if (! $this->db_link) { return false; }
    }

    // return @mysql_connect($this->hostname, $this->username, $this->password, TRUE);

    /**
     * create_account
     *   input: first name, last name,...
     *   if input is lacked, use default variable instead of
     *
     * @access public
     * @return void
    	*/
    public function create_account( $first_name, $last_name, $username, $password, $email, $age, $gender, $current_city, $fb_id, $thumb_url, $avatar_url, $role, $twitter_id, $fb_avatar=true, $default_collection=true, $follow_power_users=true)
    {
        // creating random number
        $rand = rand(10,99);

        $user_data = array(
                         'first_name'      => $first_name,
                         'last_name'       => $last_name,
                         'full_name'	   => $first_name.' '.$last_name,
                         'uri_name'        => ($username == null ? $first_name . $rand : $username),
                         'email'           => ($email == null ? $first_name . $rand . "@fantoon.com" : $email),
                         'password'        => ($password == null ? md5($this->_password) : md5($password)),
                         'gender'          => ($gender == null ? $this->_gender : $gender),
                         'current_city'    => ($current_city == null ? $this->_current_city : $current_city),
                         'role'            => ($role == null ? $this->_role : $role),
                         'fb_id'           => ($fb_id == null ? $this->_fb_id : $fb_id),
                         'twitter_id'      => ($twitter_id == null ? $this->_twitter_id : $twitter_id),
                         'status'          => 1
                     );

        if(!isset($username) || $username=='')
        {
            $username = $first_name.$last_name;
        }
        $username = $orig_username = preg_replace('/[^a-zA-Z0-9\']/','',$username);
        if(strlen($username)<5)
        {
            $len = 6 - strlen($username);
            $username = $orig_username.$this->randomDigits($len);
        }
        $len = 2;
        $this->load->model('user_model');
        while($this->user_model->count_by(array('uri_name'=>$username)) > 0)
        {
            $username = $orig_username.$this->randomDigits($len);
        }
        $user_data['uri_name'] = $username;

        // get facebook image url
        if($fb_avatar)
        {
            $thumb_url = get_headers(str_replace('http://www', 'http://graph', $thumb_url),1);
            $user_data['thumb_url'] = $thumb_url['Location'];
            $user_data['avatar_url'] = str_replace('_q.jpg', '_n.jpg', $thumb_url['Location']);
        }
        else
        {
            $user_data['avatar_url'] = $user_data['thumb_url'] = $avatar_url;
        }

        // creating dummy account
        if (!$user_id = $this->insert_account($user_data))
        {
            return array('error'=>1, 'msg'=>'count not create new account');
        }

        if($follow_power_users)
        {
            $power_users = $this->user_model->select_fields(array('id','email'))->get_many($this->config->item('power_users'));

            $this->load->model('connection_model');
            $this->load->model('request_connection_model');
            $this->load->model('notification_model');
            foreach($power_users as $friend)
            {
                if(isset($friend->id) && $this->connection_model->count_by(array('user1_id'=>$user_id, 'user2_id'=>$friend->id)) == 0)
                {

                    $this->request_connection_model->insert(array('initiator_id'=>$user_id, 'requested_id'=>$friend->id, 'read_status'=>'0'));
                    $this->connection_model->insert(array('user1_id'=>$user_id, 'user2_id'=>$friend->id));
                    $notification_id = $this->notification_model->insert(array('user_id_from'=>$user_id, 'user_id_to'=>$friend->id, 'type'=>'follow'));
                    $this->user_model->set_following_you($friend->id, $user_id);
                }
            }
        }

        if($fb_avatar)
        {
            //upload image
            $this->load->model('user_model');
            $this->user_model->update($user_id, array('avatar'=>$user_data['avatar_url']));
        }

        // setting default table for the dummy account
        if ( !$this->after_create($user_id, $default_collection) )
        {
            return array('error'=>1, 'msg'=>'count not process after create');
        }

        return array('error'=>0,'msg'=>"");
    }

    /**
     * insert_account
     *  add an account to database (users table)
     *
     * @param mixed $data
     * @access public
     * @return void
     */
    public function insert_account($data)
    {
        // select database
        mysql_select_db( $this->db->database );

        // insert new account
        $cmd = 'INSERT INTO users ' .
               '(first_name, last_name, full_name, uri_name, avatar, thumbnail, gender, current_city, email, password, fb_id, twitter_id, status, role) VALUES ' .
               '("' . $data['first_name'] . '", "' .  $data['last_name'] . '" , "' .  $data['full_name'] . '" , "' .  $data['uri_name'] . '","' .  $data['avatar_url'] . '","' .  $data['thumb_url'] . '","' .  $data['gender'] . '","' .  $data['current_city'] . '","' .  $data['email'] . '","' .  $data['password'] . '",' .  $data['fb_id'] . ',' .  $data['twitter_id'] . ',\'' . $data['status'] . '\',' .  $data['role'] . ')';

        //echo $cmd; exit();

        mysql_query( $cmd, $this->db_link );
        if (mysql_error() !== '')
        {
            echo mysql_error();
        }

        return mysql_insert_id();
    }

    /**
     * query
     *  execute an mysql query
     *
     * @param mixed $query
     * @access public
     * @return void
     */
    public function query($query)
    {
        //info( $query);
        return mysql_query($query, $this->db_link);
    }

    /**
     * after_create
     *  update user_stats, email_setting,.. and other talbes for initialize
     *  create default collections (for auto-redrop)
     *
     * @param mixed $id
     * @access public
     * @return void
     */
    public function after_create($id,$default_collection=true)
    {
        // _after_create
        $this->query("INSERT INTO user_stats (user_id) VALUE ($id)");
        $this->query("INSERT INTO email_settings (user_id) VALUE ($id)");
        $this->query("INSERT INTO user_visits (id) VALUE ($id)");
        $this->query("INSERT INTO loops (user_id, loop_name) VALUE ($id, \"Friends\")");
        if($default_collection)
        {
            $this->create_default_collection($id);
        }

        return true;
    }

    /**
     * create_multiple_account
     *  read data from database quang.facebookusers
     *  and create dummy accounts following that information
     *
     *  use information in the rows from $start to $end to create account
     *
     * @param int $start   : beginning position of facebookusers
     * @param int $number     : the number of accounts from facebookusers table
     * @access public
     * @return void
     */
    public function create_multiple_account($start=0, $number=5)
    {
        $connect = mysql_connect($this->db->hostname, $this->db->username, $this->db->password);
        mysql_select_db($this->temp_database);

        // select exist accounts (ex: facebook)
        $query_result = $this->query('SELECT * FROM ' . $this->temp_table . ' ORDER BY id LIMIT ' . $start . ',' . $number, $connect);

        // create dummy account from the exist account
        while ($data = mysql_fetch_array( $query_result , MYSQL_ASSOC ))
        {
            //var_dump($data); exit();
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $username = $data['user_name'];
            $gender = ($data['gender'] == 'female' ? 'f' : 'm');
            $fb_id = $data['id'];
            $thumb_url = $data['image_url'];
            $avatar_url = $data['image_url'];

            //dummy parameter
            $password = $email = $age = $current_city = $twitter_id = $role = null;

            $this->create_account($first_name, $last_name, $username, $password, $email, $age, $gender, $current_city, $fb_id, $thumb_url, $avatar_url, $role, $twitter_id);
        }
        mysql_free_result($query_result);

        mysql_close($connect);
        mysql_close($this->db_link);

        echo 'Finish creating dummy account from ' . $this->temp_database . '.' . $this->temp_table . '<br />';
        echo 'From ' . $start . ' to ' . $start+$number . ' element(s)';
    }

    /**
     * auto_like
     *
     * @param mixed $delta_hour   : time of like is from (current timestamp -
     * this value) to current timestamp
     *
     * @param mixed $like_count   : number of like
     * @access public
     * @return void
     */
    public function auto_like($delta_hour=6, $like_count=10 )
    {
        // auto like
        info( date('Y-m-d H:i:s', time()) );

        // type of likes
        $alltype = array('link', 'photo', 'comment');

        mysql_select_db($this->db->database);

        for ($i=0; $i<$like_count; $i++)
        {
            // generate random dummy account
            $user_id = $this->get_account();

            // select a random type
            $type = $alltype[rand(0, count($alltype)-1)];
            //$type = 'comment';

            // select a random ID
            $id = $this->get_random_id("${type}s", "${type}_id", null);

            // generate random time within delta_hour
            $time = date('Y-m-d H:i:s', $this->time_generator($delta_hour));

            info( "Auto LIKE: user_id=$user_id -- type=$type -- id=$id -- time=$time");

            // some function needs to access to session so that this setting is
            // mandatory
            $this->session->set_userdata('id', $user_id);

            // add like
            $this->add_like($user_id, $type, $id, $time);
            info( "Item $i finished at " . date('Y-m-d H:i:s', time()) );
        }

        info( date('Y-m-d H:i:s', time()) );
        // logout
        $this->session->sess_destroy();
        setcookie('u_id','',time()-3600);
        setcookie('u_code','',time()-3600);
    }

    /**
     * time_generator
     *   generate a random point of time in the past that was within last $delta
     *   hour
     *
     * @param mixed $delta   : number of hour
     * @access public
     * @return void
     */
    public function time_generator($delta=6)
    {
        return time() - rand(1, $delta*3600);
    }

    /**
     * add_like
     *
     * @param mixed $user_id       : user who makes like
     * @param mixed $type          : type of like (link, comment, ..)
     * @param mixed $id            : id of link/comment/..
     * @param mixed $time          : time of making like
     * @access public
     * @return void
     */
    public function add_like($user_id, $type, $id, $time)
    {
        $this->load->model('like_model');
        $this->load->model('activity_model');
        $this->load->model('notification_model');
        $this->load->model('link_model');
        $this->load->model('comment_model');
        $this->load->model('photo_model');

        // if is not like yet
        if($this->like_model->count_by(array('user_id'=>$user_id, $type.'_id'=>$id)) == 0)
        {
            // insert a like
            $like_id = $this->like_model->insert(array('user_id'=>$user_id, $type.'_id'=>$id, 'time'=>$time));

            // update activity & make notification
            $activity_id = $this->activity_model->get_by(array('type'=>'like', 'activity_id'=>$like_id))->id;

            if($type == 'link')
            {
                $link_info = $this->link_model->get($id);
                $notification = array('user_id_to'=>$link_info->user_id_from, 'page_id_to'=>$link_info->page_id_from, 'type'=>'link_like', 'a_id'=>$activity_id);
            }
            if($type == 'photo')
            {
                $photo_info = $this->photo_model->get($id);
                $notification = array('user_id_to'=>$photo_info->user_id_from, 'page_id_to'=>$photo_info->page_id_from, 'type'=>'photo_like', 'a_id'=>$activity_id);
            }
            if($type == 'comment')
            {
                $comm_info = $this->comment_model->get($id);
                $notification = array('user_id_to'=>$comm_info->user_id_from, 'page_id_to'=>$comm_info->page_id_from, 'type'=>'link_comm_like', 'a_id'=>$activity_id);
            }

            $notification['user_id_from'] = $user_id;
            if($notification['user_id_from'] != $notification['user_id_to'])
            {
                $notify = TRUE;
            }

            // make notification & send email
            if(isset($notify))
            {
                $notification_id = $this->notification_model->insert($notification);

                $user_id_to = $this-> {$type.'_model'}->get($id)->user_id_from;
                $likeclass = new Comment_update_controller();

                if ($this->sending_email == true)
                {
                    $likeclass->send_up_email($user_id_to, $type, $notification_id);
                }
            }
        }
    }

    /**
     * auto_redrop
     *
     * @param int $delta_hour             : for generating time of redrop
     * @param float $redrop_count         : number of redrop to make
     * @access public
     * @return void
     */
    public function auto_redrop($delta_hour=6, $redrop_count=10 )
    {
        info( date('Y-m-d H:i:s', time()) );

        // select database
        mysql_select_db($this->db->database);

        for ($i=0; $i<$redrop_count; $i++)
        {
            // generate random dummy account
            $user_id = $this->get_account();

            // select a random newsfeed_id that will be redrop
            $newsfeed_id = $this->get_random_id("newsfeed", "newsfeed_id", null);

            // generate random folder_id (belong to a dummy account)
            $folder_id = $this->get_random_id("folder", "folder_id", "user_id=$user_id");

            // generate random time
            $time = date('Y-m-d H:i:s', $this->time_generator($delta_hour));

            info( "Auto REDROP: user_id=$user_id -- newsfeed_id=$newsfeed_id -- folder_id=$folder_id -- time=$time");

            // this setting is mandatory
            $this->session->set_userdata('id', $user_id);

            // redrop
            $this->redrop($user_id, $newsfeed_id, $folder_id, $time);

            info( "Item $i finished at " . date('Y-m-d H:i:s', time()) );
        }

        info( date('Y-m-d H:i:s', time()) );
        // logout
        $this->session->unset_userdata('id');
        setcookie('u_id','',time()-3600);
        setcookie('u_code','',time()-3600);
    }

    /**
     * get_account
     *      select a random dummy account
     *
     * @access public
     * @return ID of random account
     */
    public function get_account()
    {
        return $this->get_random_id("users", "id", "role=9");
    }

    /**
     * create_default_collection
     *    create default collection that was in config['default_collections']
     *    (config/config.php)
     *
     * @param mixed $user_id      : user id that these collections belong to
     * @access public
     * @return void
     */
    public function create_default_collection($user_id)
    {
        foreach ($this->config->item('default_collections') as $folder_name)
        {
            $this->query('INSERT INTO folder (folder_name, user_id, private, editable, recent_newsfeeds) ' .
                         'VALUES ( "' . $folder_name . '", ' . $user_id . ', \'' . $this->_private . '\', 1, "' . serialize(array()) . '")');
            //echo mysql_error(); exit();
        }
    }

    /**
     * redrop
     *
     * @param mixed $user_id            : redrop by user id
     * @param mixed $newsfeed_id        : newsfeed_id that is redrop
     * @param mixed $folder_id          : redrop to this folder_id
     * @param mixed $time               : redrop at this time
     * @access public
     * @return void
     */
    public function redrop($user_id, $newsfeed_id, $folder_id, $time)
    {
        $this->load->model('link_model');
        $this->load->model('photo_model');
        $this->load->model('newsfeed_model');
        $this->load->model('folder_content_model');
        $this->load->model('link_collect_model');
        $this->load->model('activity_model');
        $this->load->model('folder_model');

        // get newsfeed
        $newsfeed = $this->newsfeed_model->get($newsfeed_id);

        //based on different type of newsfeed redrop in correct folder
        if($newsfeed->type == 'photo' || $newsfeed->link_type == 'image')
        {
            $folder_name = 'Pictures I like';
        }
        elseif($newsfeed->link_type == 'html')
        {
            $folder_name = 'Articles I like';
        }
        elseif($newsfeed->link_type == 'content')
        {
            $folder_name = 'Stuff I like';
        }
        elseif($newsfeed->link_type == 'embed' || $newsfeed->link_type == 'media_link')
        {
            $folder_name = 'Videos I like';
        }
        elseif($newsfeed->link_type == 'screen' || $newsfeed->link_type == 'text')
        {
            $folder_name = 'Quotes I like';
        }
        else
        {
            $folder_name = 'Stuff I like';
        }

        $folder_id = $this->folder_model->get_by(array('user_id'=>$user_id,'folder_name'=>$folder_name))->folder_id;
        if(!$folder_id) continue;

        // if  redrop is a link
        if ($newsfeed->type == 'link')
        {
            $link_id = $newsfeed->activity_id;

            if(isset($link_id) && $link_id > 0 && $folder_id > 0)
            {
                // if already redrop -> finish
                $exist = $this->folder_content_model->get_by(array('folder_id'=>$folder_id, 'link_id'=>$link_id));
                if ($exist !== null)
                {
                    return false;
                }

                // dupplicate link table
                $link = $this->link_model->get($link_id);
                $link_data = $link->as_array();
                unset($link_data['link_id']);
                unset($link_data['time']);
                unset($link_data['img_thumb']);
                unset($link_data['img_tile']);
                unset($link_data['img_small']);
                $link_data['user_id_from'] = $user_id;
                $link_data['page_id_from'] = $link_data['page_id_to'] = $link_data['user_id_to'] = 0;
                $link_data['time'] = $time;
                $img = $link_data['img'];
                unset($link_data['img']);
                $activity_id = $new_link_id = $this->link_model->insert($link_data);
                //avoid uploadable behavior
                $this->db->where('link_id',$new_link_id);
                $this->db->update('links',array('img'=>$img));

                // update link collect model
                $this->link_collect_model->insert(array('link_id'=>$link_id, 'user_id'=>$user_id, 'folder_id'=>$folder_id, 'new_id'=>$new_link_id));

                // update folder content table
                $this->folder_content_model->insert(array('folder_id'=>$folder_id, 'link_id'=>$new_link_id));

                // get new newsfeed & dupplicate newsfeed table
                $newsfeed_id = $this->link_model->get($link_id)->newsfeed->newsfeed_id;
                $type = 'link';

                $newsfeed = $this->newsfeed_model->get($newsfeed_id);

                $newsfeed_data = $newsfeed->as_array();
                $newsfeed_data['user_id_from'] = $newsfeed_data['activity_user_id'] = $user_id;
                $newsfeed_data['folder_id'] = $folder_id;
                $newsfeed_data['up_count'] = $newsfeed_data['comment_count'] = $newsfeed_data['collect_count'] = '0';
                $newsfeed_data['news_rank'] = 10;
                $newsfeed_data['activity_id'] = $activity_id;
                unset($newsfeed_data['newsfeed_id']);
                unset($newsfeed_data['time']);
                unset($newsfeed_data['data']);
                $newsfeed_data['time'] = $time;

                $new_newsfeed_id = $this->newsfeed_model->insert($newsfeed_data);

                // update activity model
                $this->activity_model->insert(array('user_id_from'=>$user_id,
                                                    'folder_id'=>$folder_id,
                                                    'type'=>$type,
                                                    'activity_id'=>$activity_id,
                                                    'collect'=>'1',
                                                    'time'=>$time));

            }
        }

        // if redrop is a photo
        if ($newsfeed->type == 'photo')
        {
            $photo_id = $newsfeed->activity_id;
            if(isset($photo_id) && $photo_id > 0 && $folder_id > 0)
            {
                // if already redrop -> finish
                $exist = $this->folder_content_model->get_by(array('folder_id'=>$folder_id, 'photo_id'=>$photo_id));
                if ($exist !== null)
                {
                    return false;
                }

                // dupplicate for photo table
                $photo = $this->photo_model->get($photo_id);
                $photo_data = $photo->as_array();
                unset($photo_data['photo_id']);
                unset($photo_data['time']);
                unset($photo_data['img_height']);
                $photo_data['user_id_from'] = $user_id;
                $photo_data['page_id_from'] = $photo_data['page_id_to'] = $photo_data['user_id_to'] = 0;
                $photo_data['folder_id'] = $folder_id;
                $photo_data['time'] = $time;

                $activity_id = $new_photo_id = $this->photo_model->insert($photo_data);

                // update link collect model
                $this->link_collect_model->insert(array('photo_id'=>$photo_id, 'user_id'=>$user_id, 'folder_id'=>$folder_id, 'new_id'=>$new_photo_id));

                // update folder content
                $this->folder_content_model->insert(array('folder_id'=>$folder_id, 'photo_id'=>$new_photo_id));

                $new_newsfeed_id = $this->photo_model->get($new_photo_id)->newsfeed->newsfeed_id;
            }
        }

        //update collect count on original newsfeed
        $collect_count = $this->newsfeed_model->get($newsfeed_id)->collect_count;
        $this->newsfeed_model->update($newsfeed_id, array('collect_count'=>$collect_count+1));

        $this->folder_model->add_folder_newsfeed($folder_id, $new_newsfeed_id);

        return true;
    }

    /**
     * auto_follow
     *
     * @param int $follow_count      : number of making follow
     * @access public
     * @return void
     */
    public function auto_follow($follow_count=10, $delta_hour=6)
    {
        info( date('Y-m-d H:i:s', time()) );

        mysql_select_db($this->db->database);

        for ($i=0; $i<$follow_count; $i++)
        {
            // select a dummy account
            $my_id = $this->get_account();

            // select a user to follow (allow dummy account)
            $user_id = $my_id;
            while ($user_id == $my_id)
            {
                $user_id = $this->get_random_id("users", "id", null);
            }

            // some function needs to access to session so that this setting is
            // mandatory
            $this->session->set_userdata('id', $my_id);

            info( "Auto FOLLOW: my_id=$my_id -- user_id=$user_id");

            $time = date('Y-m-d H:i:s', $this->time_generator($delta_hour));
            $this->follow($my_id, $user_id, $time);
            info( "Item $i finished at " . date('Y-m-d H:i:s', time()) );
        }

        info( date('Y-m-d H:i:s', time()) );
        // logout
        $this->session->sess_destroy();
        setcookie('u_id','',time()-3600);
        setcookie('u_code','',time()-3600);
    }

    /**
     * follow
     *
     * @param mixed $my_id         : person who follow another one (dummy account)
     * @param mixed $user_id       : person who is followed (may be real user or dummy user)
     * @access public
     * @return void
     */
    public function follow($my_id, $user_id, $time)
    {
        $this->load->model('connection_model');
        //$this->load->model('loops_model');
        $this->load->model('request_connection_model');
        $this->load->model('notification_model');
        //$this->load->model('loop_user_model');
        $this->load->model('activity_model');

        if($user_id == $my_id) return;

        //$loop_id = $this->loops_model->get_by(array('user_id'=>$my_id, 'loop_name'=>'Friends'))->loop_id;

        // if not follow yet -> making a connection
        $request_check = $this->request_connection_model->count_by(array('initiator_id'=>$my_id, 'requested_id'=>$user_id));
        if($request_check == 0)
        {
            $this->request_connection_model->insert(array('initiator_id'=>$my_id, 'requested_id'=>$user_id, 'read_status'=>'0'));
        }

        // update loop
        /*
        if($this->loop_user_model->count_by(array('loop_id'=>$loop_id, 'user_id'=>$user_id)) == 0)
        {
            $this->loop_user_model->insert(array('loop_id'=>$loop_id, 'user_id'=>$user_id));
        }
        */
        $connect_check = $this->connection_model->count_by(array('user1_id'=>$my_id, 'user2_id'=>$user_id));
        if($connect_check == 0)
        {

            //$connect_id = $this->connection_model->insert(array('user1_id'=>$my_id, 'user2_id'=>$user_id));

            $this->db->insert('connections',array('user1_id'=>$my_id,'user2_id'=>$user_id,'similarity'=>'0'));
            $connect_id = mysql_insert_id();

            $this->load->model('user_model');
            $this->load->model('folder_model');
            $this->load->model('activity_model');

            $following = $this->user_model->get($my_id)->following;
            $follower = $this->user_model->get($user_id)->follower;

            $this->user_model->update($my_id, array('following'=>$following+1));
            $this->user_model->update($user_id, array('follower'=>$follower+1));

            mysql_query("DELETE FROM folder_user WHERE user_id='".$my_id."' AND folder_id in (SELECT folder_id FROM folder WHERE user_id='".$user_id."')");

            mysql_query("INSERT INTO folder_user (user_id, folder_id) SELECT '".$my_id."', folder_id FROM folder WHERE user_id = '".$user_id."'AND private = '0'");


            $activity_id = $this->activity_model->insert(array('user_id_from'=>$my_id,
                           'user_id_to'=>$user_id,
                           'type'=>'connection',
                           'activity_id'=>$connect_id,
                           'time'=>$time));

            $notification_id = $this->notification_model->insert(array('user_id_from'=>$my_id, 'user_id_to'=>$user_id, 'type'=>'follow'));

            //send email
            $this->load->model('email_setting_model');
            $this->load->model('user_model');
            $email_settings = $this->email_setting_model->get_by(array('user_id'=>$user_id));    	                               //check user's email setting
            if(isset($email_settings->connection) && $email_settings->connection == '1')
            {
                $email = $this->user_model->get_id($user_id)->email;                                           //get email address

                $this->user_model->set_following_you($user_id, $my_id);
            }
        }
    }

    public function create_simple_accounts()
    {

        if($this->input->post())
        {
            list($first_name, $last_name) = explode(' ', $this->input->post('name'));
            $password = $this->input->post('password');
            $email = $this->input->post('email');
            $username = $this->input->post('username');

            $gender = 'm';
            $avatar_url = $thumb_url = 'https://s3.amazonaws.com/fantoon-dev/users/default/blue_thumb.png';
            $role = 0;
            $age = $current_city = $twitter_id = $fb_id = $fb_avatar = null;
            $default_collection = false;
            $res = $this->create_account($first_name, $last_name, $username, $password, $email, $age, $gender, $current_city, $fb_id, $thumb_url, $avatar_url, $role, $twitter_id, $fb_avatar, $default_collection);

            $this->session->set_flashdata('result', $res);
            //redirect('admin/add_user');
            echo 'New account is created! <a href="/admin/users">Go Back</a>';
        }
        else
        {

            $this->db->select('name, password, email, username');
            $this->db->from('Sheet1');
            //$this->db->limit(1);
            $query = $this->db->get();
            $users = $query->result_array();

            foreach($users as $user)
            {

                list($first_name, $last_name) = explode(' ', $user['name']);
                $username = $user['username'];
                $password = 'M0rningstar';
                $email = $user['email'];
                $gender = 'm';
                $avatar_url = $thumb_url = 'https://s3.amazonaws.com/fantoon-dev/users/default/defaultMale_thumb.png';
                $role = 0;
                $age = $current_city = $twitter_id = $fb_id = $fb_avatar = null;
                $default_collection = false;
                $this->create_account($first_name, $last_name, $username, $password, $email, $age, $gender, $current_city, $fb_id, $thumb_url, $avatar_url, $role, $twitter_id, $fb_avatar, $default_collection);
            }
        }
    }

    /**
     * get_random_id
     *
     * @param mixed $table
     * @param mixed $id
     * @param mixed $where
     * @access public
     * @return void
     */
    public function get_random_id($table, $id, $where)
    {
        if ($where == null)
        {
            $offset_result = mysql_query(" SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `$table` ", $this->db_link);
        }
        else
        {
            $offset_result = mysql_query(" SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `$table` WHERE $where ", $this->db_link);
        }
        $offset_row = mysql_fetch_object( $offset_result );
        $offset = $offset_row->offset;

        if ($where == null)
        {
            $offset_result = mysql_query( " SELECT $id FROM `$table` LIMIT $offset, 1 " );
        }
        else
        {
            $offset_result = mysql_query( " SELECT $id FROM `$table` WHERE $where LIMIT $offset, 1 " );
        }
        $offset_row = mysql_fetch_array( $offset_result );
        return $offset_row["$id"];
    }

    /**
     * return certain digits randon number
     */
    function randomDigits($length)
    {
        $digits = null;
        $numbers = range(0,9);
        shuffle($numbers);
        for($i = 0; $i < $length; $i++)
            $digits .= $numbers[$i];
        return $digits;
    }

}

