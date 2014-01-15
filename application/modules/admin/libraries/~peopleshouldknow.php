<?php

class Peopleshouldknow 
{

	 // definition in psk_config.php
	private $age_level;
	private $age_point;
	private $work_point;
	private $place_point;
	private $distance_level;
	private $distance_point;

	// weight
	private $age_weight;
	private $work_weight;
	private $location_weight;
	private $page_weight;
	private $topic_weight;

	/**********************************************************
	* function: index function
	* input: none
	* output: generate table 'usersimilarity'
	* 
	* Note: this function should be executed after updating userconnection table
	**********************************************************/
	function index($type) {
		// get configuration
		$this->get_config();

		// load database
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->model('peopleshouldknow_model');
		$users = $this->CI->peopleshouldknow_model->get_users();

		// increasemental execution ( =1: increasemental ; =0: full updating )
		$increasemental = $type;
		if ( $increasemental == 0 ) {
			 // if full running -> updated users = all users
			 $updated_age = $users;
			 $updated_work = $users;
			 $updated_location = $users;
			 $updated_page = $users;
			 $updated_topic = $users; 
		} else {
			 // if increasemental running -> get updated users from "user_updated" table
			 $this->CI->load->model('peopleshouldknow_model');
			 $this->CI->peopleshouldknow_model->get_updated($updated_age, $updated_work, $updated_location, $updated_page, $updated_topic);
		}
		
		// gen age_similarity
		$this->generate_age_similarity($users, $updated_age);
echo 'age';
		// gen work_similarity
		$this->generate_work_similarity($users, $updated_work);
echo 'work';
		// gen location_similarity
		$this->generate_location_similarity($users, $updated_location);
echo 'location';
		// gen psk_page_similarity table
		$this->generate_psk_page_similarity($users, $updated_page);
echo 'page';
		// gen topic_interaction table
		$this->generate_topic_interaction($updated_topic);
		$this->generate_psk_topic_similarity($users, $updated_topic);
echo 'topic';
		// gen final similarity table
		// update = all updated users of age/work/location/page/topic
		$all_updated_users = array();
		foreach ($updated_age      as $u) { if ( ! in_array($u, $all_updated_users) ) { $all_updated_users[] = $u; } }
		foreach ($updated_work     as $u) { if ( ! in_array($u, $all_updated_users) ) { $all_updated_users[] = $u; } }
		foreach ($updated_location as $u) { if ( ! in_array($u, $all_updated_users) ) { $all_updated_users[] = $u; } }
		foreach ($updated_page     as $u) { if ( ! in_array($u, $all_updated_users) ) { $all_updated_users[] = $u; } }
		foreach ($updated_topic    as $u) { if ( ! in_array($u, $all_updated_users) ) { $all_updated_users[] = $u; } }

		$this->generate_people_know_similarity($users, $all_updated_users);

		$this->generate_percentile($users, $all_updated_users);
	}

	/**
	 * get_config 
	 * 	Get setting in psk_config.php & save to private variables
	 * 
	 * @access public
	 * @return void
	 */
	function get_config()
	{
		 $this->CI =& get_instance();
		 $this->CI->config->load('psk_config');

		 // get levels of age
		 $this->age_level = array();
		 $this->age_level[0] = $this->CI->config->item('age_level_1');
		 $this->age_level[1] = $this->CI->config->item('age_level_2');
		 $this->age_level[2] = $this->CI->config->item('age_level_3');
		 $this->age_level[3] = $this->CI->config->item('age_level_4');
		 $this->age_level[4] = $this->CI->config->item('age_level_5');

		 // get point of age
		 $this->age_point = array();
		 $this->age_point[0] = $this->CI->config->item('age_point_1');
		 $this->age_point[1] = $this->CI->config->item('age_point_2');
		 $this->age_point[2] = $this->CI->config->item('age_point_3');
		 $this->age_point[3] = $this->CI->config->item('age_point_4');
		 $this->age_point[4] = $this->CI->config->item('age_point_5');

		 // get point of work
		 $this->work_point = $this->CI->config->item('work_point');

		 // get point of place
		 $this->place_point = $this->CI->config->item('place_point');

		 // get levels of distance
		 $this->distance_level = array();
		 $this->distance_level[0] = $this->CI->config->item('distance_level_1') * $this->CI->config->item('distance_level_1');
		 $this->distance_level[1] = $this->CI->config->item('distance_level_2') * $this->CI->config->item('distance_level_2');
		 $this->distance_level[2] = $this->CI->config->item('distance_level_3') * $this->CI->config->item('distance_level_3');
		 $this->distance_level[3] = $this->CI->config->item('distance_level_4') * $this->CI->config->item('distance_level_4');
		 $this->distance_level[4] = $this->CI->config->item('distance_level_5') * $this->CI->config->item('distance_level_5');

		 // get point of distance
		 $this->distance_point = array();
		 $this->distance_point[0] = $this->CI->config->item('distance_point_1');
		 $this->distance_point[1] = $this->CI->config->item('distance_point_2');
		 $this->distance_point[2] = $this->CI->config->item('distance_point_3');
		 $this->distance_point[3] = $this->CI->config->item('distance_point_4');
		 $this->distance_point[4] = $this->CI->config->item('distance_point_5');

		 // get weight
		 $this->age_weight = $this->CI->config->item('age_weight');
		 $this->work_weight = $this->CI->config->item('work_weight');
		 $this->location_weight = $this->CI->config->item('location_weight');
		 $this->page_weight = $this->CI->config->item('page_weight');
		 $this->topic_weight = $this->CI->config->item('topic_weight');

	}

