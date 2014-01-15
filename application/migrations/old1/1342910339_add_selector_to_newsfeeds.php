<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Selector button is used by the bookmarklet embed button to show the drop count
 * @author radilr
 *
 */
class Migration_Add_selector_to_newsfeeds extends CI_Migration {

	public function up()
	{
		mysql_query("ALTER TABLE  `links` ADD  `selector` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					ADD INDEX (  `selector` )");
	}

	public function down()
	{
		  $this->dbforge->drop_column('links', 'selector');
	}
}
