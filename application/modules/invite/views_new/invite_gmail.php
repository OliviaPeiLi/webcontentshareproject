<div class="half">
	<h3>Invite Friends</h3>
	<div class="inviteForm" id="FriendSelectorInput">
		<ul>
			<li>
				<input type="text" id="search_friends" name="filter-term" class="filter-term" />
				<label class="filter-term">Type a Friend's Name</label>
				<span class="blocker"></span>
			</li>
		</ul>
	</div>

	<ul class="invitesList friend-list">		
		<?php foreach($results as $row){ ?>
			<li class="friend" data-full_name="<?=$row['name']?>" data-email="<?=$row['email']?>">
				<? if(!in_array($row['email'], $invited)) {?>
					<button type="button" rel="popup" data-url="#invite_popup" class="colourless_button invite_btn">Invite</button>
				<? } else {?>
					<button type="button" class="colourless_button disabled_button">Invited</button>
				<? } ?>
				<?=Html_helper::img($this->user_model->behaviors['uploadable']['avatar']['default_image'], array('alt'=>'Profile Pic', 'class'=>'inviteeUserImage'))?>
				<div class="person">
					<div class="name"><?=$row['name']?></div>
					<?php if ($row['name'] != $row['email']) { ?>
						<div class="email"><?=$row['email']?></div>
					<?php } ?>
				</div>
			</li>
		<?php } ?>
	</ul>
</div>

<? $this->load->view('invite/follow_friends');?>
	
<? $this->load->view('invite/invite_popup');?>