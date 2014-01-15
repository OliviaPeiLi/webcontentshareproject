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

    protected function doAccountSaveSettings()  {

        var_dump('doAccountSaveSettings');

        // go to account options
        $this->url('/account_options');

        // here we are in user proile page
        $this->byId('account_basic')->byCssSelector("input.settingsUpdate")->click(); // submit first form

        $driver = $this;

         $test_success_message = function() use ( $driver )   {
            $success_message = 'Your Account information is saved.';
             return ( $success_message == $driver->byId('notification_bar')->byTag("p")->text() );
         };

         $this->spinAssert( "ERROR: Can't save basic information.", $test_success_message, array(), 5);

    }

    protected function doPasswordChange()   {

        var_dump('doPasswordChange');
        $driver = $this;

        // go to account options
        $this->url('/account_options');

        $this->byId('change_password')->byName('old_pass')->clear();
        $this->byId('change_password')->byName('new_pass')->clear();
        $this->byId('change_password')->byName('re-new_pass')->clear();

        $this->byId('change_password')->byCssSelector("input.settingsUpdate")->click(); // submit first form

         $test_success_message = function() use ( $driver )   {
             return (bool)preg_match('/Old password can\'t be blank/',$driver->byId('notification_bar')->text());
         };


         $this->spinAssert( "ERROR: Error messages doesn't display.", $test_success_message, array(), 5);

        $this->byId('change_password')->byName('old_pass')->value(self::$password);
        $this->byId('change_password')->byName('new_pass')->value(self::$password);
        $this->byId('change_password')->byName('re-new_pass')->value(self::$password);

        // submit password
        $this->byId('change_password')->byCssSelector("input.settingsUpdate")->click(); // submit first form

        $this->assertTrue('Your Password is saved.' == $driver->byId('notification_bar')->text());
    }

    protected function doBioInformation()    {

        var_dump('doBioInformation');
        
        $driver = $this;

        // go to account options
        $this->url('/account_options');

        $select_month = $this->select($this->byName('month'));
        $select_day = $this->select($this->byName('day'));
        $select_year = $this->select($this->byName('year'));

        $select_month->selectOptionByValue('5');
        $select_day->selectOptionByValue('5');
        $select_year->selectOptionByValue('1998');

        $this->byName('about')->value('test bio');

        $this->byId('account_profile_basic')->byCssSelector('input.settingsUpdate')->click();

        $test_success_message = function() use ( $driver )   {
            $success_message = 'Your Basic Information is saved.';
            return ( $success_message == $driver->byId('notification_bar')->byTag("p")->text() );
        };

        $this->spinAssert( "ERROR: Can't save Bio information.", $test_success_message, array(), 5);       

    }

    protected function doCheckEmail() {

        var_dump('doCheckEmail');

        $driver = $this;

        // go to account options
        $this->url('/account_options');      

        $email = $this->byName('email')->value();

        $this->byName('email')->clear();
        $this->byId('email_change')->byCssSelector('input.settingsUpdate')->click();
        $this->assertTrue($driver->byId('notification_bar')->text() == 'Email can\'t be blank.');

    }

    protected function checkChangePhoto()   {

        $driver = $this;
        $this->url('account_options');

        $this->execute(array(
         'script' => '
            $("#profile a.avatarPopup").css({display:"block"})
        ',
         'args' => array(),
        ));
        
        $this->byId('profile')->byCssSelector('a.avatarPopup')->click();
        $this->assertTrue( $this->byId('upload_profilepic_dlg')->displayed() );

    }

    public function testAccountSaveSettings()  {
        // log with test user account
        $this->doSignIn();
        
        // check general info settings
        $this->doAccountSaveSettings(TRUE);
        // check password change
        $this->doPasswordChange();
        // check bioInformation
        $this->doBioInformation();
        // check email save
        $this->doCheckEmail();
        
        $this->checkChangePhoto();
    }

}
/*        
        // mutltiple resulst from css
        $this->byId('account_link')->click();
        $li_elements = $this->byId('account_options')->elements( $this->using('css selector')->value('li') );
        $li_elements[1]->click();
*/