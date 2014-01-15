<!-- Wrapper -->
<? $this->lang->load('admin/admin', LANGUAGE); ?>
<? $this->lang->load('admin/dashboard', LANGUAGE); ?>
<div class="wrapper">
	<!-- Left column/section -->
	<section class="column width8 first">
		<div class="colgroup leading">
			<div class="column width2 first">
				<h4><?=$this->lang->line('dashboard_visitors_header');?>:</h4>
				<hr/>
				<table class="no-style full">
					<tbody>	
						<?php if (isset($data['num_users'])):?>
						<tr>
							<td><?=$this->lang->line('dashboard_num_registered_users');?></td>
							<td class="ta-right"><?=$data['num_users'];?></td>
						</tr>
						<?php endif; ?>
						<?php if (isset($data['act_users'])):?>
						<tr>
							<td><?=$this->lang->line('dashboard_num_active_users');?></td>
							<td class="ta-right"><?=$data['act_users'];?></td>
						</tr>
						<?php endif; ?>
						<tr>
							<td><?=$this->lang->line('dashboard_num_facebook_users');?></td>
							<td class="ta-right"><?=$data['fb'];?></td>
						</tr>
						<tr>
							<td><?=$this->lang->line('dashboard_num_twitter_users');?></td>
							<td class="ta-right"><?=$data['tw'];?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="column width2">
				<h4><?=$this->lang->line('dashboard_links_header');?>:</h4>
				<hr/>
				<table class="no-style full">
					<tbody>
						<?php foreach ($this->newsfeed_model->link_types as $key=>$type) { if (!$type) continue; ?>
						<tr>
							<td><?=ucfirst($type=='content' ? 'Live link' : ($type == 'embed' ? 'Video' : $type))?></td>
							<td class="ta-right"><?=$this->newsfeed_model->count_by(array('link_type_id'=>$key))?></td>
						</tr>
						<?php } ?>
						<tr>
							<td><strong>Total</strong></td>
							<td class="ta-right"><strong><?=$this->newsfeed_model->count_all()?></strong></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="column width2">
				<h4><?=$this->lang->line('dashboard_folders_header');?>:</h4>
				<hr/>
				<table class="no-style full">
					<tbody>
						<tr>
							<td><?=$this->lang->line('dashboard_num_collections_total');?></td>
							<td class="ta-right"><?=$data['folders_count'];?></td>
						</tr>
						<tr>
							<td><?=$this->lang->line('dashboard_private_count');?></td>
							<td class="ta-right"><?=$data['private_count'];?></td>
						</tr>
						<tr>
							<td><?=$this->lang->line('dashboard_public_count');?></td>
							<td class="ta-right"><?=$data['public_count'];?></td>
						</tr>
						<?php if (isset($data['avg_links'])):?>
						<tr>
							<td><?=$this->lang->line('dashboard_avg_links');?></td>
							<td class="ta-right"><?=$data['avg_links'];?></td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<div class="column width2">
				<h4><?=$this->lang->line('dashboard_activities_header');?>:</h4>
				<hr/>
				<table class="no-style full">
					<tbody>
						<?php if (isset($data['comments_count'])):?>
						<tr>
							<td><?=$this->lang->line('comments_count');?></td>
							<td class="ta-right"><?=$data['comments_count'];?></td>
						</tr>
						<?php endif; ?>
						<?php if (isset($data['likes_count'])): ?>
						<tr>
							<td><?=$this->lang->line('likes_count');?></td>
							<td class="ta-right"><?=$data['likes_count'];?></td>
						</tr>
						<?php endif;?>
						<?php if (isset($data['redrop_count'])): ?>
						<tr>
							<td><?=$this->lang->line('redrop_count');?></td>
							<td class="ta-right"><?=$data['redrop_count'];?></td>
						</tr>
						<?php endif;?>
					</tbody>
				</table>
			</div>
			<? /* ?>
			<div class="column width2">
				<h4><?=$this->lang->line('dashboard_interests_header');?>:</h4>
				<hr/>
				<table class="no-style full">
					<tbody>
						<?php if (isset($data['intersts_count'])):?>
						<tr>
							<td><?=$this->lang->line('dashboard_intersts_count');?></td>
							<td class="ta-right"><?=$data['intersts_count'];?></td>
						</tr>
						<?php endif; ?>
						<?php if (isset($data['intersts_links'])): ?>
						<tr>
							<td><?=$this->lang->line('dashboard_intersts_links');?></td>
							<td class="ta-right"><?=$data['intersts_links'];?></td>
						</tr>
						<?php endif;?>
					</tbody>
				</table>
			</div>
			<? */ ?>
		</div>
		<div class="colgroup leading">
			<?php if (isset($data['uniq_domains'])):?>
			<div class="column width2 first">
				<h4><?=$this->lang->line('dashboard_most_domains_header');?>:</h4>
				<hr/>
				<? 
					$uniq_domains_keys = array_keys($data['uniq_domains']);
					$uniq_domains_values = array_values($data['uniq_domains']);
					$i = 0;
				?>
				<table class="no-style full">
					<tbody>
						<? while($i < 10) {
							echo "<tr><td>{$uniq_domains_keys[$i]}</td><td class='ta-right'>{$uniq_domains_values[$i]}</td></tr>";
							$i++;
						} ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
			<?php if (isset($data['users_shares'])): ?>
			<div class="column width2">
				<h4><?=$this->lang->line('dashboard_users_most_shares_header');?>:</h4>
				<hr/>
				<table class="no-style full">
					<tbody>
						<? 
							$i = 0;
							$max = (count($data['users_shares']) >= 30) ? 30 : count($data['users_shares']);
						?>
						<? while($i < $max) {
							$row = $data['users_shares'][$i];
							echo "<tr><td><a href='/".$row['uri_name']."' target='_blank'>{$row['first_name']} {$row['last_name']}</a> id: ".$row['id']."</td><td class='ta-right'>{$row['quantity']}</td></tr>";
							$i++;
						} ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
			<?php if (isset($data['most_links'])): ?>
			<div class="column width2">
				<h4><?=$this->lang->line('dashboard_most_collections_links_header');?>:</h4>
				<hr/>
				<table class="no-style full">
					<tbody>
						<? 
							$i = 0; 
							$max = (count($data['most_links']) >= 10) ? 10 : count($data['most_links']);
						?>
						<? while($i < $max) {
							$row = $data['most_links'][$i];
							echo "<tr><td>{$row['folder_name']}</td><td class='ta-right'>{$row['quantity']}</td></tr>";
							$i++;
						} ?>
					</tbody>
				</table>
			</div>
			<?php endif;?>
			<?php if (isset($data['interests_most_links'])): ?>
			<div class="column width2">
				<h4><?=$this->lang->line('dashboard_most_interests_links_header');?>:</h4>
				<hr/>
				<table class="no-style full">
					<tbody>
						<? 
							$i = 0; 
							$max = (count($data['interests_most_links']) >= 10) ? 10 : count($data['interests_most_links']);
						?>
						<? while($i < $max) {
							$row = $data['interests_most_links'][$i];
							echo "<tr><td>{$row['page_name']}</td><td class='ta-right'>{$row['quantity']}</td></tr>";
							$i++;
						} ?>
					</tbody>
				</table>
			</div>
			<?php endif; ?>
			
		</div>
		<div class="clear">&nbsp;</div>
	</section>
	<!-- End of Right column/section -->
</div>
<!-- End of Wrapper --> 