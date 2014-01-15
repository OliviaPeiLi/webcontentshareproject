<? $this->lang->load('home/home_views', LANGUAGE); ?>
<div id="home" class="container">
	<div class="row">
		<div class="span15">
			<?=modules::run('homepage/home_folders/'.$category_type, $sort_by, $filter) ?>
		</div>
		<div class="span8 currentUser_panel">
			<div class="currentUser_panelUpper">
				<?=Html_helper::img($this->user->avatar_73) /* _thumb may be used instead of _badge*/?>
				<div class="currentUser_panel_accountButtons inlinediv">
					<? /* RR - messages are not ready yet
					<a href="/messages" class="accountButton messageButton"><span class="ico"></span><span class="accountButton_text">Messages</span></a>
					*/ ?>
					<a href="/account_options" class="accountButton optionsButton"><span class="ico"></span><span class="accountButton_text"><?=$this->lang->line('home_preferences_lexicon');?></span></a>
					<?php if ($this->user->twitter_id) { ?>
						<a href="http://twitter.com/account/redirect_by_id?id=<?=$this->user->twitter_id?>" class="accountButton twitterButton" target="_blank"><span class="ico"></span><span class="accountButton_text">Twitter</span></a>
					<?php } ?>
					<?php if ($this->user->fb_id) { ?>
						<a href="http://facebook.com/<?=$this->user->fb_id?>" class="accountButton facebookButton" target="_blank"><span class="ico"></span><span class="accountButton_text">Facebook</span></a>
					<?php } ?>
				</div>
			</div>
			<?php if ($this->is_mod_enabled('follow')) { ?>
			<div class="currentUser_panel_followPane">
				<a href="/followers/<?=$this->user->uri_name?>/" class="followersButton followButton inlinediv"><span><?=$this->user->user_stat->followers_count?></span> <?=$this->lang->line('followers');?></a><a href="/followings/<?=$this->user->uri_name?>/" class="followingButton followButton inlinediv"><span><?=$this->user->user_stat->followings_count?></span> <?=$this->lang->line('following');?></a>
			</div>
			<?php } ?>
			<div class="currentUser_panel_uploadPane">
				<a href="/create_list" class="blueButton uploadButton"><?=$this->lang->line('home_upload_lexicon');?></a>
			</div>
			<div class="currentUser_panel_postsPane">
				<a href="/<?=$this->user->uri_name?>" class="actionButton previousPosts"><?=$this->lang->line('home_previous_posts_lexicon');?></a>
			</div>
		</div>
	</div>
</div>