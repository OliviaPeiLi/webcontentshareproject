<?php if ($this->input->get('b')=='fbinvite' && strpos(Url_helper::base_url(), 'public.fandrop.com') !== false) {?>
<script type="text/javascript"><?php //RR facebook some shit override?>
	window.location.href = window.location.href.replace('public.','');
</script>
<?php } ?>
<? $this->lang->load('signup/signup', LANGUAGE); ?>

<div id="catathon_intro_popup" style="display:none">
	<div class="modal-body">
		<a href="" class="close" data-dismiss="modal"></a>
		<div class="cat_title">The Catathon begins...</div>
		<div class="cat_body_label">How to earn points:</div>
		<div class="cat_pts_table">
			<div class="cat_row">
				<div class="cat_pt_col cat_row_col inlinediv">1 pt</div>
				<div class="cat_text_col cat_row_col inlinediv">Drop cat stuff onto Fandrop and get viewed.</div>
				<div class="cat_example_col cat_row_col inlinediv">
					<div class="cat_example"><?=Html_helper::img("catathon_popup_icons/icon_droplet_plus.png", array('class'=>"cat_icon cat_icon_droplet"))?> Droplet</div>
					<div class="cat_example">Submit Drops</div>
					<div class="cat_example"><?=Html_helper::img("catathon_popup_icons/icon_views.png", array('class'=>"cat_icon cat_icon_views"))?> Views</div>
				</div>
			</div>
			<div class="cat_row">
				<div class="cat_pt_col cat_row_col inlinediv">5 pts</div>
				<div class="cat_text_col cat_row_col inlinediv">Get redropped or upvoted.</div>
				<div class="cat_example_col cat_row_col inlinediv">
					<div class="cat_example"><?=Html_helper::img("catathon_popup_icons/icon_redrop.png", array('class'=>"cat_icon cat_icon_redrop"))?> Redrop</div>
					<div class="cat_example"><?=Html_helper::img("catathon_popup_icons/icon_upvote.png", array('class'=>"cat_icon cat_icon_upvote"))?> Upvote</div>
				</div>
			</div>
			<div class="cat_row">
				<div class="cat_pt_col cat_row_col inlinediv">15 pts</div>
				<div class="cat_text_col cat_row_col inlinediv">Get commented on.</div>
				<div class="cat_example_col cat_row_col inlinediv">
					<div class="cat_example"><?=Html_helper::img("catathon_popup_icons/icon_comment.png", array('class'=>"cat_icon cat_icon_comment"))?> Comment</div>
				</div>
			</div>
			<div class="cat_row">
				<div class="cat_pt_col cat_row_col inlinediv">150 pts</div>
				<div class="cat_text_col cat_row_col inlinediv">Successfully refer others to Fandrop.</div>
				<div class="cat_example_col cat_row_col inlinediv">
					<div class="cat_example"><?=Html_helper::img("catathon_popup_icons/icon_invite.png", array('class'=>"cat_icon cat_icon_invite"))?> Invite</div>
				</div>
			</div>
		</div>
		<div class="cat_body_label">Check the Catathon Leaderboard to see where you stand.</div>
		<div class="cat_body_label">Good luck!</div>
	</div>
</div>

<!-- optimal retargeting tag --> 
<script type="text/javascript"> 
/* <![CDATA[ */ 
var optimal_pixel_code = "0y2RNk5SvliFfi4"; 
/* ]]> */ 
</script> 
<script type="text/javascript" src="//evjs.optorb.com/js/r1.js"></script> 
<noscript>
	<div style="display:inline;"> 
		<img height="1" width="1" style="border-style:none;" alt="" src="//u.optorb.com/m?p=1&r=0y2RNk5SvliFfi4"/> 
	</div> 
</noscript>
