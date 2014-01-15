<?php
/**
 *   Folder (Collection) controller class
 */
require_once 'api.php';

class User extends API {

	public function index_post() {
		if (!@$_POST['email']) $_POST['email'] = '';
		if (!@$_POST['uri_name']) $_POST['uri_name'] = '';
		if (!@$_POST['first_name']) $_POST['first_name'] = '';
		if (!@$_POST['password']) $_POST['password'] = '';
		return parent::index_post();
	}
}