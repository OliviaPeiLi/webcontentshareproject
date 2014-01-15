<div id="accountSettings">
	<div class="account_top">
		<h2>Profile Settings</h2>
	</div>
	<div class="account_main">
		<fieldset class="personal_info_form">
			<div class="account_left_col">
				<div class="clear"></div>
				<? // Basic Settings ?>
				<?=Form_Helper::open('', array('id'=>'account_basic','rel'=>'ajaxForm') )?>
					<ul class="account_section">
						<li class="account_section_title"><h6>Name</h6></li>
						<li class="form_row">
							<div class="inlinediv label">First Name</div>
							<div class="inlinediv field"><?=Form_Helper::input('first_name', Form_Helper::set_value('first_name', $this->user->first_name),array("data-validate"=>"required","data-error-required"=>"First name can't be blank"))?></div>
							<div class="error"></div>
						</li>
						<li class="form_row">
							<div class="inlinediv label">Last Name</div>
							<div class="inlinediv field"><?=Form_Helper::input('last_name', Form_Helper::set_value('last_name', $this->user->last_name),array("data-validate"=>"required","data-error-required"=>"Last name can't be blank"))?></div>
							<div class="error"></div>
						</li>
						<li class="form_row">
							<div class="inlinediv label">Username</div>
							<div class="inlinediv field"><?=Form_Helper::input('uri_name', Form_Helper::set_value('uri_name', $this->user->uri_name),array("data-validate"=>"required","data-error-required"=>"Username name can't be blank"))?></div>
							<div class="error"></div>
						</li>
						<li>
							<div class="inlinediv label"></div>
								<div class="inlinediv field">
									<?=Form_Helper::submit('submit', 'Update', array('class'=>"blue_bg"))?>
									<span id="account_options_ok" class="account_ok" style="display:none">Saved</span>
									<span id="account_options_err" class="account_err" style="display:none">Not Saved</span>
								</div>
						</li>
					</ul>				
				<?=Form_Helper::close()?>				
				<?=Form_Helper::open('', array('id'=>'account_profile_basic', 'rel'=> 'ajaxForm'))?>
				<ul class="account_section">
					<li class="account_section_title">
						<h6>Basic Information </h6>
					</li>   
					<li class="form_row">
						<div class="inlinediv label">Birthday</div>
						<div class="inlinediv field">
							<? list($year, $month, $day) = explode('-', $this->user->birthday)?>
							<?=Form_Helper::dropdown('month', array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'), (int)$month,array("data-validate"=>"required","data-error-required"=>"Month is required"))?>
							<?=Form_Helper::dropdown('day', array_merge(array(''),range(1, 31)), (int)$day,array("data-validate"=>"required","data-error-required"=>"Day is required"))?>
							<?=Form_Helper::dropdown('year', array_combine(array('0000') + range(date('Y'), 1900), array('') + range(date('Y'), 1900)), (int)$year,array("data-validate"=>"required","data-error-required"=>"Year is required"))?>
						</div>
						<div class="error"></div>
					</li>
					<li class="form_row">
						<div class="inlinediv label">Gender</div>
						<div class="inlinediv field">
						  <?=Form_Helper::form_radio('gender', 'm', $this->user->gender != 'f', 'id="radio-m"'); ?><label for="radio-m">Male</label>
						  <?=Form_Helper::form_radio('gender', 'f', $this->user->gender == 'f', 'id="radio-f"'); ?><label for="radio-f">Female</label>
						</div>
						<div class="error"></div>
					</li>
					<li class="form_row">
						<div class="inlinediv label">Bio</div>
						<div class="inlinediv field">
							<textarea name="about" class="text_input"><?=$this->user->about?></textarea>
						</div>
						<div class="error"></div>
					</li>
					<li class="form_row">
						<div class="inlinediv label"></div>
						<div class="inlinediv field">
							<?=Form_Helper::submit('submit', 'Save Basic Info', array('class'=>"submit_button blue_bg")) ?>
							<span id="profile_basic_ok" class="account_ok" style="display:none">Saved</span>
							<span id="profile_basic_err" class="account_err" style="display:none">Not Saved</span>
						</div>
						<div class="error"></div>
					</li>											
				</ul>
				<?=Form_Helper::close()?>
		  
			 	<? if($this->is_mod_enabled('education_settings')) { ?>
				<div id="account_profile_education" class="account_section">
					<div class="account_section_title">
						<h6>Education</h6>
					</div>
					<ul id="school_entry_list" class="edit_body">
						<? $this->load->view('profile/edit_schools', array('schools' => $schools)) ?>
					</ul>
					<?=Form_Helper::open('ac_add_user_school', array('id' => 'school_entry_form', 'rel'=>'ajaxForm'))?>
						<ul class="edit_body" id="add_new_school" style="<?=count($schools) ? 'display: none' : ''?>">
							<li class="autocomplete_input">
								<div class="inlinediv label">School</div>
								<div class="inlinediv field add_school_field">
									<?=Form_Helper::input('school', '', array(
										'id'=>"school_name_form",
										'class'=>"text_input school_form_text tokenInput",
										'data-url'=>"/ac_get_school",
										'token_limit'=>"1",
										'theme'=>"google",
										'allow_insert'=>"true",
										'placeholder'=>"Name",
										'placeholder'=>"School Name"
									))?>
								</div>
							</li>
							<li>
								<div class="inlinediv label">Year</div>
								<div class="inlinediv field add_school_field">
									<?=Form_Helper::dropdown('school_year', range(date('Y')+10, 1900), '', array('id'=>"school_year_form"))?>
									<input type="hidden" name="year">
								</div>
							</li>
							<li class="autocomplete_input">
								<div class="inlinediv label">Concentration</div>
								<div class="inlinediv field add_school_field">
									<?=Form_Helper::input('major', '', array(
										'id'=>"school_major_form",
										'class'=>"text_input school_form_text tokenInput",
										'data-url'=>"/get_majors",
										'theme'=>"google",
										'allow_insert'=>"true", 
										'prevent_duplicates'=>"true",
										'placeholder'=>"Enter your majors",
										'linkedText'=>"+ Add Major"
									))?>
									<input type="hidden" id="major_names" name="major_names" />
								</div>
							</li>
							<li>
								<div class="inlinediv label"></div>
								<div class="inlinediv field">
									<?=Form_Helper::submit('submit', 'Save new school', array('id'=>"submit_school", 'class'=>"submit_button body blue_bg"))?>
									<span class="account_ok" style="display:none">Saved</span>
									<span class="account_err" style="display:none">Not Saved</span>
								</div>
							</li>
						</ul> 
					<?=Form_Helper::close()?> 
					<div>
						<div class="inlinediv label"></div>
						<div class="inlinediv field">
							<a id="addSchoolForm_lnk" href="javascript:;" class="add_more body" title="" <?= empty( $schools ) ? 'style="display: none;"' : '' ?>>Add More Schools</a>
						</div>
					</div>						 
				</div>
				<? } else { ?>
					<!-- education_settings module is disabled -->
				<? } ?>
			</div><!-- End .account_left_col -->
			<div class="account_right_col">
				<?=Form_Helper::open('', array('id'=>'change_password', 'rel'=>'ajaxForm'))?>
					<ul class="account_section">
						<li class="account_section_title">
							<h6>Update Your Password </h6>
						</li>
						<li id="password_alert form_row"><?=$this->session->flashdata('password_errors')?></li>
						<? if($this->user->password != '') { ?>
							<li class="form_row">
								<div class="inlinediv label">Old password</div>
								<div class="inlinediv field"><?=Form_Helper::password('old_pass', '',array(
										'data-validate' => 'required|password|minlength',
										'minlength' => 6,
										'data-error-required' => 'Password cannot be left blank!',
										'data-error-minlength' => 'Password must be at least 6 characters.',
										'data-error-password' => 'Your password is too obvious.',
										'data-password-ok' => 'Your password is okay!',
										'data-password-weak' => 'Your password could be more secure.',
										'data-password-perfect' => 'Your password is perfect!'
								))?></div>
								<div class="error"></div>
							</li>
						<? } else { ?>
							<!-- User is registered with FB/Twitter and doesnt have old pass -->
						<? } ?>
						<li class="form_row">
							<div class="inlinediv label">New password</div>
							<div class="inlinediv field">
								<?=Form_Helper::password('new_pass', '', array(
										'class' => "input_placeholder_enh",
										'autocomplete' => 'off',
										'data-validate' => 'required|password|minlength',
										'minlength' => 6,
										'data-error-required' => 'Password cannot be left blank!',
										'data-error-minlength' => 'Password must be at least 6 characters.',
										'data-error-password' => 'Your password is too obvious.',
										'data-password-ok' => 'Your password is okay!',
										'data-password-weak' => 'Your password could be more secure.',
										'data-password-perfect' => 'Your password is perfect!'
								))?>
								<div class="score" id="scorePass" style="display: block;">
									<span><b style="width: 0%;"></b></span>
								</div>
							</div>
							<span class="error"></span>
							<span class="valid"></span>
						</li>
						<li>
							<div class="inlinediv label"></div>
							<div class="inlinediv field">
								<button class="button blue_bg">Update</button>
								<span id="change_password_ok" class="account_ok" style="display:none">Saved</span>
								<span id="change_password_err" class="account_err" style="display:none">Not Saved</span>
							</div>
						</li>
					</ul>
				<?=Form_Helper::close()?>
		
				<? // Email Settings ?>
				<?=Form_Helper::open('', array('id'=>'email_change', 'rel'=>'ajaxForm') )?>
					<ul class="account_section">
						<li class="account_section_title">
							<h6>Email </h6>
						</li>
						<li id="email_alert"><?=$this->session->flashdata('validation_errors')?></li>
						<li class="form_row">
							<div class="inlinediv label">Email Address</div>
							<div class="inlinediv field"><?=Form_Helper::input('email', Form_Helper::set_value('email', $this->user->email),array("data-validate"=>"required|email","data-error-required"=>"Email can't be blank","data-error-email"=>"You have to provide a valid email"));?></div>
							<div class="error"></div>
						</li>
						<li>
							<div class="inlinediv label"></div>
							<div class="inlinediv field">
								<button class="button blue_bg">Update</button>
								<span id="email_change_ok" class="account_ok" style="display:none">Saved</span>
								<span id="email_change_err" class="account_err" style="display:none">Not Saved</span>
							</div>
						</li>
					</ul>
				<?=Form_Helper::close()?>
			
				<?=Form_Helper::open('', array('id'=>'save_email_setting', 'rel'=>'ajaxForm', 'class'=>'save_email_setting') )?>
					<ul class="account_section">
						<li class="account_section_title">
							<h6>Email Settings </h6>
						</li>
						<?php $settings = array(
							'message' => 'New Message',
							'comment' => 'Comments to my posts',
							'up_link' => 'Posts are upvoted',
							'up_comment' => 'Comments are upvoted',
							'connection' => 'Someone followed me',
							'follow_folder' => 'My collections are followed',
							'collaboration' => 'Collection Collaboration',
							'newsletter' => 'Newsletter',
							//'follow_list' => 'Follow my list',
						)?>
						<?php foreach ($settings as $setting=>$label) { ?>
							<li class="inlinediv">
								<div class="inlinediv label"><label for="check-<?=$setting?>"><?=$label?></label></div>
								<div class="inlinediv emailChecks">
									<?=Form_Helper::hidden("email_setting[$setting]", '0')?>
									<?=Form_Helper::form_checkbox("email_setting[$setting]", '1', $this->user->email_setting->$setting, array('id' => "check-".$setting))?>
								</div>
							</li>
						<?php } ?>
						<li>
							<div class="inlinediv label"></div>
							<button class="button blue_bg">Update</button>
							<span id="email_settings_ok" class="account_ok" style="display:none">Saved</span>
							<span id="email_settings_err" class="account_err" style="display:none">Not Saved</span>
						</li>
					</ul>
				<?=Form_Helper::close()?>
				
				<? // Sharing Options (fb/twitter) ?>
				<ul id="sharing_options" class="account_section">
					<li class="account_section_title">
						<h6>Sharing</h6>
					</li>
					<li>
						<div id="fb_connect_area">
							<div id="fb_alert"></div>
							<?php if ($this->user->password) { ?>
								<a href="/disconnect_fb" rel="ajaxButton" id="fb_disconnect_wh_password" class="account_soc_disconnect" title="Facebook disconnect confirmation" style="<?=$this->user->fb_id > 0 ? "" : "display:none"?>">
									<span></span>Disconnect from Facebook
								</a>
							<?php } else { ?>
								<a href="#set_password_popup" class="account_soc_disconnect" rel="popup" title="Facebook disconnect confirmation" style="<?=$this->user->fb_id > 0 ? "" : "display:none"?>">
									<span></span>Disconnect from Facebook
								</a>
							<?php } ?>
							<a href="" class="account_soc_connect" style="<?=$this->user->fb_id > 0 ? "display:none" : ""?>">
								<span></span>Connect with Facebook
							</a>
							<a href="/enable_fb_activity" rel="ajaxButton" class="sharelink_enable" style="<?=$this->user->fb_id > 0 && $this->user->fb_activity == '0' ? "" : "display:none"?>">
								<span></span>Activity Sharing is OFF
							</a>
							<a href="/disable_fb_activity" rel="ajaxButton" class="sharelink_disable" style="<?=$this->user->fb_id > 0 && $this->user->fb_activity != '0' ? "" : "display:none"?>">
								<span></span>Activity Sharing is ON
							</a>
							<?=$this->load->view('profile/fb_disconnect_confirmation');?>
							<div id="set_password_popup" style="display:none">
								<div class="modal-body">
									<div id="fb_disconnect_warning_container">
										<span class="warning_message_icon"></span>
										<span><?=$this->lang->line('set_password_warning');?></span>
									</div>
									<div class="bottom_row">
										<? /* ?><a class="blue_bg confirm_no" data-dismiss="modal"><?=$this->lang->line('fb_warning_cancel')?></a><? */ ?>
									</div>
								</div>
							</div>
						</div> <!-- End of #fb_connect_area -->
						
						<div id="twitter_connect_area">
							<div id="twitter_alert"><?=$this->session->flashdata('twitter_error')?></div>
							<?php if ($this->user->password) { ?>
								<a href="/disconnect_twitter" rel="ajaxButton" class="account_soc_disconnect" style="<?=$this->user->twitter_id > 0 ? '' : 'display:none'?>">
									<span></span>Disconnect from Twitter
								</a>
							<?php } else { ?>
								<a href="#set_password_popup" rel="popup" class="account_soc_disconnect" style="<?=$this->user->twitter_id > 0 ? '' : 'display:none'?>">
									<span></span>Disconnect from Twitter
								</a>
							<?php } ?>
							<a href="" class="account_soc_connect" style="<?=$this->user->twitter_id > 0 ? 'display:none' : ''?>">
								<span></span>Connect with Twitter
							</a>
							<a href="/enable_twitter_activity" rel="ajaxButton" class="sharelink_enable" style="<?=$this->user->twitter_id > 0 && $this->user->twitter_activity == '0'? '' : 'display:none'?>">
								<span></span>Activity Sharing is OFF
							</a>
							<a href="/disable_twitter_activity" rel="ajaxButton" class="sharelink_disable" style="<?=$this->user->twitter_id > 0 && $this->user->twitter_activity != '0' ? '' : 'display:none'?>">
								<span></span>Activity Sharing is ON
							</a>
						</div> <!-- End of #twitter_connect_area -->
					</li>
				</ul> <!-- End of #sharing_options -->
	
				<? if($this->is_mod_enabled('deactivate_account')){ ?>
					<? if($this->user->status == 1 ){?>
						<a href="/status/0">Deactivate Account</a>
				 	<? } else { ?>
						<a href="/status/1">Activate Account</a>
					<? } ?>
				<? } ?>
				
			</div><!-- End of .account_right_col -->
		</fieldset>
		<div class="account_bot"></div> 
	</div> <!-- End of .account_main -->
</div> <!-- End of #accountSettings -->

<?=Html_helper::requireJS(array("profile/edit"))?>
