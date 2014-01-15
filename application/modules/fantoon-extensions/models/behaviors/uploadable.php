<?php
/**
 *
 * Uploadable behavior - Gets the $_FILES from multipart form, uploads the files and generates thumbs from
 * the specified config in each model.
 * @author radilr
 * @usage
 * 1. in application/uploads.php set $config['uploads']['path']
 *
 * 2. in the model load the behavior as follows:
 *  //Behaviors
 *	protected $behaviors = array(
 *		'uploadable' => array(				//This behavior name
 *			'avatar' => array(				//Database field name which is for images
 *				'folder' => 'pages',		  //Folder in which the images will be uploaded to
 *				'upload_to_s3' => true,	   //Bool - if true will be uploaded to amazon
 *				'thumbnails' => array(		//Thumbnails array
 *					'thumb' => array(		 //Thumnail name 1
 *						'width' => 500,				  //Config to be used by img lib
 *						'height' => 500,
 *						'maintain_ratio' => true,
 *						'transform' => array('resize')
 *					),
 *					'small' => array(		  //Thumbnail name 2
 *						'width' => 80, 'height' => 80,	//Config to be used by img lib
 *						'maintain_rato' => true,
 *						'transform' => array('resize', 'crop')
 *					)
 *				)
 *			)
 *		)
 *	);
 *
 * 3. To upload file in the view use standard form with upload filed. The name of the field must be the same as
 *	the database field. Or you can also use text or textarea for upload by url.
 *	   (if you use additinal js plugn for axaj upload its also ok just make sure you are setting the correct name)
 *
 * 4. In the controller
 *	   public function edit($id) {
 *		   if ($this->input->post('save)) {
 *			   $this->sample_model->update($id, array('my_upd_field'=>$this->input->post('my_upd_field')) //upload by url
 *			   $this->sample_model->update($id, array('some'=>'data')) //when uploading using $_FILES array you dont need nothng more than setting the field in the view
 *		   }
 *	   }
 */
class Uploadable_Behavior extends Behavior
{
	private static $uploads_conf = array();
	
	public static function uploads_conf($key=null) {
		$ci = get_instance();
		$ci->load->config('uploads');
		$uploads_conf = $ci->config->item('uploads');
		return $key ? $uploads_conf[$key] : $uploads_conf;
	}
	/**
	 * If form data contains the field, uploads the file before the row is saved and sets the generated file name
	 * to be saved to the database.
	 */
	//bobef: #FD-2068 - the absense of an & before $data was causing problems on my localhost, although it was working on test.
	public static function _run_before_set(&$data, $config)
	{
		foreach ($config as $field=>$field_config)
		{
			//we do not process our uploaded images (loaders, default images etc.)
			//this will match both dev and production
			$field_config['default_image'] = self::get_default_image($data, $field_config['default_image']);
			if (isset($data[$field]) && $data[$field] == $field_config['default_image']) {
				continue;
			}
			if (isset($data[$field]) && $data[$field])
			{
				if (substr($data[$field], 0, 5) == 'data:') {
					$data[$field] = self::upload_from_base64($field, $_REQUEST[$field], $config);
				} else {
					$data[$field] = self::upload_from_url($field, $data[$field], $config);
				}
			}
			elseif (isset($_FILES[$field]) && $_FILES[$field])
			{
				$data[$field] = self::upload_file($field, $_FILES[$field], $config);
			}
			else
			{
				continue; //form submitted without a file.
			}
			//populate demension
			$original = $data[$field];
			if (isset($field_config['rename_original']) && $field_config['rename_original']) {
				$original = self::get_thumbname($data[$field],'original');
				rename(self::uploads_conf('path').$field_config['folder'].'/'.$data[$field], self::uploads_conf('path').$field_config['folder'].'/'.$original);
			}
			if(isset($field_config['disable_dimesion']) && $field_config['disable_dimesion']) continue;
			if (!isset($data[$field])) continue;
			
			if (isset($field_config['save_size']) && $field_config['save_size']) {
				list($width, $height, $type, $attr) = @getimagesize(self::uploads_conf('path').$field_config['folder'].'/'.$original);
				$data[$field.'_width'] = $width;
				$data[$field.'_height'] = $height;
			}
			
			if (isset($data['newsfeed_id']) && $data['newsfeed_id'] != "") {
				self::_set($field_config, $data, $field);
			} else {
				get_instance()->session->set_userdata('do_upload',true);
			}
		}
	}
	
