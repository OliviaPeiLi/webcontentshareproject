<div id="profile_top" class="top_container">
	<div id="profile_top_middle">
		<? //All user profile info ?>
		<div class="profile_info">
			<div class="profile_info_left inlinediv">
				<div id="profile_picAndFollow" class="inlinediv">
					<div id="profile_pic_pic" class="inlinediv">
						<div id="profile_profilePic">
							<? if ($profile_user->id == $this->session->userdata('id')) { ?>
								<a href="/profile/edit_picture" id="link_to_edit_photo" rel="popup" title="Change Photo">
									<span><span class="link_to_edit_photo_icon"></span><span class="link_to_edit_photo_text">Change</span></span>
									<?=Html_helper::img($this->user->avatar_73, array('id'=>"profilePic_auth", 'border'=>"0", 'width'=>"100%", 'alt'=>"", 'onerror'=>"if (this.src.indexOf('indigo_thumb.png') == -1 ) this.src = '".$this->user_model->behaviors['uploadable']['avatar']['default_image']."'"))?>
								</a>
							<? } else { ?>
								<a href="javascript:;" id="link_to_profile_album">
									<?=Html_helper::img($profile_user->avatar_73, array('id'=>"profilePic", 'border'=>"0", 'width'=>"100%", 'alt'=>"", 'onerror'=>"if (this.src.indexOf('indigo_thumb.png') == -1 ) this.src = '".$this->user_model->behaviors['uploadable']['avatar']['default_image']."'"))?>
								</a>
							<? } ?>
							<div class="clear"></div>
						</div>
					</div>
					<div id="profile_name_folders" class="inlinediv">
						<div id="profile_name">
							<a href="<?=$profile_user->url?>" class="inlinediv h3_substitute">
								<?=$profile_user->full_name?>
								<? if ($this->user && $this->user->role == 2) { ?>
									(<?=$profile_user->id?>)
								<? } ?>
							</a>	
							<? if ($profile_user->verified) { ?>
								<?=Html_helper::img("verified.png", array(
										'id'=>"verifiedOfficial_account",
										'class'=>"custom-tooltip",
										'title-pos'=>"bottom",
										'title-class'=>"tab_label_1",
										'title'=>"Verified"
								))?>
							<? } ?>
							<span class="roleTitle">
							<? if ($profile_user->role == '1') { ?>
								<?=$this->lang->line('staff_writer');?>
							<? } elseif ($profile_user->role == '3') {?>
								<?=$this->lang->line('featured_user');?>
							<? } ?>
							</span>
							<? if($profile_user->id == $this->session->userdata('id')) { ?>
								<a class="button profile_edit_btn" href="/account_options"><span class="profile_edit_btn_contents"></span><span class="profile_edit_btn_text">Edit</span></a>
							<? } else { ?>
								<!-- An Edit Button sits here with the class of button and profile_edit_btn -->
							<? } ?>
						</div>
						<div id="profile_about">
							<?php if ($profile_user->about) { ?>
							<div class="profile_about_text_container">
								<span class="profile_about_start">Bio: </span>
								<span class="profile_about_text"><?=nl2br_except_pre(strip_tags(auto_typography($profile_user->about)))?></span>
							</div>
							<div id="profile_about_more" style="display:none;">+ More</div>
							<?php } ?>
						</div>
						<div id="profile_button_holder">
							<div id="profile_followerFollowing_count" class="inlinediv">
								<? /* ?><a class="" id="collections_top_stat" href="<?=$profile_user->url?>">
									<span class="top_stats_number"><?=($profile_user->id == $this->session->userdata('id') ? $profile_user->user_stat->collections : $profile_user->user_stat->public_collections)?> </span> Collections
								</a><? */ ?>
								<a class="followingFollowers_top_stat <?=$type == 'followings' ? 'active' : ''?>" href="/followings/<?=$profile_user->uri_name?>">
									<span class="top_stats_number"><?=$profile_user->user_stat->followings_count?></span><span class="top_stats_text">Following</span>
								</a>
								<a class="followingFollowers_top_stat <?=$type == 'followers' ? 'active' : ''?>" href="/followers/<?=$profile_user->uri_name?>">
									<span class="top_stats_number"><?=$profile_user->user_stat->followers_count?></span><span class="top_stats_text">Followers</span>
								</a>
							</div>
							<div id="profile_buttons" class="inlinediv">
								<? if ($profile_user->id == $this->session->userdata('id')) { ?>
									<? if ($fb_err = $this->session->flashdata('fb_error')) { ?>
										<span id="fb_error"><?=$fb_err?></span>
									<? } ?>
									<? if ($tw_err = $this->session->flashdata('twitter_error')) { ?>
										<span id="tw_error"><?=$tw_err?></span>
									<? } ?>
								<? } ?>
								<div id="ext_networks" class="inlinediv">
									<? /* ?>//Uncomment this later on...
									<? if ($profile_user->id == $this->session->userdata('id')) { ?>
										<div id="ext_network_my_profile">
											<? if ($profile_user->fb_id != 0) { ?>
												<a id="profile_fb_1" href="http://www.facebook.com/profile.php?id=<?=$profile_user->fb_id?>" target="_blank" class="connected"><span class="custom-tooltip" title-pos="bottom" title-class="tab_label_1" title="<?=$profile_user->first_name ?>'s Facebook"></span></a>
											<? } else { ?>
												<a id="profile_fb_1" href="" class="disconnected"><span class="custom-tooltip" title-pos="bottom" title-class="tab_label_1" title="Connect to Facebook"></span></a>
											<? } ?>
											<? if ($profile_user->twitter_id != 0) { ?>
												<a id="profile_twtr_1" href="https://twitter.com/account/redirect_by_id?id=<?=$profile_user->twitter_id?>" target="_blank" class="connected"><span class="custom-tooltip" title-pos="bottom" title-class="tab_label_1" title="<?=$profile_user->first_name ?>'s Twitter"></span></a>
											<? } else { ?>
												<a id="profile_twtr_1" href="/connect_twitter?next=profile" class="disconnected"><span class="custom-tooltip" title-pos="bottom" title-class="tab_label_1" title="Connect to Twitter"></span></a>
											<? } ?>
										</div>
									<? } else { ?>
										<div id="ext_network_else_profile">
											<? if ($profile_user->fb_id != 0) { ?>
												<a id="profile_fb_1" href="http://www.facebook.com/profile.php?id=<?=$profile_user->fb_id?>" target="_blank"><span class="custom-tooltip" title-pos="bottom" title-class="tab_label_1" title="<?=$profile_user->first_name ?>'s Facebook""></span></a>
											<? } ?>
											<? if ($profile_user->twitter_id != 0) { ?>
												<a id="profile_twtr_1" href="https://twitter.com/account/redirect_by_id?id=<?=$profile_user->twitter_id?>" target="_blank"><span class="custom-tooltip" title-pos="bottom" title-class="tab_label_1" title="<?=$profile_user->first_name ?>'s Twitter"></span></a>
											<? } ?>
										</div>
									<? } ?>
									<? */ ?>
								</div>
								<? if($profile_user->id != $this->session->userdata('id')) { ?>
									<div class="follow_button_class">
										<? $is_follow = $profile_user->is_follow($this->session->userdata('id'))?>
										<button class="button unfollow_button request_unfollow" rel="ajaxButton" data-url="/unfollow_user/<?=$profile_user->id?>" style="<?=$is_follow ? '' : 'display:none'?>">Following</button>
										<button class="button blue_bg request_follow" rel="ajaxButton" data-url="/follow_user/<?=$profile_user->id?>" style="<?=$is_follow ? 'display:none' : ''?>">Follow</button>
									</div>
								<? } else { ?>
									<!-- The Follow/Following Button sits here with the classes of "button blue_bg request_follow" and "button unfollow_button request_unfollow" -->
								<? } ?>
								<a class="button profile_info_btn" href="/info/<?=$profile_user->uri_name?>"><span class="profile_info_btn_text">Info</span></a>
							</div> <!--  End #profile_buttons -->
						</div>
					</div> <!-- End #profile_name_folders -->
				</div>
			</div> <!-- End .profile_info_top -->

			<div class="profile_info_right inlinediv">
				<div class="profile_info_stats">
					<a href="<?=$profile_user->url?>" class="<?=$type == 'collections' ? 'active' : ''?>">
						<span>
							<strong><?=($profile_user->id == $this->session->userdata('id') ? $profile_user->user_stat->collections_count : $profile_user->user_stat->public_collections_count)?></strong>
							<small>Stories</small>
						</span>
					</a>
					<a href="<?=$profile_user->drops_url?>" class="<?=$type == 'drops' ? 'active' : ''?>">
						<span>
							<strong><?=$profile_user->user_stat->drops_count?></strong>
							<small>Drops</small>
						</span>
					</a>
					<a href="<?=$profile_user->likes_url?>" class="<?=$type == 'upvotes' ? 'active' : ''?>">
						<span>
							<strong><?=$profile_user->user_stat->upvotes_count?></strong>
							<small>Upvotes</small>
						</span>
					</a>
					<a href="<?=$profile_user->mentions_url?>" class="<?=$type == 'mentions' ? 'active' : ''?>">
						<span>
							<strong><?=$profile_user->user_stat->mentions_count?></strong>
							<small>Mentions</small>
						</span>
					</a>
					<?php if ($this->is_mod_enabled('contests')) { ?>
					<a href="/contests/<?=$profile_user->uri_name?>" class="<?=$type == 'contests' ? 'active' : ''?>">
						<span>
							<strong><?=$profile_user->user_stat->contests_count?></strong>
							<small>Contests</small>
						</span>
					</a>
					<? } ?>
				</div> <!-- End .profile_info_stats -->
			</div> <!-- End .profile_info_bottom -->
		</div> <!-- End .profile_top_middle_left -->
		
		<? //Drops feature placeholder ?>
		<? //Used for Drops Feature ?>
		<div class="profile_top_bottom">
			<? $this->load->view('newsfeed/coversheet_popup')?>
			<? $this->load->view('folder/collect')?>
			<div id="latestDrops"><span class="latestDrops_text">My Latest Drops</span><span class="latestDrops_line"></span></div>
			<ul id="profile_drops">
				<?php $feat_drops = $profile_user->get_feature_drops(7)?>
				<? foreach($feat_drops as $newsfeed) { ?>
					<?php $this->load->view('profile/profile_top_element', array('newsfeed' => $newsfeed));?>
				<? } ?>
				<? for ($i=count($feat_drops); $i < 7; $i++) { ?>
					<li class="profile_drop inlinediv blank"></li>
				<? } ?>
			</ul>
		</div>
	</div>
</div>

<? //kissmetrics ?>
<script type="text/javascript">
	_kmq.push(['identify', '<?=@$this->user->uri_name?>']);
	_kmq.push(['record', 'viewed someone\'s profile']);
</script>
