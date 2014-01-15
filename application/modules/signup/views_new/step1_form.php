<?php //The upload picture dialog triggered ?>
<? $avatar_color =  $this->config->item('avatar_color');?>
<? $color_img = $avatar_color[rand(0,count($avatar_color))].'.png'; ?>
<div id="upload_image_dialog" class="modal" style="display:none">
	<div class="modal-body">
		<div class="left image_placeholder">
			<?=Html_helper::img(Url_helper::s3_url().'users/'.$color_img, array('class'=>"uploaded-image-preview", 'alt'=>"placeholder"))?>
		</div>
		<div class="right actions">
			<?=Form_Helper::open('/signup/edit_picture')?>
				<div class="hidden_upload">
					<a href="" id="signUp_chooseAvatar" class="lightGreen_bg">Choose File</a>
					<?=Form_Helper::upload('avatar','',array('size'=>"20", 'id'=>"upload_img_filename"))?>
				</div>
				<?=Form_Helper::submit('save_image', 'Save', array('class'=>"button lightBlue_bg", 'style'=>"display: none;"))?>
				<a href="" id="signUp_avatarDone" class="lightBlue_bg" data-dismiss="modal">Done</a>
				<span style="display:none" class="error"></span>
			<?=Form_Helper::close()?>
		</div>
	</div>
</div>