	//bobef: #FD-2068 - the absense of an & before $data was causing problems on my localhost, although it was working on test.
	//need to be in after set because of the watermark (for the newsfeed_id)
	public static function _run_after_set(&$data, $config)
	{

		if (!get_instance()->session->userdata('do_upload')) return;
		get_instance()->session->set_userdata('do_upload',false);

		foreach ($config as $field=>$field_config) {
			$field_config['default_image'] = self::get_default_image($data, $field_config['default_image']);
			if ($data[$field] == $field_config['default_image']) {
				continue;
			}

			self::_set($field_config, $data, $field);
		}
	}
	
	private static function _set($field_config, $data, $field) {
		if (isset($field_config['upload_to_s3']) && $field_config['upload_to_s3'])
		{
			$original = $data[$field]; 
			if (isset($field_config['rename_original']) && $field_config['rename_original']) {
				$original = self::get_thumbname($data[$field], 'original');
			}
			self::upload_to_s3($original, $field_config['folder'], self::uploads_conf('path').$field_config['folder'].'/'.$original);
		}

		if (isset($field_config['thumbnails'])) {
			foreach($field_config['thumbnails'] as $thumb_name => $thumb_config) {
				$thumb_filename = self::get_thumbname($data[$field], $thumb_name);
				$thumb_source = self::uploads_conf('path').$field_config['folder'].'/'.$thumb_filename;
				
				//check if image is smaller than config, if smaller then there is no need for watermark
				if(isset($thumb_config['width'])){
					list($w, $h) = getimagesize(self::uploads_conf('path').$field_config['folder'].'/'.$thumb_filename);
					if($w < $thumb_config['width']){
						unset($thumb_config['transform']['watermark']);
					}
				}
				if (isset($thumb_config['transform']['watermark']) && strpos($data[$field], '.gif') === false) { //no watermark for gif		
					// var_dump($thumb_source, $data, $watermark_conf);
					if (is_int(key($thumb_config['transform']['watermark']))) {
						foreach ($thumb_config['transform']['watermark'] as $watermark_conf) {
							self::image_watermark($thumb_source, $data, $watermark_conf);
						}
					} else {
						self::image_watermark($thumb_source, $data, $thumb_config['transform']['watermark']);
					}
				}
				
				if (isset($field_config['upload_to_s3']) && $field_config['upload_to_s3']) {
					self::upload_to_s3($thumb_filename, $field_config['folder'], $thumb_source);
				}
			}
		}
	}
	
	/**
	 * Alias function for more comfort when using custom upload in the controllers
	 */
	public static function do_upload($data, $config)
	{
		//var_dump($data);
		call_user_func_array(array('Uploadable_Behavior', '_run_before_set'), array(&$data, $config));
		call_user_func_array(array('Uploadable_Behavior', '_run_after_set'), array(&$data, $config));
		$data = json_decode(json_encode($data)); //convertt to object
		call_user_func_array(array('Uploadable_Behavior', '_run_after_get'), array(&$data, $config));
		foreach ($data as $key=>$val) {
			if ($key[0] == '_') {
				$data->{substr($key, 1)} = $val;
			}
		}
		return $data;
	}

	/**
	 * Deletes the file after row is deleted to save disk space
	 */
	/*public static function _run_after_delete($obj, $config)
	{
	}*/

	/**
	 * Set the virtual fields for the thumbnails
	 */
	public static function _run_after_get($result, $config)
	{
		foreach ($config as $field=>$field_config)
		{
			if (!isset($result->$field)) continue; //When dropdown() or custom select() is executed then only id and name are returned no images and thumbs
			$field_config['default_image'] = self::get_default_image($result, $field_config['default_image']);
			//generates virtual field for each thumb so it can be used in the views
			if (isset($field_config['thumbnails'])) {
				foreach ($field_config['thumbnails'] as $thumb=>$thumb_config) {
					if (!$result->$field || strpos($field_config['default_image'], $result->$field) !== false) {
						$result-> {'_'.$field.'_'.$thumb} = $field_config['default_image'];
					} else {
						if (strpos($result->$field, 'http') === 0) {
							$result-> {'_'.$field.'_'.$thumb} = $result->$field;
							continue;
						}
						
						$thumb_filename = self::get_thumbname($result->$field, $thumb);
						if (isset($field_config['upload_to_s3']) && $field_config['upload_to_s3']) {
							$result-> {'_'.$field.'_'.$thumb} = Url_helper::s3_url().$field_config['folder'].'/'.$thumb_filename;
						} else {
							$result-> {'_'.$field.'_'.$thumb} = self::uploads_conf('url').$field_config['folder'].'/'.$thumb_filename;
						}
					}
				}
			}
			if (!$result->$field || strpos($field_config['default_image'], $result->$field) !== false)
			{
				$result->$field = $field_config['default_image'];
			}
			elseif (strpos($result->$field, 'http') === 0)
			{
				//$result->$field = $result->$field;
			}
			elseif (isset($field_config['upload_to_s3']) && $field_config['upload_to_s3'])
			{
				$original = $result->$field;
				if (isset($field_config['rename_original']) && $field_config['rename_original']) {
					$original = self::get_thumbname($result->$field, 'original');
				}
				$result->{'_'.$field} = Url_helper::s3_url().$field_config['folder'].'/'.$original;
			}
			else
			{
				$original = $result->$field;
				if (isset($field_config['rename_original']) && $field_config['rename_original']) {
					$original = self::get_thumbname($result->$field, 'original');
				}
				$result->{'_'.$field} = self::uploads_conf('url').$field_config['folder'].'/'.$original;
			}
		}
	}
	
