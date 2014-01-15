<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_update_user_uri_names extends CI_Migration {

	public function up()
	{
		
		$query = mysql_query("SELECT * FROM users");
		while($user = mysql_fetch_array($query)){

			$new_uri_name = $user['first_name'];
			if(strlen($new_uri_name) < 5){
				$new_uri_name = $user['first_name'].$user['last_name'];
			}
			$orig_new_uri_name = $new_uri_name;
			$i = 1;
			while(1){
				$ret =  mysql_query("SELECT COUNT(id) FROM users WHERE uri_name = '".$new_uri_name."'");
				$num = mysql_fetch_array($ret);
				//var_dump($num[0];
				//die();
				if($num[0] == 0){
					break;
				}else{

					$new_uri_name = $orig_new_uri_name.$i;
					$i++;
				}
			}
			mysql_query("UPDATE users SET uri_name = '".$new_uri_name."' WHERE id = '".$user['id']."'");

		}
	}
	
	
	public function down()
	{
		
	}
	
	
	
}
