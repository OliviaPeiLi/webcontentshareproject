<div class="half facebook">
	<div class="invite_upper">
		<button type="button" class="colourless_button fb_deselect_all inlinediv">Deselect All</button>
		<button type="button" class="blue_bg fb_invite_all_btn inlinediv continue_button loading">Continue</button>
	</div>
	<div class="inviteForm" id="FriendSelectorInput">
        <ul>
            <li>
                <input type="text" name="filter-term" id="search_friends" class="filter-term" placeholder="Type a friend's Name"/>
                <span class="blocker"></span>
            </li>
        </ul>
    </div>
	<ul class="invitesList friend-list">
		<? foreach ($results as $row) { ?>
			<li class="friend" data-fb_id="<?=$row['id']?>" data-full_name="<?=$row['name']?>">
				<?/*?><button type="button" rel="popup" data-url="https://www.facebook.com/dialog/send?app_id=<?=$this->config->item('fb_app_key')?>&name=People%20Argue%20Just%20to%20Win&link=http://www.nytimes.com/2011/06/15/arts/people-argue-just-to-win-scholars-assert.html&redirect_uri=http://www.example.com/response" class="Button13 WhiteButton fb_invite_btn">Invite</button><?*/?>
				<?/*?><button type="button" class="colourless_button fb_invite_btn">Invite</button><?*/?>
				<input type="checkbox" checked="checked" class="colourless_button fb_invite_chk">		
				<?/*?><a class="fb-send-button"><i></i><span>Send</span></a><?*/?>
				<img src="http://graph.facebook.com/<?=$row['id']?>/picture?type=square" alt="Profile picture" class="inviteeUserImage" />
				<div class="person">
					<div class="name"><?=$row['name']?></div>
				</div>
			</li>
		<? } ?>
	</ul>
</div>

<? if($num_invited <5) {?>

	<?php if ($this->is_mod_enabled('invite5')) { ?>
		<div class="users-meter" data-base="<?=max(array(0, 5 - $num_invited))?>">
			<h2>Invite <span><?=max(array(0, 5 - $num_invited))?></span> Facebook friends to unlock the Drop It! button and access the Bookmarklet.</h2>
			<ul>
				<? for($i=0; $i < 5; $i++) { ?>
				<li class="user-ico <?=$i < $num_invited ? 'selected' : ''?>"><span></span></li>
				<? } ?>
			</ul>
		</div>
	<?php } ?>

<? } ?>

<? $this->load->view('invite/follow_friends');?>
