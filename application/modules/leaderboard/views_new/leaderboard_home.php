<div id="leaderboardBody">
	<? if($my_stats) $my_stats->group = $group; ?>
	<? if($my_stats && $my_stats->get_rank()<100){ ?>
	<div id="leaderboardMe">
		<h1>Your stats</h1>
		<div id="leaderboardMe_box">
			<div class="leaderboard_fieldNames">
				<div class="fieldTitle">Rank</div><!--
				--><div class="fieldTitle">Name</div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=drops">Drops<br><span>(1pt)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=views_count">Page Views<br><span>(1pt)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=redrops_count">Redrops<br><span>(5pts)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=upvotes_got_count">Upvotes<br><span>(5pts)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=comments_got_count">Comments<br><span>(15pts)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=ref_count">Referrals<br><span>(150pts)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=total_score">Total</a></div>
			</div>
			<div class="leaderboard_userData">
				<div class="userRank"><?=$my_stats->get_rank()?></div><!--
				--><div class="userName"><?=$this->user->full_name?></div><!--
				--><div class="userViews"><?=$my_stats->drops?></div><!--
				--><div class="userViews"><?=$my_stats->views_count?></div><!--
				--><div class="userLikes"><?=$my_stats->redrops_count?></div><!--
				--><div class="userLikes"><?=$my_stats->upvotes_got_count?></div><!--
				--><div class="userComments"><?=$my_stats->comments_got_count?></div><!--
				--><div class="userRefs"><?=$my_stats->ref_count?></div><!--
				--><div class="userScore"><?=$my_stats->total_score?></div>
			</div>
		</div>
	</div>
	<? } ?>
	
	<div id="leaderboardMain">
		<h1><?=$board_name?>Leaderboard</h1>
		<div id="leaderboardMain_box">
			<div class="leaderboard_fieldNames">
				<div class="fieldTitle">Rank</div><!--
				--><div class="fieldTitle">Name</div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=drops">Drops<br><span>(1pt)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=views_count">Page Views<br><span>(1pt)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=redrop_got">Redrops<br><span>(5pts)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=upvotes_got_count">Upvotes<br><span>(5pts)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=comments_got_count">Comments<br><span>(15pts)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=ref_count">Referrals<br><span>(150pts)</span></a></div><!--
				--><div class="fieldTitle"><a href="?order=<?=$order?>&orderby=total_score">Total</a></div>
			</div>
			
			<? foreach($top_stats as $stats){ ?>
				<div class="leaderboard_userData">
					<? $stats->group = $group; ?>
					<div class="userRank"><?=$stats->get_rank()?></div><!--
					--><div class="userName"><?=$stats->user->full_name?></div><!--
					--><div class="userViews"><?=$stats->drops?></div><!--
					--><div class="userViews"><?=$stats->views_count?></div><!--
					--><div class="userLikes"><?=$stats->redrops_count?></div><!--
					--><div class="userLikes"><?=$stats->upvotes_got_count?></div><!--
					--><div class="userComments"><?=$stats->comments_got_count?></div><!--
					--><div class="userRefs"><?=$stats->ref_count?></div><!--
					--><div class="userScore"><?=$stats->total_score?></div>
				</div>
			<? } ?>
		</div>
	</div>
</div>