<!-- CSRF Token placeholder for AJAX calls -->
<div style="display: none">
	<? 
		echo form_open('main/index/');
		echo Form_Helper::form_input('dummy', 'dummy');
		echo form_close();
	?>
</div>
<div class="container_24">
	<div class="grid_24">
		<div class="grid_12 alpha">
			<div id="find_friends_facebook">
				<?
				if ($fbme) { ?>
					<!-- <a href="<?php echo $logoutUrl; ?>">
						<img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif" border="0">
					</a>-->
					<b>Thank you for sharing your friends on facebook</b><br />
					<a href="#" class="ft-dropdown" rel="fb_friends">Check your facebook friends on Fandrop</a>
	
					<ul id="fb_friends" style="display:none">
						<? foreach($fb_friends as $k => $v)
						{
							echo '<li>'.$v["fb_firstname"].' '.$v["fb_lastname"].'</li>';
						}
						?>
					</ul>
	
				<? 
				} else{ ?>
					<a href="#" onclick="login();return false;">
					  <img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif" border="0">
					</a>
				<? } ?>
				<!-- all time check if user session is valid or not -->
				<?
				if ($fbme){ ?>
					<!-- Data retrived from user profile are shown here -->
					<div class="fb_box">
						<!--<b>User Information using Graph API</b>-->
						<?php d($fbme); ?>
					</div>
					<div class="fb_box">
						<!--<b>User's Friends</b>-->
						 <?php d($friends); ?>
					</div>
				<? } //end fb?>			
			</div>
		</div>
		<div class="grid_12 omega">
			<div id="find_friends_twitter">
				<?
				if (isset($menu)) {
					echo $menu;
				} 
				if (isset($status_text)) {
					echo '<h3>'.$status_text.'</h3>';
				} 
				?>
					<!--Display the friends_list on twitter-->
					<?php //print_r($friends_t); ?>
					<? if($tid){ ?>
						<? if($email_check){ ?>
							<b>Thank you for sharing your friends on Twitter</b><br />
							<a href="#" class="ft-dropdown" rel="twitter_friends">Check your twitter friends on Fandrop</a>
							<ul id="twitter_friends" style="display:none">
								<? 
								foreach($twitter_friends as $k => $v)
								{
									echo '<li>'.$v['t_screen_name'].'</li>';
								}
								?>
							</ul>
						<?
						} else {
							echo form_open('main/index/');
							echo Form_Helper::form_input('email', 'Email', 'class="input_placeholder" style="display:inline-block"');
							echo form_submit('submit', 'Submit', 'style="display:inline-block"');
							echo form_close();
						} 
					}
					else {
						echo '<a href="#" onclick="popup();return false;"><img src="http://si0.twimg.com/images/dev/buttons/sign-in-with-twitter-l.png" alt="Sign in with Twitter"/></a>';
					} 
					?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="grid_24">
		<div id="find_friends_email">
			Major Emails
		</div>
	</div>
	
	<div class="prefix_19 grid_5">
		<div id="find_friends_submit" class="button">
			Go to Next Step: Discover new interests
		</div>
	</div>
</div>


	<script type="text/javascript">
        var newwindow;
        var intId;
        function login(){
            newwindow=window.open('./index.php/login/fb_redirect/', 'Facebook', 'menubar=no,width=790,height=360,toolbar=no');
             if (window.focus) {newwindow.focus()}
            return false;
        }
 
        
        function popup(){
            newwindow=window.open('./index.php/login/redirect/', 'Twitter', 'menubar=no,width=790,height=360,toolbar=no');
             if (window.focus) {newwindow.focus()}
            return false;
        }
    </script>