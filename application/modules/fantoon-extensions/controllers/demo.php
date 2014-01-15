<?php

class Demo extends MX_Controller {

	public function quiz() {
		return parent::template('demo_quiz', array(), '', '');
	}


}