	public static function get_thumbname($filename, $thumb) {
		$name = substr($filename, 0, strrpos($filename, '.'));
		$ext = substr($filename, strrpos($filename, '.'));
		return $name.'_'.$thumb.$ext;		
	}
	
	public static function get_default_image($result, $def_arr) {
		if (!is_array($def_arr)) return $def_arr;
		if (!$result) return end($def_arr);
		$result = (Object) $result;
		foreach ($def_arr as $criteria=>$default_image) {
			if (strpos($criteria, '<>') !== false) {
				list($criteria_field, $criteria_val) = explode('<>', $criteria);
				if (!isset($result->$criteria_field)) return $default_image;
				if (@$result->$criteria_field <> $criteria_val)
					return $default_image;
			} elseif (strpos($criteria, '>=') !== false) {
				list($criteria_field, $criteria_val) = explode('>=', $criteria);
				if (@$result->$criteria_field >= $criteria_val)
					return $default_image;
			} elseif (strpos($criteria, '<=') !== false) {
				list($criteria_field, $criteria_val) = explode('<=', $criteria);
				if (@$result->$criteria_field <= $criteria_val)
					return $default_image;
			} elseif (strpos($criteria, '==') !== false) {
				list($criteria_field, $criteria_val) = explode('==', $criteria);
				if (@$result->$criteria_field == $criteria_val)
					return $default_image;
			}
		}
		return end($def_arr); 
	}

	/**
	 *  Uploads a file from the $_FILES array
	 */
	private static function upload_file($field, $file, $config)
	{
		switch($file['type'])
		{
		case 'image/pjpeg': //Fuckin' IE8
		case 'image/jpeg':
			$ext = '.jpg';
			break;
		case 'image/gif':
			$ext = '.gif';
			break;
		case 'image/jpg':
			$ext = '.jpg';
			break;
		case 'image/png':
			$ext = '.png';
			break;
		default :
			$ext = '';
			break;
		}
		$filename = uniqid().$ext;

		//load the upload libray and upload the file
		if (!is_dir(self::uploads_conf('path').$config[$field]['folder'].'/')) {
			mkdir(self::uploads_conf('path').$config[$field]['folder'].'/');
		}
		$upload_config['file_name'] = $filename;
		$upload_config['upload_path'] = self::uploads_conf('path').$config[$field]['folder'].'/';
		$upload_config['overwrite'] = TRUE;
		$upload_config['allowed_types'] = 'gif|jpg|png|jpeg';   // if this list is updated, please change $lang['upload_invalid_filetype'], $lang['upload_allowed_types']
		$upload_config['max_size']	= '4096';

		$CI =& get_instance();
		$CI->load->library('upload', $upload_config);

		if ( !$data = $CI->upload->do_upload($field)) //upload unsuccessful
		{
			die(json_encode(array('status'=>false, 'error'=>strip_tags($CI->upload->display_errors()))));
		}
		else //upload successful
		{
			if (isset($config[$field]['thumbnails']) && count($config[$field]['thumbnails'])) self::generate_thumbnails($filename, $field, $config, $data);
			/* moved to after set
			if (isset($config[$field]['upload_to_s3']) && $config[$field]['upload_to_s3'])
			{
				self::upload_to_s3($filename, $config[$field]['folder'], $uploads['path'].$config[$field]['folder'].'/'.$filename);
			}*/
		}
		return $filename;
	}

