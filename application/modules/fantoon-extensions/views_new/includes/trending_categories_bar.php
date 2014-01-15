<div id="trending_bar">
	<div id="trending_categories">
		<? /* ?>
		<li id="all_categories_btn" class="menu_caller ft-dropdown ft-dropdown-hover" rel="categories_menu">
			<div class="all_categories_btn_contents">
				<span>Categories</span>
				<span class="menuButton">
					<span class="menuButton_contents"></span>
				</span>
			</div>
		</li>
		<? */ ?>
		<div id="topCategories_Section" class="inlinediv">
			<span class="trendingTitles">Top: </span>
			<ul style="display:inline">
			<? foreach($top_hashtags as $ck=>$cv) { ?>
				<li class="trending_hashtags inlinediv <?=(strtoupper($this->input->get('q')) == strtoupper($cv->hashtag_name)) ? 'active' : ''?>">
					<a href="/<?=strtolower(str_replace('#','',$cv->hashtag_name))?>"><?=str_replace('#','',$cv->hashtag_name)?></a>
				</li>
			<? } ?>
			</ul>
		</div>
		<span id="trendingCategories_Section" class="inlinediv">
			<span class="trendingTitles">Trending: </span>
			<ul style="display:inline">
			<? foreach($trending_hashtags as $ck=>$cv) { ?>
				<li class="trending_hashtags inlinediv <?=(strtoupper($this->input->get('q')) == strtoupper($cv->hashtag_name)) ? 'active' : ''?>">
					<a href="<?=$cv->hashtag_url?>"><?=str_replace('#','',$cv->hashtag_name)?></a>
				</li>
			<? } ?>
			</ul>
		</span>
		<? /* ?>
		<? if($this->session->userdata('id')){ ?>
			<li class="totalPoints inlinediv"><span>Total Points: </span><a href="/leaderboard" target=_blank><?=$this->user->user_stat->total_score?></a></li>
		<? } ?>
		<? */ ?>
		<? /* if (in_array($this->uri->segment(1), array('likes','mentions'))) { ?>
			<? $this->load->view('newsfeed/filter_types_menu') ?>
		<? } */ ?>
		<? /* ?>
		<? if($this->is_mod_enabled('open_signup')){ ?>
		<? } else { ?>
			<? if(!$this->session->userdata('id')){ ?>
			<? $redirect_url = null; ?>
			<? if(Url_helper::current_url() != Url_helper::base_url() || !empty($_GET)){
				$parameter = '';
				$i = 0;
				foreach($_GET as $name=>$value){
					if($i==0){
						$parameter = '?'.$name.'='.$value;
					}else{
						$parameter.='&'.$name.'='.$value;
					}
					$i++;
				}
				$redirect_url = Url_helper::current_url().$parameter;
			} ?>
			<? $signinUrl = $redirect_url ? '/signin?redirect_url='.$redirect_url : base_url().'signin'; ?>
			<li id="trendingHeader_login_container">
				<a id="trendingHeader_login" href="<?=$signinUrl?>">Login</a>
			</li>
			<? } ?>
		<? } ?>
		<? */ ?>
		<?php if ($this->is_mod_enabled('invite5') && $this->session->userdata('id')) { ?>
			<?php if ($num_invited_users < 5) { ?>
				<a href="/invites/facebook" class="users-meter">
					<? /* <strong>Invite <span><?=max(array(0, 5 - $num_invited_users))?></span> more Facebook friends to unlock the Drop It! button.</strong> */ ?>
					<ul class="custom-title" title-pos="bottom" title-class="invite-more tab_label_1" title="Invite <?=max(array(0, 5 - $num_invited_users))?> more Facebook friends to unlock the Drop It! button.">
						<?php for ($i=0; $i<5; $i++) { ?>
							<li class="user-ico <?=$i < $num_invited_users ? 'selected' : ''?>"><span></span></li>
						<?php } ?>
					</ul>
				</a>
			<?php } ?>
		<?php } ?>
	</div>
</div>