<? $this->lang->load('badge/badge_views', LANGUAGE); ?>
<div class="badge_detail" rel="<?=$user->id?>">
	<div class="badge_left">
		<a href="<?=$user->url?>">
			<?=Html_helper::img($user->avatar_73)?>
		</a>
	</div>
	<div class="badge_right">
		<div class="badge_title"><a href="<?=$user->url?>"><?=$user->full_name?></a><? if($user->role == '1'){ ?><div class="roleTitle"><?=$this->lang->line('staff_writer');?></div>
		<? } elseif($user->role == '3'){ ?><div class="roleTitle"><?=$this->lang->line('featured_user');?></div><? } ?></div>
		<div class="badge_counts">
			<a href="<?=$user->url?>">
				<?=$collections_number?>
				<span class="count_titles"><?=$this->lang->line('badge_views_collections_number_title');?></span> 
			</a>
			<? /* ?><span class="badge_middot">&middot;</span><? */ ?>
			<a href="<?=$user->drops_url?>">
				<?=$drops_count?>
				<span class="count_titles"><?=$this->lang->line('badge_views_drops_title');?></span> 
			</a>
			<? /* ?><span class="badge_middot">&middot;</span><? */ ?>
			<a href="<?=$user->likes_url?>">
				<?=$upvotes_count?>
				<span class="count_titles"><?=$this->lang->line('badge_views_likes_title');?></span> 
			</a>
		</div>
		<div class="badge_options"></div>		
		<? if($user->id != $this->session->userdata('id')){ ?>
			<div id="follow_button_div_badge_<?=$user->id?>" class="follow_button_class">
				<button class="button blue_bg follow_button_align unfollow_button request_unfollow" rel="ajaxButton" data-url="/unfollow_user/<?=$user->id?>" style="<?=$check_friends ? 'display:block' : 'display:none'?>"><?=$this->lang->line('badge_views_following_btn');?></button>
				<button class="button blue_bg follow_button_align request_follow" rel="ajaxButton" data-url="/follow_user/<?=$user->id?>" style="<?=$check_friends ? 'display:none' : 'display:block'?>"><?=$this->lang->line('badge_views_follow_btn');?></button>
			</div>
		<? } ?>
	</div>
</div>