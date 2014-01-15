<?php

class Statsmodel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    protected function get()
    {
        return $this->db
               ->select('COUNT('.$this->count.') as qnty, `'.$this->time.'` as time')
               ->from($this->table)
               ->group_by('EXTRACT(DAY FROM `'.$this->time.'`)')
               ->order_by('`'.$this->time.'` ASC')
               ->where('`'.$this->time.'` >=', $this->time_range[0])
               ->where('`'.$this->time.'` <=', $this->time_range[1])
               ->get()
               ->result_array();
    }

    /*
    ** @time_range - an array of start and end dates: array('YYYY-MM-DD HH:MM:SS', 'YYYY-MM-DD HH:MM:SS');
    */
    function get_likes($time_range)
    {
        $this->table = 'likes';
        $this->count = 'like_id';
        $this->time = 'time';
        $this->time_range = $time_range;
        $data = $this->get();
        return $data;
    }

    /*
    ** @time_range - an array of start and end dates: array('YYYY-MM-DD HH:MM:SS', 'YYYY-MM-DD HH:MM:SS');
    */
    function get_links($time_range)
    {
        $this->table = 'links';
        $this->count = 'link_id';
        $this->time = 'time';
        $this->time_range = $time_range;
        $data = $this->get();
        return $data;
    }

    /*
    ** @time_range - an array of start and end dates: array('YYYY-MM-DD HH:MM:SS', 'YYYY-MM-DD HH:MM:SS');
    */
    function get_users($time_range)
    {
        $this->table = 'users';
        $this->count = 'id';
        $this->time = 'sign_up_date';
        $this->time_range = $time_range;
        $data = $this->get();
        return $data;
    }

    /*
    ** @time_range - an array of start and end dates: array('YYYY-MM-DD HH:MM:SS', 'YYYY-MM-DD HH:MM:SS');
    */
    function get_shares($time_range)
    {
        $this->table = 'newsfeed';
        $this->count = 'newsfeed_id';
        $this->time = 'time';
        $this->time_range = $time_range;
        $data = $this->get();
        return $data;
    }

    /*
    ** @time_range - an array of start and end dates: array('YYYY-MM-DD HH:MM:SS', 'YYYY-MM-DD HH:MM:SS');
    */
    function get_comments($time_range)
    {
        $this->table = 'comments';
        $this->count = 'comment_id';
        $this->time = 'time';
        $this->time_range = $time_range;
        $data = $this->get();
        return $data;
    }

    /*
    ** @time_range - an array of start and end dates: array('YYYY-MM-DD HH:MM:SS', 'YYYY-MM-DD HH:MM:SS');
    */
    function get_notifications($time_range)
    {
        $this->table = 'notifications';
        $this->count = 'id';
        $this->time = 'time';
        $this->time_range = $time_range;
        $data = $this->get();
        return $data;
    }

    function get_active_users_num()
    {
        return $this->db
               ->select('time')
               ->where('time >', date('Y-m-d H:i:s', strtotime('-1 month')))
               ->group_by('user_id_from')
               ->get('links')
               ->result_array();
    }

    function get_fb_users_num()
    {
        return $this->db->from('users')->where('fb_id !=', 0)->count_all_results();
    }

    function get_tw_users_num()
    {
        return $this->db->from('users')->where('twitter_id !=', 0)->count_all_results();
    }

    function get_unique_domains()
    {
        $domains = array();
        $links = $this->db->select('link,source')->get('links')->result_array();
        foreach($links as $link)
        {
            if($link['source'])
            {
                if(!in_array($link['source'], array_keys($domains))) $domains[$link['source']] = 1;
                else $domains[$link['source']]++;
            }
            /*
            if($link['link'] && preg_match('/^http/i', $link['link'])) {
            	$host = parse_url($link['link']);
            	$host = str_replace('www.', '', $host['host']);

            	if(!in_array($host, array_keys($domains))) $domains[$host] = 1; else $domains[$host]++;
            }
            */
        }
        array_multisort($domains, SORT_DESC, array_values($domains));
        return $domains;
    }

    function get_avg_links_per_folder()
    {
        return $this->db
               ->query('SELECT AVG(`newsfeeds`.`num`) AS avarage
                       FROM(
                       		SELECT COUNT(newsfeed_id) as num FROM `newsfeed` GROUP BY `folder_id`
                       ) AS `newsfeeds`')
               ->result_array();
    }

    function get_interest_count()
    {
        return $this->db
               ->query('SELECT COUNT(page_id) AS count FROM `pages`')
               ->result_array();
    }

    function get_most_collections_links()
    {
        return $this->db
               ->select('COUNT(newsfeed_id) as quantity, folder.folder_name')
               ->from('newsfeed')
               ->join('folder', 'folder.folder_id = newsfeed.folder_id')
               ->group_by('newsfeed.folder_id')
               ->order_by('quantity', 'DESC')
               ->limit(10)
               ->get()
               ->result_array();
    }

    function get_users_with_most_shares()
    {
        return $this->db
               ->select('COUNT(newsfeed.newsfeed_id) as quantity, users.id, users.first_name, users.last_name, users.uri_name')
               ->from('newsfeed')
               ->join('users', 'users.id = newsfeed.user_id_from')
               ->group_by('newsfeed.user_id_from')
               ->order_by('quantity', 'DESC')
               ->get()
               ->result_array();
    }

}