<? $this->lang->load('invite/profile', LANGUAGE); ?>
<div class="inviteFollow">
	<div class="inviteFollow_head">
		<h3><?=$this->lang->line('invite_follow_friends_title');?></h3>
		<button class="button inviteButton request_follow_all blueButton"><?=$this->lang->line('invite_follow_all_lexicon');?></button>
	</div>
	<ol class="invitesList">
		<? foreach($registered as $user) { ?>
		<? if ( $user->email == $this->user->email ) { continue; } ?>
			<li class="fandropFriend">
				<a href="#" class="fandropFriend_image"><?=Html_helper::img($user->avatar_25, array('class'=>"inviteeUserImage", 'alt'=>"Profile picture of Dan Lawrence"))?></a>
				<span class="name"><a href="<?=$user->url?>"><?=$user->full_name?></a></span>
				<div class="follow_button_class inlinediv">
					<?php $is_follow = $this->user->is_following($user) ?>
					<button data-url="/unfollow_user/<?=$user->id?>" rel="ajaxButton" class="button inviteButton greyButton unfollow_button request_unfollow" style="<?=$is_follow ? '' : 'display:none'?>"><?=$this->lang->line('following');?></button>
					<button data-url="/follow_user/<?=$user->id?>" rel="ajaxButton" class="button inviteButton blueButton request_follow" style="<?=$is_follow ? 'display:none' : ''?>"><?=ucfirst($this->lang->line('follow'));?></button> 	
				</div>
			</li>
		<? } ?>
	</ol>
</div>