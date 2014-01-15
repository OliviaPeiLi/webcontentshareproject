<div id="inviteContainer">
	<div id="inviteLeft">
		<ul>
			<li>
				<a href="javascript:;" rel="emailInviter" class="selected emailInvite">
					<span class="inviterIcon"><span class="invitesFavicon" alt="Email Favicon"></span>Email</span>
				</a>
			</li>
			<li>
				<a href="javascript:;" rel="facebookInviter" class="facebookInvite">
					<span class="inviterIcon"><span class="invitesFavicon" alt="Facebook Favicon"></span>Facebook</span>
				</a>
			</li>
			<li>
				<a href="javascript:;" rel="gmailInviter" class="gmailInvite">
					<span class="inviterIcon"><span class="invitesFavicon" alt="Gmail Favicon"></span>Gmail</span>
				</a>
			</li>
			<li>
				<a href="javascript:;" rel="yahooInviter" class="yahooInvite">
					<span class="inviterIcon"><span class="invitesFavicon" alt="Yahoo! Favicon"></span>Yahoo!</span>
				</a>
			</li>
		</ul>
	</div>
	<div id="inviteRight">
		<!-- Gmail Autentication -->
		<div id="gmailInviter" class="inviteContent">
			<h1>Gmail</h1>
			<div class="clr">
				<div id="google-connector-button">
					<span id="google_desc1" class="google_desc1">
						<p>We couldn't retrieve your Gmail contacts because you haven't connected Gmail and Fandrop. Click the button to temporarily connect Gmail.</p>
						<p>We will never send out any email messages without asking.</p>
						<a class="blue_bg_tall blue_bg" href="javascript:;" id="link_google">Find Friends from Gmail</a>
						<a rel="popup" id="invite_gmail_success" data-url="<?=base_url()?>gmail_auth" style="display:none">Gmail</a>
					</span>
					<span id="google_desc2" class="google_desc2" style="display:none">Loading emails from Gmail...</span>
				</div>
			</div>
		</div>
		<!-- Yahoo Autentication -->
		<div id="yahooInviter" class="inviteContent">
			<h1>Yahoo!</h1>
			<div class="clr">
				<div id="yahoo-connector-button">
					<p>We couldn't retrieve your friends from Yahoo! because you haven't connected Yahoo! and Fandrop. Click the button below to connect.</p>
					<a class="blue_bg_tall blue_bg" href="javascript:;" id="link_yahoo">Find Friends from Yahoo!</a>
				</div>
			</div>
		</div>
		<!-- Facebook Autentication -->
		<div id="facebookInviter" class="inviteContent">
			<h1>Facebook</h1>
			<div class="clr">
				<div id="facebook-connector-button">
					<p>We couldn't retrieve your friends from Facebook because you haven't connected Facebook and Fandrop. Click the button below to connect. </p>
					<a class="blue_bg_tall blue_bg" href="javascript:;" id="link_facebook">Find Friends from Facebook</a>
				</div>
			</div>
		</div>
		<!-- Email Autentication -->
		<div id="emailInviter" class="inviteContent" style="display:block;">
			<h1>Invite Your Friends to Fandrop</h1>
			<div class="inviteForm" id="EmailAddresses">
				<ul id="invite_content_wrapper">
					<? echo form_open('/invite_friends', array('id'=>'', 'class'=>'')); ?>
					<?
					            
					$msg_data = array(
					              'name'        => 'message',
					              'value'       => '',
					              'maxlength'   => '100',
					              'size'        => '50',
					              'rows'		=> '5',
					              'style'       => 'width:240px',
								  'class'	   	=> 'inviteMessage',
					              'placeholder' => 'Add a personal note (optional):'
					            );
					
					for($i=1;$i<6;$i++){  
			
						$email_data = array(
					              'name'        => 'email['.$i.']',
					              'value'       => '',
					              'maxlength'   => '100',
					              'size'        => '50',
					              'style'       => 'width:240px',
					              'class'		=> 'inviteField_email',
					              'placeholder' => 'Email Address '.$i,
					              'data-field'	=> $i
					            );
					    if(isset($messages[$i]['email'])){
					    	$email_data['value'] = $messages[$i]['email'];
					    }
					?>
					    <li class="invite_email_field">   
						    <span>        
								<?=Form_Helper::form_input($email_data)?>
							</span>
							<div class="messageForm messageEmail">
							    <p class="ok isaok" style="display:none">Valid Email</p>
							    <? if(isset($messages[$i]['msg']) && $messages[$i]['msg']== 'valid'){ ?>
							    	<p class="ok isaok keep invitesent">Invite Sent!</p>
							    	<p class="blank" style="display:none">Enter your friend's email</p>
							    <? } else{ ?>
							    	<p class="blank">Enter your friend's email</p>
							    <? } ?>
							    <p class="invalid" style="display:none">Invalid Email</p>
							</div>
							
						</li>
					<?
					}
					echo form_textarea($msg_data);
			
					echo form_submit('submit','Send Invites','class="inviteField_button blue_bg blue_bg_tall" id="submit_invites"');
					?>
					<? echo form_close(); ?>
				</ul>
				<? /* ?>
				<ul>
					<li>
						<input type="text" tabindex="1" class="email" />
						<label>Email Address 1</label>
						<span class="blocker"></span>
						<span class="helper"></span>
					</li>
					<li>
						<input type="text" tabindex="2" class="email" />
						<label>Email Address 2</label>
						<span class="blocker"></span>
						<span class="helper"></span>
					</li>
					<li>
						<input type="text" tabindex="3" class="email" />
						<label>Email Address 3</label>
						<span class="blocker"></span>
						<span class="helper"></span>
					</li>
					<li>
						<input type="text" tabindex="4" class="email" />
						<label>Email Address 4</label>
						<span class="blocker"></span>
						<span class="helper"></span>
					</li>
					<li>
						<textarea tabindex="5" style="min-height: 6.85em;" name="message"></textarea>
						<label>Add a personal note (optional):</label>
						<span class="blocker"></span>
						<span class="helper"></span>
					</li>
				</ul>
				<div class="clear">
					<button type="button" class="blue_bg_tall blue_bg" id="SendInvites">Send Invites</button>
				</div>
				<? */ ?>
			</div>
		</div>
	</div>
</div>
<?=requireJS(array("jquery","signup/invite","apis"))?>
<script type="text/javascript">
	php.picture = '<?=$this->user->avatar?>';
	php.fb_invite_description = "<?=$this->user->first_name?> is on Fandrop, a place to collect and share the best discoveries on the web. Join Fandrop to see <?=$this->user->first_name?>'s collections and start dropping!";
	php.fb_invite_message = "<?=$this->user->first_name?> is on Fandrop. Join to see his or her collections";
	php.fb_invite_title = "Join me on Fandrop!";
	php.basepath = '<?=BASEPATH.'signup?a=fb&b=b87jgzfke5'?>';
	php.user_id = '<?=$this->session->userdata('id')?>';
	php.username = '<?=$this->user->uri_name?>';
</script>