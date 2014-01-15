<?php
class Circle_database extends CI_Model
{

    public function index()
    {
        $this->load->database();
    }

    /**
     * get_circle
     *
     * @param mixed $uid	: user id
     * @access public
     * @return void				: all circles that he has
     */
    function get_circle ( $user_id )
    {
        $this->db->select('loop_id as cid, loop_name as name');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('loops');

        return $query;
    }

    /**
     * get_friend
     *
     * @param mixed $uid		: user id
     * @access public
     * @return void					: return all friends that he has
     */
    function get_friend ( $uid )
    {
        $this->db->select('loop_id');
        $this->db->where('user_id', $uid);
        $this->db->from('loops');
        $query = $this->db->get();
        $row = $query->result_array();
        foreach($row as $loop)
        {
            $loop_row[]=$loop['loop_id'];
        }

        $this->db->distinct('user_id');
        $this->db->select('user_id as uid, users.first_name, users.last_name, users.uri_name, thumbnail as avatar, gender');
        $this->db->from('loop_user');
        $this->db->join('users', 'users.id = loop_user.user_id');
        $this->db->where_in('loop_id', $loop_row);
        $this->db->where('status','1');
        $query = $this->db->get();
        return $query->result();
        /*
        		$this->db->select ('users.first_name, users.last_name, users.uri_name, user2_id as uid, avatar, thumbnail, gender');
        		$this->db->from('connections');
        		$this->db->where('connections.user1_id', $uid);
        		$this->db->join('users', 'users.id = connections.user2_id', 'left');
        		$this->db->where('status', '1');
        		$query = $this->db->get();
        		return $query->result();

        		$statement = 'SELECT DISTINCT users.id as uid, users.first_name, users.last_name, gender, users.avatar
        									FROM users, loops, loop_user as uc
        									WHERE loops.user_id = ? AND uc.user_id = users.id AND uc.loop_id = loops.loop_id' ;

        		$query = $this->db->query( $statement, array($uid));
        		echo $this->db->last_query();
        		return $query->result();
        */
    }

    /**
     * get_friend_of_circle
     *
     * @param mixed $uid			: user id
     * @param mixed $cid			: circle id
     * @access public
     * @return void						: all friends inside that circle (separate items
     *												by ',' for putting to view
     */
    function get_friend_of_circle ( $uid, $cid )
    {
        $statement = 'select * from loop_user where loop_id=?';
        $query = $this->db->query( $statement, array($cid));

        $str = Array();
        foreach ($query->result() as $r)
        {
            $str[] = $r->user_id;
        }

        return join(",", $str);
    }

    /**
     * change_circle_name
     *
     * @param mixed $uid				: user id
     * @param mixed $cid				: circle id
     * @param mixed $newname		: new name
     * @access public
     * @return void
     */
    function change_circle_name ( $uid, $cid, $newname )
    {
        $this->load->database();

        $data = array(
                    'user_id' => $uid,
                    'loop_id' => $cid,
                    'loop_name' => $newname );

        $this->db->where('user_id', $uid);
        $this->db->where('loop_id', $cid);
        $this->db->update('loops', $data);
    }

    /**
     * circle_is_exist
     *		Check a circle name is exist or not
     *
     * @param mixed $uid
     * @param mixed $name
     * @access public
     * @return void
     */
    function circle_is_exist( $uid, $name )
    {
        $this->load->database();

        $this->db->where('user_id', $uid);
        $query = $this->db->get('loops');

        foreach ( $query->result() as $r )
        {
            // circle name is exist
            if ( strcmp($r->loop_name, $name) == 0 )
            {
                return true;
            }
        }
        return false;
    }

