<? if ( !isset( $is_collection_page ) ) {
	$is_collection_page = false;
} ?>
<div id="emailInvite_box" style="display:none">
	<div class="emailInviteSignup_box js-landing-signup <?= $is_collection_page ? 'withBlueCircle' : '' ?>">
		<? if ( $is_collection_page ) { ?>
		<div class="inlinediv emailInviteSignup_blueCircle"><?=Html_helper::img("request_invite_popup/blue-circle.png")?></div>
		<? } ?>
		<div class="<?= $is_collection_page ? 'emailInviteSignup_right inlinediv' : '' ?>">
			<div class="emailInviteSignup_text1 js-invite-text">
			<? if ((isset($folder) && $folder && $folder->type == 1) || (isset($newsfeed) && $newsfeed->folder->type == 1)) { ?>
				Enter your email to complete the Step
			<? } else if (isset($contest) && $contest->url == 'fndemo') { ?>
				Enter Email to Submit Your Vote
			<? } else if ( $is_collection_page ) { ?>
				Sign up now and show us what you've got!
			<? } else { ?>
				Be the first to find out when Fandrop is ready
			<? } ?>
			</div>
			<div class="emailInviteSuccess_text js-invite-success" style="display:none">Thank you!</div>
			<? /*<div class="emailInviteSignup_text2">We'll let you know when it's ready.</div>*/ ?>
			<div class="emailInviteSignup_textarea js-invite-init">
				<?=Form_Helper::open('/request_invite', array('class'=>'newsfeed_list_new_comment public', 'rel'=>'ajaxForm', 'data-type'=>'json', 'data-nopopupinfo'=>'true')); ?>
					<div class="emailInviteSignup_inputContainer"><input name="email" type="text" placeholder="Enter your email" class="js-emailInviteSignup_input input_placeholder_enh"><span class="emailInviteSignup_goButton"><input type="submit" value=""></span></div>
					<? /* ?><input name="email" type="text" placeholder="Enter your email"><span class="emailInviteSignup_goButton"><input type="submit" value="">Go<span class="emailInvitegoArrow"></span></span> // Old Version<? */ ?>
					<div class="emailInviteSuccess_text js-success success" style="display:none">Thank you! Your email has been submitted to fandrop waiting list. You will receive email when we open the sign up.</div>
				<?=Form_Helper::close()?>
			</div>
			<div class="js-invite-error requestInvite_error" style="display:none"></div>
		</div>
	</div>
</div>
<?=Html_helper::requireJS(array('signup/request_invite_popup'))?>