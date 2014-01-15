<?php
//# Sauce Folder @demo_test

$current_dir = dirname(__FILE__);
$base_dir = substr( $current_dir, 0, strpos($current_dir,"application") );

set_include_path(get_include_path() . PATH_SEPARATOR . $base_dir . 'sauce\vendor\\');

require_once 'autoload.php';

class DemoPage extends Sauce\Sausage\WebDriverTestCase
{
    public static $browsers = array(
        // FF 11 on Sauce
        array(
            'browser' => 'firefox',
            'browserVersion' => '11',
            'os' => 'Windows 2003'
        )
    );

    public static $url = 'https://test.fandrop.com/';

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl( self::$url );
    }

    public function testSubmitPublisher()  {

        $this->url('/demo');

         $elements = $this->byId('all_folders')->elements( $this->using('css selector')->value('div.js-folder') );

         $this->assertTrue( count( $elements ) > 0 );

    }

}