    /**
     * create_new_circle
     *		Creating new circle
     *
     * @param mixed $uid			: user id
     * @param mixed $name			: name
     * @access public
     * @return void
     */
    function create_new_circle( $uid, $name )
    {
        $this->load->database();

        $data = Array (
                    'user_id' => $uid,
                    'loop_id' => "",					//auto increament
                    'loop_name' => $name
                );

        $this->db->save_queries = false;
        $this->db->insert('loops', $data);

        $id = $this->db->insert_id();
        return $id;
        /*
        		$data = Array (
        				'user_id' => $uid,
        				'loop_name' => $name
        		);
        		$this->db->where('user_id', $uid);

        		//for case-sensitive (for supporting unicode)
        		$this->db->where('loop_name like binary "'.$name.'"');
        		$query = $this->db->get('loops');

        		foreach ( $query->result() as $q )
        		{
        				return $q->loop_id;
        		}
        */
    }

    /**
     * save_element
     *		Save a element to circle
     *
     * @param mixed $uid				: user id
     * @param mixed $cid				: circle id
     * @param mixed $element		: friend id
     * @access public
     * @return void
     */
    function save_element( $uid, $cid, $element )
    {
        $this->load->database();

        if ( $element <> null )
        {
            foreach ( $element as $e )
            {
                $data = Array('loop_id'=>$cid, 'user_id'=>$e);
                $this->db->save_queries = false;
                $this->db->insert('loop_user', $data);
            }
        }
    }

    /**
     * delete_circle
     *			Delete a circle
     *
     * @param mixed $uid			: user id
     * @param mixed $cid			: circle id
     * @access public
     * @return void
     */
    function delete_circle( $uid, $cid )
    {
        $this->load->database();
        $this->db->delete('loops', array('user_id' => $uid, 'loop_id' => $cid));
        $this->db->delete('loop_user', array('loop_id' => $cid));
    }

    /**
     * add_items_to_circle
     *			Add a array of items to circle
     *
     * @param mixed $uid			: user id
     * @param mixed $cid			: circle id
     * @param mixed $items		: array of items
     * @access public
     * @return void
     */
    function add_items_to_circle( $uid, $cid, $items )
    {
        $this->load->database();

        foreach ($items as $i)
        {
            $data = array ( 'loop_id' => $cid , 'user_id' => $i );
            $this->db->where($data);
            $query = $this->db->get('loop_user');

            // check if element is exist
            if ($query->num_rows() == 0)
            {
                $this->db->save_queries = false;
                $this->db->insert('loop_user', $data);
            }
        }
    }

    /**
     * remove_item_from_circle
     *			Remove item from a circle
     *
     * @param mixed $uid				: user id
     * @param mixed $cid				: circle id
     * @param mixed $item				: item
     * @access public
     * @return void
     */
    function remove_item_from_circle( $uid, $cid, $item )
    {
        $this->load->database();

        $data = array ( 'loop_id' => $cid , 'user_id' => $item );
        $this->db->where($data);
        $this->db->delete('loop_user');
    }

    /**
     * set_circle_order
     *				Save circle order (which should be showed first, second,..)
     *				Circle 0 is always at top
     *
     * @param mixed $uid				: user id
     * @param mixed $order			: circle id
     * @access public
     * @return void
     */
    function set_circle_order( $uid, $order )
    {
        $this->load->database();
        $this->db->where('uid', $uid);
        $query = $this->db->get('loop_order');

        if ( $query->num_rows() == 0 )
        {
            // insert
            $data = array ( 'uid' => $uid , 'order' => $order );

            $this->db->save_queries = false;
            $this->db->insert('loop_order', $data);
        }
        else
        {
            // update
            $data = array ( 'uid' => $uid , 'order' => $order );

            $this->db->where('uid', $uid);
            $this->db->update('loop_order', $data);
        }
    }

    /**
     * get_circle_order
     *			Get circle order
     *
     * @param mixed $uid			: user id
     * @access public
     * @return void
     */
    function get_circle_order( $uid )
    {
        $this->load->database();
        $this->db->where('uid', $uid);
        $query = $this->db->get('loop_order');

        return $query;
    }



}


?>
