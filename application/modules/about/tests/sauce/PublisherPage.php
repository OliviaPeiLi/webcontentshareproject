<?php
//# Sauce Profile @publisher_test

$current_dir = dirname(__FILE__);
$base_dir = substr( $current_dir, 0, strpos($current_dir,"application") );

set_include_path(get_include_path() . PATH_SEPARATOR . $base_dir . 'sauce\vendor\\');

require_once 'autoload.php';

class PublisherPage extends Sauce\Sausage\WebDriverTestCase
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

        $this->url('/publishers');

        $fake_email = uniqid('test') . '@gmail.com';

        // check if empty form is sent
        $this->byId('publishers_form')->byName('url')->clear();
        $this->byId('publishers_form')->byName('submit')->click();

        $driver = $this;

        $test_missing_email_error = function() use ($driver)    {
           return  $driver->byId('notification_bar')->displayed() && $driver->byId('notification_bar')->text() == 'The URL field is required.' ? true : false;
        };

        $this->spinAssert('Erorr message doesn\'t show', $test_missing_email_error, array(), 10);

        $this->byId('publishers_form')->byName('url')->value($fake_email);
        $this->byId('publishers_form')->byName('submit')->click();
        $this->assertTrue( $this->byId('thankyou-msg')->displayed() );

    }

}