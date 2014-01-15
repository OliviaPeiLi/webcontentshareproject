<div id="contests">
	<div class="contestsHead">
		<h1><?=$user->id == $this->user->id ? 'Your' : $user->full_name."'s"?> contests</h1>
		<?php if ($user->id == $this->user->id) { ?>
		<a href="/contest/create" class="contests_button contests_newButton blue_bg blue_bg_tall blueButton">Create New Contest</a>
		<?php } ?>
	</div>
	<ul>
		<? if(count($contests) > 0) { ?>
			<?php foreach ($contests as $contest) { ?>
			<li class="contests_unit" <?=Html_helper::item_data($contest, array('id','url'))?>>
				<a href="/<?=$contest->url?>" class="contests_image">
					<?=Html_helper::img($contest->logo_thumb)?>
				</a>
				<span class="contests_sharesViews">
					<strong><?=$contest->shares?></strong>
					<small>Total Shares</small>
				</span>
				<span class="contests_sharesViews">
					<strong><?=$contest->views?></strong>
					<small>Views</small>
				</span>
				<span class="contests_buttonHolder">
					<a href="/<?=$contest->url?>/dashboard" class="dashboard_btn contests_button blue_bg blue_bg_tall">Live Dashboard</a>
					<? if ($user->id == $this->user->id) { ?>
						<? if ($contest->is_simple) { ?>
							<a href="/<?=$contest->url?>?edit=true" class="contests_button grey_bg grey_bg_tall">Edit Contest</a>
						<? } else { ?>
							<a href="#contest_edit_popup" rel="popup" title="Edit contest" class="contests_button grey_bg grey_bg_tall">Edit Contest</a>
						<? } ?>
					<? } ?>
				</span>
			</li>
			<?php } ?>
		<? } else { ?>
			<li class="contests_unit">No Contests</li>
		<? } ?>
	</ul>
	<? $this->load->view('edit') ?>
</div>