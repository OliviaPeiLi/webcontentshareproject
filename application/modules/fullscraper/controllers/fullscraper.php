<?php
/**
 * Share drop on a social networ facebook, twitter or pinterest
 * @author radilr, Ray
 */
class Fullscraper extends MX_Controller {

	public function index()	{
		//@todo - this is a good idea we nee to make this universal for is_mod_enabled
		if(!$this->is_mod_enabled('enable_fullsraper')) {
			return parent::template('fullscraper/error_404');
		}

		$max_upload = (int)(ini_get('upload_max_filesize'));
		$max_post = (int)(ini_get('post_max_size'));
		$memory_limit = (int)(ini_get('memory_limit'));
		$upload_mb = min($max_upload, $max_post, $memory_limit);

		$hashtags = $this->hashtag_model->top_hashtags()->get_all();

		return parent::template('fullscraper/full_scraper',array(
			"hashtags"=>$hashtags,
			"upload_mb"=>$upload_mb
		));
	}

}
