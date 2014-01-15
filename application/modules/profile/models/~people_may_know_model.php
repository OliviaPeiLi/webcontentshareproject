<?php
class People_may_know_model extends CI_Model
{

    /********************************************************
     * function: delete the specific table
     * input: table name
     * output: none
     ********************************************************/
    function tables_deletion($table)
    {
        $this->load->dbforge();

        $this->dbforge->drop_table($table);
    }

    /********************************************************
     * function: create 'connections' table
     * input: none
     * output: none
     * Note:
     *   connections table contains the direct connection
     *   if any between 2 users
     *		useid1, useid2 : connection
     *		similarity:
     *			0: not executed yet (new connection for this running)
     *			1: already updated
     ********************************************************/
    function tables_connections_creation()
    {
        $this->load->dbforge();

        // create connections table
        $fields = array(
                      'user1_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'user2_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'similarity' => array( 'type' => 'INT', 'constraint' => 1)
                  );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('user1_id', TRUE);
        $this->dbforge->add_key('user2_id', TRUE);
        $this->dbforge->create_table('connections', TRUE);
    }

    /********************************************************
     * function: create 'user_schools' table
     * input: none
     * output: none
     * Note:
     *   user_schools table contains the related information
     *   of a user
     ********************************************************/
    function tables_user_schools_creation()
    {
        $this->load->dbforge();

        // create user_schools table
        $fields = array(
                      'pid' => array( 'type' => 'INT', 'constraint' => 5),
                      'user_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'school_id' => array( 'type' => 'VARCHAR', 'constraint' => '100'),
                      'year' => array( 'type' => 'VARCHAR', 'constraint' => '10'),
                      'major' => array( 'type' => 'VARCHAR', 'constraint' => '100')
                  );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('pid', TRUE);
        $this->dbforge->create_table('user_schools', TRUE);
    }

    /********************************************************
     * function: create 'usersimilarity' table
     * input: none
     * output: none
     * Note:
     *   usersimilarity table contains the potention connection
     *   between 2 users. It is as same as in index for quick
     *   searching
     ********************************************************/
    function tables_usersimilarity_creation()
    {
        $this->load->dbfandrop_ci();

        // create usersimilarity table
        $fields = array(
                      'user1_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'user2_id' => array( 'type' => 'INT', 'constraint' => 5),
                      'weight' => array( 'type' => 'INT', 'constraint' => 10)
                  );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('user1_id', TRUE);
        $this->dbforge->add_key('user2_id', TRUE);
        $this->dbforge->create_table('usersimilarity', TRUE);

    }

    /**********************************************************
    * function: get all connection relates to user_id
    * input: user_id
    * output: result of all connections that relates to user_id
    **********************************************************/
    function get_connection($user_id)
    {
        $this->db->where('user1_id', $user_id);
        // separate connection direction (ver2)
        // $this->db->or_where('user2_id', $user_id);
        $query = $this->db->get('connections');
        return $query->result();
    }

    /**********************************************************
    * function: check if there is a direct connection between user1_id & user2_id
    * input: user1_id, user2_id
    * output:	true if user1_id & user2_id have connection
    * 				other case: return false
    **********************************************************/
    function is_connection($user1_id, $user2_id)
    {
        $this->db->where('user1_id', $user1_id);
        $this->db->where('user2_id', $user2_id);
        $query = $this->db->get('connections');

        return ( ($query->num_rows() > 0) ? true : false );
    }

    /**********************************************************
    * function: get all connections
    * input: none
    * output: all connections (information in connections table)
    **********************************************************/
    function get_all_connections()
    {
        //$this->db->select('*');
        //$this->db->from('connections');
        //$query = $this->db->get();
        $query = $this->db->get('connections');
        return $query->result();
    }

    /**********************************************************
    * function: get all connections that has similarity=0
    * input: none
    * output: all connections (information in connections table)
    **********************************************************/
    function get_all_connections_not_similarity()
    {
        $this->db->where('similarity', 0);
        $query = $this->db->get('connections');
        return $query->result();
    }


    /**********************************************************
    * function: get profile entries of user_id
    * input: user_id
    * output: result of all entries that has user_id = inputted user_id
    **********************************************************/
    function get_profile($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_schools');
        return $query->result();
    }