	/**
	 * get_age_point 
	 * 	Compare the age with levels & return corresponding point
	 * 
	 * @param mixed $delta 		: the different between ages of 2 users (user1's age - user2's age)
	 * @access public
	 * @return void				: corresponding point that is configured
	 */
	function get_age_point($delta)
	{
		 if ( $delta <= $this->age_level[0] ) {
			  return $this->age_point[0];
		 } else if ( $delta <= $this->age_level[1] ) {
			  return $this->age_point[1];
		 } else if ( $delta <= $this->age_level[2] ) {
			  return $this->age_point[2];
		 } else if ( $delta <= $this->age_level[3] ) {
			  return $this->age_point[3];
		 } else if ( $delta <= $this->age_level[4] ) {
			  return $this->age_point[4];
		 } else {
			  return 0;
		 }
	}

	/**
	 * get_distance_point 
	 * 	Return the point of distance (km) between 2 users
	 *		by comparing it with levels & return corresponding point
	 * 
	 * @param mixed $deltax 		: absolute value of (x1 - x2)
	 * @param mixed $deltay 		: absolute value of (y1 - y2)
	 * @access public
	 * @return void					: corresponding point that is configured
	 */
	function get_distance_point($deltax, $deltay)
	{
		 // delta = x^2 + y^2
		 $delta = $deltax * $deltax + $deltay * $deltay;

		 if ( $delta <= $this->distance_level[0] ) {
			  return $this->distance_point[0];
		 } else if ( $delta <= $this->distance_level[1] ) {
			  return $this->distance_point[1];
		 } else if ( $delta <= $this->distance_level[2] ) {
			  return $this->distance_point[2];
		 } else if ( $delta <= $this->distance_level[3] ) {
			  return $this->distance_point[3];
		 } else if ( $delta <= $this->distance_level[4] ) {
			  return $this->distance_point[4];
		 } else {
			  return 0;
		 }
	}

	/**
	 * generate_age_similarity 
	 * 	Generate age_similarity table
	 * 
	 * @param mixed $users 
	 * @param mixed $updated_users 
	 * @access public
	 * @return void
	 */
	function generate_age_similarity($users, $updated_users)
	{
		 $this->CI =& get_instance();
		 $this->CI->load->model('peopleshouldknow_model');

		 $data['content'] = array();
		 $data['content'][] = '<a href="/admin">Home</a>';
		 $data['content'][] = "Generating age similarity";

		 // $users = $this->Database_model->get_users();
		 foreach ($users as $user1_id)
		 {
			  foreach ($updated_users as $user2_id)
			  {
					if ($user1_id <> $user2_id) {
						 // get year of birthday
						 $year1 = $this->CI->peopleshouldknow_model->get_birthyear($user1_id);
						 $year2 = $this->CI->peopleshouldknow_model->get_birthyear($user2_id);

						 $point = $this->get_age_point(abs($year1 - $year2));
						 if ($point > 0) {
							  $this->CI->peopleshouldknow_model->insert_similarity("age_similarity", $user1_id, $user2_id, $point);
							  $this->CI->peopleshouldknow_model->insert_similarity("age_similarity", $user2_id, $user1_id, $point);
							  $data['content'][] = "user1_id=$user1_id -- user2_id=$user2_id -- similarity=$point";
							  $data['content'][] = "user1_id=$user2_id -- user2_id=$user1_id -- similarity=$point";
						 }
					}
			  }
		 }

		 $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
		 $data['title'] = "Welcome to People You Should Know";
		 $this->CI->load->view('recommendations/gen_similarity_finish', $data);
	}

