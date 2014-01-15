<?php
class Search_controller_test extends Web_Test_Case
{

    /*
    **	Check drops
    */
    public function disabled_test_drops()
    {
        $search_query = "fun";
        $search_limit = 16;

        $this->login();

        $search_result = $this->get('search?q='.$search_query);
        if(strlen($search_query) > 3)
        {
            $query = "
                     SELECT
                     photos.photo_id as activity_id, 'photo' as type,
                     MATCH(caption) AGAINST ('$search_query') as relevance
                     FROM photos WHERE
                     MATCH(caption) AGAINST('$search_query')
                     UNION SELECT
                     links.link_id as activty_id, 'link' as type,
                     MATCH(title, text, content_plain) AGAINST ('$search_query') as relevance
                     FROM links WHERE
                     MATCH (title, text, content_plain) AGAINST('$search_query')
                     ORDER BY relevance DESC
                     LIMIT $search_limit
                     OFFSET 0 ";
        }
        else
        {
            $query = "
                     SELECT
                     photos.photo_id as activity_id, 'photo' as type,
                     ((CASE WHEN `caption` LIKE '$search_query%' THEN 2 ELSE 0 END)) AS relevance
                     FROM photos WHERE
                     caption LIKE '$search_query%'
                     UNION SELECT
                     links.link_id as activty_id, 'link' as type,
                     ((CASE WHEN `title` LIKE '$search_query%' THEN 1 ELSE 0 END) + (CASE WHEN `text` LIKE '$search_query%' THEN 1 ELSE 0 END)) AS relevance
                     FROM links WHERE
                     title LIKE '$search_query%' OR text LIKE '$search_query%'
                     ORDER BY relevance DESC
                     LIMIT $search_limit
                     OFFSET 0 ";
        }
        $drops = $this->db_interface->db->query($query)->result();
        if($drops)
        {
            $drops_count = 0;
            foreach( $drops as $row )
            {
                $drop = $this->db_interface->db
                        ->select('newsfeed_id')
                        ->where('type', $row->type)
                        ->where('activity_id', $row->activity_id)
                        ->get('newsfeed')
                        ->row();
                if( preg_match('#data-newsfeed_id="'.$drop->newsfeed_id.'"#msi', $search_result) )
                {
                    $drops_count++;
                }
            }
            $this->assertEqual(count($drops), $drops_count, "Number of drops found not match, db:".count($drops)." page:$drops_count");
        }
        else
        {
            $this->assertPattern('<div class="no_results">No Results</div>');
        }

        $this->logout();
    }


    /*
    **	Check collections
    */
    public function disabled_test_collections()
    {
        $search_query = "fun";
        $search_limit = 15;

        $this->login();

        $search_result = $this->get('search/collections?q='.$search_query);
        $folder= $this->db_interface->db->select('folder_name')->like('folder_name', $search_query)->limit($search_limit)->get('folder')->result();
        if($folder)
        {
            $folders_count = 0;
            foreach($folder as $row)
            {
                if( preg_match('#<span class="folder_name">'.$row->folder_name.'</span>#msi', $search_result) )
                {
                    $folders_count++;
                }
            }
            $this->assertEqual(count($folder), $folders_count, "Number of stories found not match, db:".count($folder)." page:$folders_count");
        }
        else
        {
            $this->assertPattern('<div class="no_results">No Results</div>');
        }

        $this->logout();
    }


    /*
    **	Check topics
    */
    public function disabled_test_topics()
    {
        $search_query = "fun";
        $search_limit = 26;

        $this->login();

        $search_result = $this->get('search/topics?q='.$search_query);
        $topics = $this->db_interface->db->select('topic_name')->like('topic_name', $search_query)->limit($search_limit)->get('topics')->result();
        if($topics)
        {
            $topics_count = 0;
            foreach($topics as $row)
            {
                if( preg_match('#<a href=".*">'.$row->topic_name.'</a>#msi', $search_result) )
                {
                    $topics_count++;
                }
            }
            $this->assertEqual(count($topics), $topics_count, "Number of topics found not match, db:".count($topics)." page:$topics_count");
        }
        else
        {
            $this->assertPattern('<div class="no_results">No Results</div>');
        }

        $this->logout();
    }


    /*
    **	Check people
    */
    public function disabled_test_people()
    {
        $search_query = "fun";
        $search_limit = 30;

        $this->login();

        $search_result = $this->get('search/people?q='.$search_query);
        $search_where = "(CONCAT(first_name, ' ' ,last_name) LIKE '".$search_query."%' OR Email LIKE '".$search_query."%')";
        $people = $this->db_interface->db->select('first_name, last_name, uri_name')->where($search_where)->limit($search_limit)->get('users')->result();
        if(count($people) > 0)
        {
            $people_count = 0;
            foreach($people as $row)
            {
                $url = $this->config['base_url'].'collections/'.$row->uri_name;
                if( preg_match('#<a href="'.$url.'">[[:space:]]*'.$row->first_name.' '.$row->last_name.'[[:space:]]*</a>#msi', $search_result) )
                {
                    $people_count++;
                }
            }
            $this->assertEqual(count($people), $people_count, "Number of people found not match, db:".count($people)." page:$people_count");
        }
        else
        {
            $this->assertPattern('<div class="no_results">No Results</div>');
        }

        $this->logout();
    }

    function search_users($uid, $connections, $connected, $search_query)
    {

        $this->db_interface->db
        ->select('id, gender, first_name, last_name, uri_name, thumbnail')
        ->where('id !=' , $uid)
        ->where('status', '1');
        if($connected)
        {
            $where2 = "id IN(".$connections.") ";
        }
        else
        {
            $where2 = "id NOT IN(".$connections.") ";
        }
        $this->db_interface->db->where($where2);

        $where = "(CONCAT(first_name, ' ' ,last_name) LIKE '".$search_query."%' OR Email LIKE '".$search_query."%')";
        $this->db_interface->db->where($where);

        $query = $this->db_interface->db->get('users');
        return $query->result();

    }



}