<div id="login_wrapper" class="container_24">
	<div class="signup_container signup_form">
		<div class="signup_title">
			<h1 id="signup_title_step1" class="inlinediv">
					<?=$this->lang->line('welcome2');?>
			</h1>
			<? /* ?>
			<div class=status>
				<span class="stepText">Step</span>
				<span class="step active"></span>
				<span class="step"></span>
				<span class="step"></span>
			</div>
			<? */ ?>
		</div>
		<div class="signup_content grey">
			<?=Form_Helper::open('/signup/form', array('id'=>'create_user', 'class'=>'error','autocomplete'=>'off'), array(
					'autocomplete'=>'off',
					'alpha_user_id' => Form_Helper::set_value('alpha_user_id'),
					'fb_id' => Form_Helper::set_value('fb_id'),
					'twitter_id' => Form_Helper::set_value('twitter_id'),
					'gender' => Form_Helper::set_value('gender', 'm'),
					'birthday' => Form_Helper::set_value('birthday'),
					'avatar' => $color_img
			)); ?>
				
			<div class="form">
				<div class="form_row">			
					<label>
						<?=Form_Helper::input('uri_name', Form_Helper::set_value('uri_name'), array(
								'placeholder'=>'Username',
								'class'=>"input_placeholder_enh",
								'autocomplete' => 'off',
								'data-validate' => 'required|minlength|username',
								'minlength' => 6,
								'data-error-minlength' => "The Username must be at least 6 characters long.",
								'data-error-required' => "A Username is required!",
						))?>
						<span class="tmp_input_holder" style="display:none">Username</span>
					</label>
					<span class="error"></span>
					<span class="valid"><span class="valid_contents"></span>This username is available.</span>
					<span class="field_tip">Enter your preferred username.</span>
					<span class="loading">Validating...</span>
					<?=@$_POST['submit'] ? Form_Helper::form_error('uri_name') : '' ?>
				</div>
				<div class="form_row">									
					<label>
						<?=Form_Helper::input('email', Form_Helper::set_value('email'), array(
								'placeholder'=>'Email Address',
								'class'=>"input_placeholder_enh",
								'autocomplete' => 'off',
								'data-validate' => 'required|email|uniqemail',
								'data-error-required' => "An email is required!",
								'data-error-email' => "Doesn't look like a valid email.",
								'data-error-uniqemail' => "This email is already registered.<br>Want to <a href='/signin'>login</a> or <a href='/forgotpassword'>recover your password</a>?"
						))?>
						<span class="tmp_input_holder" style="display:none">Email Address</span>
					</label>
					<span class="error"></span>
					<span class="valid"><span class="valid_contents"></span>Validated Email.</span>
					<span class="field_tip">What's your email address?</span>
					<span class="loading">Validating...</span>
					<?=@$_POST['submit'] ? Form_Helper::form_error('email') : ''?>
				</div>
				<div class="form_row">				
					<label>
						<?=Form_Helper::password('password', '', array(
								'placeholder'=>'Password',
								'class' => "input_placeholder_enh",
								'id'=>'password',
								'autocomplete' => 'off',
								'data-validate' => 'required|minlength|password',
								'minlength' => 6,
								'data-error-required' => 'Password cannot be left blank!',
								'data-error-minlength' => 'Password must be at least 6 characters.',
								'data-error-password' => 'Your password is too obvious.'
						))?>
						<span class="tmp_input_holder" style="display:none">Password</span>
						<div class="score" id="scorePass" style="display: block;">
							<span><b style="width: 0%;"></b></span>
						</div>
					</label>
					<span class="error"></span>
					<span class="valid"><span class="valid_contents"></span></span>
					<span class="field_tip">6 characters or more! Be tricky.</span>
					<?=@$_POST['submit'] ? Form_Helper::form_error('password') : '' ?>
					<script type="text/javascript">
						php.lang.password = {
								'perfect': 'Your password is perfect!',
								'weak': 'Your password could be more secure.',
								'ok': 'Your password is okay!',
						}
					</script>			
				</div>
				<div class="form_row">				
					<label>
						<?=Form_Helper::input('first_name', Form_Helper::set_value('first_name'), array(
								'placeholder'=>'First Name',
								'class'=>"input_placeholder_enh",
								"maxlength" => 30,
								"data-validate" => "required|maxlength|specialchars",
								"data-error-required"=>"A First name is required!",
								"data-error-specialchars"=>"No special characters are allowed.",
								"data-error-maxlength" => "Your name can be up to 30 characters"
						)); ?>
						<span class="tmp_input_holder" style="display:none">First Name</span>
					</label>
					<span class="error"></span>
					<span class="valid"><span class="valid_contents"></span>Your First Name looks great.</span>
					<span class="field_tip">Enter your first name.</span>
					<?=@$_POST['submit'] ? Form_Helper::form_error('first_name') : '' ?>
				</div>
				<div class="form_row">				
					<label>
						<?= Form_Helper::input('last_name', Form_Helper::set_value('last_name'), array(
								'placeholder'=>'Last Name',
								'class' => "input_placeholder_enh",
								"maxlength" => 30, 
								"data-validate" => "maxlength|specialchars",
								"data-error-specialchars"=> "No special characters are allowed.",
								"data-error-maxlength" => "Your Last name can be up to 30 characters"
							))?>
						<span class="tmp_input_holder" style="display:none">Last Name</span>
					</label>
					<span class="error"></span>
					<span class="valid"><span class="valid_contents"></span>Your Last Name looks great.</span>
					<span class="field_tip">Enter your last name.</span>
					<?=@$_POST['submit'] ? Form_Helper::form_error('last_name') : '' ?>
				</div>
				<div class="form_row">				
					<input id="submit_signup" type="submit" name="submit" class="blue_bg blue_bg_tall" value="Create my account">
				</div>
			</div>
			<div id="email_image_upload">
				<?=Html_helper::img(Url_helper::s3_url().'users/'.$color_img, array('class'=>"uploaded-image-preview", 'alt'=>"picture preview"))?>
				<a href="#upload_image_dialog" id="email_image_upload_button" class="lightBlue_bg" rel="popup" title="<?=$this->lang->line('signup_upload_pic_title');?>"><?=$this->lang->line('signup_upload_pic_lexicon');?></a>
			</div>
		<?=Form_Helper::close()?>
		</div>
	</div>
</div>
<?=Html_helper::requireJS(array("signup/step1_form"))?> 
