<?php
class Lists_database extends CI_Model
{

    function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('lists_model');
        $this->load->model('list_order_model');
        $this->load->model('list_page_model');
        $this->load->model('list_users_model');
    }

//		public function index()
//		{
//				$this->load->database();
//		}

    /**
     * get_circle
     *
     * @param mixed $uid	: user id
     * @access public
     * @return void				: all circles that he has
     */
    function get_circle ( $user_id )
    {
        $this->load->database();
        // $this->lists_model->select('list_id as cid, list_name as name, visibility');
        $query = $this->lists_model->get_many_by('list_maker_id', $user_id);
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
    //	function get_pages($uid)		//need to change to get page lists
    {
        /*				$this->db->select('loop_id');
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
        */

        $this->db->select('pages.page_id as uid, page_name as name, uri_name, thumbnail as avatar, interest_id');
        $this->db->from('pages');
        $this->db->join('page_users', 'page_users.page_id = pages.page_id');
        $this->db->where('user_id', $uid);
        $query = $this->db->get();
        //print_r($query->result());
        return $query->result();

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
        $query = $this->list_page_model->get_many_by( array('list_id' => $cid) );

        $str = Array();
        foreach ($query as $r)
        {
            $str[] = $r->page_id;
        }

        return join(",", $str);
    }

    /**
     * change_circle_name
     *
     * @param mixed $uid				: user id
     * @param mixed $cid				: circle id
     * @param mixed $newname		: new name
     * @param mixed $visibility	: 0=private, 1=public
     * @access public
     * @return void
     */
    function change_circle_name ( $uid, $cid, $newname, $visibility )
    {
        $data = array(
                    'list_maker_id' => $uid,
                    'list_id' => $cid,
                    'list_name' => $newname,
                    'visibility' => ( $visibility == 0 ? 'private' : 'public' )
                );

        $this->lists_model->update_by(
            array('list_maker_id' => $uid, 'list_id' => $cid),
            $data
        );
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
        $query = $this->lists_model->get_many_by(array('list_maker_id', $uid));

        foreach ( $query as $r )
        {
            // circle name is exist
            if ( strcmp($r->list_name, $name) == 0 )
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
    function create_new_circle( $uid, $name, $visibility )
    {
        $data = Array (
                    'list_maker_id' => $uid,
                    'list_id' => "",					//auto increament
                    'list_name' => $name,
                    'visibility' => ($visibility == 0 ? 'private' : 'public')
                );

        // $this->db->save_queries = false;
        $this->lists_model->insert($data);

        $id = $this->lists_model->db->insert_id();
        return $id;
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
        if ( $element <> null )
        {
            foreach ( $element as $e )
            {
                $data = Array('list_id'=>$cid, 'page_id'=>$e);
                // $this->db->save_queries = false;
                $this->list_page_model->insert($data);
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
        $this->lists_model->delete_by(array('list_maker_id' => $uid, 'list_id' => $cid));
        $this->list_page_model->delete_by(array('list_id' => $cid));
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
        foreach ($items as $i)
        {
            $data = array ( 'list_id' => $cid , 'page_id' => $i );
            $query = $this->list_page_model->get_many_by( $data );

            // check if element is exist
            if ( count($query) == 0)
            {
                // $this->db->save_queries = false;
                $this->list_page_model->insert($data);
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
        $data = array ( 'list_id' => $cid , 'page_id' => $item );
        $this->list_page_model->delete_by( $data );
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
        $query = $this->list_order_model->get($uid);

        if ( count($query->order) == 0 )
        {
            $data = array ( 'uid' => $uid, 'order' => $order );
            // $this->db->save_queries = false;
            $this->list_order_model->insert($data);
        }
        else
        {
            $data = array ( 'order' => $order );
            $this->list_order_model->array2string($data);
            $this->list_order_model->update($uid, $data);
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
        $query = $this->list_order_model->get($uid);

        return $query;
    }

    function get_all_circle( $uid )
    {
        $this->load->database();
        $this->load->model('lists_model');
        $circles = $this->lists_model->get_many_by( array('list_maker_id' => $uid) );
        $orders = array();
        $orders[] = 0;
        foreach ( $circles as $c )
        {
            $orders[] = $c->list_id;
        }

        return $orders;
    }

    function get_visibility( $uid, $cid )
    {
        $query = $this->lists_model->get_by(array('list_maker_id' => $uid, 'list_id' => $cid));

        if ( count($query) == 1 )
        {
            return ( strcmp($query->visibility, 'private') == 0 ? 0 : 1 );
        }
        else
        {
            return 0; // default = private visibility
        }
    }



}


?>
