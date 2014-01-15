<?php foreach ($users as $user) { ?>
<?php if ($user->id <= 0) echo '
	<script type="template/html" id="user-template"
		data-id = "button @data-url"
		data-_url = ".js-user > a @href, .user_badge_right > a @href, .js-user-collections @href"
		data-_drops_url = ".js-user-drops @href"
		data-_likes_url = ".js-user-votes @href"
		data-_avatar_73 = ".js-user > a img @src"
		data-_full_name = ".user_image @title, .js_user_name"
		data-_user_stat-_collections_count = ".js-user-collections strong"
		data-_user_stat-_drops_count = ".js-user-drops strong"
		data-_user_stat-upvotes_count = ".js-user-votes strong"
	>' ?>
	<li>
		<div class="js-user">
			<a href="<?=$user->url?>">
				<?=Html_helper::img($user->avatar_73, array('title'=>$user->full_name, 'width'=>"30", 'alt'=>"", "class"=>"user_image"))?>
			</a>
			<div class="user_badge_right">
				<a href="<?=$user->url?>" class="js_user_name"><?=$user->full_name?></a>
					<div class="badge_counts">
						<a href="<?=$user->url?>" class="js-user-collections">
							<strong><?=@$user->id == $this->session->userdata('id') ? $user->user_stat->collections_count : $user->user_stat->public_collections_count?></strong>
							<span class="count_titles">Stories</span> 
						</a>
						<?php /* <a href="<?=$user->drops_url?>" class="js-user-drops">
							<strong><?=@$user->user_stat->drops_count?></strong>
							<span class="count_titles">Drops</span> 
						</a>
						*/ ?>
						<a href="<?=$user->likes_url?>" class="js-user-votes">
							<strong><?=$user->user_stat->upvotes_count?></strong>
							<span class="count_titles">Upvotes</span> 
						</a>
					</div>
				<? if($user->id != $this->session->userdata('id')){ ?>
					<span>
						<?php $is_following = @$this->user && $this->user->is_following($user) ?>
						<?php if($this->session->userdata('id')) : ?>
						<button data-url="/unfollow_user/<?=$user->id?>" rel="ajaxButton" class="button lightBlue_bg greyButton follow_button_align followPerson_button unfollow_button request_unfollow" style="<?=$is_following? '' : 'display:none'?>">Following</button>
						<button data-url="/follow_user/<?=$user->id?>" rel="ajaxButton" class="button lightBlue_bg blueButton follow_button_align followPerson_button request_follow" style="<?=$is_following? 'display:none' : ''?>">Follow</button>
						<?php else : ?>
						<a href="/signin?redirect_url=<?=urlencode($_SERVER['REQUEST_URI']) ?>" class="button lightBlue_bg follow_button_align request_follow">Follow</a>
						<?php endif; ?>
					</span>
				<? }?>
			</div>
			<? /*
			<div class="obj_id" style="display:none"><?=$user->id?></div>
			<div class="offset" style="display:none">
				<div class="x">0</div>
				<div class="y">0</div>
			</div>
			*/ ?>
		</div>
	</li>
<?=$user->id <= 0 ? '</script>' : ''?>
<?php } ?>