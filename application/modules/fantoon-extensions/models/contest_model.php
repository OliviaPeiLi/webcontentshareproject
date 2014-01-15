<?php

class Contest_model extends MY_Model
{
	
	//Relations
	protected $belongs_to = array('user');
	
	protected $has_many = array(
								'folders'
							);

	//Behaviors
    public $behaviors = array(
                            'uploadable' => array(
                                'logo' => array(
                                    'folder' => 'contests',
                                    'default_image' => 'https://s3.amazonaws.com/fantoon-dev/users/default/blue_thumb.png',
                                    'upload_to_s3' => true,
                                    'thumbnails' => array(
                                        'thumb' => array( //side bar
                                            'width' => 430, 'height' => 60,
                                            'maintain_ratio' => true,
                                            'create_thumb' => true,
                                            'transform' => array('resize', 'crop')
                                        )
                                    )
                                )
                            ),
                            'countable' => array(
                            	array(
									'table' => 'user_stats',
									'relation' => array('user_id' => 'user_id'),
									'fields' => array('contests_count'),
								)
                            ),
                        );
                        
    protected $validate = array(
								'url' => array(
									'label' => 'Contest',
									'rules' => 'required|max_length[30]|special_char_space|unique_username'
								),
						);

    protected $categories = array();
    protected $is_open = false;
    
    /* ==================== Per Item Funcs =================== */
    
    public function get_shares($contest) {
    	return (int) $this->db->query("
    		SELECT SUM(newsfeed.fb_share_count+newsfeed.twitter_share_count+newsfeed.pinterest_share_count+newsfeed.gplus_share_count+newsfeed.linkedin_share_count) as share_count
    		FROM newsfeed
    		JOIN folder ON (newsfeed.folder_id = folder.folder_id AND folder.contest_id = $contest->id) 
    	")->first_row()->share_count;
    }
    
    public function get_views($contest) {
    	$newsfeed_hits = (int) $this->db->query("
    		SELECT SUM(newsfeed.hits) as hits
    		FROM newsfeed
    		JOIN folder ON (newsfeed.folder_id = folder.folder_id AND folder.contest_id = $contest->id)
    	")->first_row()->hits;
    	
    	$folder_hits = (int) $this->db->query("
    		SELECT SUM(hits) as hits FROM folder WHERE contest_id = $contest->id
    	")->first_row()->hits;
    	return $newsfeed_hits + $folder_hits;
    }
    
    /* ============== Events ======================= */
    
    protected function _run_before_create($data) {
    	if (!isset($data['user_id'])) $data['user_id'] = get_instance()->user->id;
    	if (!$data['categories']) {
    		$data['categories'] = array(array($data['url']));
    	}
    	$data['created_at'] = date('Y-m-d H:i:s');
    	return parent::_run_before_create($data);
    }
    
	protected function _run_before_set($data) {
		if (!isset($data['name'])) {
			$data['name'] = ucfirst(str_replace(array('-','_'), ' ', $data['url']));
		}
		if (isset($data['categories'])) {
			if (count($data['categories'][0]) == 1) {
				$data['categories'][0][0] = $data['url'];
				$data['is_simple'] = 1;
			}
			$this->categories = $data['categories'];
			$this->is_open = $data['is_open'];
		}
		unset($data['categories'], $data['is_open']);
		return parent::_run_before_set($data);
	}
	
	protected function _run_after_set($obj) {
		$ret = parent::_run_after_set($obj);
		if ($this->categories) {
			foreach ($this->categories as $id => $name) {
				if (!$id) {
					foreach ($name as $category) {
						$this->folder_model->insert(array(
							'folder_name' => $category,
							'type' => 2,
							'contest_id' => $obj['id'], 
							'user_id' => $obj['user_id'],
							'is_open' => $this->is_open,
							'sort_by' => 2
						), true);
					}
				}
			}
		}
		return $ret;
	}

	/* ======================= Filters ======================== */
	
	public function filter_user($user_id) {
		$this->db->where('user_id', $user_id);
		return $this;
	}
}