<div class="half">
	<h3 class="inlinediv">Invite Friends</h3>
	<button type="button" class="colourless_button fb_deselect_all inlinediv">Deselect All</button>
	<button type="button" class="colourless_button fb_invite_all_btn inlinediv loading" style=="margin-left:10px;">Invite</button>
	<div class="inviteForm" id="FriendSelectorInput">
        <ul>
            <li>
                <input type="text" name="filter-term" id="search_friends" class="filter-term" placeholder="Type a friend's Name"/>
                <span class="blocker"></span>
            </li>
        </ul>
    </div>
	<ul class="invitesList friend-list">
		<?php
		if (isset($results)) {
			foreach($results as $row){
			?>
			<li class="friend" data-fb_id="<?=$row->friend_id?>" data-fullname="<?=$row->friend_name?>">
				<? /* ?>
				<button type="button" rel="popup" data-url="https://www.facebook.com/dialog/send?app_id=<?=$this->config->item('fb_app_key')?>&name=People%20Argue%20Just%20to%20Win&link=http://www.nytimes.com/2011/06/15/arts/people-argue-just-to-win-scholars-assert.html&redirect_uri=http://www.example.com/response" class="Button13 WhiteButton fb_invite_btn">Invite</button>
				<? */ ?>
				<? /* ?>
				<button type="button" class="colourless_button fb_invite_btn">Invite</button>	
				<? */ ?>
				<input type="checkbox" checked="checked" class="colourless_button fb_invite_chk" data-fbid="<?=$row->friend_id?>">		
				<? /* ?>
				<a class="fb-send-button"><i></i><span>Send</span></a>
				<? */ ?>
				<img alt="Profile picture" src="http://graph.facebook.com/<?=$row->friend_id?>/picture?type=square" class="inviteeUserImage" />
				<div class="person">
					<div class="name"><?php echo $row->friend_name;?></div>
				</div>
			</li>
			<?php
			}
		}
		?>
	</ul>
</div>


	<?=$this->load->view('invite/friends_from_invite','',true);?>