    /**********************************************************
    * function: get profile entries that match with inputted
    * 				year/major/school_id field
    * input: year, major, school_id
    * output: result of all entries that match with inputted info
    **********************************************************/
    function get_profile_specific($year, $major, $school_id)
    {
        $this->db->where('year', $year);
        $this->db->where('major', $major);
        $this->db->where('school_id', $school_id);
        $query = $this->db->get('user_schools');
        return $query->result();
    }

    /**********************************************************
    * function: clean up the table
    * input: table name (ex: connections or user_schools)
    * output: none
    *	note: table must be empty before generation new database
    *			to avoid same primary key (with old database)
    **********************************************************/
    function clean_table($table)
    {
        $this->db->empty_table($table);
    }

    /**********************************************************
    * function: insert/update a connection between user1_id & user2_id
    * input: id1 & id2 key
    * output: none
    **********************************************************/
    function insert_connection($id1, $id2, $similarity)
    {
        $data = array(
                    'user1_id' => "$id1" ,
                    'user2_id' => "$id2"
                );

        $this->db->where($data);
        $query = $this->db->get('connections');

        if ($query->num_rows() == 0)
        {
            $data = array(
                        'user1_id' => "$id1" ,
                        'user2_id' => "$id2" ,
                        'similarity' => "$similarity"
                    );

            $this->db->save_queries = false;
            $this->db->insert('connections', $data);
        }
    }

    /**********************************************************
    * function: set similarity field to 1 (after connection is updated)
    * input: none (all records are updated)
    * output: none
    **********************************************************/
    function set_connection_similarity()
    {
        $this->db->where('similarity', 0);
        $query = $this->db->get('connections');

        foreach ($query->result() as $con)
        {
            $data = array(
                        'user1_id' => $con->user1_id,
                        'user2_id' => $con->user2_id,
                        'similarity' => 1
                    );

            $this->db->where('user1_id', $con->user1_id);
            $this->db->where('user2_id', $con->user2_id);

            $this->db->update('connections', $data);
        }
    }

    /**********************************************************
    * function: insert a user profile
    * input: id, user_id, school_id, year, major
    * output: none
    **********************************************************/
    function insert_profile($id, $user_id, $school_id, $year, $major)
    {
        $data = array(
                    'pid' => $id ,
                    'user_id' => $user_id ,
                    'school_id' => $school_id ,
                    'year' => "$year" ,
                    'major' => strtoupper($major)
                );

        $this->db->save_queries = false;
        $this->db->insert('user_schools', $data);
    }

    /**********************************************************
    * function: insert/update a similarity value
    * input: user1_id, user2_id, similarity
    * output: none
    * note:
    *	because there is possibility of changing the weight
    *	due to new connection, therefore, it is necessary to
    *	handle the 'update' situation (already available in table)
    **********************************************************/
    function insert_similarity($user1_id, $user2_id, $similarity)
    {
        $this->db->where('user1_id',$user1_id);
        $this->db->where('user2_id',$user2_id);
        $query = $this->db->get('usersimilarity');

        $data = array(
                    'user1_id' => $user1_id ,
                    'user2_id' => $user2_id ,
                    'weight' => $similarity
                );

        if ($query->num_rows() == 0)
        {
            // insert
            $this->db->save_queries = false;
            $this->db->insert('usersimilarity', $data);
        }
        else
        {
            // update
            $this->db->where('user1_id', $user1_id);
            $this->db->where('user2_id', $user2_id);
            $this->db->update('usersimilarity', $data);
        }
    }

    /**********************************************************
    * function: get the weight field in database
    * input: user1_id, user2_id
    * output: weight field of the record of user1_id, user2_id
    **********************************************************/
    function get_weight($user1_id, $user2_id)
    {
        $this->db->where('user1_id', $user1_id);
        $this->db->where('user2_id', $user2_id);
        $query = $this->db->get('usersimilarity');

        if ($query->num_rows() == 0)
        {
            return 0;
        }

        foreach ($query->result() as $row)
        {
            return $row->weight;
        }
    }
}

?>
