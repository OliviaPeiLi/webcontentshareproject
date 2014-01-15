<?php
/**
 *  Me controller class
 */
require_once 'user.php';

class Me extends User {
    protected $model = 'user_model';

    public function item_delete()
    {
        $this->response("Not authorized", 401);;
    }

    public function index_get()
    {
    	$this->item($this->user);
        return $this->item_get();
    }

}