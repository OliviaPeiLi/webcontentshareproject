<?php
class Peopleshouldknow_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('topic_points_model');
    }


    /**
     * tables_deletion
     * 	Delete a table
     *
     * @param mixed $table 		: table name
     * @access public
     * @return void
     */
    function tables_deletion($table)
    {
        $this->load->dbforge();

        $this->dbforge->drop_table($table);
    }

    /**
     * tables_points_system_creation
     * 	Create "points_system" table
     *		This table information will be collected by another program
     *		This table creation is for testing only
     */
    function tables_points_system_creation()
    {
        $this->load->dbforge();

        // create userconnection table
        $fields = array(
                      'user_id' => array( 'type' => 'INT', 'constraint' => 10),
                      'page_id' => array( 'type' => 'INT', 'constraint' => 10),
                      'point' => array( 'type' => 'INT', 'constraint' => 10)
                  );
        $this->dbforge->add_field($fields);

        $this->dbforge->add_key('user_id', TRUE);
        $this->dbforge->add_key('page_id', TRUE);
        $this->dbforge->create_table('points_system', TRUE);
    }

    /**
     * tables_topic_points_creation
     * 	Generate "topic_points"
     *
     * @access public
     * @return void
     */
    function tables_topic_points_creation()
    {
        $this->load->dbforge();

        // create userconnection table
        $fields = array(
                      'user_id' => array( 'type' => 'INT', 'constraint' => 10),
                      'topic_id' => array( 'type' => 'INT', 'constraint' => 10),
                      'point' => array( 'type' => 'INT', 'constraint' => 10)
                  );
        $this->dbforge->add_field($fields);

        $this->dbforge->add_key('user_id', TRUE);
        $this->dbforge->add_key('topic_id', TRUE);
        $this->dbforge->create_table('topic_points', TRUE);
    }

    /**
     * tables_psk_page_similarity_creation
     * 	Create psk_page_similarity table (empty table)
     * @access public
     * @return void
     */
    function tables_psk_page_similarity_creation()
    {
        $this->load->dbforge();

        // create usersimilarity table
        $fields = array(
                      'user1_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'user2_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'similarity' => array( 'type' => 'INT', 'constraint' => 10)
                  );
        $this->dbforge->add_field($fields);

        $this->dbforge->add_key('user1_id', TRUE);
        $this->dbforge->add_key('user2_id', TRUE);
        $this->dbforge->create_table('psk_page_similarity', TRUE);
    }

    /**
     * tables_psk_topic_similarity_creation
     * 	Create psk_topic_similarity table (empty table)
     *
     * @access public
     * @return void
     */
    function tables_psk_topic_similarity_creation()
    {
        $this->load->dbforge();

        // create usersimilarity table
        $fields = array(
                      'user1_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'user2_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'similarity' => array( 'type' => 'INT', 'constraint' => 10)
                  );
        $this->dbforge->add_field($fields);

        $this->dbforge->add_key('user1_id', TRUE);
        $this->dbforge->add_key('user2_id', TRUE);
        $this->dbforge->create_table('psk_topic_similarity', TRUE);
    }

    /**
     * tables_interest_similarity_creation
     * 	Create "interest_similarity" table (empty table)
     *
     * @access public
     * @return void
     */
    /*	function tables_interest_similarity_creation()
    	{
    		$this->load->dbforge();

    		// create usersimilarity table
    		$fields = array(
    			'user1_id' => array( 'type' => 'INT', 'constraint' => 5),
    			'user2_id' => array( 'type' => 'INT', 'constraint' => 5),
    			'similarity' => array( 'type' => 'INT', 'constraint' => 10)
    		);
    		$this->dbforge->add_field($fields);

    		$this->dbforge->add_key('user1_id', TRUE);
    		$this->dbforge->add_key('user2_id', TRUE);
    		$this->dbforge->create_table('interest_similarity', TRUE);
    	}
    */
    /**
     * user_topic_point
     * 	Collect the topic point from points_system table
     *
     * @param mixed $user_id 			: user id
     * @param mixed $topic_id 			: topic id
     * @access public
     * @return void						: the point that matches between (user_id, topic_id)
     *
     */
    function user_topic_point ( $user_id , $topic_id )
    {
        $statement = "select sum(points_system.points) as point from points_system, topic_page
                     where points_system.page_id=topic_page.page_id AND points_system.user_id=? AND topic_page.topic_id=?";
        $query = $this->db->query($statement, array($user_id, $topic_id));

        foreach ($query->result() as $r)
        {
            return $r->point;
        }
    }

    /**
     * psk_page_similarity
     * 	Calculate the similarity point between 2 users
     *
     * @param mixed $user1_id 			: user id1
     * @param mixed $user2_id 			: user id2
     * @access public
     * @return void						: similarity point
     */
    function psk_page_similarity($user1_id, $user2_id)
    {
        $statement = 'select sum(case when p1.points<p2.points then p1.points else p2.points end) as point
                     from points_system as p1, points_system as p2
                     where p1.user_id=? AND p1.page_id=p2.page_id AND p2.user_id=?';


        $query = $this->db->query($statement, array($user1_id, $user2_id));

        foreach ($query->result() as $r)
        {
            return $r->point;
        }

    }

    /**
     * psk_topic_similarity
     * 	Calculate topic similarity point between 2 users
     *
     * @param mixed $user1_id 			: user id1
     * @param mixed $user2_id 			: user id2
     * @access public
     * @return void						: similarity point
     */
    function psk_topic_similarity($user1_id, $user2_id)
    {
        $statement = 'select sum(case when t1.points<t2.points then t1.points else t2.points end) as point
                     from topic_points as t1, topic_points as t2
                     where t1.user_id=? AND t1.topic_id=t2.topic_id AND t2.user_id=?';


        $query = $this->db->query($statement, array($user1_id, $user2_id));

        foreach ($query->result() as $r)
        {
            return $r->point;
        }

    }

    /**
     * work_similarity
     * 	Calculate work similarity between 2 users
     *
     * @param mixed $user1_id 			: user id1
     * @param mixed $user2_id 			: user id2
     * @access public
     * @return void						: number (count) of common company name
     */
    function work_similarity($user1_id, $user2_id)
    {
        $statement = 'select *
                     from user_company as c1, user_company as c2
                     where c1.user_id=?  AND c2.user_id=? AND c1.company=c2.company';

        $query = $this->db->query($statement, array($user1_id, $user2_id));

        return $query->num_rows();

    }

    /**
     * place_similarity
     * 	Calculate place similarity between 2 users
     * 	(common place of lived/travelled)
     *
     * @param mixed $user1_id 			: user id1
     * @param mixed $user2_id 			: user id2
     * @access public
     * @return void						: similarity point
     */
    function place_similarity($user1_id, $user2_id)
    {
        $statement = 'select *
                     from user_locations as p1, user_locations as p2
                     where p1.user_id=?  AND p2.user_id=? AND p1.place_name=p2.place_name';

        $query = $this->db->query($statement, array($user1_id, $user2_id));

        return $query->num_rows();

    }

    /**
     * get_users
     * 	Get all users from a page_users table
     *		Because there is no available user table
     *			this function should be changed to access to user tables
     *
     * @access public
     * @return void			: an array that contains all user_id
     */
    function get_users()
    {
        $query = $this->db->get('page_users');

        $users = array();
        foreach ($query->result() as $u)
        {
            if ( ! in_array($u->user_id, $users) )
            {
                $users[] = $u->user_id;
            }
        }

        return $users;
    }

    /**
     * get_topics
     * 	Get all topic id from "topics" table
     *
     * @access public
     * @return void			: an array of topic_id
     */
    function get_topics()
    {
        $query = $this->db->get('topics');

        $topics = array();
        foreach ($query->result() as $u)
        {
            if ( ! in_array($u->topic_id, $topics) )
            {
                $topics[] = $u->topic_id;
            }
        }

        return $topics;
    }

    /**
     * get_location
     * 	Get location of user = (x,y) data
     *
     * @param mixed $user_id
     * @access public
     * @return void
     */
    function get_location($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_locations');

        foreach ($query->result() as $r)
        {
            return $r;
        }

        return false;
    }

    /**
     * insert_topic_points
     * 	Insert an item to topic_points table
     * 	If item is exist then update to table
     * 	Unless, insert to table
     *
     * @param mixed $user_id
     * @param mixed $topic_id
     * @param mixed $point
     * @access public
     * @return void
     */
    function insert_topic_points($user_id, $topic_id, $point)
    {
        $data = array(
                    'user_id' => "$user_id" ,
                    'topic_id' => "$topic_id"
                );

        // $this->db->where($data);
        $query = $this->topic_points_model->get_many_by( $data );

        $data = array(
                    'user_id' => "$user_id" ,
                    'topic_id' => "$topic_id" ,
                    'points' => "$point"
                );

        // $this->db->save_queries = false;
        if ( count($query) == 0)
        {
            // insert
            $this->topic_points_model->insert($data);
        }
        else
        {
            // update
//			$this->db->where('user_id', $user_id);
//			$this->db->where('topic_id', $topic_id);
            $this->topic_points_model->update(
                array('user_id' => $user_id, 'topic_id' => $topic_id),
                $data
            );
        }
    }

    /**
     * insert_similarity
     * 	Insert an item to a similarity table
     *		Table name is inputed
     *
     * 	If item is exist then update to table
     * 	Unless, insert to table
     *
     * @param mixed $tablename 			: table name
     * @param mixed $user1_id 				: user1_id
     * @param mixed $user2_id 				: user2_id
     * @param mixed $similarity 			: similarity point
     * @access public
     * @return void
     */
    function insert_similarity($tablename, $user1_id, $user2_id, $similarity)
    {
        $data = array(
                    'user1_id' => "$user1_id" ,
                    'user2_id' => "$user2_id"
                );

        $this->db->where($data);
        $query = $this->db->get($tablename);

        $data = array(
                    'user1_id' => "$user1_id" ,
                    'user2_id' => "$user2_id" ,
                    'similarity' => "$similarity"
                );

        $this->db->save_queries = false;
        if ($query->num_rows() == 0)
        {
            // insert
            $this->db->insert($tablename, $data);
        }
        else
        {
            // update
            $this->db->where('user1_id', $user1_id);
            $this->db->where('user2_id', $user2_id);
            $this->db->update($tablename, $data);
        }
    }

    /**
     * get_similarity
     * 	Get similarity point of a table
     *		Table name is inputted
     *
     * @param mixed $tablename 		: table name
     * @param mixed $user1_id 			: user1_id
     * @param mixed $user2_id 			: user2_id
     * @access public
     * @return void						: similarity point
     */
    function get_similarity($tablename, $user1_id, $user2_id)
    {
        $this->db->where('user1_id', $user1_id);
        $this->db->where('user2_id', $user2_id);

        $query = $this->db->get($tablename);

        foreach ($query->result() as $r)
        {
            return $r->similarity;
        }

        return 0;
    }

    /**
     * get_birthyear
     * 	Get the year of birthday of a user
     *
     * @param mixed $user_id 		: user id
     * @access public
     * @return void					: year of birthday
     */
    function get_birthyear($user_id)
    {
        $this->db->select('birthday');
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        $row = $query->result_array();
        $bday = $row[0]['birthday'];
        $year = substr($bday,0,4);
        return $year;
    }

    /**
     * get_updated_item
     * 	Return an array of the "item" in "user_updated" table
     *
     * @param mixed $item 		: item (age/work/location/page/topic)
     * @access public
     * @return void				: users that has been updated
     */
    function get_updated_item($item)
    {
        $this->db->where('updated', $item);
        $query = $this->db->get('user_updated');

        $updated = array();
        foreach ($query->result() as $q)
        {
            if ( ! in_array($q->user_id, $updated) )
            {
                $updated[] = $q->user_id;
            }
        }

        return $updated;
    }

    /**
     * get_updated
     * 	Access to user_update table
     *		Save user_id that were updated of age/work/location/page/topic to suitable array
     *		Make empty of user_updated table (for increasemental execution)
     *
     * @param mixed $age 			: output updated users of age
     * @param mixed $work 			: output updated users of work
     * @param mixed $location 		: output updated users of location
     * @param mixed $page 			: output updated users of page
     * @param mixed $topic 			: output updated users of topic
     * @access public
     * @return void
     */
    function get_updated(&$age, &$work, &$location, &$page, &$topic)
    {
        $age = $this->get_updated_item('age');
        $work = $this->get_updated_item('work');
        $location = $this->get_updated_item('location');
        $page = $this->get_updated_item('page');

        // if page changed -> topic_points is also changed
        // -> topic updated = topic updated + page updated
        $topic = $this->get_updated_item('topic');
        foreach ($page as $p)
        {
            if ( ! in_array($p, $topic) )
            {
                $topic[] = $p;
            }
        }

        $this->db->empty_table('user_updated');
    }

    /**
     * full_similarity
     * 	Return the final value of people you should know's similarity
     *			from available value in age_similarity/work_similarity/location_similarity/psk_page_similarity/psk_topic_similarity
     *
     * @param mixed $user1_id
     * @param mixed $user2_id
     * @param mixed $age_weight
     * @param mixed $work_weight
     * @param mixed $location_weight
     * @param mixed $page_weight
     * @param mixed $topic_weight
     * @access public
     * @return void
     */
    function full_similarity($user1_id, $user2_id, $age_weight, $work_weight, $location_weight, $page_weight, $topic_weight)
    {
        // get similarity
        $psk_page_similarity = $this->get_similarity("psk_page_similarity", $user1_id, $user2_id);
        $psk_topic_similarity = $this->get_similarity("psk_topic_similarity", $user1_id, $user2_id);
        $age_similarity = $this->get_similarity("age_similarity", $user1_id, $user2_id);
        $work_similarity = $this->get_similarity("work_similarity", $user1_id, $user2_id);
        $location_similarity = $this->get_similarity("location_similarity", $user1_id, $user2_id);

        // multiply with weight
        $psk_page_similarity     = $psk_page_similarity  * $page_weight ;
        $psk_topic_similarity    = $psk_topic_similarity  * $topic_weight ;
        $age_similarity      = $age_similarity  * $age_weight ;
        $work_similarity     = $work_similarity  * $work_weight ;
        $location_similarity = $location_similarity  * $location_weight ;

        $total_similarity    = $psk_page_similarity + $psk_topic_similarity + $age_similarity + $work_similarity + $location_similarity ;

        return $total_similarity;
    }

    /**
     * get_connection_count
     * 	Get total connection of $user_id
     *
     * @param mixed $user_id
     * @access public
     * @return void
     */
    function get_connection_count($user_id)
    {
        $statement = 'SELECT count(*) as connection_count
                     FROM `should_know_similarity`
                     WHERE (user1_id = ?)';
        $query = $this->db->query($statement, array($user_id));

        foreach ($query->result() as $r)
        {
            return $r->connection_count;
        }

        return 0;
    }

    /**
     * percentile
     * 	Return count of element that has similarity <= point
     * 	Real percentile = ($this->percentile * 100% ) / $this->get_connection_count
     *
     * @param mixed $user1_id
     * @param mixed $user2_id
     * @param mixed $point
     * @access public
     * @return void
     */
    function percentile($user1_id, $user2_id, $point)
    {
        $statement = 'SELECT count(*) as percentile
                     FROM `should_know_similarity`
                     WHERE (user1_id = ?) AND (similarity <= ?) AND (user2_id <> ?)';
        $query = $this->db->query($statement, array($user1_id, $point, $user2_id));

        foreach ($query->result() as $r)
        {
            return $r->percentile;
        }

        return 0;
    }



}

?>