	/**
	 * generate_work_similarity 
	 * 	Generate work_similarity table
	 * 
	 * @param mixed $users 
	 * @param mixed $updated_users 
	 * @access public
	 * @return void
	 */
	function generate_work_similarity($users, $updated_users)
	{
		 $this->CI =& get_instance();
		 $this->CI->load->model('peopleshouldknow_model');

		 $data['content'] = array();
		 $data['content'][] = '<a href="/admin">Home</a>';
		 $data['content'][] = "Generating work similarity";

		 // $users = $this->Database_model->get_users();
		 foreach ($users as $user1_id)
		 {
			  foreach ($updated_users as $user2_id)
			  {
					if ($user1_id <> $user2_id) {
						 $point = $this->work_point * $this->CI->peopleshouldknow_model->work_similarity($user1_id, $user2_id);
						 if ($point > 0) {
							  $this->CI->peopleshouldknow_model->insert_similarity("work_similarity", $user1_id, $user2_id, $point);
							  $this->CI->peopleshouldknow_model->insert_similarity("work_similarity", $user2_id, $user1_id, $point);
							  $data['content'][] = "user1_id=$user1_id -- user2_id=$user2_id -- similarity=$point";
							  $data['content'][] = "user1_id=$user2_id -- user2_id=$user1_id -- similarity=$point";
						 }
					}
			  }
		 }

		 $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
		 $data['title'] = "Welcome to People You Should Know";
		 $this->CI->load->view('recommendations/gen_similarity_finish', $data);
	}

	/**
	 * generate_location_similarity 
	 * 	Generate location_similarity table
	 * 
	 * @param mixed $users 
	 * @param mixed $updated_users 
	 * @access public
	 * @return void
	 */
	function generate_location_similarity($users, $updated_users)
	{
		 $this->CI =& get_instance();
		 $this->CI->load->model('peopleshouldknow_model');

		 $data['content'] = array();
		 $data['content'][] = '<a href="/admin">Home</a>';
		 $data['content'][] = "Generating location similarity";

		 // $users = $this->Database_model->get_users();
		 foreach ($users as $user1_id)
		 {
			  foreach ($updated_users as $user2_id)
			  {
					if ($user1_id <> $user2_id) {
						 $location1 = $this->CI->peopleshouldknow_model->get_location($user1_id);
						 $location2 = $this->CI->peopleshouldknow_model->get_location($user2_id);

						 if ($location1 === false || $location2 === false) {
							  // the information of user's location is NOT exist
							  $point = $this->place_point * $this->CI->peopleshouldknow_model->place_similarity($user1_id, $user2_id) ;
						 } else {
							  // the information of user's location is exist
							  $point = $this->place_point * $this->CI->peopleshouldknow_model->place_similarity($user1_id, $user2_id) +
							       $this->get_distance_point( abs($location1->x - $location2->x) , abs($location1.y - $location2.y) ) ;
						 }

						 if ($point > 0) {
							  $this->CI->peopleshouldknow_model->insert_similarity("location_similarity", $user1_id, $user2_id, $point);
							  $this->CI->peopleshouldknow_model->insert_similarity("location_similarity", $user2_id, $user1_id, $point);
							  $data['content'][] = "user1_id=$user1_id -- user2_id=$user2_id -- similarity=$point";
							  $data['content'][] = "user1_id=$user2_id -- user2_id=$user1_id -- similarity=$point";
						 }
					}
			  }
		 }

		 $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
		 $data['title'] = "Welcome to People You Should Know";
		 $this->CI->load->view('recommendations/gen_similarity_finish', $data);
	}

