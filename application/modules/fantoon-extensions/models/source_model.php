<?php
/**
 * Just an indexed table with sources to improve the search/source page
 * @author radilr
 *
 */
class Source_model extends MY_Model {
	
	protected $belongs_to = array('newsfeed');
}