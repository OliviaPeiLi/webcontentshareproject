<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<!-- email send newsfeed template -->
<div id="share_email_form_wrap" style="display: none;">
	<?=Form_Helper::open('share_email', array('id'=>'share_email_form', 'rel'=>'ajaxForm',"data-required_login"=>"false"))?>
		<ul style="width:100%">
<!--
			<li class="form_row">
				<div class="text_align_left form_field inlinediv">
					<input id="share_name_to" style="width: 400px; " placeholder="Recipient Name:" name="share_name_to" value="" type="text" data-validate="required" data-error-required="Recipient Name can't be blank">
				</div>
				<div class="error"></div>
			</li>
-->
			<li class="form_row">
				<div class="text_align_left form_field inlinediv addEmailRecipients">
					<?=Form_Helper::input('share_email_to[]', '', array(
						'id' => 'share_email_to',
						'class' => 'tokenInput',
						'allow_insert' => 'true',
						'showDropdown_on_focus' => 'false',
						'data-validate' => 'required|email',
						'data-error-email' => "Doesn't look like a valid email.",
						'data-error-required' => "An email is required!",
						'hint_text' => '',
						'searching_text' => '',
						'no_result_text' => '',
						'placeholder' => 'Recipient Email:',
						'style' => 'width: 400px'
					))?>
				</div>
				<span class="hint">Use "Tab" to input multiple email address</span>
				<div class="error"></div>
			</li>
			<li class="form_row">
				<div class="text_align_left form_field inlinediv">
					<textarea placeholder="Message (optional)" rows="5" data-maxlength="250" style="width:400px; height: 70px;" name="share_email_body" id="share_email_body"></textarea>
					<div class="textLimit">250</div>
				</div>
			</li>
			<li>
				<div>
					<input type="hidden" name="newsfeed_id" id="share_email_newsfeed_id" value="" />
					<?=Form_Helper::submit('submit', 'Send Email', array('id'=>"share_email_button", 'class'=>"blue_bg s_button"))?>
				</div>
			</li>
		</ul>
	<?=Form_Helper::close()?>
</div>
<div id="share-email-message" class="modal" style="display: none;">
	<div class="success-msg">
		<div class="text_loading">Your message was sent</div>
	</div>
</div>
<?=Html_helper::requireJS(array('share/share_email_share'))?>
<!-- end of email send newsfeed template -->