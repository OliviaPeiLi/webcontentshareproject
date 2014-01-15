<?php

require_once 'vendor/autoload.php';

class ProfileSettingsPage extends Sauce\Sausage\WebDriverTestCase
{
    public static $browsers = array(
        // FF 11 on Sauce
        array(
            'browser' => 'firefox',
            'browserVersion' => '11',
            'os' => 'Windows 2003'
        )
         //Chrome on Linux on Sauce
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

    public static $location = '';

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl( self::$url );
    }

    protected function getCurrentUrl()   {
        $elementArray = $this->execute(array(
             'script' => 'return window.location.href',
             'args' => array(),
         ));
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

    protected function doCheckScroll()  {

        $this->url('/test_user1');
        $driver = $this;

       $elementArray = $this->execute(array(
             'script' => "window.scrollTo(0, document.body.scrollHeight);",
             'args' => array(),
        ));

       $test_autoscroll_items = function() use ($driver) {
         return count($this->byId('folders')->elements( $this->using('css selector')->value('li.folder_ugc'))) > 15 ? true : false;
       };

       $this->spinAssert('Scroll doesn\'t work', $test_autoscroll_items, array(), 10);

    }

    protected function doComment()  {

        $driver = $this;

        $this->url('/test_user1');

        $li_elements = $this->byId('folders')->elements( $this->using('css selector')->value('li.folder_ugc') );
        $li_elements[0]->click();

        $wait_to_folder_profile_load = function() use ($driver) {
            return $driver->byCssSelector('form.form_row')->displayed();
        };

        /* delete all comments */
        $li_elements = $this->byCssSelector('ul.commentsUL')->elements( $this->using('css selector')->value('li') );
        
        foreach ($li_elements as $k => $v) {
            # code...
            $v->byCssSelector('.js-delete_comment')->click();
        }        

        $this->spinAssert('Can\'t load folder profile page', $wait_to_folder_profile_load, array(), 10);

        $this->byName('comment')->value('Test Comment @test');

        // check mentions
        $wait_to_mentions_load = function() use ($driver)   {
            return $driver->byCssSelector('ul.ui-autocomplete')->displayed();
        };

        $this->spinAssert('Mention on comment doesn\'t display', $wait_to_mentions_load, array() , 10);

        $this->byCssSelector('ul.ui-autocomplete li')->click();
        $this->byName('create')->click();

        $wait_to_new_comment_load = function() use ($driver) {
            $li_elements = $this->byCssSelector('ul.commentsUL')->elements( $this->using('css selector')->value('li') );
            return count($li_elements) > 0 ? true : false;
        };

        $this->spinAssert('Comment doesn\'t show.', $wait_to_new_comment_load, array(), 10);

        // downvote the comment the comment
        $this->byCssSelector('ul.commentsUL li a.downvote')->click();
        $this->assertTrue( $this->byCssSelector('ul.commentsUL li a.upvote')->displayed() );

        // delete comment
        $this->byCssSelector('ul.commentsUL .js-delete_comment')->click();

        $wait_comment_to_delete = function() use ($driver)  {
            return count($this->byCssSelector('ul.commentsUL')->elements( $this->using('css selector')->value('li') )) > 0 ? false : true;
        };

        $this->spinAssert( 'Can\'t delete the comment', $wait_comment_to_delete, array(), 10 );
        // $this->assertTrue(!$this->byCssSelector('ul.commentsUL li a.upvote'));

        self::$location = $this->getCurrentUrl();

    }

    protected function doUpvoteFolder() {

        // de-comment if need
        $this->url(self::$location);

        $old_num = (int)$this->byId('folderTop')->byCssSelector('a.upvote .num')->text();

        if (!$this->byId('folderTop')->byCssSelector('a.upvote')->displayed())  {
            $this->byId('folderTop')->byCssSelector('a.downvote')->click();
        }

        $this->byId('folderTop')->byCssSelector('a.upvote')->click();

        $new_num = (int)$this->byId('folderTop')->byCssSelector('a.downvote .num')->text();

        $this->assertTrue( $old_num != $new_num );

        $this->byId('folderTop')->byCssSelector('a.downvote')->click();
        $this->assertTrue( $new_num != (int)$this->byId('folderTop')->byCssSelector('a.upvote .num')->text() );

    }
    
    protected function doNewsfeedActions()  {

        $driver = $this;

        $newsfeeds = $this->byId('list_newsfeed')->elements( $this->using('css selector')->value('li') );
        $this->assertTrue( count($newsfeeds) > 0 );

        $newsfeed = $newsfeeds[0];

        $elementArray = $this->execute(array(
             'script' => "$('.newsfeed_dropInfoContent').css({display:'block'})",
             'args' => array(),
         ));

        $newsfeed->byCssSelector('.js_edit_newsfeed')->click();

        $this->assertTrue( $this->byId('newsfeed_popup_edit')->displayed() );

        $description_text = $this->byId('newsfeed_popup_edit')->byName('description')->value();
        $this->assertTrue( $newsfeed->byCssSelector('h2.js-description')->text() == $description_text );

        $new_title = uniqid("description_");

        $this->byId('newsfeed_popup_edit')->byName('description')->clear();
        $this->byId('newsfeed_popup_edit')->byName('description')->value($new_title);

        $this->byId('newsfeed_popup_edit')->byCssSelector('.done_button')->click();

        $this->assertTrue( $newsfeed->byCssSelector('h2.js-description')->text() == $new_title );

        // clear upvote downvote state

        if ($newsfeed->byCssSelector('.downvote')->displayed())  {
            $newsfeed->byCssSelector('.downvote')->click();
        }

        // test upvote

            $old_value = $newsfeed->byCssSelector('.upvote .js_upvotes_count')->text();
            $newsfeed->byCssSelector('.upvote')->click();

            $this->assertTrue( $newsfeed->byCssSelector('.downvote .js_upvotes_count')->text() != $old_value );

        // test downvote

            $old_value = $newsfeed->byCssSelector('.downvote .js_upvotes_count')->text();
            $newsfeed->byCssSelector('.downvote')->click();

            $this->assertTrue( $newsfeed->byCssSelector('.upvote .js_upvotes_count')->text() != $old_value );


        // redrop window
        $newsfeed->byCssSelector('.redrop_button')->click();
        $this->assertTrue( $this->byId('collect_popup')->displayed());

        // check fill redrop window
        $this->assertTrue ( $new_title == $this->byId('collect_popup')->byName('description')->value() );

        // check error if there is no content
        $this->byId('collect_popup')->byName('description')->clear(); // clear the field

        // try to submit button
        $this->byId('collect_popup')->byCssSelector('.redropSubmit')->click();
        $this->assertTrue( $this->byId('notification_bar')->text() == 'The title cannot be blank' );

        // repopulate description and submit the form
        $this->byId('collect_popup')->byName('description')->value($new_title);
        $this->byId('collect_popup')->byCssSelector('.redropSubmit')->click();

        $wait_to_success_message = function() use ($driver) {
            return strpos( $driver->byId('notification_bar')->text(), 'You have successfully shared') >= 0 ? true : false;
        };

        $this->spinAssert('Unable to redrop the post.', $wait_to_success_message, array(), 10);

    }

    protected function checkEditFolderButton() {

        $driver = $this;

        $this->byId('folderTop')->byCssSelector('a.edit')->click();

        $wait_to_redirect = function() use ($driver)    {
            return $driver->byCssSelector('.list-titlez')->displayed();
        };

        $this->spinAssert('Can\'t redirect to story', $wait_to_redirect, array(), 10);

    }

    public function testAccountSaveSettings()  {

        // log with test user account
        $this->doSignIn();

        // check autoscroll
        $this->doCheckScroll();

        // do comment
        $this->doComment();

        // upvote folder
        $this->doUpvoteFolder();

        $this->doNewsfeedActions();

        $this->checkEditFolderButton();

    }

}
/*
        // mutltiple resulst from css
        $this->byId('account_link')->click();
        $li_elements = $this->byId('account_options')->elements( $this->using('css selector')->value('li') );
        $li_elements[1]->click();
*/