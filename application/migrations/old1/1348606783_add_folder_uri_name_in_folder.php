<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_folder_uri_name_in_folder extends CI_Migration {

	public function up()
	{
	
		$this->dbforge->add_column('folder', array(
			'folder_uri_name'=>array(
				'type'=>'VARCHAR',
				'constraint'=>100,
				'default'=>''
			)
		));
		
		$res = mysql_query("SELECT folder_id, folder_name 
								FROM folder 
							");
		while ($row = mysql_fetch_object($res)) {
			//die(var_dump($row));
			$url_name = str_replace(array('?','&'), array('','-'), $row->folder_name);
			$url_name = str_replace(array(' ','_(','(',':','/','&',')'), '_', $url_name);
			$url_name = rtrim($url_name, '_');
			$new_url = preg_replace_callback('/[^a-z0-9-_\/\.:%=&\?]+/i', create_function('$matches', 'return "";'), $url_name);
			$url_name = rtrim($new_url, '_');
			$url_name = strtolower($url_name);
			//die($url_name);
			mysql_query("UPDATE folder SET folder_uri_name='".$url_name."' WHERE folder_id=$row->folder_id");

		}
	}

	public function down()
	{
		
		$this->dbforge->drop_column('folder', 'folder_uri_name');

	}
}
