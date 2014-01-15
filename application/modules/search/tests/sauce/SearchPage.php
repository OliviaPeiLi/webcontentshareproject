<?php
//# Sauce Profile @search_test

$current_dir = dirname(__FILE__);
$base_dir = substr( $current_dir, 0, strpos($current_dir,"application") );

set_include_path(get_include_path() . PATH_SEPARATOR . $base_dir . 'sauce\vendor\\');

require_once 'autoload.php';

class SearchPage extends Sauce\Sausage\WebDriverTestCase
{
    public static $browsers = array(
        // FF 11 on Sauce
        array(
            'browser' => 'firefox',
            'browserVersion' => '11',
            'os' => 'Windows 2003'
        )
         // Chrome on Linux on Sauce
        // ,array(
        //     'browser' => 'googlechrome',
        //     'browserVersion' => '',
        //     'os' => 'Linux'
        // ),
        //  //Chrome on local machine
        // array(
        //     'browser' => 'googlechrome',
        //     'local' => true
        // )
    );

    public static $url = 'https://test.fandrop.com/';
    public static $username = "test.user1@example.com";
    public static $password = "lFDvlksDF";

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl( self::$url );
    }

    protected function doLogout() {
        // logout
        $this->url('/logout');
    }

    protected function doSignIn( )   {

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

    protected function doSearch( $keyword )    {

        $driver = $this;

        $this->url('/');
        $this->byId('token-input-header_search_box')->value($keyword);
        $this->byId('searchButton')->click();

        $wait_for_results = function() use ($driver)    {
            return (bool)$driver->byId('folders')->byCssSelector('.folder_ugc');
        };

        $this->spinAssert('No search results.', $wait_for_results, array() , 10);

        // check if title = searched keyword
        $this->assertTrue( $this->byCssSelector('.searchHeader h1')->text()  == $keyword );

        // checkdrop down
        $this->url('/');
        $this->byId('token-input-header_search_box')->value($keyword);

        $wait_for_drop_down_preview = function() use ($driver)  {
             $elms = $driver->byCssSelector('.token-input-dropdown-search')->elements( $this->using('css selector')->value('li') );
            return count($elms) > 2 ? true : false;
        };

        $this->spinAssert('Main search dropdown doesn\'t work', $wait_for_drop_down_preview, array(), 10);

    }

    public function testMainSearch()  {
        // search and show results
        $this->doSearch('test');
    }

}
