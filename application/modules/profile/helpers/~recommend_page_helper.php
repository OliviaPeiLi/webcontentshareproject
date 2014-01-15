<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    function getRecommendations($userId)
    {
    	$CI =& get_instance();
     	$CI->load->library('Recommender');
        $rec = new Recommender();

        // params
        $result = $rec->getRecommendations($userId);
		return $result;        		
    }
    
    function get_people($userId)
    {
    
    	$CI =& get_instance();
     	$CI->load->library('peoplemayknow');
        $ppl = new Peoplemayknow();

        // params
        $result = $ppl->get_people($userId);
        if(!empty($result))
        {
        	$new_result = people_may_know($result['people']);
			return $new_result;        		
		}
    }
    
    function people_may_know($users)
    {
    	$CI =& get_instance();
    	$CI->load->database();
    	for($i=0;$i<3;$i++)
    	{
    		$CI->db->select('id, first_name, last_name, avatar, thumbnail, gender, uri_name');
    		$CI->db->from('users');
    		$CI->db->where('id', $users[$i]);
    		$query = $CI->db->get();
    		$row = $query->result_array();
    		$data[$i] = @$row[0];
    	}
    	return $data;
    }

