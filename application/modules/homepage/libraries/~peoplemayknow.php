<?php

class Peoplemayknow
{
	/**********************************************************
	* function: return max level of searching
	* input: none
	* output: specified MAX level for searching
	**********************************************************/
	public function get_max_level() {
		return 4;
//		return 2;
	}

	/**********************************************************
	* function: insert time & note to profile for speed analysis
	* input: data (previous status of profile), str (new profile information)
	* output: none
	* note: data is updated
	**********************************************************/
	public function updateprofile(&$data, $str="") {
		$data .= time() . ":" .$str . "\n";
	}

	/**********************************************************
	* function: index function
	* input: none
	* routine:
	*	- get max level
	*	- search all candidate that has same school_id/year/major
	*	- search all candidate that has same friend
	*	- calculating the weight for showing out to screen
	**********************************************************/
	public function get_people($user_id) {

		$this->CI =& get_instance();
		$this->CI->load->helper('file');

		// initial profile data
		$profile = ""; $start_program = time();
		//$this->updateprofile($profile, "Start");

		//load database
		$this->CI->load->database();

		//configuration, maximum level of searching
		$MAX_CON_LEVEL = $this->get_max_level();

		//get user_id input
		//$user_id = $this->input->post('user_id');

		//$data['people'][] = '<a href="myform">Home</a>';

		if (strcmp(trim($user_id),'') == 0 || is_numeric($user_id) == false) {
			$data['people'][] = "user_id is a number & must not be empty";
		} else {

			//$data['people'][] = "<b>Same school & major of user=$user_id</b>";
	
			//search school/major
			//$this->updateprofile($profile, "Start searching info_candidate");
			$candidate_school = $this->info_candidate($user_id);
			//$this->updateprofile($profile, "End searching info_candidate");

			foreach ($candidate_school as $can) {
				$data['people'][] = $can;								//save result
			}
	
			//$data['people'][] = "";
		
			// looking for candidates of friends in RELATION graph
			//$this->updateprofile($profile, "Start searching graph_candidate");
			$friends = $this->graph_candidate($user_id,$MAX_CON_LEVEL);
			//$this->updateprofile($profile, "End searching graph_candidate");
	
			//$this->updateprofile($profile, "Start searching get_connection_weight");
			// realizing the weight
			foreach ($friends as $f) {
				$this->updateprofile($profile, "Start get_connection_weight of $f");
				$weight = $this->get_connection_weight($user_id, $f);
				if($f != $user_id)
				{
					$candidate_graph[] = array('user_id' => $f, 'weight' => $weight);
				}
			}
			//$this->updateprofile($profile, "End searching get_connection_weight");
	
			//$data['people'][] = "<b>People that user=<i>$user_id</i> may know</b>";
	
			//$this->updateprofile($profile, "Start sorting the result");
			if (isset($candidate_graph)) {
				foreach ($candidate_graph as $key => $row) {
					$a_user_id[$key]  = $row['user_id'];
					$a_weight[$key]  = $row['weight'];
				}
	
				//sort to desc for weight
				array_multisort($a_weight, SORT_DESC, $a_user_id, SORT_ASC, $candidate_graph);
			
				foreach ($candidate_graph AS $i=>$f) {
					if($f['user_id']>0)
					{
						$data['people'][] = $f['user_id'];
					}
				}
				$this->updateprofile($profile, "End sorting the result");
			}
		}

		//$data['people'][] = '<a href="myform">Home</a>';
			//print_r($data);
		return isset($data) ? $data : null;
		
		//$this->updateprofile($profile, "Finish in " . (time() - $start_program));

		//write_file('./profile.txt', $profile);
		//$this->load->view('search/search_result', $data);
	}

