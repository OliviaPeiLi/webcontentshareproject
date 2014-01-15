<div class="half right">
	<h3 class="clearfix">
		<button class="button request_follow_all blue_bg">Follow All</button>
		Friends on Fandrop
	</h3>
	<ol class="invitesList">
		<? foreach($registered as $user) { ?>
			<? if ( $user->email == $this->user->email ) { continue; } ?>
			<li class="fandropFriend">
				<a href="#"><?=Html_helper::img($user->avatar_25, array('class'=>"inviteeUserImage", 'alt'=>"Profile picture of Dan Lawrence"))?></a>
				<span class="name"><a href="<?=$user->url?>"><?=$user->full_name?></a></span>
				<div class="follow_button_class inlinediv">
					<?php $is_follow = $this->user->is_following($user) ?>
					<button data-url="/unfollow_user/<?=$user->id?>" rel="ajaxButton" class="button unfollow_button request_unfollow" style="<?=$is_follow ? '' : 'display:none'?>">Following</button>
					<button data-url="/follow_user/<?=$user->id?>" rel="ajaxButton" class="button blue_bg request_follow" style="<?=$is_follow ? 'display:none' : ''?>">Follow</button> 	
				</div>
			</li>
		<? } ?>
	</ol>
</div>