	/**
	 * generate_psk_page_similarity 
	 * 	Generate psk_page_similarity table (from page_interaction table)
	 * 
	 * @param mixed $users 
	 * @param mixed $updated_users 
	 * @access public
	 * @return void
	 */
	function generate_psk_page_similarity($users, $updated_users)
	{
		 $this->CI =& get_instance();
		 $this->CI->load->model('peopleshouldknow_model');

		 // $this->Database_model->tables_psk_page_similarity_creation();

		 // $users = $this->Database_model->get_users();

		 $data['content'] = array();
		 $data['content'][] = '<a href="/admin">Home</a>';
		 $data['content'][] = "Generating page similarity";

		 foreach ($users as $user1_id) {
			  foreach ($updated_users as $user2_id)
			  {
					if ($user1_id <> $user2_id) {
						 $point = $this->CI->peopleshouldknow_model->psk_page_similarity($user1_id, $user2_id);
						 if ($point <> 0) {
							  $this->CI->peopleshouldknow_model->insert_similarity("psk_page_similarity", $user1_id, $user2_id, $point);
							  $this->CI->peopleshouldknow_model->insert_similarity("psk_page_similarity", $user2_id, $user1_id, $point);
							  $data['content'][] = "user1_id=$user1_id -- user2_id=$user2_id -- similarity=$point";
							  $data['content'][] = "user1_id=$user2_id -- user2_id=$user1_id -- similarity=$point";
						 }
					}
			  }
		 }

		 $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
		 $data['title'] = "Welcome to People You Should Know";
		 $this->CI->load->view('recommendations/gen_similarity_finish', $data);
	}

	/**
	 * generate_topic_interaction 
	 * 	Generate topic_interaction table (from page_interaction table)
	 * 
	 * @param mixed $updated_users 
	 * @access public
	 * @return void
	 */
	function generate_topic_interaction($updated_users)
	{
		 $this->CI =& get_instance();
		 $this->CI->load->model('peopleshouldknow_model');
		 // $this->Database_model->tables_topic_interaction_creation();

		 // $users = $this->Database_model->get_users();
		 $topics = $this->CI->peopleshouldknow_model->get_topics();

		 $data['content'] = array();
		 $data['content'][] = '<a href="/admin">Home</a>';
		 $data['content'][] = "Generating topic interaction (from page_interaction table)";

		 foreach ($updated_users as $user_id) {
			  foreach ($topics as $topic_id)
			  {
					 $point = $this->CI->peopleshouldknow_model->user_topic_point($user_id, $topic_id);
					 if ($point <> 0) {
						  $this->CI->peopleshouldknow_model->insert_topic_points($user_id, $topic_id, $point);
						  $data['content'][] = "user_id=$user_id -- topic_id=$topic_id -- point=$point";
					 }
			  }
		 }

		 $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
		 $data['title'] = "Welcome to People You Should Know";
		 $this->CI->load->view('recommendations/gen_similarity_finish', $data);
	}

	/**
	 * generate_psk_topic_similarity 
	 * 	Generate topic_simialrity table
	 * 
	 * @param mixed $users 
	 * @param mixed $updated_users 
	 * @access public
	 * @return void
	 */
	function generate_psk_topic_similarity($users, $updated_users) 
	{
		 $this->CI =& get_instance();
		 $this->CI->load->model('peopleshouldknow_model');
		 // $this->Database_model->tables_psk_topic_similarity_creation();

		 // $users = $this->Database_model->get_users();

		 $data['content'] = array();
		 $data['content'][] = '<a href="/admin">Home</a>';
		 $data['content'][] = "Generating topic similarity";

		 foreach ($users as $user1_id) {
			  foreach ($updated_users as $user2_id)
			  {
					if ($user1_id <> $user2_id) {
						 $point = $this->CI->peopleshouldknow_model->psk_topic_similarity($user1_id, $user2_id);
						 if ($point <> 0) {
							  $this->CI->peopleshouldknow_model->insert_similarity("psk_topic_similarity", $user1_id, $user2_id, $point);
							  $this->CI->peopleshouldknow_model->insert_similarity("psk_topic_similarity", $user2_id, $user1_id, $point);
							  $data['content'][] = "user1_id=$user1_id -- user2_id=$user2_id -- similarity=$point";
							  $data['content'][] = "user1_id=$user2_id -- user2_id=$user1_id -- similarity=$point";
						 }
					}
			  }
		 }

		 $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
		 $data['title'] = "Welcome to People You Should Know";
		 $this->CI->load->view('recommendations/gen_similarity_finish', $data);
	}