	/**********************************************************
	* function: return the array that contains all user_id that
	*	has same friend with user_id
	* input: user_id, conlevel (connection level)
	* output: an array of friend candidate
	* routine:
	*	- if conlevel = 1, show out all friend (already friend)
	*	  unless,
	*	  	+ search all friends
	*		+ from each friend, search for next level connection
	**********************************************************/
	public function graph_candidate($user_id, $conlevel) {
		//level = 1 -> list up all friends (already, not candidate)
		if ($conlevel <= 1) {
			//load database model
			$this->CI =& get_instance();
			$this->CI->load->model('people_may_know_model');
			$result = $this->CI->people_may_know_model->get_connection($user_id);

			foreach ($result as $row) {
				$user1_id = $row->user1_id;
				$user2_id = $row->user2_id;
				
				//if user_id = user1_id or user_id = user2
				if (strcmp($user1_id, $user_id) == 0) {
					if (!isset($friends)) {
						$friends[] = $user2_id;
					} else {
						if (!in_array($user2_id, $friends)) {
							$friends[] = $user2_id;
						}
					}
				} else {
					//save to friends array
					if (!isset($friends)) {
						$friends[] = $user1_id;
					} else {
						if (!in_array($user1_id, $friends)) {
							$friends[] = $user1_id;
						}
					}
				}
			}

			//in the case of no friend, return empty array
			return (isset($friends) ? $friends : array());
		} else {																								// if level > 1
			$friends = $this->graph_candidate($user_id, $conlevel-1);							// calculate -1 level
			$final_friends = $friends;

			// from $conlevel -1, go to 1 more level
			foreach ($friends as $friend) {
				$friendplus = $this->graph_candidate($friend, 1);								// loop to get next level friends

				//save to result array, ignore if duplicated
				foreach ($friendplus as $f) {
					if (!isset($final_friends) && !in_array($f, $friends) && strcmp($f, $user_id) != 0) {
							$final_friends[] = $f;
					} else {
						if (!in_array($f, $final_friends) && !in_array($f, $friends) && strcmp($f, $user_id) != 0) {										
							$final_friends[] = $f;
						}
					}
				}
			}

			//remove direct connect to avoid same entry (because already friend whereas the function searches the candidates of friend)
			$level1friend = $this->graph_candidate($user_id, 1);
			foreach ($final_friends as $f) {
				if (!in_array($f, $level1friend)) {													// only find candidate, not already friends
					$final_candidate[] = $f;
				}
			}

			return (isset($final_candidate) ? $final_candidate : array());
		}
	}

	/**********************************************************
	* function: return the weight of each connection
	* input: user1_id, user2_id
	* output: a number that represent for weight of each connection
	*	from user1_id to user2_id (data is already available in usersimilarity
	*	table). 'usersimilarity' is updated periodically
	**********************************************************/
	public function get_connection_weight($user1_id,$user2_id) {
		$this->CI =& get_instance();
		$this->CI->load->model('people_may_know_model');

		return $this->CI->people_may_know_model->get_weight($user1_id, $user2_id);
	}

	/**********************************************************
	* function: return a array of candidate with matching user
	*	profile's information
	* input: user_id
	* output: a array of all entries that have same school_id, year
	*	and major with inputted user_id
	* routine:
	*	- search all information related to inputted user_id
	*	- with each searched result
	*		+ find the matching entries that has same school_id, year, major
	*		+ accummulate to searching result
	**********************************************************/
	public function info_candidate($user_id) {
		//load database model
		$this->CI->load->model('people_may_know_model');

		// Assign the query
		$result = $this->CI->people_may_know_model->get_profile($user_id);

		foreach ($result as $row) {
			$school_id	= $row->school_id;
			$major		= $row->major;
			$year			= $row->year;

			//query to look for same school_id, major, and year
			$allsamemajor = $this->CI->people_may_know_model->get_profile_specific($year, $major, $school_id);

			//save to candidate list
			$candidate_tmp[] = $user_id;

			foreach ($allsamemajor as $row2) {
					//avoid dupplicated
					if (!in_array($row2->user_id,$candidate_tmp)) {
						$candidate_tmp[] = $row2->user_id;
					}
			}

			//remove $user_id (to avoid searching back)
			foreach ($candidate_tmp as $can) {
				if (isset($candidate)) {
					if ((!in_array($can, $candidate)) && ($can =! $user_id)) {
						$candidate[] = $can;
					}
				} else {
					if ($can != $user_id) {
						$candidate[] = $can;
					}
				}
			}
		}

		//remove friend already, because it can be searched back
		if (isset($candidate)) {
			$friend_already = $this->graph_candidate($user_id, 1);
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

?>
