<?php $start_date = strtotime('2013-02-22 00:00:00')?>
<div id="controlDashboard">
    <div id="controlDashboard_top"><h1><a href="<?=$folder->get_folder_url()?>"><?=$folder->folder_name?></a> - Live Dashboard</h1><a class="contestFeed" href="/winsxsw">WinSXSW Contest</a></div>
    <div id="controlDashboard_bottom">
    <script type="text/javascript">php.companies_data = {}</script>
	<? foreach($companies as $company) { ?>
	    <script type="text/javascript">php.companies_data[<?=$company->newsfeed_id?>] = <?=json_encode($company->get_shares($start_date));?></script>
	    <span class="companyTile" data-id="<?=$company->newsfeed_id?>">
		<span class="companyTile_container">
		    <span class="companyTile_labels">
			<span class="companyTile_label companyTile_labelsCompany"><span class="companyTile_labelsContents">Company</span></span>
			<span class="companyTile_label companyTile_labelsShares"><span class="companyTile_labelsContents">Total Shares</span></span>
			<span class="companyTile_label companyTile_labelsGraph"><span class="companyTile_labelsContents">Graph</span></span>
		    </span>
		    <span class="companyTile_data">
			<span class="companyLogo_holder">
			    <span>
			    	<?=Html_helper::img($company->img_tile, array('class'=>"companyLogo", 'alt'=>""))?>
			    </span>				
			</span>
			<span class="companyShares"><?=$company->share_count?></span>
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
			    <span class="companyTile_sharesCount"><?=$company->fb_share_count?></span>
			</span>
			<span class="companyTile_sharesColumn">
			    <span class="companyTile_sharesIcon tw_sharesIcon"></span>
			    <span class="companyTile_sharesText">Shares:</span>
			    <span class="companyTile_sharesCount"><?=$company->twitter_share_count?></span>
			</span>
			<span class="companyTile_sharesColumn">
			    <span class="companyTile_sharesIcon pin_sharesIcon"></span>
			    <span class="companyTile_sharesText">Shares:</span>
			    <span class="companyTile_sharesCount"><?=$company->pinterest_share_count?></span>
			</span>
			<span class="companyTile_sharesColumn">
			    <span class="companyTile_sharesIcon gplus_sharesIcon"></span>
			    <span class="companyTile_sharesText">Shares:</span>
			    <span class="companyTile_sharesCount"><?=$company->gplus_share_count?></span>
			</span>
			<span class="companyTile_sharesColumn">
			    <span class="companyTile_sharesIcon linkedin_sharesIcon"></span>
			    <span class="companyTile_sharesText">Shares:</span>
			    <span class="companyTile_sharesCount"><?=$company->linkedin_share_count?></span>
			</span>
		    </span>
		</span>
	    </span>
	<? } ?>
    </div>
</div>
<?=Html_helper::requireJS(array('sxsw/dashboard'))?>