<? $this->lang->load('profile/profile', LANGUAGE); ?>
<div id="settings">
	<div class="profileHead settingsHead">
		<h2><?=$this->lang->line('profile_preferences_lexicon');?></h2>
	</div>
<div class="settingsBody">
		<?=Form_Helper::open('', array(
			'id'=>'account_basic','rel'=>'ajaxForm','class'=>'settingsItem',
			'data-error'=>'popup', 'success' => 'Your Account information is saved.')
		)?>
			<fieldset class="">
				<legend><?=$this->lang->line('name');?></legend>
				<label class="form_row">
					<span class="form_rowTitle"><?=ucwords($this->lang->line('first_name'));?></span>
					<?=Form_Helper::input('first_name', Form_Helper::set_value('first_name', $this->user->first_name), array(
						"data-validate"=>"required|minlength|maxlength",
						'data-error'=>'popup',
						'minlength' => 1,
						'maxlength' => 30,
						"data-error-required" => sprintf($this->lang->line('profile_err_required_tpl'), ucwords($this->lang->line('first_name'))),
						'data-error-minlength' => sprintf($this->lang->line('profile_err_minlength_tpl'), ucwords($this->lang->line('first_name')), 1),
						'data-error-maxlength' => sprintf($this->lang->line('profile_err_maxlength_tpl'), ucwords($this->lang->line('first_name')), 30)
					))?>
					<span class="error"></span>
				</label>
				<label class="form_row">
					<span class="form_rowTitle"><?=ucwords($this->lang->line('last_name'));?></span>
					<?=Form_Helper::input('last_name', Form_Helper::set_value('last_name', $this->user->last_name),array(
						"data-validate"=>"maxlength",
						'data-error'=>'popup',
						'maxlength' => 30,
						'data-error-maxlength' => sprintf($this->lang->line('profile_err_maxlength_tpl'), ucwords($this->lang->line('last_name')), 30)
					))?>
					<span class="error"></span>
				</label>
				<label class="form_row">
					<span class="form_rowTitle"><?=ucfirst($this->lang->line('username'));?></span>
					<?=Form_Helper::input('uri_name', Form_Helper::set_value('uri_name', $this->user->uri_name),array(
						"data-validate"=>"required|minlength",
						'minlength' => 3,
						'maxlength' => 30,
						'data-error'=>'popup',
						'data-error-required' => sprintf($this->lang->line('profile_err_required_tpl'), ucfirst($this->lang->line('username'))),
						'data-error-minlength' => sprintf($this->lang->line('profile_err_minlength_tpl'), ucfirst($this->lang->line('username')), 3),
						'data-error-maxlength' => sprintf($this->lang->line('profile_err_maxlength_tpl'), ucfirst($this->lang->line('username')), 30)
					))?>
					<span class="error"></span>
				</label>
				<label class="form_row">
					<?=Form_Helper::submit('submit', $this->lang->line('profile_form_update_lexicon'), array('class'=>'greyButton settingsUpdate'))?>
					<span class="status_ok"><?=$this->lang->line('profile_form_status_ok_lexicon');?></span>
				</label>
			</fieldset>
		<?=Form_Helper::close()?>
					
		<?=Form_Helper::open('', array(
			'id'=>'change_password', 'rel'=>'ajaxForm','class'=>'settingsItem',
			'data-error' => 'popup', 'success' => 'Your Password is saved.'
		))?>
			<fieldset>
				<legend><?=$this->lang->line('profile_update_pass_title');?></legend>
				<? if ($this->user->password) { ?>
					<label class="form_row">
						<span class="form_rowTitle"><?=$this->lang->line('profile_old_pass_label');?></span>
						<?=Form_Helper::password('old_pass', '',array(
												'data-validate' => 'required|password|minlength',
												'data-error'=>'popup',
												'data-obvious'=>'false',
												'minlength' => 6,
												'data-error-required' => sprintf($this->lang->line('profile_err_required_tpl'), $this->lang->line('profile_old_pass_label')),
												'data-error-minlength' => sprintf($this->lang->line('profile_err_minlength_tpl'), $this->lang->line('profile_old_pass_label'), 6),
												'data-error-password' => $this->lang->line('profile_pass_msg_obvious'),
												'data-password-ok' => $this->lang->line('profile_pass_msg_ok'),
												'data-password-weak' => $this->lang->line('profile_pass_msg_weak'),
												'data-password-perfect' => $this->lang->line('profile_pass_msg_perfect'),
										))?>
						<span class="error"></span>
					</label>
				<? } else { ?>
					<!-- User is registered with FB/Twitter and doesnt have old pass -->
				<? } ?>
				<label class="form_row">
					<span class="form_rowTitle"><?=$this->lang->line('profile_new_pass_label');?></span>
					<?=Form_Helper::password('new_pass', '', array(
												'class' => "input_placeholder_enh",
												'autocomplete' => 'off',
												'data-validate' => 'required|password|minlength',
												'data-error'=>'popup',
												'minlength' => 6,
												'data-error-required' => sprintf($this->lang->line('profile_err_required_tpl'), $this->lang->line('profile_new_pass_label')),
												'data-error-minlength' => sprintf($this->lang->line('profile_err_minlength_tpl'), $this->lang->line('profile_new_pass_label'), 6),
												'data-error-password' => $this->lang->line('profile_pass_msg_obvious'),
												'data-password-ok' => $this->lang->line('profile_pass_msg_ok'),
												'data-password-weak' => $this->lang->line('profile_pass_msg_weak'),
												'data-password-perfect' => $this->lang->line('profile_pass_msg_perfect'),
										))?>
					<span class="score" id="scorePass"><span style="width:0%"></span></span>
					<span class="error"></span>
				</label>
				<label class="form_row">
					<span class="form_rowTitle"><?=$this->lang->line('profile_confirm_new_pass_label');?></span>
					<?=Form_Helper::password('re-new_pass', '', array(
												'class' => "input_placeholder_enh",
												'autocomplete' => 'off',
												'data-validate' => 'required|password|minlength|confirm',
												'data-error'=>'popup',
												'minlength' => 6,
												'data-error-confirm' => $this->lang->line('profile_pass_err_conf'),
												'data-error-required' => sprintf($this->lang->line('profile_err_required_tpl'), $this->lang->line('profile_confirm_new_pass_label')),
												'data-error-minlength' => sprintf($this->lang->line('profile_err_minlength_tpl'), $this->lang->line('profile_confirm_new_pass_label'), 6),
												'data-error-password' => $this->lang->line('profile_pass_msg_obvious'),
												'data-password-ok' => $this->lang->line('profile_pass_msg_ok'),
												'data-password-weak' => $this->lang->line('profile_pass_msg_weak'),
												'data-password-perfect' => $this->lang->line('profile_pass_msg_perfect'),
										))?>
					<span class="score" id="scorePass"><span style="width:0%"></span></span>
					<span class="error"></span>
				</label>				
				<label>
					<?=Form_Helper::submit('submit', $this->lang->line('profile_form_update_lexicon'), array('class'=>'greyButton settingsUpdate')) ?>
					<span class="status_ok"><?=$this->lang->line('profile_form_status_ok_lexicon');?></span>
				</label>
			</fieldset>
		<?=Form_Helper::close()?>
						
		<?=Form_Helper::open('', array(
			'id'=>'account_profile_basic', 'rel'=> 'ajaxForm','class'=>'settingsItem',
			'data-error' => 'popup', 'success' => 'Your Basic Information is saved.'
		))?>
			<fieldset>
				<legend><?=$this->lang->line('profile_basic_info_title');?></legend>
				<label class="form_row">
					<span class="form_rowTitle"><?=$this->lang->line('profile_dob_label');?></span>
					<? list($year, $month, $day) = explode('-', $this->user->birthday)?>
					<?=Form_Helper::dropdown('month', array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'), (int)$month,array("data-validate"=>"required","data-error-required"=>sprintf($this->lang->line('profile_err_required2_tpl'), "Month")))?>
					<?=Form_Helper::dropdown('day', array_merge(array(''),range(1, 31)), (int)$day,array("data-validate"=>"required","data-error-required"=>sprintf($this->lang->line('profile_err_required2_tpl'), "Day")))?>
					<?=Form_Helper::dropdown('year', array_combine(array('0000') + range(date('Y'), 1900), array('') + range(date('Y'), 1900)), (int)$year,array("data-validate"=>"required","data-error-required"=>sprintf($this->lang->line('profile_err_required2_tpl'), "Year")))?>
					<span class="error"></span>
				</label>
				<label class="form_row">
					<span class="form_rowTitle"><?=$this->lang->line('profile_gender_label');?></span>
					<?=Form_Helper::form_radio('gender', 'm', $this->user->gender != 'f', 'id="radio-m"'); ?><label for="radio-m"><?=$this->lang->line('male');?></label>
					<?=Form_Helper::form_radio('gender', 'f', $this->user->gender == 'f', 'id="radio-f"'); ?><label for="radio-f"><?=$this->lang->line('female');?></label>
					<!--<span class="error"></span>-->
				</label>
				<label class="form_row">
					<span class="form_rowTitle"><?=$this->lang->line('profile_bio_label');?></span>
					<textarea name="about" class="text_input" maxlength="100" data-validate="maxlength"><?=$this->user->about?></textarea>
					<span class="textLimit">100</span>
					<span class="error"></span>
				</label>
				<label class="submitBio">
					<?=Form_Helper::submit('submit', $this->lang->line('profile_form_update_lexicon'), array('class'=>'greyButton settingsUpdate')) ?>
					<span class="status_ok"><?=$this->lang->line('profile_form_status_ok_lexicon');?></span>
				</label>
			</fieldset>
		<?=Form_Helper::close()?>
		<div class="settingsItem_column inlinediv">
		<?=Form_Helper::open('', array(
			'id'=>'email_change', 'rel'=>'ajaxForm','class'=>'settingsItem',
			'data-error' => 'popup', 'success' => 'Your Email is saved.'
		))?>
			<fieldset>
				<legend><?=$this->lang->line('email');?></legend>
				<label class="form_row">
					<span class="form_rowTitle"><?=ucwords($this->lang->line('email_address'));?></span>
					<?=Form_Helper::input('email', Form_Helper::set_value('email', $this->user->email),array(
						"data-validate" => "required|email",
						'data-error'=>'popup',
						"data-error-required" => sprintf($this->lang->line('profile_err_required_tpl'), $this->lang->line('email')),
						"data-error-email" => $this->lang->line('profile_email_invalid_error')
					))?>
					<span class="error"></span>
				</label>
				<label>
					<?=Form_Helper::submit('submit', $this->lang->line('profile_form_update_lexicon'), array('class'=>'greyButton settingsUpdate')) ?>
					<span class="status_ok"><?=$this->lang->line('profile_form_status_ok_lexicon');?></span>
				</label>
			</fieldset>
		<?=Form_Helper::close()?>
		<?php /*		
		<?=Form_Helper::open('', array(
			'id'=>'save_email_setting', 'rel'=>'ajaxForm', 'class'=>'save_email_setting settingsItem',
			'data-error' => 'popup', 'success' => 'Your Email settings are saved.'
		) )?>
			<fieldset>
				<legend><?=$this->lang->line('profile_email_settings_label');?></legend>
				<?php $settings = array(
					// 'message' => 'New Message',
					'comment' => $this->lang->line('profile_email_comment_label'),
					'up_link' => $this->lang->line('profile_email_up_link_label'),
					'up_comment' => $this->lang->line('profile_email_up_comment_label'),
					// 'connection' => 'Someone followed me',
					'folder_like' => $this->lang->line('profile_email_folder_like_label'),
					// 'follow_folder' => 'My lists are followed',
					// 'collaboration' => 'Collection Collaboration', // FD-4686
					'newsletter' => $this->lang->line('profile_email_newsletter_label'),
					//'follow_list' => 'Follow my list',
				)?>
				<?php foreach ($settings as $setting=>$label) { ?>
					<label class="mailSetting">
						<span><?=$label?></span>
						<?php // Form_Helper::hidden("email_setting[$setting]", '0')?>
						<?=Form_Helper::form_checkbox("email_setting[$setting]", '1', $this->user->email_setting->$setting, ' id ="check-' . $setting . '"' )?>
					</label>
				<?php } ?>		
				<label>
					<input type="hidden" name="section" value="email" />
					<?=Form_Helper::submit('submit', $this->lang->line('profile_form_update_lexicon'), array('class'=>'greyButton settingsUpdate')) ?>
					<span class="status_ok"><?=$this->lang->line('profile_form_status_ok_lexicon');?></span>
				</label>
			</fieldset>
		<?=Form_Helper::close()?>
		*/ ?>
		</div>
		<form id="sharing_options">
			<fieldset>
				<legend><?=$this->lang->line('profile_sharing_lexicon');?></legend>
				<label id="fb_connect_area">				
					<div id="fb_alert"></div>
					<?php if ($this->user->password) { ?>
						<a href="/disconnect_fb" rel="ajaxButton" id="fb_disconnect_wh_password" class="account_soc_disconnect connectButton connectButton_FB" title="Facebook disconnect confirmation" style="<?=$this->user->fb_id > 0 ? "" : "display:none"?>">
							<span class="ico"></span><span class="connect_buttonText"><?=$this->lang->line('profile_disconnect_fb_lexicon');?></span>
						</a>
					<?php } else { ?>
						<a href="#set_password_popup" class="account_soc_disconnect connectButton connectButton_FB" rel="popup" title="Please, set the password first." style="<?=$this->user->fb_id > 0 ? "" : "display:none"?>">
							<span class="ico"></span><span class="connect_buttonText"><?=$this->lang->line('profile_disconnect_fb_lexicon');?></span>
						</a>
					<?php } ?>
					<a href="" class="account_soc_connect connectButton connectButton_FB" style="<?=$this->user->fb_id > 0 ? "display:none" : ""?>">
						<span class="ico"></span><span class="connect_buttonText"><?=$this->lang->line('profile_connect_fb_lexicon');?></span>
					</a>
					<?php  /*
					<a href="/enable_fb_activity" rel="ajaxButton" class="sharelink_enable" style="<?=$this->user->fb_id > 0 && $this->user->fb_activity == '0' ? "" : "display:none"?>">
						<span class="ico"></span>Activity Sharing is OFF
					</a>
					<a href="/disable_fb_activity" rel="ajaxButton" class="sharelink_disable" style="<?=$this->user->fb_id > 0 && $this->user->fb_activity != '0' ? "" : "display:none"?>">
						<span class="ico"></span>Activity Sharing is ON
					</a>
					*/ ?>
					<span class="error"></span>
				</label>
				
				<label id="twitter_connect_area">
					<?php if ($this->user->password) { ?>
						<a href="/disconnect_twitter" rel="ajaxButton" class="account_soc_disconnect connectButton connectButton_TWT" title="Disconnect Twitter confirmation" style="<?=$this->user->twitter_id > 0 ? '' : 'display:none'?>">
							<span class="ico"></span><span class="connect_buttonText"><?=$this->lang->line('profile_disconnect_tw_lexicon');?></span>
						</a>
					<?php } else { ?>
						<a href="#set_password_popup" rel="popup" class="account_soc_disconnect connectButton connectButton_TWT" title="Disconnect Twitter confirmation" style="<?=$this->user->twitter_id > 0 ? '' : 'display:none'?>">
							<span class="ico"></span><span class="connect_buttonText"><?=$this->lang->line('profile_disconnect_tw_lexicon');?></span>
						</a>
					<?php } ?>
					<a href="" class="account_soc_connect connectButton connectButton_TWT" style="<?=$this->user->twitter_id > 0 ? 'display:none' : ''?>">
						<span class="ico"></span><span class="connect_buttonText"><?=$this->lang->line('profile_connect_tw_lexicon');?></span>
					</a>
					<?php /*
					<a href="/enable_twitter_activity" rel="ajaxButton" class="sharelink_enable" style="<?=$this->user->twitter_id > 0 && $this->user->twitter_activity == '0'? '' : 'display:none'?>">
						<span class="ico"></span>Activity Sharing is OFF
					</a>
					<a href="/disable_twitter_activity" rel="ajaxButton" class="sharelink_disable" style="<?=$this->user->twitter_id > 0 && $this->user->twitter_activity != '0' ? '' : 'display:none'?>">
						<span class="ico"></span>Activity Sharing is ON
					</a>
					*/ ?>
					<span class="error"></span>
				</label>
			</fieldset>
		</form>
	</div>
</div>
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
<?=Html_helper::requireJS(array("profile/edit_ugc"))?>