	/**
	 * generate_people_know_similarity 
	 * 	Generate People You Should Know similarity table
	 * 
	 * @param mixed $users 
	 * @param mixed $updated_users 
	 * @access public
	 * @return void
	 */
	function generate_people_know_similarity($users, $updated_users)
	{
		 $this->CI =& get_instance();
		 $this->CI->load->model('peopleshouldknow_model');

		 $data['content'] = array();
		 $data['content'][] = '<a href="/admin">Home</a>';
		 $data['content'][] = "Generating FINAL similarity";

		 foreach ($users as $user1_id) 
		 {
			  foreach ($updated_users as $user2_id)
			  {
					if ($user1_id != $user2_id) 
					{
				
						 // point could be REAL value, it is become INT when storing to table
						 // no problem, because it is small
						 $point = $this->CI->peopleshouldknow_model->full_similarity($user1_id, $user2_id,
									$this->age_weight, $this->work_weight, $this->location_weight,
									$this->page_weight, $this->topic_weight);
						 //$point = 3;
						 if ($point != 0) 
						 {
							  $this->CI->peopleshouldknow_model->insert_similarity("should_know_similarity", $user1_id, $user2_id, $point);
							  $this->CI->peopleshouldknow_model->insert_similarity("should_know_similarity", $user2_id, $user1_id, $point);
							  $data['content'][] = "user1_id=$user1_id -- user2_id=$user2_id -- similarity=$point";
							  $data['content'][] = "user1_id=$user2_id -- user2_id=$user1_id -- similarity=$point";
						 }
					}
			  }
		 }

		// $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
		// $data['title'] = "Welcome to People You Should Know";
		// $this->CI->load->view('recommendations/gen_similarity_finish', $data);
	}

	/**
	 * generate_percentile 
	 * 	The user_percentile is not the same with (user1_id, user2_id) & (user2_id, user1_id)
	 *		It is because the number of connection of (user1_id, *) & (user2_id, *) are different
	 *		So, there is a processing for such case
	 * 
	 * @param mixed $users 
	 * @param mixed $updated_users 
	 * @access public
	 * @return void
	 */
	function generate_percentile($users, $updated_users)
	{
		 $this->CI =& get_instance();
		 $this->CI->load->model('peopleshouldknow_model');

		 $data['content'] = array();
		 $data['content'][] = '<a href="/admin">Home</a>';
		 $data['content'][] = "Generating percentile";

		 foreach ($users as $user1_id) {
			  $total_connection = $this->CI->peopleshouldknow_model->get_connection_count($user1_id);
			  if ($total_connection == 0) { continue; }

			  foreach ($updated_users as $user2_id)
			  {
					if ( in_array($user1_id, $updated_users) ) {
						if ($user1_id <> $user2_id) {
							 $similarity = $this->CI->peopleshouldknow_model->get_similarity('should_know_similarity', $user1_id, $user2_id);
							 $percentile = $this->CI->peopleshouldknow_model->percentile($user1_id, $user2_id, $similarity);
	
							 $point = (100 * $percentile) / $total_connection;
	
							 if ($point <> 0) {
								  $this->CI->peopleshouldknow_model->insert_similarity("user_percentile", $user1_id, $user2_id, $point);
								  $data['content'][] = "user1_id=$user1_id -- user2_id=$user2_id -- percentile=$point";
							 }
						}
					} else {
						 $similarity = $this->CI->peopleshouldknow_model->get_similarity('should_know_similarity', $user1_id, $user2_id);
						 $percentile1 = $this->CI->peopleshouldknow_model->percentile($user1_id, $user2_id, $similarity);
						 $point1 = (int) ( (100 * $percentile1) / $total_connection );

						 $total_connection2 = $this->CI->peopleshouldknow_model->get_connection_count($user2_id);
						 if ( $total_connection2 == 0 ) {
							  $point2 = 0;
						 } else {
							  $percentile2 = $this->CI->peopleshouldknow_model->percentile($user2_id, $user1_id, $similarity);
							  $point2 = (int) ( (100 * $percentile2) / $total_connection2 );
						 }

						 if ($point1 <> 0) {
							  $this->CI->peopleshouldknow_model->insert_similarity("user_percentile", $user1_id, $user2_id, $point1);
							  $data['content'][] = "user1_id=$user1_id -- user2_id=$user2_id -- percentile=$point1";
						 }

						 if ($point2 <> 0) {
							  $this->CI->peopleshouldknow_model->insert_similarity("user_percentile", $user2_id, $user1_id, $point2);
							  $data['content'][] = "user1_id=$user2_id -- user2_id=$user1_id -- percentile=$point2";
						 }
					}
			  }
		 }

		 $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
		 $data['title'] = "Welcome to People You Should Know";
		 $this->CI->load->view('recommendations/gen_similarity_finish', $data);
	}

}

?>
