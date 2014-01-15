<div id="newuser_reg_wrapper">
    <div id="newuser_reg_form_main" class="container_24">
	<div id="signup_title">
	    <h1 id="signup_title_step1" class="inlinediv">
	    		<?=$this->lang->line('welcome');?>
	    </h1>
	    <h1 id="signup_title_step2" class="inlinediv" style="display:none">
		Create Fandrop Account.
	    </h1>
	    <div id="reg_status_frame" class="inlinediv">
		<div id="reg_step1" class="inlinediv reg_status_step reg_step_active"></div>
		<div id="reg_step2" class="inlinediv reg_status_step"></div>
		<? /* ?>
		<div id="reg_step3" class="inlinediv reg_status_step"></div>
		<div id="reg_step4" class="inlinediv reg_status_step"></div>
		<? */ ?>
	    </div>
	</div>
	<? echo Form_Helper::form_open('create_user'); ?>
	<? echo form_hidden('beta_id', set_value('email_address', $beta_id)); ?>
	    
    <script type="text/javascript">
	    php.fb_id = '<?=$fb_id?>';
	    php.twitter_id = '<?=$twitter_id?>';
	    php.fb_app_id = '<?=$this->config->item('fb_app_key')?>'
    </script>
    <?=requireJS(array("jquery","signup/basic_info","facebook/fb_init"))?>
	    
	<div id="signup_left" class="grid_18 prefix_3 suffix_3" style="position:relative; display: block;">
	    <? /* ?>
		    <div id="signup_left_overlay" class="overlay" style="opacity:0.75;filter:alpha(opacity=75);"></div>
		    <? */ ?>
	    <div class="signup_left_wrapper">
		<ul id="register">
		    <div id="user_id" style="display: none"><? echo @$user_id; ?></div>
		    <div id="signup_form_step1">
		    <span class="error"><?php echo $this->session->flashdata('validation_errors');?></span>
		    <?= form_hidden('fb_id',$fb_id) ?>
		    <?= form_hidden('twitter_id',$twitter_id) ?>
		    <?= form_hidden('first_name',$first_name) ?>
		    <?= form_hidden('last_name',$last_name) ?>
		    <?= form_hidden('next',$this->input->get('next',true)) ?>
		    <?= form_hidden('action_type',$this->input->get('action_type',true)) ?>
    
		    <? 	if($this->uri->segment(2) != ''){ echo form_hidden('from',$this->uri->segment(2)); }?>
		  
		    <li>
			<span>
			    <? $user_empty = (trim($username) === '' || trim($username) === 'Username') ? '' : ' nonempty'; ?>
			    <? echo Form_Helper::form_input('username', $username, 'id="reg_username" autocomplete="off" class="input_placeholder_enh'.$user_empty.'"'); ?>
			    <span class="tmp_input_holder" style="display:none">Username</span>
			</span>
			<div class="messageForm" id="messageUsername">
			    <p class="ok isaok" style="display:none">This username is available.</p>
			    <p class="field_tip">Enter your preferred username.</p>
			    <p class="checking">Validating...</p>
			    <p class="taken error" style="display:none">This Username has already been taken.</p>
			</div>
			<div class="clear"></div>
			<?php echo form_error('username'); ?>
		    </li>
    <? /* ?>                
		    <li>
			<span>
			    <? $ln_empty = (trim($last_name) === '' || trim($last_name) === 'Last Name') ? '' : ' nonempty'; ?>
			    <? echo Form_Helper::form_input('last_name', $last_name, 'id="reg_last_name" autocomplete="off" class="input_placeholder_enh'.$ln_empty.'"'); ?>
			    <span class="tmp_input_holder" style="display:none">Last Name</span>
			</span>
			<div class="messageForm" id="messageLastName">
			    <p class="ok isaok">Your Last Name looks great.</p>
			    <p class="field_tip">Enter your last name.</p>
			    <p class="blank invalid error">A Last name is required!</p>
			</div>
			<div class="clear"></div>
			<?php echo form_error('last_name'); ?>
		    </li>
    <? */ ?>                
		    <li>
			<span>
			    <? $ea_empty = (trim($email_address) === '' || trim($email_address) === 'Email Address') ? '' : ' nonempty'; ?>
			    <? echo Form_Helper::form_input('email_address', set_value('email_address', $email_address), 'id="reg_email" autocomplete="off" class="input_placeholder_enh '.$ea_empty.'"'); ?>
			    <span class="tmp_input_holder" style="display:none">Email Address</span>
			</span>
			<div class="messageForm" id="messageEmail">
			  <p class="field_tip">What's your email address?</p>
			  <p class="ok isaok">Validated Email.</p>
			  <p class="checking">Validating...</p>
			  <p class="invalid error">Doesn't look like a valid email.</p>
			  <p class="blank error">An email is required!</p>
			  <p class="taken error">This email is already registered.<br>Want to <a href="/signin">login</a> or <a href="/forgotpassword">recover your password</a>?</p>
			</div>
			<div class="clear"></div>
			<?php echo form_error('email_address'); ?>
		    </li>
		    <li>
			<span>
			    <? $pw_empty = (trim($password) === '' || trim($password) === 'Password') ? '' : ' nonempty'; ?>
			    <? echo form_password('password', $password, 'id="reg_password" autocomplete="off" class="input_placeholder_enh'.$pw_empty.'"'); ?>
			    <span class="tmp_input_holder" style="display:none">Password</span>
			</span>
			<div class="messageForm" id="messagePassword">
			    <p class="field_tip">6 characters or more! Be tricky.</p>
			    <p class="perfect isaok">Your password is perfect!</p>
			    <p class="ok isaok">Your password is okay.</p>
			    <p class="weak isaok">Your password could be more secure.</p>
			    <p class="obvious error">Your password is too obvious.</p>
			    <p class="blankspace error">You need a password!</p>
			    <p class="invalid error">Password must be at least 6 characters.</p>
			    <p class="blank error">Password cannot be left blank!</p>
			</div>
    
			<div class="score" id="scorePass" style="display: block;">
			    <span><b style="width: 0%;"></b></span>
			</div>
			<div class="clear"></div>
			<?php echo form_error('password'); ?>
		    </li>
    <? /* ?>                
		    <li>
			<span>
			    <? echo Form_Helper::form_dropdown('gender', $genders, $gender ? $gender : 'm', 'id="reg_gender" class="input_placeholder_enh nonempty"'); ?>
			    <span class="tmp_input_holder" style="display:none">Gender</span>
			</span>
			<div class="messageForm" id="messageGender">
			    <p class="ok isaok">ok</p>
			    <p class="field_tip">Choose your gender</p>
			    <p class="blank invalid error">A gender is required!</p>
			</div>
			<div class="clear"></div>
			<?php echo form_error('gender'); ?>
		    </li>
		    <li>
			<span><? echo Form_Helper::form_dropdown('dob_m', $months, $dob_m ? $dob_m : 0); ?></span>
			<span><? echo Form_Helper::form_dropdown('dob_d', $days, $dob_d ? $dob_d : 0); ?></span>
			<span><? echo Form_Helper::form_dropdown('dob_y', $years, $dob_y ? $dob_y : 0); ?></span>
			<div class="messageForm" id="messageDOB">
			    <p class="ok isaok">Date of Birth is valid</p>
			    <p class="field_tip">Choose your Birthday</p>
			    <p class="invalid error">Date of Birth is invalid</p>
			    <p class="blank error">A Date of Birth is Required</p>
			</div>
			<?php echo form_error('dob'); ?>
		    </li>
    <? */ ?>                
				    <li>
			    <input id="submit_signup" type="submit" name="submit" class="inactive_bg inactive_bg_tall" value="Create my account">
				    </li>
		    </div>
		</ul>
	    </div>
	    </div>
    
	
	
	<? echo form_close();?>
    </div>
</div>