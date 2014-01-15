<?php
/** 
 * General up/unup
 * @author radilr
 *
 */
class Like extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->lang->load('like/like', LANGUAGE);
	}
}
