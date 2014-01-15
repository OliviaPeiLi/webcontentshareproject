<?php
class Qunit extends MX_Controller {
	
	public function check_db() {
		if ($this->user->id != 4) {
			return show_404(); 
		}
	}
}