<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_add_content_plain extends CI_Migration {

	public function up()
	{
		$this->db->query("ALTER TABLE  `links` ADD  `content_plain` TEXT NOT NULL AFTER  `content`");
		$this->db->query("ALTER TABLE `links` DROP INDEX `title`");
		$this->db->query("ALTER TABLE `links` DROP INDEX `text`");
		$this->db->query("ALTER TABLE `links` DROP INDEX `content`");
		$this->db->query("ALTER TABLE  `links` ADD FULLTEXT  `content` (`title` ,`text` ,`content_plain`)");
		
		$last_id = 0;
		while (1) {
			echo "Link ".$last_id."\r\n";
			$res = mysql_query("SELECT link_id, content FROM links WHERE link_id > ".$last_id." LIMIT 1000");
			$has_rows = false;
			while($row = mysql_fetch_object($res)) {
				$this->db->query("UPDATE links SET content_plain = '".mysql_escape_string(strip_tags($row->content))."' WHERE link_id = ".$row->link_id);
				$last_id = $row->link_id;
				$has_rows = true;
			}
			if (!$has_rows) break;
		}
	}

	public function down()
	{
		$this->db->query("ALTER TABLE `links` DROP INDEX `content`");
		$this->db->query("ALTER TABLE  `fantoon_ci`.`links` ADD FULLTEXT  `title` (`title`)");
		$this->db->query("ALTER TABLE  `fantoon_ci`.`links` ADD FULLTEXT  `text` (`text`)");
		$this->db->query("ALTER TABLE  `fantoon_ci`.`links` ADD FULLTEXT  `content` (`content`)");
		$this->dbforge->drop_column('links', 'content_plain');
		
	}
}
