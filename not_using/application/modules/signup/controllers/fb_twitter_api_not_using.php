<?
		session_start();
		require_once "fbmain.php";
		$config['baseurl'] = base_url().'register';
		echo $config['baseurl'];
		echo 'aaa';

		// login or logout url will be needed depending on current user state.
		if ($fbme)
		{
			$logoutUrl = $facebook->getLogoutUrl(array('next' => $config['baseurl'],));
			$data['logoutUrl']=$logoutUrl;
		}
		else
		{
			$loginUrl = $facebook->getLoginUrl(array('display'   => 'popup',
								 'next'      => $config['baseurl'] . '?loginsucc=1',
								 'cancel_url'=> $config['baseurl'] . '/cancel',
								 'req_perms' => 'email,user_birthday'));
			$data['loginUrl']=$loginUrl;
		}
	     
		// if user click cancel in the popup window
		if (isset($_REQUEST['cancel']))
		{
			echo "<script>
			window.close();
			</script>";
		}
	     
		if ($fbme && isset($_REQUEST['loginsucc']))
		{
			//only if valid session found and loginsucc is set
		 
			//after facebook redirects it will send a session parameter as a json value
			//now decode them, make them array and sort based on keys
			$sortArray = get_object_vars(json_decode($_GET['session']));
			ksort($sortArray);
		 
			$strCookie  =   "";
			$flag       =   false;
			foreach($sortArray as $key=>$item)
			{
				if ($flag) $strCookie .= '&';
				$strCookie .= $key . '=' . $item;
				$flag = true;
			}
		 
			//now set the cookie so that next time user don't need to click login again
			setCookie('fbs_' . "{$fbconfig['appid']}", $strCookie);
		 
			echo "<script>
			window.close();
			opener.location = '../'; 
			self.close();
			</script>";
		}
		
		//if user is logged in and session is valid.
		if ($fbme)
		{
			//Retriving friends list
			try
			{
				$friends = $facebook->api('/me/friends');
				$data['friends']=$friends;
			}
			catch(Exception $o)
			{
			   // d($o);
			}
		}
		$data['fbme']=$fbme;
		if ($fbme)
		{
			
			$this->load->model('membership_model');
			$fb_me = $facebook->api('/me');
			$fb_me_id = $fb_me['id'];
			//print_r($fb_me['id']);
			$this->membership_model->add_fb_info($fbme, $fb_me_id);
		
			for ($i=0;$i<count($friends['data']);$i++)
			{
				$fb_array[$i]=$friends['data'][$i]['id'];
			}
			
			$data['fb_friends'] = $this->membership_model->check_fb($fb_array);
			
		}//end fb
		
		
		
	 
		define('CONSUMER_KEY', twtr_api_key());
		define('CONSUMER_SECRET', twtr_api_secret());
		define('OAUTH_CALLBACK', base_url().'index.php/main/callback');
		if (CONSUMER_KEY === '' || CONSUMER_SECRET === '')
		{
			echo 'You need a consumer key and secret to test the sample code. Get one from <a href="https://twitter.com/apps">https://twitter.com/apps</a>';
			exit;
		}
	       
		// Build an image link to start the redirect process. 
		//$content = '<a href="#" onclick="popup();return false;"><img src="http://si0.twimg.com/images/dev/buttons/sign-in-with-twitter-l.png" alt="Sign in with Twitter"/></a>';
		
		include_once('TwitterOAuth.php');
		//require_once('config.php');
	       
		// If access tokens are not available redirect to connect page. 
		if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret']))
		{
			$twitterinfo = '';
		}
		else{
			// Get user access tokens out of the session. 
			$access_token = $_SESSION['access_token'];
			//print_r($access_token);
			
			// Create a TwitterOauth object with consumer/user tokens. 
			$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
			//print_r($connection);
			
			// If method is set change API call made. Test is called by default. 
			$twitterinfo = $connection->get('account/verify_credentials');
			$this->load->model('membership_model');
			$this->membership_model->add_t_info($twitterinfo);
			$tid=$twitterinfo->id;

			$friends_t=$connection->get('friends/ids');
			if(!isset($twitterinfo->error))
			{
				$this->load->model('membership_model');
				$data['twitter_friends'] = $this->membership_model->check_twitter($friends_t);
			}
			
			if($this->input->post('email', true))
			{
				$this->membership_model->update_twitter($twitterinfo->id);
			}
			
			if($this->membership_model->check_t_email($twitterinfo->id))
			{
				$data['email_check'] = TRUE;
			}
			
		    
		}
		
		
		
		$this->load->library('form_validation');
		
		// field name, error message, validation rules
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
		$this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
		
		$data['main_content'] = 'home/index';
		$data['title'] = 'Fandrop - Alpha Sign Up!';
		$data['header'] = 'header_lean';
				
		if($this->form_validation->run() == FALSE)
		{
			$data['main_content'] = 'home/index';
			$data['header'] = 'header_lean';   
			
		}
		else
		{		
			$this->load->model('user_model');
			
			$new_member_insert_data = array(
    			'first_name' => $this->input->post('first_name', true),
    			'last_name' => $this->input->post('last_name', true),
    			'email' => $this->input->post('email_address', true),			
    			'password' => md5($this->input->post('password', true)),
    			'sign_up_date'=> date("Y-m-d H:i:s")
    		);
			
			if($this->user_model->insert($new_member_insert_data))
			{
				$data['check_sign_up'] = TRUE;
			}
			
		}
		
		$data['friends_t']=$friends_t;
		$data['tid']=$tid;
		$data['content']=$content;
		$data['twitterinfo']=$twitterinfo;
		
?>