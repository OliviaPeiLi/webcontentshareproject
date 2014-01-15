<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Newsfeed extends ADMIN
{

	//protected $model = 'alpha_user_model';
	protected $model = 'newsfeed_model';

	protected $list_fields = array(
								'newsfeed_id'	=>'primary_key',
								'img'			=>'image_bigsquare',
								/*'type' 		=> 'string',*/
								'link_type' 	=> 'string',
								'user_from' 	=> 'belongs_to',
								'folder' 		=> 'belongs_to',
								/*'data'		=>'string'*/
								'link_url' 		=> 'link',
								'time' 			=> 'time',
							 );
	 protected $list_actions = array(
	 							'edit'=>'Edit',
	 							'delete'=>'Delete',
	 							'reDownload' => array(
	 								'condition' => 'link_type==image',
	 								'class' => 'nyroModal',
	 							),
	 							'screenshot'=>array(
	 								'condition' => 'link_type!=text && link_type!=image',
	 								'class' => 'nyroModal',
	   							),
	 							'reCache'=>array(
	 								'condition' => 'link_type==content',
	 								'class' => 'nyroModal',
	   							),
	 						);
	 protected $form_fields = array(
	 							array(
									 'Edit',
									 'img'=>'img',
									 'img_tile'=>'img',
									 'link_type' => 'string',
									 'user_id_from' => 'string',
									 'folder_id' => 'string',
									 'link_url' => 'url',
									 'title' => 'string',
									 'description' => 'text',
									 'activity[link][media]' => 'external_text',
									 'activity[link][content]' => 'external_text',
									 ),
								array(
									 'Redrop',
									 'newsfeed_id'=>'primary_key',
									 'folder_id'=>'string',
									 'redrop'=>'true_value'
								)
							 );
	 protected $filters = array(
								'newsfeed_id' => 'primary_key',
	 							'user_id_from' => 'number',
	 							'folder_id' => 'number',
	 							'link_url' => 'string',
	 							'link_type' => array(
	 									'' => 'NONE',
	 									'content' => 'content',
	 									'embed' => 'embed',
	 									'html' => 'html',
	 									'image' => 'image',
	 									'text' => 'text',
	 								)
							 );
							 
	public function create_post() {
    	$post = $this->input->post();
    	$post['activity']['link']['media'] = $_REQUEST['activity']['link']['media'];
    	$post['activity']['link']['content'] = $_REQUEST['activity']['link']['content'];
    	
    	foreach($_FILES as $field=>$value) { //Dont delete the file is no file is selected on submit
	    	if($value['name'] == ''){
		    	unset($_FILES[$field]);
	    	}
    	}
    	$post['link_url'] = str_replace(' ','',$post['link_url']);
    	
    	if($this->folder_model->get($post['folder_id'])->type){
	    	$post['hashtag_id'] = $this->hashtag_model->get_by(array('hashtag'=>'_hash_WinSXSW'))->id;
	    	$post['description'] = $post['description'].' _hash_WinSXSW';
    	}
    	    	
    	$id = $this->{$this->model}->insert($post);
    	$model_name = $this->model();   	
    	$this->item($this->$model_name->get($id)); //Refresh item with new data
    	
		if (isset($_FILES['img_tile'])) { //Update the logo only if its selected
        	$file_name = str_replace(Url_helper::s3_url(), '', $this->item()->img_tile); //get the folder/file_name from uploadable config
        	$this->load->library('S3'); 
    		if ( ! S3::putObject(S3::inputFile($_FILES["img_tile"]["tmp_name"]), Url_helper::s3_bucket(), $file_name, S3::ACL_PUBLIC_READ) ) {
				die('Error uploading to S3');
    		}
        }
    	
    	Url_helper::redirect('/admin/'.strtolower(get_class($this)).'/'.$id);
    }
    
    public function item_post() {
    	$post = $this->input->post();
    	
    	if($post['redrop'] == 'true'){
    		$folder = $this->folder_model->get($post['folder_id']);
	    	$new_newsfeed_id = $this->item()->redrop($folder->user_id, $folder->folder_id);
	    	Url_helper::redirect('/admin/'.strtolower(get_class($this)).'/'.$new_newsfeed_id);
    	}else{
	    	$post['activity']['link']['media'] = $_REQUEST['activity']['link']['media'];
	    	$post['activity']['link']['content'] = $_REQUEST['activity']['link']['content'];
	    	
	    	foreach($_FILES as $field=>$value) { //Dont delete the file is no file is selected on submit
		    	if($value['name'] == ''){
			    	unset($_FILES[$field]);
		    	}
	    	}
	    	$post['link_url'] = str_replace(' ','',$post['link_url']);
	        $this->item()->update($post);
	        $this->item($this->item()->_model->get($this->item()->primary_key())); //Refresh item with new data
	        
	    	if (isset($_FILES['img_tile'])) { //Update the logo only if its selected
	        	$file_name = str_replace(Url_helper::s3_url(), '', $this->item()->img_tile); //get the folder/file_name from uploadable config
	        	$this->load->library('S3'); 
	    		if ( ! S3::putObject(S3::inputFile($_FILES["img_tile"]["tmp_name"]), Url_helper::s3_bucket(), $file_name, S3::ACL_PUBLIC_READ) ) {
					die('Error uploading to S3');
	    		}
	        }
        }
        
        Url_helper::redirect('/admin/'.strtolower(get_class($this)).'/'.$this->item()->primary_key());
        //$this->response($this->item(), 'form');
    }		 
							 
	protected function filter($items) {
		if ($this->user->role != 2)
		{
			$items->_set_where(array("(user_id_from = {$this->user->id})"));
		}
		return parent::filter($items);
	}

	protected function check_access($item) {
		if ($this->user->role != 2)
		{
			if ($item->user_id_from != $this->user->id) return false;
		}
		return parent::check_access($item);
	}
	
	public function item_recache() {
		$item = $this->item();
		if ($item->link_type != 'content') die('cannot recache non content drops');
		echo "reCaching page<br/>";
		$this->load->model('link_model');
		$job_id = $this->link_model->cache_page($item);
		if ($job_id) {
			echo "Waiting beasntalk job ($job_id)...";
		} else {
  	  		echo "Done";
	  	}
	}
	
	public function item_redownload() {
		return $this->item_screenshot();
	}
	
	public function item_screenshot() {
		$item = $this->item();
		if (!$item->link_url) {
			die("Error: The current newsfeed doesnt have a link");
		} else {
			echo 'Link <a href="'.$item->link_url.'" target="_blank">'.substr($item->link_url, 0, 40).'...</a><br/>';
		}
		if ($item->link_type == 'embed') {
			echo "Processing video: </br>";
			$scraper = $this->load->library('scraper');
			$driver = $scraper->driver($item->link_url);
			echo "Driver: ".get_class($driver)."<br/>";
			$images = $driver->get_images();
			if (isset($images['status']) && !$images['status']) {
				print_r($images);
				return ;
			}
			if (!isset($images[0])) {
				die('No image found');
			}
			$item->update(array('img'=>$images[0]['src']));
			echo '<img src="'.$item->img_tile.'"/><br/>';
			echo "Done";
		} else if ($item->link_type == 'image') {
			echo "Processing image</br>";
			if (!$item->activity->source_img) {
				die('Source link not found');
			}
			echo 'Source <a href="'.$item->activity->source_img.'" target="_blank">'.substr($item->activity->source_img, 0, 40).'...</a><br/>';
			$item->update(array('img'=>$item->activity->source_img));
			echo '<img src="'.$item->img_tile.'"/><br/>';
			echo "Done";
		} else if ($item->link_type == 'text') {
			echo "Processing text</br>";
			echo "The text type doesnt have a thumbnail";
		} else if ($item->link_type == 'content') {
			echo "Processing bookmarked page<br/>";
			$this->load->model('link_model');
			$job_id = $this->link_model->generate_screenshot($item);
			if (! $job_id) {
				$item = $this->newsfeed_model->get($item->newsfeed_id);
				echo '<img src="'.$item->img_tile.'"/><br/>';
				echo "Done";
			} else {
				echo "Wating for beasntalk job ($job_id)...";
			}
		} else if ($item->link_type == 'html') {
			echo "Processing html content<br/>";
			$this->load->model('link_model');
			$job_id = $this->link_model->generate_snapshot($item);
			if (! $job_id) {
				$item = $this->newsfeed_model->get($item->newsfeed_id);
				echo '<img src="'.$item->img_tile.'"/><br/>';
				echo "Done";
			} else {
				echo "Wating for beasntalk job...".$job_id;
			}
		} else {
			die("Error: link_type(".$item->link_type.") not recognized");
		}
	}

}