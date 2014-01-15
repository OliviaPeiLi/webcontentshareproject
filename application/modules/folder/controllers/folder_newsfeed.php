<?php
/**
 * List collection newsfeeds
 * @author radilr
 *
 */
require_once 'application/modules/newsfeed/controllers/newsfeed.php';

class Folder_newsfeed extends Newsfeed {

	protected $default_view = 'tile_new';
	protected $default_sort = 'position asc, newsfeed_id desc';
	
	public function index($folder_id=null, $filter=null) {
		if ($this->is_mod_enabled('design_ugc')) $this->default_view = 'ugc';
        $view = $this->input->get('view', true, $this->default_view);
        $per_page = $this->config->item($view.'_newsfeed_limit', 10, true);
        $folder = $this->folder_model->get($folder_id);
        
        // sort_by = 0 - time ; sort_by = 1 - up_count; 2 - sxsw shares
        //RR - may be should be moved to the model
		if ($folder->type && in_array($folder->contest->url, array('fndemo','crowdfunderio','cite'))) {
        	$this->default_sort = 'points';
        } elseif ($folder->sort_by == 1) {
            $this->default_sort = 'up_count';
        } elseif ($folder->sort_by == 2) { //sxsw
        	$this->default_sort = 'share_count';
        }
        
        $this->default_filter = $filter;

        if ($folder->type) {
        	$this->default_view = $this->folder_model->types[$folder->type];
        }
        
        $newsfeeds = $this->get_model($per_page, $folder)->folder_id_in($folder_id);
		
        if ($folder->type && $folder->filters && $this->input->get('filter')) {
        	$sub_type = 0;
        	foreach ($folder->filters as $key=>$filter) if ($this->input->get('filter') == $filter) $sub_type = $key+1;
        	if ($sub_type > -1) {
        		$newsfeeds->_set_where(array('sub_type', $sub_type));
        	}
        }
        
		if ($folder->type) {
			//RR - was used in sxsw
			/*$newsfeeds->select_fields(array("(SELECT SUM(fb_share_count+pinterest_share_count+twitter_share_count+gplus_share_count+linkedin_share_count)
											FROM newsfeed as n1 
											WHERE n1.user_id_from = newsfeed.user_id_from
												AND n1.title = newsfeed.title) 
											as share_count"));*/
		}
		return $this->output($newsfeeds, $folder_id);
    }
    
    protected function output($newsfeeds, $folder_id) {
        
    	$url =  '/newsfeed/collection/'.$folder_id;
    	$get = array();
		if ($this->default_filter) $get[] = 'type='.$this->default_filter;
		
    	return parent::output($newsfeeds, $url, $get);
    }
    
    protected function get_model($per_page, $folder) {
    	$model = parent::get_model($per_page);
    	if ($folder->type && in_array($folder->contest->url, array('fndemo','crowdfunderio','cite'))) {
    		$model = $model->select_fields(array(
    			"(
    				COALESCE((SELECT SUM(views) FROM newsfeed_referrals WHERE newsfeed_id = newsfeed.newsfeed_id),0)
    				+newsfeed.uniqview+(newsfeed.twitter_share_count*10)
    			) as points"
    		)); 
    	}
    	return $model;
    }

}