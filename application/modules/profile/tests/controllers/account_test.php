<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class profile_account_test extends Web_Test_Case
{

    /*
    **	The test function for page /account_options
    */
    public function disabled_test_account()
    {
        $user = $this->db_interface->db->where('id', $this->config['login']['id'])->get('users')->row_array();
        if(!$user)
        {
            $user = $this->config['login'];
        }
        $email_settings = $this->db_interface->db->where('user_id', $this->config['login']['id'])->get('email_settings')->row_array();

        $this->login();
        $this->get("/account_options");

        $this->assertTitle('Account Options');
        $this->assertPattern("#<h1>{$user['first_name']} {$user['last_name']}</h1>#msi");
        $this->assertPattern("#<h2>Account Settings</h2>#msi");

        $this->assertField('first_name', $user['first_name']);
        $this->assertField('last_name', $user['last_name']);
        $this->assertField('uri_name', $user['uri_name']);
        $this->assertField('email', $user['email']);

        if( $user['fb_id'] > 0 )
        {
            $this->assertText('Disconnect from Facebook');
            if( $user['fb_activity'] == 1 )
            {
                $this->assertPattern('#<a id="fb_activity_sharelink" class="sharelink_disable" href="/disable_fb_activity"><span class="status_light"></span>Activity Sharing is ON</a>#msi');
            }
            else
            {
                $this->assertPattern('#<a id="fb_activity_sharelink" class="sharelink_enable" href="/enable_fb_activity"><span class="status_light"></span>Activity Sharing is OFF</a>#msi');
            }
        }
        else
        {
            $this->assertText('Connect with Facebook');
        }

        if( $user['twitter_id'] > 0 )
        {
            $this->assertText('Disconnect from Twitter');
            if( $user['twitter_activity'] == 1 )
            {
                $this->assertPattern('#<a id="twitter_activity_sharelink" class="sharelink_disable" href="/disable_twitter_activity"><span class="status_light"></span>Activity Sharing is ON</a>#msi');
            }
            else
            {
                $this->assertPattern('#<a id="twitter_activity_sharelink" class="sharelink_enable" href="/enable_twitter_activity"><span class="status_light"></span>Activity Sharing is OFF</a>#msi');
            }
        }
        else
        {
            $this->assertText('Connect with Twitter');
        }

        if($email_settings)
        {
            $email_fields = array('message', 'comment', 'up_link', 'up_comment', 'connection', 'follow_folder', 'collaboration');
            foreach($email_fields as $field)
            {
                $checked = $email_settings[$field] == 1 ? ' checked="checked"' : '';
                $this->assertPattern('#<input type="checkbox" name="'.$field.'" value="1"'.$checked.'#msi');
            }
        }

        $this->logout();
    }

}