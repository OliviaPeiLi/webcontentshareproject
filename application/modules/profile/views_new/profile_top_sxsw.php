<? $this->lang->load('folder/folder', LANGUAGE); ?>
<? $this->load->view('fantoon-extensions/winsxsw_top')?>

<? /* ?>
<div id="folder_top" class="top_container sxswTop_Container">

	<h4>Prizes</h4>
	<p>Grand Prize for best startup $10,000.</p>
	<p>Prizes for Music, Film, and Interactive - $10,000.</p>
	<p>Meeting with top Venture Capital firms. Anderson Horowitz, Kleiner Perkins Caufield & Byers, Accel Partners, Benchmark Capital, SV Angel among others</p>
	
	<h4>WINSXSW CONTEST RULES</h4>
	<ol>
		<li>1. To enter the competition, your startup's website or app must be live.</li>
		<li>2. Startups must interview on camera with the WINSXSW team (see schedule for list of locations at SXSW).</li>
		<li>3. Startups will be given 30 seconds to answer questions, which will subsequently be available for viewing at WinSXSW.com.</li>
		<li>4. Crowd-voted winners will be chosen overall, and daily, based off of "shares" through social media networks.</li>
		<li>5. Daily contests will take place the day after a startup has interviewed.</li>
		<li>6. Interviews will take place on March 8, 9, 10, and 11th.</li>
		<li>7. Daily contest dates and voting will take place on March 9, 10, 11, and 12th.</li>
		<li>8. Overall crowd-voted winners will be announced on March 20th.</li>
		<li>9. Those voting for the "Investor" and "VentureBeat" picks are not permitted to vote for startups they have invested in or employ a family member.</li>
		<li>10. Companies may be entered in more than one category and companies may win multiple awards.</li>
	</ol>
	<div id="profile_top" class="profile_top_bottom">
		
		<? /* ?>
		<div id="latestDrops"><span class="latestDrops_text">Most Shared</span><span class="latestDrops_line"></span></div>
		<ul id="profile_drops">
			<?php $feat_drops = $profile_user->get_most_shared_drops()?>
			<? foreach($feat_drops as $newsfeed) { ?>
				<li class="profile_drop inlinediv" data-url="#preview_popup" rel="popup" <?=Html_helper::item_data($newsfeed,  array('newsfeed_id', 'img_width', 'img_height'))?>">
					<div class="topDrop_dropTop">
						<span class="<?=$newsfeed->link_type_class?> tl_icon"></span>
					</div> <? //used by the popup?>					
					<div>
						<img src="<?=$newsfeed->img_bigsquare?>" class="drop-preview-img">
					</div>
					<div class="drop_info">
						<div class="what_who">
							<span class="what drop-description"><?=Text_Helper::character_limiter_tag($newsfeed->title, 60)?></span>
							<span class="drop_desc_plain" style="display:none"><?=strip_tags($newsfeed->description)?></span> 
							<span class="js-share_count" style="display:none"><?=$newsfeed->share_count?></span>
						</div>
						<div class="where_div">Dropped in <a class="where folder-url" href="<?=$newsfeed->folder->get_folder_url()?>"><?=$newsfeed->folder->folder_name?></a></div>
					</div>
				</li>
			<? } ?>
			<? for ($i=count($feat_drops); $i < 7; $i++) { ?>
				<li class="profile_drop inlinediv" style="background:#ddd"></li>
			<? } ?>
		</ul>
	</div>

</div>
<? */ ?>
<div class="sxswButton_box">
	<div class="sxswButtons">
		<a href="http://register.winsxsw.com/register/" target="_blank">Register</a>
		<a href="http://register.winsxsw.com/contest-prizes/" target="_blank">Prizes</a>
		<a href="http://register.winsxsw.com/contest-rules/" target="_blank">Rules</a>
		<a href="http://register.winsxsw.com/sponsors/" target="_blank">Sponsors</a>
		<a href="http://register.winsxsw.com/investors/" target="_blank">Investors</a>
		<a href="http://register.winsxsw.com/email/" target="_blank">Email Updates</a>
	</div>
</div>