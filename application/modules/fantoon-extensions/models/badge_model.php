<?php

class Badge_model extends MY_Model {
	//Relations
	protected $has_many = array(
							  'badge_users'
						  );

	//Behaviors
	public $behaviors = array(
							'uploadable' => array(
								'img' => array(
									'folder' => 'badges',
									'default_image' => '/images/load_clip.png',
									'upload_to_s3' => true,
									'disable_dimesion' => true,
									'thumbnails' => array(
										'small' => array( // for grabbed data from fb/twtr to display in a signup popup
											'width' => 50,
											'height' => 999999999,//200,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize')//, 'crop')
										),
										'square' => array( // for link popup right side "Originally dropped by", "Dropped via"
											'width' => 50,
											'height' => 50,
											'maintain_ratio' => true,
											'create_thumb' => true,
											'transform' => array('resize', 'crop')
										)
									)
								)
							)
						);


}