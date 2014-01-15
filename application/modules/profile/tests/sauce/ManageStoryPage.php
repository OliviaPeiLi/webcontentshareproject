<?php
//# Sauce Profile @lists_test

$current_dir = dirname(__FILE__);
$base_dir = substr( $current_dir, 0, strpos($current_dir,"application") );

set_include_path(get_include_path() . PATH_SEPARATOR . $base_dir . 'sauce\vendor\\');

require_once 'autoload.php';

class ManageStoryPage extends Sauce\Sausage\WebDriverTestCase
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

    protected function doCreateNewList()    {

        $this->url('/create_list');

        $new_list_name = uniqid('story_');

        $this->byName('folder_name')->value($new_list_name);
        $this->byId('hashtags_input')->value($new_list_name);

        // $this->byCssSelector('.ui-menu-item a')->click();
        $driver = $this;

        //var_dump($driver->byCssSelector('.ui-menu-item a'));
        //exit;

         $test_hashtag_output = function() use ($driver) {
             return $driver->byCssSelector('.ui-menu-item a')->displayed();
         };

         $this->spinAssert( "Hashtag populate doesn't work.", $test_hashtag_output, array(), 10);

         $this->byCssSelector('.ui-menu-item a')->click();

         // submit the form
         $this->byCssSelector('.newList_finish')->click();

         // check if redirected to list details

         $wait_for_list = function() use ($driver,$new_list_name) {
            return ( trim( $this->byCssSelector('.list-titlez h4')->text() ) == $new_list_name );
         };

         $this->spinAssert( 'Added list doesn\'n redirect to its details', $wait_for_list, array(), 10 );

         // test if new tabs show

         // automaticly goes to image mode
         $this->byCssSelector('.editList_preview')->click();

         // check if image mode is visible
         $this->assertTrue( $this->byCssSelector('form.temp')->displayed() );


        // go to url mode
         $this->byCssSelector('a.linkButton')->click();
         $this->assertTrue( $this->byCssSelector('form.temp')->byName('link_url')->displayed() );

         // go to video mode
         $this->byCssSelector('a.videoButton')->click();
         $this->assertTrue( $this->byCssSelector('form.temp')->byName('link_url')->displayed() );

         // go to text mode
         $this->byCssSelector('a.textButton')->click();
         $this->assertTrue( $this->byCssSelector('form.temp')->byName('activity[link][content]')->displayed() );


         $this->execute(array(
             'script' => '
                window.scrollTo(0, document.body.scrollHeight);
                $("form.temp [name=description]").val("Test Title");
            ',
             'args' => array(),
         ));

         // add a text post
         $this->byName('activity[link][content]')->value('Test Story');
         $this->byCssSelector('.addList_save')->click();

         $wait_for_submit = function() use ($driver)    {
            return $driver->byCssSelector('form.temp')->displayed();
         };

         $this->spinAssert("Story is not uploaded", $wait_for_submit, array(), 20);

         $this->execute(array(
             'script' => '
                window.scrollTo(0, 0);
            ',
             'args' => array(),
         ));

        // mutltiple resulst from css
        $li_elements = $this->byCssSelector('.editList_upper')->elements( $this->using('css selector')->value('li') );
        $element = $li_elements[0];

        // edit element
        $element->byCssSelector('.setAsCover')->click();
        $this->assertTrue( (bool)$element->byCssSelector('.covered') );

        $element->byCssSelector('a.itemEdit')->click();
        // check if the form is showed
        $this->assertTrue($this->byCssSelector('form.temp')->displayed());

        // check if story is populated

        $this->assertTrue( $this->byName('description_orig')->value() == 'Test Title');
        $this->assertTrue( $this->byName('activity[link][content]')->value() == 'Test Story');

        // delete story
        $this->byCssSelector('.itemDelete')->click();

        $this->assertTrue($this->byId('confirm')->displayed());
        $this->byId("confirm")->byCssSelector('a.confirmButton')->click();

        $wait_to_delete = function() use ($driver)  {

            $cnt = 0;

            $elms = $driver->byCssSelector('.editList_upper')->elements( $this->using('css selector')->value('li') );

            foreach ($elms as $k=>$v) {
                # code...
                if ($v->displayed())    {
                    $cnt++;
                }
            }
            
            return $cnt > 0 ? false : true;
        };

        $this->spinAssert("Item is not deleted", $wait_to_delete, array(), 15);

        // test upvote - downvote

        $this->byCssSelector('a.publish')->click();

        $what_to_publish = function () use ($driver)    {
            return  ( $driver->byCssSelector('.editList_finish')->text() == 'Unpublish' );
        };

        $this->spinAssert('Can\'t publish the story', $what_to_publish, array(), 10);

        // edit and delete story details

        $this->byCssSelector('a.editList_editDetails')->click();

        $wait_list_option_to_load = function() use ($driver)    {
            return $driver->byCssSelector('.newList_delete')->displayed();
        };

        $this->spinAssert("Can't redirect to story profile", $wait_list_option_to_load, array(), 10);

        $driver->byCssSelector('.newList_delete')->click();
        $this->assertTrue( $this->byId('confirm')->displayed() );
        $this->byId('confirm')->byCssSelector('.confirmButton')->click();

        $wait_to_delete = function() use ($driver)  {
            return $this->byCssSelector('ul.allLists_body') ? true : false;
        };

        $this->spinAssert( "Can't redirect to stories page - the story is not deleted", $wait_to_delete );

    }

    public function testCreateNewList()  {
        // log with test user account
        $this->doSignIn();
        // check general info settings
        $this->doCreateNewList(TRUE);
    }

}
