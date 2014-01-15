<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	
	/**********************************************************
	function: return max level of searching
	input: none
	output: 4
	**********************************************************/
	if ( ! function_exists('get_max_level'))
	{
		function get_max_level() 
		{
			return 4;
		}
	}

	/**********************************************************
	function: friends_suggestion function
	input: none
	routine:
		- get max level
		- search all candidate that has same schoolid/year/major
		- search all candidate that has same friend
		- calculating the weight for showing out to screen
	**********************************************************/
	if ( ! function_exists('friends_suggestion'))
	{
		function friends_suggestion($userid, $limit) 
		{
			//load database
			//$this->load->database();		//do we need load database here?
	
			//configuration, maximum level of searching
			$MAX_CON_LEVEL = get_max_level();
	
			//get userid input
			//$userid = $this->input->post('userid');
	
			if (strcmp(trim($userid),'') == 0 || is_numeric($userid) == false) {
				$data['content'][] = "UserID is a number & must not be empty";
			} else {
	
				//$data['content'][] = "<b>Same school & major of user=$userid</b>";
		
				//search school/major
				$candidate_school = info_candidate($userid);
				foreach ($candidate_school as $can) {
					//$data['content'][] = "Userid = ".$can;								//save result
					$data['content'][] = $can;
				}
		
				//$data['content'][] = "";
			
				// looking for candidates of friends in RELATION graph
				$friends = graph_candidate($userid,$MAX_CON_LEVEL);
		
				// realizing the weight
				foreach ($friends as $f) {
					$weight = get_connection_weight($userid, $f, $MAX_CON_LEVEL);
					$candidate_graph[] = array('userid' => $f, 'weight' => $weight);
				}
		
				//$data['content'][] = "<b>People that user=<i>$userid</i> may know</b>";
		
				if (isset($candidate_graph)) {
					foreach ($candidate_graph as $key => $row) {
						$a_userid[$key]  = $row['userid'];
						$a_weight[$key]  = $row['weight'];
					}
		
					//sort to desc for weight
					array_multisort($a_weight, SORT_DESC, $a_userid, SORT_ASC, $candidate_graph);
				
					foreach ($candidate_graph AS $i=>$f) {
						//$data['content'][] = "Userid = ". $f['userid'] . " (with weight is " . $f['weight'] . ")";
						$data['content'][] = $f['userid'];
					}
				}
			}
			
			if($limit)
			{
				foreach($data['content'] as $k=>$v)
				{
					if($k >= $limit)
					{
						unset($data['content'][$k]);
					}
				}
			}
			
			foreach($data['content'] as $k=>$v)
			{
				$suggestion[$k]['user_id'] = $v;
				$suggestion[$k]['mutul'] = mutul_friends($userid, $v);
			}
			
			//$data['content'][] = '<a href="myform">Home</a>';
			//$this->load->view('search/search_result', $data);
			return $suggestion;
		}
	}	

	/**********************************************************
	function: return the array that contains all userid that
		has same friend with userid
	input: userid, conlevel (connection level)
	output: an array of friend candidate
	routine:
		- if conlevel = 1, show out all friend (already friend)
		  unless,
		  	+ search all friends
			+ from each friend, search for next level connection
	**********************************************************/
	if ( ! function_exists('graph_candidate'))
	{
		function graph_candidate($userid, $conlevel) 
		{
			//level = 1 -> list up all friends (already, not candidate)
			if ($conlevel <= 1) {
				
				//load database model
				$CI =& get_instance();
    			$CI->load->model('suggestion_model');
				//$this->load->model('Database_model');
				$result = $CI->suggestion_model->get_connection($userid);
	//return $result;
				foreach ($result as $row) {
					$userid1 = $row->user1_id;
					$userid2 = $row->user2_id;
					
					//if userid = userid1 or userid = user2
					if (strcmp($userid1, $userid) == 0) {
						if (!isset($friends)) {
							$friends[] = $userid2;
						} else {
							if (!in_array($userid2, $friends)) {
								$friends[] = $userid2;
							}
						}
					} else {
						//save to friends array
						if (!isset($friends)) {
							$friends[] = $userid1;
						} else {
							if (!in_array($userid1, $friends)) {
								$friends[] = $userid1;
							}
						}
					}
				}
	
				//in the case of no friend, return empty array
				return (isset($friends) ? $friends : array());
			} else {																								// if level > 1
				$friends = graph_candidate($userid, $conlevel-1);							// calculate -1 level
				$final_friends = $friends;
	
				// from $conlevel -1, go to 1 more level
				foreach ($friends as $friend) {
					$friendplus = graph_candidate($friend, 1);								// loop to get next level friends
	
					//save to result array, ignore if duplicated
					foreach ($friendplus as $f) {
						if (!isset($final_friends) && !in_array($f, $friends) && strcmp($f, $userid) != 0) {
								$final_friends[] = $f;
						} else {
							if (!in_array($f, $final_friends) && !in_array($f, $friends) && strcmp($f, $userid) != 0) {										
								$final_friends[] = $f;
							}
						}
					}
				}
	
				//remove direct connect to avoid same entry (because already friend whereas the function searches the candidates of friend)
				$level1friend = graph_candidate($userid, 1);
				foreach ($final_friends as $f) {
					if (!in_array($f, $level1friend)) {													// only find candidate, not already friends
						$final_candidate[] = $f;
					}
				}
				//return $friends;
				return (isset($final_candidate) ? $final_candidate : array());
			}
		}
	}

	/**********************************************************
	function: return the weight of each connection
	input: userid1, userid2, level
	output: a number that represent for weight of each connection
		from userid1 to userid2
	routine:
		- if (level = 1) {
			+ return 1 when having connection (friend already)
			+ return 0 when no connection (not friend)
		  } else {	
		  	+ scan all current friends of userid1
			+ sum the weight of all current friends to userid2
		  }
	**********************************************************/
	if ( ! function_exists('get_connection_weight'))
	{
		function get_connection_weight($userid1,$userid2,$level) 
		{
			//list all friends (already know)
			$friends = graph_candidate($userid1, 1);
	
			//there is a connection, weight=1
			if (in_array($userid2, $friends)) {
				$weight = 1;
			} else {
				$weight = 0;
			}
	
			if ($level == 1) { return $weight; }
			foreach ($friends as $f) {
				//check whether of not the $f has how many connection possibility
				$weight += get_connection_weight($f, $userid2, $level - 1);
			}
			return $weight;
		}
	}	

	/**********************************************************
	function: return a array of candidate with matching user
		profile's information
	input: userid
	output: a array of all entries that have same schoolid, year
		and major with inputted userid
	routine:
		- search all information related to inputted userid
		- with each searched result
			+ find the matching entries that has same schoolid, year, major
			+ accummulate to searching result
	**********************************************************/
	if ( ! function_exists('info_candidate'))
	{
		function info_candidate($userid) 
		{
			//load database model
			$CI =& get_instance();
		    $CI->load->model('suggestion_model');
			//$this->load->model('Database_model');
	
			// Assign the query
			$result = $CI->suggestion_model->get_profile($userid);
	
			foreach ($result as $row) {
				$schoolid	= $row->school_id;
				$major		= $row->major;
				$year		= $row->year;
	
				//query to look for same schoolid, major, and year
				$allsamemajor = $CI->suggestion_model->get_profile_specific($year, $major, $schoolid);
	
				//save to candidate list
				$candidate_tmp[] = $userid;
	
				foreach ($allsamemajor as $row2) {
						//avoid dupplicated
						if (!in_array($row2->user_id,$candidate_tmp)) {
							$candidate_tmp[] = $row2->user_id;
						}
				}
	
				//remove $userid (to avoid searching back)
				foreach ($candidate_tmp as $can) {
					if (isset($candidate)) {
						if ((!in_array($can, $candidate)) && ($can =! $userid)) {
							$candidate[] = $can;
						}
					} else {
						if ($can != $userid) {
							$candidate[] = $can;
						}
					}
				}
			}
	
			//remove friend already, because it can be searched back
			if (isset($candidate)) {
				$friend_already = graph_candidate($userid, 1);
				foreach ($candidate as $can) {
					if (!in_array($can, $friend_already)) {
						$finallist[] = $can;
					}
				}
	
				//handler for empty entry
				return (isset($finallist) ? $finallist : array());
			} else {
				return array();
			}
		}
	}
	
	if ( ! function_exists('mutul_friends'))
	{
		function mutul_friends($user1_id, $user2_id)
		{
			//load database model
			$CI =& get_instance();
			$CI->load->model('connection_model');
			//$this->load->model('Database_model');
			$result_1 = $CI->connection_model->connection_list($user1_id);
			foreach($result_1 as $k=>$v)
			{
				$array_1[$k] = $v['user2_id'];
			}
			$result_2 = $CI->connection_model->connection_list($user2_id);
			foreach($result_2 as $k=>$v)
			{
				$array_2[$k] = $v['user2_id'];
			}
			$result = array_intersect($array_1, $array_2);
			foreach($result as $k=>$v)
			{
				$row[] = $result_1[$k];
			}
			//print_r($row);
			return $row;
		}
	}
	
	