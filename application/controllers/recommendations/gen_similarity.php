<?php

class Gen_similarity extends CI_Controller
{

    /**********************************************************
    * function: index function
    * input: none
    * output: generate table 'usersimilarity'
    *
    * Note: this function should be executed after updating connections table
    **********************************************************/
    function index()
    {
        $this->load->database();
        //$this->create_table();
        $type = $this->uri->segment(2);

        // call to full or increasemental
        if($type == 'full')
        {
            $this->generate_similarity_table_full();
        }
        if($type == 'increase')
        {
            $this->generate_similarity_table_increasemental();
        }
    }

    /**********************************************************
    * function: return max level of searching
    * input: none
    * output: specified MAX level for searching
    **********************************************************/
    function get_max_level()
    {
        return 4;
    }

    /**********************************************************
    * function: create 'usersimilarity' table if not exists
    * input: none
    **********************************************************/
    function create_table()
    {
        $this->load->model('people_may_know_model');
        $this->people_may_know_model->tables_usersimilarity_creation();
    }

    /**********************************************************
    * function: update 'usersimilarity' table as index of searching
    * input: none
    * method: full running (for all data)
    **********************************************************/
    function generate_similarity_table_full()
    {

        // setting timer for long running
        set_time_limit(0);
        $data['content'][] = 'FULL MODE: all connections are updated';
        $data['content'][] = 'Warning: We disable time limit for long running script';
        print_r($data['content']);
        $this->load->model('people_may_know_model');
        //$this->people_may_know_model->clean_table('usersimilarity');

        // get all connection from 'connections' table
        // to realize all users
        $allconnection = $this->people_may_know_model->get_all_connections();
        print_r($allconnection);
        foreach ($allconnection as $con)
        {
            if (!isset($entries))
            {
                $entries[] = $con->user1_id;
            }
            else
            {
                if (!in_array($con->user1_id, $entries))
                {
                    $entries[] = $con->user1_id;
                }
            }

            if (!in_array($con->user2_id, $entries))
            {
                $entries[] = $con->user2_id;
            }
        }

        // as same as a matrix, create the weight for each entry of matrix
        foreach ($entries as $user1_id)
        {
            foreach ($entries as $user2_id)
            {
                // ignore same entry
                if ($user1_id == $user2_id)
                {
                    continue;
                }

                // ignore already friend
                if ($this->people_may_know_model->is_connection($user1_id, $user2_id) === true)
                {
                    continue;
                }

                // comment because separate connection directions (ver2)
                // if ($user1_id > $user2_id) { continue; }

                // create the searching chain for avoid dupplicating
                $chain = array();
                $chain[] = $user1_id;
                $chain[] = $user2_id;
                $weight	= $this->get_connection_weight($user1_id, $user2_id, $this->get_max_level(), $chain);
                // $data['content'][] =  "user1_id=$user1_id -- user2_id=$user2_id --> weight=$weight";

                // only save weight > 0
                if ($weight > 0)
                {
                    $this->people_may_know_model->insert_similarity($user1_id, $user2_id, $weight);
                    // comment because separate connection directions (ver2)
                    // $this->people_may_know_model->insert_similarity($user2_id, $user1_id, $weight);
                }
            }
        }
        $data['content'][] = "General similarity table sucessfully";
        $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();
        $data['title'] = "Welcome to People You May Know";
        $this->load->view('recommendations/gen_similarity_finish', $data);
    }

    /**********************************************************
    * function: return the weight of each connection
    * input: user1_id, user2_id, level
    * output: a number that represent for weight of each connection
    *		from user1_id to user2_id
    * routine:
    *	- if (level = 1) {
    *		+ return 1 when having connection (friend already)
    *		+ return 0 when no connection (not friend)
    *	  } else {
    *	  	+ scan all current friends of user1_id
    *		+ sum the weight of all current friends to user2_id
    *	  }
    **********************************************************/
    function get_connection_weight($user1_id,$user2_id,$level,&$chain)
    {
        gc_enable();
        //list all friends (already know)
        $result = $this->people_may_know_model->get_connection($user1_id);
        foreach  ($result as $row)
        {
            $friends[] = $row->user2_id;
        }

        //there is a connection, weight=1
        if (isset($friends))
        {
            if (in_array($user2_id, $friends))
            {
                $weight = 1;
            }
            else
            {
                $weight = 0;
            }

            // level = 1, weight = direct connection
            if ($level == 1)
            {
                return $weight;
            }
            else
            {
                if ($level == 2)
                {
                    // if level = 2, it backwarded from user2_id
                    // this reduces number of many many user1_id -> user2_id with level=1
                    $result = $this->people_may_know_model->get_connection($user2_id);
                    foreach  ($result as $row)
                    {
                        $f = $row->user2_id;
                        if (in_array($f, $friends))
                        {
                            $weight++;
                        }
                    }
                    return $weight;			//because we skip 1 level
                }
                else
                {
                    // level > 1, weight = direct connection + inter-connection
                    foreach ($friends as $f)
                    {
                        if (in_array($f, $chain))
                        {
                            continue;
                        }
                        $chain[] = $f;
                        //check whether of not the $f has how many connection possibility
                        $weight += $this->get_connection_weight($f, $user2_id, $level-1, $chain);
                    }
                    return $weight;
                }
            }
        }
        else
        {
            // is the case of no-friend
            // for future improvement, may put the entries as similar schoolid/... as defined in user_school
            return 0;
        }
    }

