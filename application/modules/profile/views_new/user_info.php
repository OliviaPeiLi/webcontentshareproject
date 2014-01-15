<div>
	<div class="info_main">
  		<ul><? //Basic Info ?>
			<li class="info_section">
				<h6>Basic Information</h6> 
				<? if($user->id == $this->session->userdata('id')) { ?>
					<a class="edit_button" href="/account_options">Edit</a>
				<? } ?>										   
				<div class="info_body">
					<div>
						<?php if ($user->birthday != '0000-00-00') { ?>
							<ul><li class="left">Birthday:</li><li class="data"><?=date('m/d/Y', strtotime($user->birthday))?></li></ul>
						<?php } ?>
						<?php if (in_array($user->gender, array('f', 'm'))) { ?>
							<ul><li class="left">Gender:</li><li class="data"><?=$user->gender == 'f' ? 'Female' : 'Male'?></li></ul>
						<?php } ?>
						<?php if($user->about) { ?>
							<ul><li class="left">Bio:</li><li class="data"><?=nl2br_except_pre(strip_tags(auto_typography($user->about)))?></li></ul>
						<?php } ?>
						<?php if($user->quotes) { ?>
							<ul><li class="left">Quotes:</li><li class="data"><?=nl2br_except_pre(strip_tags(auto_typography($user->quotes)))?></li></ul>
						<?php } ?>
						<?php // FD-3456 ?>
						<?php /* if($user->color_id) { ?>
							<ul><li class="left">Favorite Color:</li><li class="data"><?=$user->color_id?></li></ul>
						<?php } ?>
						<?php if($user->im) { ?>
							<ul><li class="left">IM:</li><li class="data"><?=$user->im?></li></ul>
						<?php } ?>
						<?php if($user->phone) { ?>
							<ul><li class="left">Phone:</li><li class="data"><?=$user->phone?></li></ul>
						<?php } ?>
						<?php if($user->address) { ?>
							<ul><li class="left">Address:</li><li class="data"><?=$user->address?></li></ul>
						<?php } */?>
			  		</div>
			  	</div>
		 	</li>
		 	<? if($this->is_mod_enabled('education_settings')){ ?>
			 	<? //School info ?>
				<li class="info_section">
					<h6>Education</h6>
					<? if (count($user->user_schools) > 0) { ?>
						<? foreach($user->user_schools as $school) { ?>
							<div class="info_body">
								<span class="spacing"><?=$school->school->name?></span>, Class of 
								<span><?=$school->year?></span>, majoring in 
								<span><?=$school->major ?></span>
							</div>
						<? } ?>
					<? } else { ?>
						<div class="info_body"><i>You have not entered any school information</i></div>
					<? } ?>
				</li>
		   	<? } ?>
		   	
		   	<? //Disabling for production/staging for now ?> 
			<? if($this->is_mod_enabled('location_links_settings')){ //Location Info ?>
			   	<? if ($user->user_locations) { ?>
					<li class="info_section">
						<h6>Location</h6>
							<?=$user->id == $this->session->userdata('id') ? 'You have' : 'Has'; ?>
							<ul class="info_body">
								<li class="locations">
									<strong>Current Location</strong>
									<small class="current"><?=$user->get('user_locations')->get_by(array('options'=>'current'))->place_name?></small>
									<strong><?=$person?> lived in</strong>
									<small class="green"><?=$user->get('user_locations')->get_by(array('options'=>'place'))->place_name?></small>
									<strong><?=$person?> traveled to</strong>
									<small class="green"><?=$user->get('user_locations')->get_by(array('options'=>'travel'))->place_name?></small>
								</li>
								<li class="on_the_map">
									<?=Html_helper::img($user->get('user_locations')->get_map(), array('alt'=>"map place"))?>
								</li>
							</ul>
					</li>  
				<? } else { ?>
					<li class="info_section">
						<h6>Location</h6>
						<div class="info_body"><i>You have not entered any location data.</i></div>
					</li> 				
			   		
			   	<? } ?>
		   	
			   	<? //Links ?>
				<? if ($user->user_links || $user->id == $this->session->userdata('id')) { ?>
					<li class="last info_section">
						<h6>Links</h6>
						<? if ($user->user_links) { ?>
							<div class="info_body">
								<?php foreach($user->user_links as $row){ ?>
									<a class="my_links" href="<?=$url?>" target="_blank">
										<img src="//s2.googleusercontent.com/s2/favicons?alt=p&domain=<?=str_replace('http://','',$row->url)?>" alt="" />
									</a>
									<a class="my_links" href="http://<?=str_replace(array('http://', 'https://'), '', $row->url)?>" target="_blank">
										<?=$row->label?>
									</a>
								<?php } ?>
							</div>
						<?php } else { ?>
							<div class="info_body"><i>You have not provided any links</i></div>
						<?php } ?>
					</li> 
				 <? } ?>
			 <? } ?>
		 	</li>
	 	</ul>
	 </div>
	 <div class="info_bot"></div>
