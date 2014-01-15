<? $this->lang->load('invite/profile', LANGUAGE); ?>
<div class="row">
	<div class="span18 inviteHead">
		<h2><?=$this->lang->line('invite_ginvite_heading');?></h2>
	</div>
</div>
<div class="row">
	<div class="span9 inviteBody">
		<div class="inviteBody_head">
			<div class="inviteBody_searchBox">
				<input type="text" name="filter-term" placeholder="<?=$this->lang->line('invite_friend_name_placeholder');?>" id="search_friends"/>
				<span class="blocker"></span>
			</div>
		</div>
		<div class="inviteBody_users">
			<?php foreach($results as $row){ ?>
				<label class="inviteBody_userUnit"  data-email="<?=$row['email']?>" data-full_name="<?=$row['name']?>">
					<span class="inviteBody_userUnit_imageContainer">
						<?=Html_helper::img($this->user_model->behaviors['uploadable']['avatar']['default_image'], array('alt'=>'Profile Pic', 'class'=>'inviteeUserImage'))?>
					</span>
					<span class="name">
						<?=$row['name']?>
						<?php if ($row['name'] != $row['email']) { ?>
							<small><?=$row['email']?></small>
						<?php } ?>
					</span>
					<button type="button" rel="popup" data-url="#invite_popup" class="blueButton inviteButton"  title="<?=$row['name'];?>" data-email="<?=$row['email'] != "" ? $row['email'] : $row['name'];?>" data-msgsuccess="<?=$this->lang->line('invite_invited_message');?>" style="<?=in_array($row['email'], $invited) ? 'display:none' : '';?>"><?=$this->lang->line('invite_invite_lexicon');?></button>
					<button type="button" class="greyButton inviteButton invited" style="<?=in_array($row['email'], $invited) ? '' : 'display:none';?>" disabled><?=$this->lang->line('invite_invited_message');?></button>					
					<!--
					<? if(!in_array($row['email'], $invited)) {?>
						<button type="button" rel="popup" data-url="#invite_popup" class="blueButton inviteButton" title="<?=$row['name'];?>">Invite</button>
					<? } else {?>
						<button type="button" class="greyButton inviteButton invited">Invited</button>
					<? } ?>
					-->
				</label>
			<?php } ?>
		</div>
	</div>
	<div class="span8">
		<div class="followBody">
			<? $this->load->view('invite/follow_friends_ugc');?>
		</div>
	</div>
</div>
	
<? $this->load->view('invite/invite_popup_ugc')?>