    /**********************************************************
    * function: get candidate of increasemental nodes
    * input:
    *	 useid : user_id of new connection
    *   chain : to keep chain of nodes to avoid dupplication
    *
    * Note:
    *	Because the new connection contains 2 nodes, each node
    *	need to tract for (max_level - 1) to get objectives of
    *	similarity updating
    **********************************************************/
    function get_candidate($user_id, &$chain)
    {
        // 1 level already connected to new connection
        $result = $this->get_connection_level_all($user_id, $this->get_max_level() - 1, $chain);

        return $result;
    }

    function get_connection_level_all($user_id, $level, &$chain)
    {
        $this->load->model('people_may_know_model');

        // level 1
        $friend = $this->people_may_know_model->get_connection($user_id);

        $listall = array();
        foreach ($friend as $f)
        {
            if (!in_array($f->user2_id, $chain))
            {
                $listall[] = $f->user2_id;
                $chain[] = $f->user2_id;
                echo ($f->user2_id . ' ');
            }
        }

        if ($level == 1)
        {
            return $listall;
        }
        else
        {
            $friendnext = array();
            foreach ($friend as $f)
            {
                $friendnext = $this->get_connection_level_all($f->user2_id, $level - 1, $chain);
                foreach ($friendnext as $fn)
                {
                    $listall[] = $fn;
                    // $chain[] = $fn;
                    // echo ($fn . ' ');
                }
            }
            return $listall;
        }
    }

    /**********************************************************
    * function: update 'usersimilarity' table as index of searching
    * input: none
    * method: increasemental
    **********************************************************/
    function generate_similarity_table_increasemental()
    {

        // setting timer for long running
        set_time_limit(0);
        $data['content'][] = 'INCREASEMENTAL MODE (only updating connection)';
        $data['content'][] = 'Warning: We disable time limit for long running script';

        $this->load->model('people_may_know_model');

        // do not remove old result
        // $this->people_may_know_model->clean_table('usersimilarity');

        // get connection that has similarity=0 (new connections)
        // do not get the OLD connection of previous execution time
        $allconnection = $this->people_may_know_model->get_all_connections_not_similarity();
        $entries = array();
        foreach ($allconnection as $con)
        {
            if (!in_array($con->user1_id, $entries))
            {
                $entries[] = $con->user1_id;

                // get objectives of updating
                $alllevel = $this->get_candidate($con->user1_id, $entries);
                foreach ($alllevel as $mc)
                {
                    if (!in_array($mc, $entries))
                    {
                        $entries[] = $mc;
                    }
                }
            }

            if (!in_array($con->user2_id, $entries))
            {
                $entries[] = $con->user2_id;

                // get objectives of updating
                $alllevel = $this->get_candidate($con->user2_id, $entries);
                foreach ($alllevel as $mc)
                {
                    if (!in_array($mc, $entries))
                    {
                        $entries[] = $mc;
                    }
                }
            }
        }

        // as same as a matrix, create the weight for each entry of matrix
        foreach ($entries as $user1_id)
        {
            foreach ($entries as $user2_id)
            {
                // ignore same entry
                if ($user1_id == $user2_id)
                {
                    continue;
                }

                // ignore already friend
                if ($this->people_may_know_model->is_connection($user1_id, $user2_id) === true)
                {
                    continue;
                }

                // comment because separate connection directions (ver2)
                // if ($user1_id > $user2_id) { continue; }

                // create the searching chain for avoid dupplicating
                $chain = array();
                $chain[] = $user1_id;
                $chain[] = $user2_id;
                $weight	= $this->get_connection_weight($user1_id, $user2_id, $this->get_max_level(), $chain);
                // $data['content'][] =  "user1_id=$user1_id -- user2_id=$user2_id --> weight=$weight";

                // only save weight > 0
                if ($weight > 0)
                {
                    $this->people_may_know_model->insert_similarity($user1_id, $user2_id, $weight);
                }
            }
        }
        $data['content'][] = "General similarity table sucessfully";
        $data['content'][] = "Memory peak usage: " . memory_get_peak_usage();

        // for next time do not execute due to increasemental (ver1.5)
        // set all similarity as 1 to avoid next time running (increasemental mode)
        $this->people_may_know_model->set_connection_similarity();

        $data['title'] = "Welcome to People You May Know";
        $this->load->view('recommendations/gen_similarity_finish', $data);
    }


}

?>