	/**
	 *  Uploads a file from url
	 */
	private static function upload_from_url($field, $value, $config)
	{
		$ext = pathinfo($value, PATHINFO_EXTENSION);
		if (!in_array($ext, array('jpg','jpeg','png','gif'))) $ext = 'jpg';
		
		$filename = isset($config[$field]['filename']) ? $config[$field]['filename'] : uniqid().'.'.$ext;

		if (strpos($value, 'data:image') === 0) {   //base64 image - example: google images
			list($type, $contents) = explode(',', $value, 2);
			$contents = base64_decode($contents);
		} elseif ($value[0] == '/' || (substr($value, 0, strpos($value, '/', 8)+1) == Url_helper::base_url() && strpos($value, '?') === false)) { //local image
			$value = BASEPATH.'../'.str_replace(Url_helper::base_url(), '', $value);
			$contents = file_get_contents($value);
			unlink($value);
		} else { //some other url
			$CI = get_instance();
			$scraper = $CI->load->library('scraper');
			$contents = $scraper->request($value, 0);
		}

		file_put_contents(self::uploads_conf('path').$config[$field]['folder'].'/'.$filename, $contents);
		if (isset($config[$field]['thumbnails']) && count($config[$field]['thumbnails'])) self::generate_thumbnails($filename, $field, $config, false);

		return $filename;
	}

	/**
	 *  Uploads a file from base64
	 */
	private static function upload_from_base64($field, $value, $config)
	{
		$CI = get_instance();
		$file_mime = substr($value, 5, strpos($value, ';')-5);
		$ext = 'jpg';
		include(APPPATH.'config/mimes.php');
		foreach ($mimes as $_ext => $mime) {
			if (is_array($mime)) foreach($mime as $_mime) {
				if ($_mime == $file_mime) {
					$ext = $_ext;
					break;
				}
			} else {
				if ($mime == $file_mime) {
					$ext = $_ext;
					break;
				}				
			}
		}
		if ($ext == 'jpe') $ext = 'jpeg';
		
		$filename = isset($config[$field]['filename']) ? $config[$field]['filename'] : uniqid().'.'.$ext;
		
		list($type, $contents) = explode(',', $value, 2);
		
		$contents = base64_decode(str_replace(' ', '+', $contents));

		file_put_contents(self::uploads_conf('path').$config[$field]['folder'].'/'.$filename, $contents);
		if (isset($config[$field]['thumbnails']) && count($config[$field]['thumbnails'])) self::generate_thumbnails($filename, $field, $config, false);

		return $filename;
	}
	
	/**
	 * Generates thumbs
	 * @var $filename -  the baseanem of the file e.g.   filename.jpg
	 * @var $field -  the database field name - its used to get the correct config record
	 * @var $config - full behavior config - used to get the folder and thumbs for the $field
	 * @var $data - link info - used to get newsfeed_id for watermark
	 */
	private static function generate_thumbnails($filename, $field, $config)
	{
		//load the img lib and generate thumbs
		$folder = $config[$field]['folder'];
		foreach($config[$field]['thumbnails'] as $thumb_name=>$thumb_config)
		{

			if ($thumb_config['create_thumb'] == TRUE)
			{
				$thumb_filename = self::get_thumbname($filename, $thumb_name);
				copy(self::uploads_conf('path').$folder.'/'.$filename, self::uploads_conf('path').$folder.'/'.$thumb_filename);
			}
			else
			{
				$thumb_filename = $filename;
			}

			if (in_array('resize', $thumb_config['transform']))
			{
				$thumb_config1 = $thumb_config;
				if (in_array('crop', $thumb_config1['transform']))
				{
					list($w, $h) = getimagesize(self::uploads_conf('path').$folder.'/'.$filename);
					if ($w > $h)
					{
						$thumb_config1['width'] = 100000;
					}
					else
					{
						$thumb_config1['height'] = 100000;
					}
				}
				list($w, $h) = getimagesize(self::uploads_conf('path').$folder.'/'.$filename);
				if($w <= $thumb_config['width']){
					$thumb_config1['width'] = $w;
				}
				/*
				if(isset($thumb_config['min_width'])){
					list($w, $h) = getimagesize(self::uploads_conf('path').$folder.'/'.$filename);
					if($w <= $thumb_config['min_width']){
						$thumb_config1['width'] = $thumb_config['min_width'];
					}
				}
				*/
				self::image_resize($thumb_filename, $field, $thumb_config1, $folder);
			}
			if (in_array('crop', $thumb_config['transform']))
			{
				self::image_crop($thumb_filename, $field, $thumb_config, $folder);
			}
			/* - moved to after set
			if (isset($config[$field]['upload_to_s3']) && $config[$field]['upload_to_s3'])
			{
				self::upload_to_s3($thumb_filename, $folder, $uploads['path'].$folder.'/'.$thumb_filename);
			}
			*/
		}
	}
	
