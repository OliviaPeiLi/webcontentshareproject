<? $this->lang->load('invite/profile', LANGUAGE); ?>
<div class="row">
	<div class="span18 inviteHead">
		<h2>Facebook</h2>
	</div>
</div>
<div class="row">
	<div class="span9 inviteBody">
		<div class="inviteBody_head">
			<div class="inviteBody_headButtons">
				<button type="button" class="greyButton inviteButton fb_deselect_all inlinediv"><?=$this->lang->line('invite_deselect_all_lexicon');?></button>
				<button type="button" class="blueButton inviteButton blue fb_invite_all_btn inlinediv continue_button loading"><?=$this->lang->line('fb_warning_confirm_yes');?></button>
			</div>
			<div class="inviteBody_searchBox">
				<input type="text" name="filter-term" id="search_friends" placeholder="<?=$this->lang->line('invite_friend_name_placeholder');?>" />
				<span class="blocker"></span>
			</div>
		</div>
		<div class="inviteBody_users">
			<? foreach ($results as $row) { ?>
				<label class="inviteBody_userUnit" data-fb_id="<?=$row['id']?>" data-full_name="<?=$row['name']?>">
					<span class="inviteBody_userUnit_imageContainer">
						<img src="http://graph.facebook.com/<?=$row['id']?>/picture?type=small" alt="Profile picture"/>
					</span>
					<span class="name"><?=$row['name']?></span>
					<input type="checkbox" checked="checked">	
				</label>
			<? } ?>
		</div>
	</div>
	<div class="span8">
		<div class="followBody">
			<? $this->load->view('invite/follow_friends_ugc');?>
		</div>
		
	</div>
</div>