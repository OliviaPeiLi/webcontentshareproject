<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Usergraphic_old
{
	
	/**
    * Usergraphic Class
    *
    * Provides methods to build nested tree for interests
    *
    **/

    private $CI;
    
    public function __construct()
    {
        $this->CI =& get_instance();
        
        // load database helper; configurations in config/database.php
        $this->CI->load->database();       
    }
    
    public function generateTree($userId)
    {
    	$limit = '3';								//the limit of the recommend page under the user's interest' page
    	$interests_data = $this->getUserAllInterests($userId);
    	$user_info = $this->UserInfo($userId);
    	foreach ($interests_data as $k=>$v)
    	{
    		$check_list[] = $v['page_id'];
    	}
    	foreach ($interests_data as $k=>$v)
    	{
    		$interests_data[$k]['child'] = $this->getSimilarPages($v['page_id'], $v['interest_id'], $check_list, $limit);
    		$interests[$v['interest_id']][] = $interests_data[$k];
    	}
    	
    	$all_nodes = count($interests)+count($interests_data)+3*count($interests_data);

    	$main_left = '1';
    	$main_right = 2*$all_nodes+1;
    	$main_node_info = array('id'=>$userId, 'caption'=>$user_info['first_name'].' '.$user_info['last_name'], 'left'=>$main_left, 'right'=>$main_right, 'level'=>'0', 'name'=>$main_left.':'.$main_right, 'size'=>'size0', 'link'=>'/library/'.$user_info['uri_name'].'/'.$userId, 'img'=>s3_url().$user_info['thumbnail']);
    	$main_node_name = $main_left.':'.$main_right;
    	$tree['nodes'][$main_node_name] = $main_node_info;
    	
    	$start = '2';								//the main node should be user's node start from '0'
    	foreach ($interests as $k=>$v)
    	{
    		$num = count($interests[$k])+3;										//need to be check, there may be a bug!!!!!!!!!!!!!!!!!!!!!!!!!
    		$all_num = $num*3;
    		$category_left = $start;
    		$category_right = $start+2*$all_num+1;
    		$node_info = array('id'=>$k, 'caption'=>$v[0]['type'], 'left'=>$category_left, 'right'=>$category_right, 'level'=>'1', 'name'=>$category_left.':'.$category_right, 'size'=>'size1', 'link'=>'', 'img'=>base_url().'images/category_icons/'.$v[0]['type'].'.png');
    		$category_node_name = $category_left.':'.$category_right;
    		$tree['nodes'][$category_node_name] = $node_info;
    		$edge_info = array('from'=>$main_node_name, 'to'=>$category_node_name, 'color'=>'#FF9999', 'type'=>'arrow');
    		$tree['edges'][$main_node_name.'-'.$category_node_name] = $edge_info;
    		
    		$page_start = $category_left+1;
    		foreach ($v as $page)
    		{
    			if($page['thumbnail'] == '')
    			{
    				$page['thumbnail'] = "pages/default/defaultInterest.png";
    			}
    			$node_left = $page_start;
    			$node_right = $page_start+2*$limit+1;
    			$node_info = array('id'=>$page['page_id'], 'caption'=>$page['page_name'], 'left'=>$node_left, 'right'=>$node_right, 'level'=>'2', 'name'=>$node_left.':'.$node_right, 'size'=>'size2', 'link'=>'/interests/'.$page['uri_name'].'/'.$page['page_id'], 'img'=>s3_url().$page['thumbnail']);
    			$node_name = $node_left.':'.$node_right;
    			$tree['nodes'][$node_name] = $node_info;
    			$edge_info = array('from'=>$category_node_name, 'to'=>$node_name, 'color'=>'#9999FF', 'type'=>'arrow');
    			$tree['edges'][$category_node_name.'-'.$node_name] = $edge_info;
    			
    			$child_start = $page_start+1;
    			foreach($page['child'] as $child_page)
    			{
    				if($child_page['thumbnail'] == '')
	    			{
	    				$child_page['thumbnail'] = "pages/default/defaultInterest.png";
	    			}
	    			$child_left = $child_start;
	    			$child_right = $child_start+1;
	    			$node_info = array('id'=>$child_page['page_id'], 'caption'=>$child_page['page_name'], 'left'=>$child_left, 'right'=>$child_right, 'level'=>'3', 'name'=>$child_left.':'.$child_right, 'size'=>'size3', 'link'=>'/interests/'.$child_page['uri_name'].'/'.$child_page['page_id'], 'img'=>s3_url().$child_page['thumbnail']);
	    			$child_node_name = $child_left.':'.$child_right;
	    			$tree['nodes'][$child_node_name] = $node_info;
	    			$edge_info = array('from'=>$node_name, 'to'=>$child_node_name, 'color'=>'#9999FF', 'type'=>'arrow');
	    			$tree['edges'][$node_name.'-'.$child_node_name] = $edge_info;
	    			
	    			$child_start = $child_right+1;
    			}
    			
    			$page_start = $node_right+1;
    		}
    		
    		$start = $category_right+1;
    	}
    	
    	return $tree;
    }
    
    public function UserInfo($userId)
    {
    	$this->CI->db->select('first_name, last_name, uri_name, thumbnail, gender');
    	$this->CI->db->from('users');
    	$this->CI->db->where('id', $userId);
    	$query = $this->CI->db->get();
    	$row = $query->result_array();
    	if($row[0]['thumbnail'] == '')
    	{
    		if($row[0]['gender'] == 'm')
    		{
    			$row[0]['thumbnail'] = "users/default/defaultMale.png";
    		}
    		else
    		{
    			$row[0]['thumbnail'] = "users/default/defaultFemale.png";
    		}
    	}
    	return $row[0];
    }
    
    public function getUserAllInterests($userId)
    {
    	$this->CI->db->select('page_users.page_id, page_name, uri_name, official_url, thumbnail, interest_id, interest_category.type');
		$this->CI->db->from('page_users');
		$this->CI->db->where('page_users.user_id', $userId);
		$this->CI->db->join('pages', 'pages.page_id = page_users.page_id');	
		$this->CI->db->join('interest_category', 'interest_category.id = interest_id');	
		$this->CI->db->order_by('interest_id','ASC');
		$query = $this->CI->db->get();
		$row = $query->result_array();
		return $row;
    }
    
    public function getSimilarPages($pageId, $interest_id, $check_list, $limit)
    {
    	$this->CI->db->select('page2_id, page_name, uri_name, official_url, thumbnail, interest_id, interest_category.type');
    	$this->CI->db->from('pages_similarity');
    	$this->CI->db->join('pages', 'pages.page_id = page2_id');	
		$this->CI->db->join('interest_category', 'interest_category.id = interest_id');
		$this->CI->db->where(array('page1_id'=>$pageId, 'interest_id'=>$interest_id));
		$this->CI->db->where_not_in('page2_id', $check_list);
		$this->CI->db->order_by('similarity', 'random');					//order_by should be by DESC, random just for demo
		$this->CI->db->limit($limit);
		$query = $this->CI->db->get();
		$row = $query->result_array();
		return $row;
    }
	
	
}