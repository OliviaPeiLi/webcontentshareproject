	<div id="notification_friendfollowText">Based on the hashtags you chose, we had you follow these users.</div>
	<span id="notification_friendfollowContainer">
		<? foreach($this->user->user_followings as $connection) { ?>
			<span class="inlinediv show_badge newsfeed_entry_avatar_user user_avatar">
	    		<?=Html_helper::img($connection->user2->avatar, array('class'=>"newsfeed_avatar_img"))?>
	       	</span>
		<? } ?>
    </span>