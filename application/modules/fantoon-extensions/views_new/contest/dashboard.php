<?php $start_date = strtotime('2013-02-22 00:00:00')?>
<div id="controlDashboard">
    <div id="controlDashboard_top">
    	<h1><a href="/<?=$contest->url.'/'.($folder ? $folder->folder_uri_name : '')?>"><?=$folder ? $folder->folder_name : $contest->name?></a> - Live Dashboard</h1>
    	<? if ($this->user && $contest->user_id == $this->user->id) { ?>
    		<a href="/<?=$contest->url.'/'.($folder ? $folder->folder_uri_name : '')?>?edit=true" class="contestFeed">Edit Contest</a>
    	<? } ?>
	    <?php if ($contest->url == 'demo' || $contest->url == 'fndemo') { ?>
			<div id="contest_tabs">
				<div class="contest_tabs_container">
					<a href="/<?=$contest->url.'/'.($folder ? $folder->folder_uri_name : '')?>" class="contest_tab active">Home</a>
					<a href="/<?=$contest->url.'/'.($folder ? $folder->folder_uri_name : '')?>#contest-prizes" class="contest_tab">How It Works</a>
					<a href="/<?=$contest->url.'/'.($folder ? $folder->folder_uri_name : '')?>#contest-sponsors" class="contest_tab">Press</a>
					<a href="" class="contest_tab">Live Dashboard</a>
				</div>
			</div>
		<?php } ?>
    </div>
	
    <div id="controlDashboard_bottom">
    <script type="text/javascript">php.companies_data = {}</script>
	<? foreach($drops as $drop) { ?>
	    <script type="text/javascript">php.companies_data[<?=$drop->newsfeed_id?>] = <?=json_encode($drop->get_shares(strtotime($contest->created_at)))?></script>
	    <span class="companyTile" data-id="<?=$drop->newsfeed_id?>">
		<span class="companyTile_container">
		    <span class="companyTile_labels">
			<span class="companyTile_label companyTile_labelsCompany"><span class="companyTile_labelsContents">Company</span></span>
			<span class="companyTile_label companyTile_labelsShares"><span class="companyTile_labelsContents">Total Shares</span></span>
			<span class="companyTile_label companyTile_labelsGraph"><span class="companyTile_labelsContents">Graph</span></span>
		    </span>
		    <span class="companyTile_data">
			<span class="companyLogo_holder">
			    <span>
			    	<?=Html_helper::img($drop->img_tile, array('class'=>"companyLogo", 'alt'=>""))?>
			    </span>				
			</span>
			<span class="companyShares"><?=$drop->share_count?></span>
			<span class="companyGraph">
			    <span></span>
			</span>
		    </span>
		</span>
		<span class="companyTile_shares">
		    <span class="companyTile_sharesContent">
			<span class="companyTile_sharesColumn">
			    <span class="companyTile_sharesIcon fb_sharesIcon"></span>
			    <span class="companyTile_sharesText">Shares:</span>
			    <span class="companyTile_sharesCount"><?=$drop->fb_share_count?></span>
			</span>
			<span class="companyTile_sharesColumn">
			    <span class="companyTile_sharesIcon tw_sharesIcon"></span>
			    <span class="companyTile_sharesText">Shares:</span>
			    <span class="companyTile_sharesCount"><?=$drop->twitter_share_count?></span>
			</span>
			<span class="companyTile_sharesColumn">
			    <span class="companyTile_sharesIcon gplus_sharesIcon"></span>
			    <span class="companyTile_sharesText">Shares:</span>
			    <span class="companyTile_sharesCount"><?=$drop->gplus_share_count?></span>
			</span>
			<?php if (!in_array($contest->url, array('cite'))) { ?>
				<span class="companyTile_sharesColumn">
				    <span class="companyTile_sharesIcon pin_sharesIcon"></span>
				    <span class="companyTile_sharesText">Shares:</span>
				    <span class="companyTile_sharesCount"><?=$drop->pinterest_share_count?></span>
				</span>
				<span class="companyTile_sharesColumn">
				    <span class="companyTile_sharesIcon linkedin_sharesIcon"></span>
				    <span class="companyTile_sharesText">Shares:</span>
				    <span class="companyTile_sharesCount"><?=$drop->linkedin_share_count?></span>
				</span>
			<?php } ?>
		    </span>
		</span>
	    </span>
	<? } ?>
    </div>
</div>
<?=Html_helper::requireJS(array('sxsw/dashboard'))?>