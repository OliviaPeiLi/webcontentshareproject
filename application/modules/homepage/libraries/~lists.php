<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lists {


	/**
    * Loops Class
    *
    * Provides methods to load loops script function
    *
    **/

    private $CI;
		private $maxWidthfor2column = 1300;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        
        // load database helper; configurations in config/database.php
        $this->CI->load->database();       
    }

	public function genLists()
	{
		//$this->CI->load->database();
		$this->CI->load->model('Lists_database');
		$this->CI->load->library('session');
		$this->CI->load->helper(array('form', 'url'));
		
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
      $this->CI->load->library('session');
			$userid = $this->CI->session->userdata('id');

			$data = Array();

			// access to circle user_circle to get all circles
			// $circle = $this->CI->Lists_database->get_circle($userid)->result();
			$circle = $this->CI->Lists_database->get_circle($userid);
			foreach ( $circle as $c )
			{
					// circle name
					$data['circlename'][$c->list_id] = $c->list_name;
			}

			// get members of each circle (uid=owner, cid=circleid, uid2=friend)
			$friend = $this->CI->Lists_database->get_friend( $userid );
			//print_r($friend);
			$col = 0; $row = 0;
			foreach ( $friend as $r )
			{
					// friend name
					if(strlen($r->name)>15)
					{
					   $page_name = substr($r->name, 0, 15).'…';
					}
					else
					{
					   $page_name = $r->name;
					}
				    $data['friendname'][$r->uid] = addslashes($page_name);

					// avatar
					if($r->avatar == '')
					{
							$data['avatar'][$r->uid] = Url_helper::s3_url().'pages/default/defaultInterest/'.$r->interest_id.'.png';;
					}
					else
					{
						$data['avatar'][$r->uid] = Url_helper::s3_url().$r->avatar;
					}
					// location of friendbox
					// adjust the items for moving up
					// $data['friendbox'][$r->uid] = Array ( 'top' => $row*90+130  , 'left' => 20+$col*200 );
					$data['friendbox'][$r->uid] = Array ( 'top' => $row*90+40, 'left' => 100+$col*200 );
					
					$col++; if ( $col == 3 ) { $col = 0; $row++; }
			}

			// position of circles
			$data['circlebox'] = Array( );

			// items of each circle
			$data['circle_element']['0'] = "";

			$colnum = $this->CI->session->userdata('colnum');
			if ( $colnum == null ) {
        $colnum = 2;
        $this->CI->session->set_userdata('colnum', $colnum);
			}

			$col = 0; $row = 0; $order_tmp = Array(); $order_tmp[] = 0;
			foreach ( $circle as $r )
      {
					$data['circle_element'][$r->list_id] = $this->CI->Lists_database->get_friend_of_circle( $userid, $r->list_id );

					$col++; if ( $col == $colnum ) { $col = 0; $row++; }
					// move up items
					// $data['circlebox'][$r->cid] = Array( 'cid' => $r->cid, 'top' => $row*200+100, 'left' => 200*$col );
					$data['circlebox'][$r->list_id] = Array( 'cid' => $r->list_id, 'top' => $row*200, 'left' => 200*$col );
					$order_tmp[] = $r->list_id;
			}

			// set order of circles
			$circle_order = $this->CI->Lists_database->get_circle_order( $userid );

			if ( count($circle_order->order) > 0 ) {
				// if order is in database
				$data['circle_order'] = join(",", $circle_order->order);
			} else {
				// if order is not saved -> get all circles that created by userid
				$orders = $this->CI->Lists_database->get_all_circle( $userid );
				$data['circle_order'] = join(",", $orders);

				// save that order to database for next times
				$this->CI->Lists_database->set_circle_order( $userid, $orders );
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
	function ajax_changeCircleName($cid, $newname, $visibility)
	{
		//	$cid = $this->CI->input->post('id');
		//	$newname = $this->CI->input->post('newname');

			if ( $cid > 0 ) {
					// change database
					$this->CI->load->library('session');
					$uid = $this->CI->session->userdata('id');

					$this->CI->load->model('Lists_database');
					$this->CI->Lists_database->change_circle_name( $uid, $cid, $newname, $visibility );
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
	 *				visibility: 0=private, 1=public
	 * @access public
	 * @return void
	 */
	function ajax_createNewCircle($name, $element, $visibility = 0)
	{
			//$name = $this->CI->input->post('name');
			//$element = $this->CI->input->post('element');

			$this->CI->load->database();
			$this->CI->load->model('Lists_database');

			$this->CI->load->library('session');
			$uid = $this->CI->session->userdata('id');

			// check if name is empty or exist
			//no need to check if the same name
//			if ( trim($name) == "" || $this->CI->Lists_database->circle_is_exist($uid, trim($name)) === true ) {
			if ( trim($name) == "") {
					$data['status'] = 0;
			} else {
					$data['status'] = 1;
					$data['circle_id'] = $this->CI->Lists_database->create_new_circle( $uid, trim($name), $visibility );

					$top = 0; $left = 0;
					$tmp = $this->gen_topleft($uid);
					$data['top'] = $tmp['top'];
					$data['left'] = $tmp['left'];
					$data['name'] = trim($name);

					// save new element to database
					$this->CI->Lists_database->save_element( $uid, $data['circle_id'], $element );

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
			$this->CI->load->model('Lists_database');

			$order = $this->CI->Lists_database->get_circle_order($userid)->order;
			$order[] = $cid;

			// $this->CI->Lists_database->set_circle_order( $userid, join(",", $order) );
			$this->CI->Lists_database->set_circle_order( $userid, $order );
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
			$this->CI->load->model('Lists_database');

			$order = $this->CI->Lists_database->get_circle_order($userid)->order;

			$order_tmp = Array();
			foreach ( $order as $o )
			{
					if ( $o <> $cid ) {
							$order_tmp[] = $o;
					}
			}

			// $this->CI->Lists_database->set_circle_order( $userid, join(",", $order_tmp) );
			$this->CI->Lists_database->set_circle_order( $userid, $order_tmp );
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
			$this->CI->load->model('Lists_database');

			// 1 is "New Circle" (item 0th)
			// $circle_count = 1 + $this->CI->Lists_database->get_circle($uid)->num_rows();
			$circle_count = 1 + count($this->CI->Lists_database->get_circle($uid));

			// each line has 3 elements
			$this->CI->load->library('session');
			$colnum = $this->CI->session->userdata('colnum');
			if ( $colnum == null ) {
        $colnum = 2;
        $this->CI->session->set_userdata('colnum', 2);
			}

			$col = ($circle_count - 1) % $colnum ;
			$row = round ( ($circle_count - $col - 1) / $colnum ) ;

			$data = Array();
			// for moving up
			// $data['top']  = $row * 200 + 100;
			$data['top']  = $row * 200 ;
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
			$this->CI->load->model('Lists_database');

			$this->CI->load->library('session');
			$uid = $this->CI->session->userdata('id');

			$cid = $this->CI->input->post('circle');

			// delete circle
			$this->CI->Lists_database->delete_circle( $uid, $cid );

			// send new (top,left) for adjusting circle position
			// $circle = $this->CI->Lists_database->get_circle($uid)->result();
			$circle = $this->CI->Lists_database->get_circle($uid);
			$col = 0; $row=0;
			foreach ( $circle as $r )
			{
					$col++; if ( $col == 2 ) { $col = 0; $row++; }
					$data['index'][] = $r->list_id;
					$data['left'][$r->list_id] = 200*$col;
					// for moving up
					// $data['top'][$r->cid] = $row*200+100;
					$data['top'][$r->list_id] = $row*200;
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
			$uid = $this->CI->session->userdata('id');

			$this->CI->load->database();
			$this->CI->load->model('Lists_database');
			$this->CI->Lists_database->add_items_to_circle( $uid, $cid, $items );

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
			$uid = $this->CI->session->userdata('id');

			$this->CI->load->database();
			$this->CI->load->model('Lists_database');
			$this->CI->Lists_database->remove_item_from_circle( $uid, $cid, $item );

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
					$userid = $this->CI->session->userdata('id');
		
					$this->CI->load->database();
					$this->CI->load->model('Lists_database');
		
					foreach ($items as $item)
					{
							$this->CI->Lists_database->remove_item_from_circle( $userid, $cid, $item );
					}
		
					// get members of each circle (uid=owner, cid=circleid, uid2=friend)
					$friend = $this->CI->Lists_database->get_friend_of_circle( $userid, $cid );
					$col = 0; $row = 0;
					foreach ( preg_split("/,/",$friend) as $r )
					{
							$col++; if ( $col == 2 ) { $col = 0; $row++; }
							$data['element'][] = $r;
							$data['left'][] = 100+$col*200;
							// adjust the items for moving up
							// $data['top'][] = $row*90+130;
							$data['top'][] = $row*90+40;
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
			$uid = $this->CI->session->userdata('id');

			$this->CI->load->database();
			$this->CI->load->model('Lists_database');
			// $this->CI->Lists_database->set_circle_order( $uid, join(",", $order) );
			$this->CI->Lists_database->set_circle_order( $uid, $order );

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
			$uid = $this->CI->session->userdata('id');
			$this->CI->session->set_userdata('circle', $cid);

			$this->CI->load->database();
			$this->CI->load->model('Lists_database');

			$this->CI->load->helper(array('form', 'url'));

			$data = $this->get_circleinfo( $cid );

			$data['status'] = 1;
			echo json_encode( $data );
			exit();
	}

	function ajax_updateResolution($width)
	{
			// add items to database
		$this->CI->load->library('session');
		$this->CI->load->model('Lists_database');

		// check if have room at right hand side so that using 3 column of list 
		// instead of 2
		if ( $width > $this->maxWidthfor2column ) {
			$colnum = 3;
		} else {
			$colnum = 2;
		}

		$this->CI->session->set_userdata('colnum', $colnum);
		$data_tmp = $this->get_userinfo();
//		var_dump( $data_tmp['circlebox'] );
		foreach ( $data_tmp['circlebox'] as $c ) {
			$data['cid'][] = $c['cid'];
			$data['top'][] = $c['top'];
			$data['left'][] = $c['left'];
		}

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
			$userid = $this->CI->session->userdata('id');

			$data = Array();

			// get members of each circle (uid=owner, cid=circleid, uid2=friend)
			$this->CI->load->database();

			if ( $cid <> 0 ) {
					$friend = $this->CI->Lists_database->get_friend_of_circle( $userid, $cid );
					$col = -1; $row = 0;
					foreach ( preg_split("/,/",$friend) as $r )
					{
							$col++; if ( $col == 3 ) { $col = 0; $row++; }
							$data['element'][] = $r;
							$data['left'][] = 100+$col*200;
							// adjust the item for moving up
							// $data['top'][] = $row*90+130;
							$data['top'][] = $row*90+40;
					}
					$data['visibility'] = $this->CI->Lists_database->get_visibility( $userid, $cid );
			} else {
				    $friend = $this->CI->Lists_database->get_friend( $userid );
					$col = -1; $row = 0;
					foreach ( $friend as $r )
					{
							$col++; if ( $col == 3 ) { $col = 0; $row++; }
							$data['element'][] = $r->uid;
							$data['left'][] = 100+$col*200;
							// adjust the item for moving up
							// $data['top'][] = $row*90+130;
							$data['top'][] = $row*90+40;
					}
					$data['visibility'] = 0; // default = private
			}
			
			return $data;
	}


}

/* End of file circle.php */
/* Location: ./application/controllers/circle.php */