	public static function image_resize($filename, $field, $config, $folder)
	{
		get_instance()->load->library('image_lib');
		$resize_config['image_library'] = 'gd2';
		$resize_config['source_image'] = self::uploads_conf('path').$folder.'/'.$filename;
		//$resize_config['create_thumb'] = $config['create_thumb'];
		$resize_config['maintain_ratio'] = $config['maintain_ratio'];
		$resize_config['width'] = $config['width'];
		$resize_config['height'] = $config['height'];

		$obj = new MY_Image_lib();
		$obj->initialize($resize_config);

		if ( ! $obj->resize())
		{
			echo $obj->display_errors();
		}
		else
		{
			return true;
		}
	}

	public static function image_crop($filename, $field, $config, $folder)
	{
		get_instance()->load->library('image_lib');

		$resize_config['image_library'] = 'gd2';
		$resize_config['source_image'] = self::uploads_conf('path').$folder.'/'.$filename;
		//$resize_config['create_thumb'] = $config['create_thumb'];
		$resize_config['maintain_ratio'] = false;
		$resize_config['width'] = $config['width'];
		$resize_config['height'] = $config['height'];
		list($w, $h) = getimagesize($resize_config['source_image']);
		$resize_config['x_axis'] = ($w - $config['width'])/2;
		$resize_config['y_axis'] = ($h - $config['height'])/2;

		$obj = new MY_Image_lib();
		$obj->initialize($resize_config);

		if ( ! $obj->crop())
		{
			echo $obj->display_errors();
		}
		else
		{

		}
	}

	public static function image_watermark($source, $row, $wm_config)
	{
		get_instance()->load->library('image_lib');
		
		$default = array(
			'image_library' => 'gd2',
			'source_image' => $source,
			'wm_vrt_alignment' => 'bottom',
			'wm_hor_alignment' => 'left',
		);
		$default_text = array(
			'wm_type' => 'text',
			'wm_font_path' => dirname(__FILE__) . '/../../fonts/MyriadPro-BoldCond.otf',
			'wm_font_size' => 16,
			'wm_font_color' => '000000',
			'bg_color' => 'ffffff',
			'wm_padding' => 3,
		);
		$default_image = array(
			'wm_type' => 'overlay',
			'wm_opacity' => 100,
			'wm_x_transp' => -1,
			'wm_padding' => 0,
		);
		
		$wm_config = array_merge($default, $wm_config);
		if (isset($wm_config['wm_text'])) {
			//To do - more universal regex for fields
			//check internal scraper 
			$wm_config = array_merge($default_text, $wm_config);
			$wm_config['wm_text'] = str_replace('{newsfeed_id}', @$row['newsfeed_id'], $wm_config['wm_text']);
		} else {
			$wm_config = array_merge($default_image, $wm_config);
			if (isset($wm_config['wm_overlay_path'])) {
				$wm_config['wm_overlay_path'] = str_replace('{images_path}', substr(BASEPATH,0,strrpos(BASEPATH, '/',-2)).'/images', $wm_config['wm_overlay_path']);
			}
			if ($wm_config['wm_vrt_alignment'] == 'below') {
				$wm_config['wm_vrt_alignment'] = 'bottom';
				$obj1 = new MY_Image_lib();
				list($w, $h) = getimagesize($source);
				list($w, $wm_h) = getimagesize($wm_config['wm_overlay_path']);
				$obj1->initialize(array('source_image'=>$source,'height'=>$h+$wm_h));
				$obj1->crop();
			}
		}
		$obj = new MY_Image_lib();
		$obj->initialize($wm_config);

		if ( ! $obj->watermark()) {
			echo $obj->display_errors();
		} else {
			return true;
		}
	}

	/**
	 * Uploads the files from temp folder to s3 and deletes the local ones
	 */
	public static function upload_to_s3($filename, $folder, $full_path)
	{
		//load s3 lib and upload to s3
		$CI = get_instance();
		$CI->load->library("s3");
		if ( ! S3::putObject(S3::inputFile($full_path), Url_helper::s3_bucket(), $folder.'/'.$filename, S3::ACL_PUBLIC_READ) ) {
			return false;
		} else {
			unlink($full_path);
		}
		return TRUE;
	}
}
