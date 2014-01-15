<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loops {

	protected $_tcid = null;

	function get_uid()
	{
		if ($this->_tcid) {
			return $this->_tcid ;
		} else {
			return $this->CI->session->userdata('id');
		}
	}

	/**
    * Loops Class
    *
    * Provides methods to load loops script function
    *
    **/

    private $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        
        // load database helper; configurations in config/database.php
        $this->CI->load->database();       
    }

	public function genLoops()
	{
		//$this->CI->load->database();
		$this->CI->load->model('Circle_database');
		$this->CI->load->library('session');
		$this->CI->load->helper(array('form', 'url'));
		//var_dump( $this->CI->session );
		//$this->CI->author();
		$data = $this->get_userinfo();
		return $data;

		$this->CI->load->view('circle', $data);
	}

	/**
	 * author 
	 *			Temporary authority
	 *			When someone uses this program, they should assign userid to the 
	 *			current loggin user
	 *
	 * @access public
	 * @return void
	 */
	function author()
	{
			$this->CI->load->library('session');
			$this->CI->session->set_userdata('userid', 1);
			$this->CI->session->set_userdata('circle', 0);
	}

	/**
	 * get_userinfo 
	 *			Get current setting in database, calculate position of 
	 *			circle/friend,... & transfer to view
	 * 
	 * @access public
	 * @return void
	 */
	function get_userinfo()
	{
			$userid = $this->get_uid();

			$data = Array();

			// access to circle user_circle to get all circles
			$circle = $this->CI->Circle_database->get_circle($userid)->result();
			//print_r($circle);
			foreach ( $circle as $c )
			{
					// circle name
					$data['circlename'][$c->cid] = $c->name;
			}

			// get members of each circle (uid=owner, cid=circleid, uid2=friend)
			$friend = $this->CI->Circle_database->get_friend( $userid );
			$col = 0; $row = 0;
			foreach ( $friend as $r )
			{
					// friend name
				$data['friendname'][$r->uid] = addslashes($r->first_name.' '.$r->last_name);

				$col++; if ( $col == 3 ) { $col = 0; $row++; }

					// avatar
					if($r->avatar == '')
					{
						if($r->gender == 'm')
						{
							$data['avatar'][$r->uid] = Url_helper::s3_url()."users/default/defaultMale_thumb.png";
						}
						else
						{
							$data['avatar'][$r->uid] = Url_helper::s3_url()."users/default/defaultFemale_thumb.png";
						}
					}
					else
					{
						$data['avatar'][$r->uid] = Url_helper::s3_url().$r->avatar;
					}
					// location of friendbox
					$data['friendbox'][$r->uid] = Array ( 'top' => $row*90+130  , 'left' => 20+$col*200 );
			}

			// position of circles
			$data['circlebox'] = Array( );

			// items of each circle
			$data['circle_element']['0'] = "";

			$col = 0; $row = 0; $order_tmp = Array(); $order_tmp[] = 0;
			foreach ( $circle as $r )
			{
					$data['circle_element'][$r->cid] = $this->CI->Circle_database->get_friend_of_circle( $userid, $r->cid );

					$col++; if ( $col == 2 ) { $col = 0; $row++; }
					$data['circlebox'][$r->cid] = Array( 'cid' => $r->cid, 'top' => $row*200+100, 'left' => 200*$col );
					$order_tmp[] = $r->cid;
			}

			// set order of circles
			$circle_order = $this->CI->Circle_database->get_circle_order( $userid );
			if ( $circle_order->num_rows() > 0 ) {
					foreach ( $circle_order->result() as $r )
					{
						$data['circle_order'] = $r->order;
					}
			} else {
					$data['circle_order'] = join(",", $order_tmp);
					$this->CI->Circle_database->set_circle_order( $userid, $data['circle_order'] );
			}

			return $data;
	}

	/**
	 * ajax_changeCircleName 
	 *			Change circle name
	 *
	 * @access public
	 * @return void
	 */
	function ajax_changeCircleName($cid, $newname)
	{
		//	$cid = $this->CI->input->post('id');
		//	$newname = $this->CI->input->post('newname');

			if ( $cid > 0 ) {
					// change database
				$this->CI->load->library('session');
					$uid = $this->get_uid();

					$this->CI->load->model('Circle_database');
					$this->CI->Circle_database->change_circle_name( $uid, $cid, $newname );
					$data['status'] = 1;
			} else {
					$data['status'] = 0;
			}

			echo json_encode( $data );
			exit();
	}

	/**
	 * ajax_createNewCircle 
	 *			Create a new circle
	 * @access public
	 * @return void
	 */
	function ajax_createNewCircle($name, $element)
	{
			//$name = $this->CI->input->post('name');
			//$element = $this->CI->input->post('element');

			$this->CI->load->database();
			$this->CI->load->model('Circle_database');

			$this->CI->load->library('session');
			$uid = $this->get_uid();

			// check if name is empty or exist
			if ( trim($name) == "" || $this->CI->Circle_database->circle_is_exist($uid, trim($name)) === true ) {
					$data['status'] = 0;
			} else {
					$data['status'] = 1;
					$data['circle_id'] = $this->CI->Circle_database->create_new_circle( $uid, trim($name) );

					$top = 0; $left = 0;
					$tmp = $this->gen_topleft($uid);
					$data['top'] = $tmp['top'];
					$data['left'] = $tmp['left'];
					$data['name'] = trim($name);

					// save new element to database
					$this->CI->Circle_database->save_element( $uid, $data['circle_id'], $element );

					// update circle order (new created circle is located at end)
					$this->add_circleOrder( $uid, $data['circle_id']);
			}


			echo json_encode( $data );
			exit();
	}

	/**
	 * add_circleOrder 
	 *				Change a circle order to database
	 *
	 * @param mixed $userid 
	 * @param mixed $cid 
	 * @access public
	 * @return void
	 */
	function add_circleOrder( $userid, $cid )
	{
			$this->CI->load->database();
			$this->CI->load->model('Circle_database');

			$circle_order = $this->CI->Circle_database->get_circle_order( $userid );
			foreach ( $circle_order->result() as $r )
			{
				$order_tmp = $r->order;
			}
			$order = preg_split("/,/", $order_tmp);
			$order[] = $cid;

			$this->CI->Circle_database->set_circle_order( $userid, join(",", $order) );
	}

	/**
	 * remove_circleOrder 
	 *			Remove circle order & update new order
	 *
	 * @param mixed $userid 
	 * @param mixed $cid 
	 * @access public
	 * @return void
	 */
	function remove_circleOrder( $userid, $cid )
	{
			$this->CI->load->database();
			$this->CI->load->model('Circle_database');

			$circle_order = $this->CI->Circle_database->get_circle_order( $userid );
			foreach ( $circle_order->result() as $r )
			{
				$order_tmp = $r->order;
			}
			$order = preg_split("/,/", $order_tmp);

			$order_tmp = Array();
			foreach ( $order as $o )
			{
					if ( $o <> $cid ) {
							$order_tmp[] = $o;
					}
			}

			$this->CI->Circle_database->set_circle_order( $userid, join(",", $order_tmp) );
	}

	/**
	 * gen_topleft 
	 * 		General position in client for new circle
	 * 
	 * @param mixed $uid 
	 * @param mixed $top 
	 * @param mixed $left 
	 * @access public
	 * @return void
	 */
	function gen_topleft( $uid )
	{
			$this->CI->load->database();
			$this->CI->load->model('Circle_database');

			// 1 is "New Circle" (item 0th)
			$circle_count = 1 + $this->CI->Circle_database->get_circle($uid)->num_rows();

			// each line has 3 elements
			$col = ($circle_count - 1) % 2 ;
			$row = round ( ($circle_count - $col - 1) / 2 ) ;

			$data = Array();
			$data['top']  = $row * 200 + 100;
		  	$data['left'] = $col * 200;

			return $data;
	}

	/**
	 * ajax_deleteCircle 
	 *			Delete a circle
	 * @access public
	 * @return void
	 */
	function ajax_deleteCircle ()
	{
			$this->CI->load->database();
			$this->CI->load->model('Circle_database');

			$this->CI->load->library('session');
			$uid = $this->get_uid();

			$cid = $this->CI->input->post('circle');

			// delete circle
			$this->CI->Circle_database->delete_circle( $uid, $cid );

			// send new (top,left) for adjusting circle position
			$circle = $this->CI->Circle_database->get_circle($uid)->result();
			$col = 0; $row=0;
			foreach ( $circle as $r )
			{
					$col++; if ( $col == 2 ) { $col = 0; $row++; }
					$data['index'][] = $r->cid;
					$data['left'][$r->cid] = 200*$col;
					$data['top'][$r->cid] = $row*200+100;
			}

			// update order of circle
			$this->remove_circleOrder ( $uid, $cid );

			$data['status'] = 1;

			echo json_encode( $data );
			exit();
	}

	/**
	 * ajax_addItemToCircle 
	 *			Add item to circle
	 *
	 * @access public
	 * @return void
	 */
	function ajax_addItemToCircle ($cid, $items)
	{
			//$cid = $this->CI->input->post('circle');
			//$items = $this->CI->input->post('items');

			// add items to database
			$this->CI->load->library('session');
			$uid = $this->get_uid();

			$this->CI->load->database();
			$this->CI->load->model('Circle_database');
			$this->CI->Circle_database->add_items_to_circle( $uid, $cid, $items );

			$data['status'] = 1;
			echo json_encode( $data );
			exit();
	}

	/**
	 * ajax_removeItemFromCircle 
	 *				Remove 1 item from circle
	 * @access public
	 * @return void
	 */
	function ajax_removeItemFromCircle ($cid, $item)
	{
			//$cid = $this->CI->input->post('circle');
			//$item = $this->CI->input->post('item');

			// add items to database
			$this->CI->load->library('session');
			$uid = $this->get_uid();

			$this->CI->load->database();
			$this->CI->load->model('Circle_database');
			$this->CI->Circle_database->remove_item_from_circle( $uid, $cid, $item );

			$data['status'] = 1;
			echo json_encode( $data );
			exit();
	}

	/**
	 * ajax_removeItemsFromCircle 
	 *			Remove many items from circles
	 * @access public
	 * @return void
	 */
	function ajax_removeItemsFromCircle ($cid, $items)
	{
			//$cid = $this->CI->input->post('circle');
			//$items = $this->CI->input->post('items');

			if ( $items == null ) {
			} else {
					// add items to database
					$this->CI->load->library('session');
					$userid = $this->get_uid();
		
					$this->CI->load->database();
					$this->CI->load->model('Circle_database');
		
					foreach ($items as $item)
					{
							$this->CI->Circle_database->remove_item_from_circle( $userid, $cid, $item );
					}
		
					// get members of each circle (uid=owner, cid=circleid, uid2=friend)
					$friend = $this->CI->Circle_database->get_friend_of_circle( $userid, $cid );
					$col = 0; $row = 0;
					foreach ( preg_split("/,/",$friend) as $r )
					{
							$col++; if ( $col == 2 ) { $col = 0; $row++; }
							$data['element'][] = $r;
							$data['left'][] = 20+$col*200;
							$data['top'][] = $row*90+130;
					}
			}

			$data['status'] = 1;
			echo json_encode( $data );
			exit();
	}

	/**
	 * ajax_changeCircleOrder 
	 *			Change circle
	 *
	 * @access public
	 * @return void
	 */
	function ajax_changeCircleOrder ($order)
	{
			//$order = $this->CI->input->post('order');

			// add items to database
			$this->CI->load->library('session');
			$uid = $this->get_uid();

			$this->CI->load->database();
			$this->CI->load->model('Circle_database');
			$this->CI->Circle_database->set_circle_order( $uid, join(",", $order) );

			$data['status'] = 1;
			echo json_encode( $data );
			exit();
	}

	/**
	 * ajax_loadInsideCircle 
	 *			Going to inside a circle
	 * @access public
	 * @return void
	 */
	function ajax_loadInsideCircle ($cid)
	{
			//$cid = $this->CI->input->post('circle');

			// add items to database
			$this->CI->load->library('session');
			$uid = $this->get_uid();
			$this->CI->session->set_userdata('circle', $cid);

			$this->CI->load->database();
			$this->CI->load->model('Circle_database');

			$this->CI->load->helper(array('form', 'url'));

			$data = $this->get_circleinfo( $cid );

			$data['status'] = 1;
			echo json_encode( $data );
			exit();
	}

	/**
	 * get_circleinfo 
	 *		Calculate position of circle (ex: after remove/add)
	 *
	 * @param mixed $cid 
	 * @access public
	 * @return void
	 */
	function get_circleinfo( $cid )
	{
			$this->CI->load->library('session');
			$userid = $this->get_uid();

			$data = Array();

			// get members of each circle (uid=owner, cid=circleid, uid2=friend)
			$this->CI->load->database();

			if ( $cid <> 0 ) {
					$friend = $this->CI->Circle_database->get_friend_of_circle( $userid, $cid );
					$col = 0; $row = 0;
					foreach ( preg_split("/,/",$friend) as $r )
					{
							$col++; if ( $col == 2 ) { $col = 0; $row++; }
							$data['element'][] = $r;
							$data['left'][] = 20+$col*200;
							$data['top'][] = $row*90+130;
					}
			} else {
				  $friend = $this->CI->Circle_database->get_friend( $userid );
					$col = 0; $row = 0;
					foreach ( $friend as $r )
					{
							$col++; if ( $col == 2 ) { $col = 0; $row++; }
							$data['element'][] = $r->uid;
							$data['left'][] = 20+$col*200;
							$data['top'][] = $row*90+130;
					}
			}
			
			return $data;
	}


}

/* End of file circle.php */
/* Location: ./application/controllers/circle.php */
