<?php
//# Sauce Profile @landing_test

$current_dir = dirname(__FILE__);
$base_dir = substr( $current_dir, 0, strpos($current_dir,"application") );

set_include_path(get_include_path() . PATH_SEPARATOR . $base_dir . 'sauce\vendor\\');

require_once 'autoload.php';

class LandingPage extends Sauce\Sausage\WebDriverTestCase
{
    public static $browsers = array(
        // FF 11 on Sauce
        array(
            'browser' => 'firefox',
            'browserVersion' => '11',
            'os' => 'Windows 2003'
        )//,
         //Chrome on Linux on Sauce
        //array(
            //'browser' => 'googlechrome',
            //'browserVersion' => '',
            //'os' => 'Linux'
        //),
         //Chrome on local machine
        //array(
            //'browser' => 'googlechrome',
            //'local' => true
        //)
    );

    public static $url = 'http://test.fandrop.com/';
    public static $username = "test.user1@example.com";
    public static $password = "lFDvlksDF";

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl( self::$url );
    }

    protected function doLogout() {
        $this->url('/logout');
    }

    protected function doRegister() {

         // go to url
        $this->url('/');
        
        // click on login button
        $this->byCss('a.signup-btn')->click();
        $this->assertTrue( $this->byId('signup-popup')->displayed() );       

        $prefix = "test_";
        $unique = uniqid($prefix);

        $generated_username = $unique;
        $generated_email = $unique . "@gmail.com";
        $generated_password = uniqid();

        // set Login information
        $this->byId('signup-popup')->byName('uri_name')->value($generated_username);
        $this->byId('signup-popup')->byName('email')->value($generated_email);
        $this->byId('signup-popup')->byName('password')->value($generated_password);
        $this->byId('signup-popup')->byName('first_name')->value("TEST");
        $this->byId('signup-popup')->byName('last_name')->value("TEST");

        $this->byId('signup-popup')->byCssSelector('.logSign_submitButton')->click();

        $driver = $this;

        // check if browser is redirected and if is logged in account

        $check_for_close_window = function() use ($driver)   {
            return $driver->byId("signup-popup")->displayed();
         };

        $this->spinAssert( "Registration failed", $check_for_close_window, array(), 5);

        $check_if_account_exists = function() use ($driver)   {
            return $this->byId('account_link')->displayed();
         };

        $this->spinAssert( "Registration failed", $check_if_account_exists, array(), 10);

    }

    public function doInvalidSignin()
    {

        // go to url
        $this->url('/');

        // click on login button
        $this->byCss('a.signin-btn')->click();
        $this->assertTrue( $this->byId('login-popup')->displayed() );

        $fake_email = uniqid() . "@gmail.com";
        $fake_password = uniqid();

        // object to driver - needs for anonymous function
        $driver = $this;

        $test_visible = function()  use ( $driver )  {
          return $this->byId('login-popup')->displayed();
        };

        // error popup is not displayed
        $this->spinAssert("Popup doesn't show", $test_visible );

        // set Login information
        $this->byId('login-popup')->byName('email')->value($fake_email);
        $this->byId('login-popup')->byName('password')->value($fake_password);
        $this->byId('login-popup')->byCssSelector('.logSign_submitButton')->click();

        // failure message
        $failure_message = 'Invalid e-mail or password';

         $test_failure_message = function() use ( $failure_message, $driver )   {
             return ( $failure_message == $driver->byId('notification_bar')->byTag("p")->text() );
         };

        $this->spinAssert( "Invalid account message doesn't show", $test_failure_message, array(), 5);

        $this->doLogout();

    }

    protected function doSignIn()   {

        // go to url
        $this->url('/');

        // click on login button
        $this->byCss('a.signin-btn')->click();
        $this->assertTrue( $this->byId('login-popup')->displayed() );

        // object to driver - needs for anonymous function
        $driver = $this;

        $test_visible = function()  use ( $driver )  {
          return $this->byId('login-popup')->displayed();
        };

        // error popup is not displayed
        $this->spinAssert("Popup doesn't show", $test_visible );

        // set Login information
        $this->byId('login-popup')->byName('email')->value(self::$username);
        $this->byId('login-popup')->byName('password')->value(self::$password);
        $this->byId('login-popup')->byCssSelector('.logSign_submitButton')->click();

        $check_if_account_exists = function() use ($driver)   {
            return $this->byId('account_link')->displayed();
         };

        $this->spinAssert( "Login Failed.", $check_if_account_exists, array(), 10);

    }

    protected function doTestUpvote($is_logged = false)   {

        $upvote_button = $this->byId('folder_ugc_top')->byCssSelector('.bigBox')->byCssSelector('a.upvote');
        $old_upvtes_cnt = $upvote_button->byCssSelector("span.js_upvotes_count")->text();

        $upvote_button->click();

        if (!$is_logged)    {

            $driver = $this;
            // anonymous function to check if signup window is open
            $test_visible = function()  use ( $driver )  {
              return !$this->byId('login-popup')->displayed();
            };
            // error popup is not displayed
            $this->spinAssert("Popup doesn't show", $test_visible );

            return false;
        }

        $downvote_button = $this->byId('folder_ugc_top')->byCssSelector('.bigBox')->byCssSelector('a.downvote');
        $new_upvtes_cnt = $downvote_button->byCssSelector("span.js_upvotes_count")->text();
        
        $this->assertTrue($new_upvtes_cnt != $old_upvtes_cnt);

        $downvote_button->click();
        $this->assertTrue($old_upvtes_cnt == $upvote_button->byCssSelector("span.js_upvotes_count")->text());

     }

     public function testRegister()  {
         $this->doRegister();
     }

     public function testLogin() {
         $this->doInvalidSignin();
     }

    public function testUpvote()    {
        $this->doLogout();
        $this->doTestUpvote(false);
    }

    public function testSignIn()    {
        $this->doLogout();
        $this->doSignIn();
    }

    public function testLoggedUpvote()  {
        $this->doSignIn();
        $this->doTestUpvote(TRUE);
    }

}