</div>

<? /* ?>	
	<? if($life_philo){?>
	<div class="info_section">
		<div class="section_title">Life and Philosophy</div>
		<div class="section_body">
			<?
			$arr = null;
			foreach($life_philo as $key => $value)
			{ 
				if ($arr[$value['type']] === null) {
					$arr[$value['type']] = '';
				}
				$arr[$value['type']] .= '<li class="info_token inlinediv">'.$value['type_name'].'</li>';
				//echo $value['type'].'->'.$value['type_name'];		
			} 
			foreach($arr as $k => $v) {
				echo '<div><div class="detail_label">'.$k.':</div><div class="detail_field"><ul>'.$v.'</ul></div></div>';
			}
			?>
		</div>
	</div>
	<? }?>
		
	<? if($more_about_me){?>
	<div class="info_section">
		<div class="section_title">More About Me</div>
		<div class="section_body">
			<?
			$arr = null;
			foreach($more_about_me as $key => $value)
			{ 
				if ($arr[$value['type']] === null) {
					$arr[$value['type']] = '';
				}
				$arr[$value['type']] .= '<li class="info_token inlinediv">'.$value['type_name'].'</li>';
				//echo $value['type'].'->'.$value['type_name'];		
			} 
			foreach($arr as $k => $v) {
				echo '<div><div class="detail_label">'.$k.':</div><div class="detail_field"><ul>'.$v.'</ul></div></div>';
			}
			?>
		</div>
	</div>
	<? }?>
	
	<? if($art_entertainment){?>
	<div class="info_section">
		<div class="section_title">Art and Entertainment</div>
		<div class="section_body">	
			<?
			$arr = null;
			foreach($art_entertainment as $key => $value)
			{ 
				if ($arr[$value['type']] === null) {
					$arr[$value['type']] = '';
				}
				$arr[$value['type']] .= '<li class="info_token inlinediv">'.$value['type_name'].'</li>';
				//echo $value['type'].'->'.$value['type_name'];		
			} 
			foreach($arr as $k => $v) {
				echo '<div><div class="detail_label">'.$k.':</div><div class="detail_field"><ul>'.$v.'</ul></div></div>';
			}
			?>
		</div>
	</div>
	 <? }?>
		
	<? if($sports_activities){?>
	<div class="info_section">
		<div class="section_title">Sports and Activities</div>
		<div class="section_body">	
			<?
			$arr = null;
			foreach($sports_activities as $key => $value)
			{ 
				if ($arr[$value['type']] === null) {
					$arr[$value['type']] = '';
				}
				$arr[$value['type']] .= '<li class="info_token inlinediv">'.$value['type_name'].'</li>';
				//echo $value['type'].'->'.$value['type_name'];		
			} 
			foreach($arr as $k => $v) {
				echo '<div><div class="detail_label">'.$k.':</div><div class="detail_field"><ul>'.$v.'</ul></div></div>';
			}
			?>
		</div>
		</div>
	</div>
	 <? }?>
<? */ ?> 
