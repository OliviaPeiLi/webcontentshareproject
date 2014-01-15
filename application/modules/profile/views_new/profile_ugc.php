<? $this->lang->load('profile/profile', LANGUAGE); ?>
<div id="profile" class="container" style="margin-top: 90px" data-id="<?=$profile_user->id;?>">
	<div class="row">
		<div class="span8 currentUser_panel" id="mainProfileInfo">
			<div class="currentUser_panelUpper">
				<div class="currentUser_panelFollowBox">
					<?=Html_helper::img($profile_user->avatar_73) ?>
					<?php if ($profile_user->id == @$this->user->id) { ?>
						<a href="/profile/edit_picture" class="avatarPopup" rel="popup" title="<?=$this->lang->line('profile_change_avatar_lexicon');?>"><?=ucfirst($this->lang->line('change'));?></a>
						<a href="/account_options" class="actionButton currentUser_panelUnfollow"><?=$this->lang->line('profile_edit_profile_lexicon');?></a>
					<?php } else if ($this->is_mod_enabled('follow')) { ?>
						<? $is_follow = $profile_user->is_follow($this->session->userdata('id'))?>
						<a href="/unfollow_user/<?=$profile_user->id?>" class="actionButton currentUser_panelUnfollow" rel="ajaxButton" style="<?=$is_follow ? '' : 'display:none'?>"><?=$this->lang->line('following');?></a>
						<a href="/follow_user/<?=$profile_user->id?>" class="actionButton currentUser_panelFollow" rel="ajaxButton" style="<?=$is_follow ? 'display:none' : ''?>"><?=ucfirst($this->lang->line('follow'));?></a>
					<?php }?>
				</div>
				<div class="currentUser_panel_infoPane">
					<h2><?=$profile_user->full_name?><?=@$this->user->role==2 ? ' '.$profile_user->id : ''?></h2>
					<div class="currentUser_panel_bio"><p><?=nl2br_except_pre(strip_tags(auto_typography($profile_user->about)))?></p><div class="currentUser_panel_bioGrad"></div></div>
				</div>
			</div>
			<?php if ($this->is_mod_enabled('follow')) { ?>
			<div class="currentUser_panel_followPane">
				<a href="/followers/<?=$profile_user->uri_name?>/" class="followersButton followButton inlinediv"><span><?=$profile_user->user_stat->followers_count?></span> <?=$this->lang->line('followers');?></a><a href="/followings/<?=$profile_user->uri_name?>/" class="followingButton followButton inlinediv"><span><?=$profile_user->user_stat->followings_count?></span> <?=$this->lang->line('following');?></a>
			</div>
			<?php } ?>
			<div class="currentUser_panel_pagePane">
				<a href="/<?=$profile_user->uri_name?>" class="<?=$type == 'collections' ? 'active' : ''?> pageSelect_button">
					<strong><?=$this->lang->line('profile_stories_lexicon');?></strong> <?=($profile_user->id == $this->session->userdata('id') ? $profile_user->user_stat->collections_count : $profile_user->user_stat->public_collections_count)?>
				</a>
				<? /* <a href="/<?=$profile_user->uri_name?>" class="<?=$type == 'drops' ? 'active' : ''?>">
					<strong>Drops</strong> <?=$profile_user->user_stat->drops_count?>
				</a>*/ ?>
				<a href="/upvotes/<?=$profile_user->uri_name?>" class="<?=$type == 'upvotes' ? 'active' : ''?> pageSelect_button">
					<strong><?=$this->lang->line('profile_upvotes_lexicon');?></strong> <?=$total_upvotes?>
				</a>
				<?php /* FD-5181
				<a href="/mentions/<?=$profile_user->uri_name?>" class="<?=$type == 'mentions' ? 'active' : ''?> pageSelect_button">
					<strong><?=$this->lang->line('profile_mentions_lexicon');?></strong> <?=$public_mentions?>
				</a>
				*/ ?>
				<?php if ($this->is_mod_enabled('contests')) { ?>
					<a href="/contests/<?=$profile_user->uri_name?>" class="<?=$type == 'contests' ? 'active' : ''?> pageSelect_button">
						<strong><?=$this->lang->line('profile_contests_lexicon');?></strong> <?=$profile_user->user_stat->contests_count?>
					</a>
				<?php } ?>
			</div>
		</div>
		<div class="span15">
			<? if ($type == 'collections') { ?>
				<?=Modules::run('profile/profile_folder/'.$type, $profile_user->id, true)?>
			<? } elseif ($type == 'contests') { ?>
				<?=Modules::run('profile/profile_contests/'.$type, $profile_user->id)?>
				
			<? } elseif (in_array($type, array('drops', 'upvotes', 'mentions'))) { ?>
				<?=Modules::run('profile/profile_folder/'.$type, $profile_user->id, true)?>
				<?php /*
				<?=Modules::run('profile/profile_newsfeed/'.$type, $profile_user->id, $filter)?>
				*/ ?>
				
			<? } elseif (in_array($type, array('followings', 'followers'))) { ?>
				<?=Modules::run('profile/profile_connection/'.$type, $profile_user->id)?>
				
			<? } elseif (in_array($type, array('settings'))) { ?>
				<?=Modules::run('profile/profile/edit')?>
				
			<? } elseif (in_array($type, array('info'))) { ?>
				<?=Modules::run('profile/profile/user_info', $profile_user->id)?>
				
			<? } else { ?>
				<?=$this->lang->line('profile_subpage_not_found_err');?>: <?=$type?>
			<? } ?>
		</div>
	</div>
</div>
<?=Html_helper::requireJS(array("profile/profile_ugc"))?> 
