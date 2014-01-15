<div id="inviteContainer" class="FB_only">

	<div id="invite_title">
		<h1>Facebook Friends</h1>
	</div>

	<div id="invite_body">
		<div id="inviteRight">
			<div>
				<h2 style="line-height: 24px;">Select all friends who you would like to share fandrop with, then click continue to send request via Facebook.</h2>
			</div>
			<!-- Facebook Autentication -->
			<div id="facebookInviter" class="inviteContentNEW">
				<div class="clr">
					<div id="facebook-connector-button">
						<?=$this->load->view('invite/invite_facebook')?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	php.picture = '<?=$this->user->avatar?>';
	php.fb_invite_description = "<?=$this->user->first_name?> is on Fandrop, a place to collect and share the best discoveries on the web. Join Fandrop to see <?=$this->user->first_name?>'s stories and start dropping!";
	php.fb_invite_message = "<?=$this->user->first_name?> is on Fandrop. Join to see his or her stories";
	php.fb_invite_title = "Join me on Fandrop!";
	php.basepath = '<?=BASEPATH.'signup?a=fb&b=b87jgzfke5'?>';
	php.user_id = '<?=$this->session->userdata('id')?>';
	php.username = '<?=$this->user->uri_name?>';
	php.redirect_to = '<?=Url_helper::base_url()?>';
</script>
<?=Html_helper::requireJS(array("profile/invite"))?>
