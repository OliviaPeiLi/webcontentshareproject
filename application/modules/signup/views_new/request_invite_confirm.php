<? $this->lang->load('signup/signup', LANGUAGE) ?>
<div id="login_wrapper" class="container_24">
	<div class="signup_container request_invite">
		<div class="signup_content">
			<? if ( isset($exist) && $exist == 1 ) { ?>
				<h3><?=sprintf($this->lang->line('signup_request_invite_exist_user'), $email)?></h3>
			<? } else { ?>
				<h3><?=sprintf($this->lang->line('signup_request_invite_response'), $email)?></h3>
			<? } ?>
		</div>
	</div>
</div>

<?=Html_helper::requireJS(array('includes/header_lean'))?> 
