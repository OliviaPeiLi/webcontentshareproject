<?php

class Folder_hashtag_model extends MY_Model {
	
	protected $belongs_to = array(
								'folder', 'hashtag'
							);
}