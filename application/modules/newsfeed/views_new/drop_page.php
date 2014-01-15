<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<? $is_liked = $newsfeed->is_liked($this->user); ?>
<? if ($this->user) { $this->load->view('folder/collect'); }?>
<? if ($newsfeed->folder->type == 1) { //sxsw?>
	<? $this->load->view('fantoon-extensions/winsxsw_top', array('folder'=>$newsfeed->folder))?>
	<div id="sxsw_collectionLink">
		<h1><a href="<?=$newsfeed->folder->folder_url?>"><?=$newsfeed->folder->folder_name?></a></h1>
	</div>
<? } elseif ($newsfeed->folder->type) { ?>
	<? $this->load->view('fantoon-extensions/contest/top', array('folder'=>$newsfeed->folder))?>
	<div id="sxsw_collectionLink">
		<?php if (isset($contest) && $contest->url == 'fndemo') { ?>
			<h1>Help <a href="<?=$newsfeed->short_url ? $newsfeed->short_url : Url_helper::base_url('/drop/'.$newsfeed->url)?>"><?=$newsfeed->title?></a> become the <a href="https://twitter.com/search?q=%23fnDemo&src=hash" target="_blank">#fnDemo</a> Champ by tweeting this page!</h1>
			<a href="http://www.foundersnetwork.com/fndemo.php">http://www.foundersnetwork.com/fndemo.php</a>
		<?php } else { ?>
			<h1><a href="<?=$newsfeed->folder->folder_url?>"><?=$newsfeed->folder->folder_name?></a></h1>
		<?php } ?>
	</div>
	<?php if ($contest->url == 'fndemo') { ?>
		<div style="width: 97%">
			<? $this->load->view('contest/topbox_fndemo', array('include_folder_top'=>''))?>
		</div>
	<?php } else if ($contest->url == 'crowdfunderio') { ?>
		<div style="width: 97%; margin: auto">
			<? $this->load->view('contest/topbox_crowdfund', array('include_folder_top'=>''))?>
		</div>
	<?php } else if ($contest->url == 'cite') { /*?>
		<div style="width: 97%; margin: auto">
			<? $this->load->view('contest/topbox_crowdfund', array('include_folder_top'=>''))?>
		</div>
	<?php */ } ?>
<? } ?>
<div id="content" class="drop-page" data-newsfeed_id="<?=$newsfeed->newsfeed_id?>" data-url="<?=$newsfeed->url?>">
	<div id="main-content">
		<div class="item_top">
			<div class="stats_container inlinediv">
				<?php if ($newsfeed->folder->type) { ?>
					<div class="sharebox inlinediv">
					<?php if (in_array($contest->url, array('fndemo','crowdfunderio','cite'))) { ?>
						<div class="share_count js-points_count"><?=$newsfeed->points?></div>
						<div class="share_text">Points</div>
					<?php } else { ?>
						<div class="share_count js-share_count"><?=$newsfeed->share_count?></div>
						<div class="share_text">Shares</div>
					<?php } ?>
					</div>
				<?php } else {?>
					<div class="upbox inlinediv">
						<a class="up_button" rel="ajaxButton" href="<?='/add_like/'.$newsfeed->type.'/'.$newsfeed->activity_id?>" <?=$is_liked ? 'style="display:none"' : ''?>>
							<span class="upvote_wrapper">
								<span class="upvote_contents"></span>
							</span>
							<? /* ?><div class="upvote_text">Upvote</div><? */ ?>
						</a>
						<a class="undo_up_button" rel="ajaxButton" href="<?='/rm_like/'.$newsfeed->type.'/'.$newsfeed->activity_id?>" <?=$is_liked ? '' : 'style="display:none"'?>>
							<span class="downvote_wrapper">
								<span class="downvote_contents"></span>
							</span>
							<? /* ?><div class="upvote_text">Upvoted</div><? */?>
						</a>
						<div class="up_count"><?=$newsfeed->up_count?></div>
					</div>
				
					<div class="redropbox inlinediv">
						<a href="#collect_popup" class="redrop_button" rel="popup" title="<?=$this->lang->line('newsfeed_views_redrop_lexicon');?>">
							<span class="redrop_icon">
								<span class="redrop_iconContents"></span>
							</span>
						</a>
						<div class="redrop_count"><?=$newsfeed->collect_count?></div>
					</div>
				<?php } ?>
			</div>
			<div class="item_info inlinediv">
				<div>
					<?php if ($newsfeed->folder->type) { ?>
						<h2 class="pop_up_title drop-description">
							<strong><?=Html_helper::img($newsfeed->img_tile, array('alt'=>"",'style'=>'height: 20px; padding: 0; margin: 0 3px -5px 0;'))?></strong>
							<?=$newsfeed->title?>
						</h2>
						<? if (in_array($contest->url, array('fndemo','crowdfunderio','cite'))) { ?>
							<p>
								<a href="<?=@$referral ? $referral->url : ($newsfeed->short_url ? $newsfeed->short_url : Url_helper::base_url('/drop/'.$newsfeed->url))?>" target="_blank">
									<?=@$referral ? $referral->url : ($newsfeed->short_url ? $newsfeed->short_url : Url_helper::base_url('/drop/'.$newsfeed->url))?>
								</a> - share this url everywhere to help promote "<?=$newsfeed->title?>"
							</p>
						<? } ?>
						<div><strong><?=$newsfeed->description?></strong></div>
					<?php } else { ?>
						<span class="tl_icon <?=$newsfeed->link_type_class?>"></span>
						<h2 class="pop_up_title drop-description"><?=$newsfeed->description?></h2>
					<?php } ?>
				</div>
				<div class="post_detail inlinediv">
					<?php if (!$newsfeed->folder->type) { ?>
						<div class="postDetail_drop">
							Dropped in <a href="<?=$newsfeed->folder->get_folder_url()?>" class="folder_link"><?=$newsfeed->folder->folder_name?></a>
						</div>
						<div class="postDetail_user">
							By <a href="<?=$newsfeed->user_from->url?>" class="user_link"><?=$newsfeed->user_from->full_name?></a>
							<? if($newsfeed->user_from->role == '1'){ ?>
								<span class="roleTitle"><?=$this->lang->line('staff_writer');?></span>
							<? } elseif($newsfeed->user_from->role == '3'){ ?>
								<span class="roleTitle"><?=$this->lang->line('featured_user');?></span>
							<? } ?>
							<? /* ?><span class="posted_when"><?=Date_Helper::time_ago($newsfeed->time)?></span><? */ ?>
							<span class="topPost_actions">
								<span class="divIder"></span>
								<? if ($newsfeed->can_edit($this->user)) { ?>
									<a href="#newsfeed_popup_edit" class="newsfeed_edit_lnk edit_btn" rel="popup" title="Edit Drop">
										<span class="edit_wrapper"><span class="edit_contents"></span></span>
										<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_edit_btn');?></span>
									</a>
								<? } ?>
							</span>
						</div>
					<?php } ?>			
				</div>
				<?php if (!isset($contest) || !in_array($contest->url, array('fndemo','crowdfunderio','cite'))) { ?>
					<div class="page_navArrows inlinediv">
						<a class="page_arrow <?=@$prev_newsfeed ? "" : "arr_inactive";?>" id="page_arrow_left" href="<?=@$prev_newsfeed ? base_url("drop/".$prev_newsfeed->url) : "javascript:;";?>">
							<span class="page_arrowIcon"></span>
						</a>
						<a class="page_arrow <?=@$next_newsfeed ? "" : "arr_inactive";?>" id="page_arrow_right" href="<?=@$next_newsfeed ? base_url("drop/".$next_newsfeed->url) : "javascript:;";?>">
							<span class="page_arrowIcon"></span>
						</a>
					</div>
				<?php } ?>
				<div class="clear"></div>
				<div id="permalinks">
					<?php if (isset($contest) && in_array($contest->url, array('fndemo','crowdfunderio'))) { ?>
						<?=Html_helper::twitter_btn($newsfeed)?>
					<?php } else if (isset($contest) && in_array($contest->url, array('cite'))) { ?>
						<?=Html_helper::fb_share_btn($newsfeed)?>
						<?=Html_helper::twitter_btn($newsfeed)?>
						<?=Html_helper::gplus($newsfeed)?>
					<?php } ?>
					
					<a href="<?=$newsfeed->link_url?>" style="<?=$newsfeed->link_url ? '': 'display:none'?>" target="_blank">
						<img src="https://www.google.com/s2/favicons?domain=<?=$newsfeed->source?>" id="link_favicon" alt=""><span class="linktext"><?=Text_Helper::character_limiter_strict($newsfeed->link_url, 32)?></span>
					</a>
						<div class="social inlinediv">
							<?php if (!isset($contest) || !in_array($contest->url, array('fndemo','crowdfunderio','cite'))) { ?>
								<?=Html_helper::twitter_btn($newsfeed, array())?>
								<?=Html_helper::fb_share_btn($newsfeed)?>
								<?=isset($contest) && $newsfeed->link_type != 'text' ? Html_helper::pinterest_btn($newsfeed) : ''?>
								<? if ($newsfeed->folder->type > 0) { ?>
									<?=Html_helper::gplus($newsfeed)?>
									<?=Html_helper::likedin($newsfeed)?>
								<? } ?>
								<?php if ($this->is_mod_enabled('email_share') && $this->user) { ?>
									<a href="#share_email_form_wrap" class="share_email" rel="popup" data-type="newsfeed" title="Email This Drop">@ Email</a>
								<?php } ?>
							<?php } ?>
						</div>
				</div>
			</div>
		</div>
		<div class="preview_popup_main">
			<?php if ($newsfeed->link_type == 'text') { ?>
				<div class="text_wrapper">
					<p class="text_content"><?=$newsfeed->activity->content?></p>
				</div>
			<?php } else { ?>
				<div class="images_container">
					<?php if (!$newsfeed->coversheet_updated) {?>
						<?=Html_helper::img($newsfeed->img_thumb, array(
							'class'=> "thumb-img ".( $newsfeed->link_type=='image' && substr( basename( $newsfeed->img_thumb ), -4 ) != '.gif' ? 'watermarked' : ''),
							'style'=> "width: " . ( $newsfeed->complete == 1 ? ( $newsfeed->img_width ? $newsfeed->img_width.'px' : '' ) : 'auto' ),
							'alt'=>""
						))?>
					<?php } ?>
					<?=Html_helper::img($newsfeed->img_full, array(
						'class'=>"full-img".($newsfeed->link_type=='image' && substr( basename( $newsfeed->img_full ), -4 ) != '.gif' ? ' watermarked' : ''),
						'onload'=>"this.className += ' loaded'",
						'onerror'=>"if (this.src.indexOf('_full')>-1) this.src = this.src.replace('_full','');",
						'alt'=>""
					))?>
				</div>
				<?php if ($newsfeed->newsfeed_id <= 0) { ?>
					<?php if ($newsfeed->link_type == 'embed') { ?>
						<?=str_replace('<iframe', '<iframe style="width: 500px"', $newsfeed->activity->media)?>
						<script type="text/javascript">
							window.setTimeout(function() {
								var el = document.getElementsByClassName('preview_popup_main');
								el = el[el.length - 1];
								el.className = el.className.replace('img-loaded','');
								el.className += ' iframe-loaded'; 
							}, 5000);
						</script>
					<? } ?>
				<?php } else if (!in_array($newsfeed->link_type, array('photo','image'))) { ?>
					<?php $href = strpos($newsfeed->link_url, 'https://') !== false ? Url_helper::base_url() : str_replace('https://', 'http://', Url_helper::base_url())?>
					<iframe src="<?=$href.'/bookmarklet/snapshot_preview/'.$newsfeed->newsfeed_id ?>" style="height: <?=$newsfeed->img_height && $newsfeed->img_height < 1200 ? $newsfeed->img_height : 1000?>px;<?=$newsfeed->link_type == 'content' ? 'min-width:700px' : '' ;?>"></iframe>
				<?php } ?>
			<?php } ?>
			<div class="controls newsfeed_entry_comment_options">
				<? /* ?><a href="#collect_popup" class="newsfeed_collect_lnk" rel="popup" title="<?=$this->lang->line('newsfeed_views_redrop_lexicon');?>">
					<span class="redrop_wrapper"><span class="redrop_contents"></span></span>
					<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_redrop_lexicon');?></span>
				</a><? */ ?>
				<? /* ?><? if ($newsfeed->can_edit($this->user)) { ?>
					<a href="#newsfeed_popup_edit" class="newsfeed_edit_lnk edit_btn" rel="popup" title="Edit Drop">
						<span class="edit_wrapper"><span class="edit_contents"></span></span>
						<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_edit_btn');?></span>
					</a>
				<? } ?><? */ ?>
				<?php /* RR - 1/21/2013 - http://dev.fantoon.com:8100/browse/FD-2976?>
				<a class="newsfeed_hide_comments_lnk newsfeed_comments_lnk" rel="0" href="javascript:;">
					<span class="view_comments_wrapper"><span class="view_comments_contents"></span></span>
					<span class="actionButton_text"><?=$this->lang->line('newsfeed_views_comment_btn');?></span>
				</a>
				<? */ ?>
			</div>
			<?php if (isset($contest) && $contest->url == 'crowdfunderio') {/* ?>
				<div id="topReferral_competitorsList">
					<label>Current Top 10 Competitors:</label>
					<div class="competitorsList_listContainer">
						<div class="referral_search">
							<span class="ico"></span>
							<span class="referral_searchText">See where you stand:</span>
							<div class="referral_searchContainer">
								<input type="text" placeholder="Enter Your Email...">
								<input type="button" name="save" value="Go" class="referral_emailSubmit"/>
							</div>
						</div>
						<ol id="referral_list_table">
							<?php foreach ($newsfeed->get('newsfeed_referrals')->order_by('(points+views)','desc')->get_all(20) as $nf_referral) { ?>
							<li class="referral_item"><span class="referral_userName"><?=$nf_referral->name?></span><span class="referral_points"><?=$nf_referral->points+$nf_referral->views?> pts</span></li>
							<?php } ?>

						</ol>
					</div>
				</div>
			<?*/ } ?>
		</div><!-- End .left -->
	</div>
	<div id="right">
		<?php if (isset($contest) && $contest->url == 'fndemo') { /*?>
		<?php */} else if (isset($contest) && $contest->url == 'crowdfunderio') { /*?>
		<?php */} else if (isset($contest) && $contest->url == 'cite') { /*?>
			<div id="referral_list" class="preview_popup_comments">
				<div class="contest-info">
					<div class="prize">
						<span class="ico"></span>
						<span class="inlinediv">
							<strong>Top Prize</strong>
							<small><?=$newsfeed->top_prize?></small>
						</span>
					</div>
					<div class="duration">
						<span class="ico"></span>
						<span class="inlinediv">
							<strong>Duration: <?=Date_Helper::time_ago(strtotime($newsfeed->folder->ends_at), 3)?></strong>
						</span>
					</div>
					<?php if ($newsfeed->share_goal) { ?>
					<div class="goal">
						<strong>Campaign Goal:</strong>
						<div class="bar">
							<div class="progress" style="width: <?=min(array(100, round($newsfeed->points/$newsfeed->share_goal*100)))?>%"><span class="barLine"><span class="progressCounter"><span class="progressCounter_contents"><?=$newsfeed->points?> points</span></span></span></div>
						</div>
						<small><?=$newsfeed->share_goal?> points</small>
					</div>
					<?php } ?>
				</div>
				<?=Form_Helper::open('/add_referral', array('rel'=>'ajaxForm','class'=>'public joinCampaign'), array('newsfeed_id'=>$newsfeed->newsfeed_id,'referral_id'=>$referral ? $referral->id : 0))?>
					<label>Join the Campaign:</label>
					<div class="form_row email">
						<div class="joinCampaign_emailContainer">
							<?=Form_Helper::input('email', '', array(
								'class' => 'joinCampaign_emailInput',
								'data-validate' => 'required|email',
								'data-error-required' => 'Please enter an email',
								'data-error-email' => 'The email doesnt appear to be valid',
								'placeholder' => 'Enter Your Email',
							))?>
							<span class="ico"></span>
							<input type="submit" name="save" value="GO" class="joinCampaign_emailSubmit"/>
						</div>
						<div class="joinCampaign_descriptionText"></div>
						<span class="error"></span>
					</div>
					<div class="form_row success" style="display:none">
						<textarea readonly="readonly" class="bitlyLink"></textarea>
						<div class="socialShares_container">
							<div class="socialShares_text">
								Share, post the URL everywhere on the social web, blogs and forums to receive points on the traffic you bring in. There are 3 ways to get points.
								<ul>
									<li>1 point for each person brought by the referral url</li>
									<li>10 points for every share/reshare/retweet on social networks of the referral url</li>
									<li>10 points for both you and your friend that joined the campaign from the referral url</li>
								</ul>
							</div>
							<div class="socialShares">
								<?=Html_helper::twitter_btn($newsfeed)?>
								<?=Html_helper::fb_share_btn($newsfeed)?>
								<?=Html_helper::pinterest_btn($newsfeed)?>
								<?=Html_helper::gplus($newsfeed)?>
								<?=Html_helper::likedin($newsfeed)?>
							</div>
						</div>
					</div>
				<?=Form_Helper::close()?>
			</div>
		<?php*/ } else { ?>
			<div class="preview_popup_comments">
			    <div class="comments_list">
		      		<?=$newsfeed->newsfeed_id ? modules::run('comment/comment/index', $newsfeed->newsfeed_id) : ''?>
				</div>
				
				<div class="comments-bottom">
					<div class="newsfeed_entry_add_comment">
						<?=Html_helper::img($this->user ? $this->user->avatar_25 : Uploadable_Behavior::get_default_image(null, $this->user_model->behaviors['uploadable']['avatar']['default_image']), array(
							'class'=>"previewPopup_userAvatar", 'alt'=>"avatar"
						))?>
	
						<?=Form_Helper::open('/comment', array('rel'=>'ajaxForm','class'=>'comments_form'), array('newsfeed_id'=>$newsfeed->newsfeed_id))?>
							<div class="form_row">
								<textarea name="comment" class="fd_mentions" data-maxlength="250" cols="36" rows="4" placeholder="Write a comment..."></textarea>
								<span class="comment_char_count" style="display:none;">250</span>
								<input type="submit" name="submit" class="blue_bg" value="Comment" style="display:none;"/>
							</div>
							<span class="error"></span>
						<?=Form_Helper::close()?>
					</div>
					<div class="more-info">
						<?php if ($newsfeed->folder->type == 0) { ?>
							<?=Modules::run('newsfeed/newsfeed_controller/popup_right', $newsfeed->newsfeed_id, false)?>
						<?php } ?>
					</div>
				</div>
			</div><!-- End .preview_popup_comments -->
		<? } ?>
	</div>
</div><!-- End #content -->

<script type="text/javascript">
	php.fb_id = <?=isset($this->user->fb_id) ? $this->user->fb_id : 0?>;
	php.twtr_id = <?=isset($this->user->twtr_id) ? $this->user->twitter_id : 0?>;
	php.referral = '<?=$referral ? $referral->id : 0?>';
</script>
<?=Html_helper::requireJS(array("newsfeed/drop_page"))?>
