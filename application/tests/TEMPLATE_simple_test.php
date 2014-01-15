<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ExampleTest extends UnitTestCase
{
	
	function setUp() {
	}

	function tearDown() {
	}
	
	///// All functions starting with "test" will be tested /////
	
	function testSimpleStuff()
	{
		$name = 'Andreas';
		$online = TRUE;
		
		$this->assertEqual($name, 'Andreas');
		$this->assertTrue($online);
	}
}