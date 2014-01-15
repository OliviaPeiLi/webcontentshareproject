<?
    $is_my_profile = ($this->session->userdata['id'] === trim($this->uri->segment(2),'#'));
?>	
    <? if($info){?>
        <div id="profile_info">
            <div id="profile_info_title" class="inlinediv">Personal Information</div>
            <div class="inlinediv"><? if($edit_info_url){ ?><a href="<?=$edit_info_url?>">Edit</a> <? } ?></div>
            <div class="info_section">
                <div class="section_title">Basic Information</div>
                <div class="section_body">
                    <?
                    if($info[0][color_id])
                    {
                        echo '<div><div class="detail_label">Color:</div><div class="detail_field">'.$info[0][color_id].'</div></div>';
                    }
                    if($info[0][birthday])
                    {
                        echo '<div><div class="detail_label">birthday:</div><div class="detail_field">'.$info[0][birthday].'</div></div>';
                    }
                    if($info[0][gender] == 'm')
                    {
                        echo '<div><div class="detail_label">Gender:</div><div class="detail_field">Male</div></div>';
                    }
                    else if ($info[0][gender] == 'f')
                    {
                        echo '<div><div class="detail_label">Gender:</div><div class="detail_field">Female</div></div>';
                    }
                    if($info[0][about])
                    {
                        echo '<div><div class="detail_label">Bio:</div><div class="detail_field">'.$info[0][about].'</div></div>';
                    }
                    if($info[0][quotes])
                    {
                        echo '<div><div class="detail_label">Quotes:</div><div class="detail_field">'.$info[0][quotes].'</div></div>';
                    }
                    if($info[0][im])
                    {
                        echo '<div><div class="detail_label">IM:</div><div class="detail_field">'.$info[0][im].'</div></div>';
                    }
                    if($info[0][phone])
                    {
                        echo '<div><div class="detail_label">Phone:</div><div class="detail_field">'.$info[0][phone].'</div></div>';
                    }
                    if($info[0][address])
                    {
                        echo '<div><div class="detail_label">Address:</div><div class="detail_field">'.$info[0][address].'</div></div>';
                    }
                    ?>
                </div>
            </div>
            <? //echo 'something ese'; ?>
            <? //echo $profile_id; ?> 
            <? //echo $this->session->userdata['id']; ?>
            <? //print_r($this->session->userdata);?>
            <? //echo $real_id; ?>
            <? if ((count($schools) > 0) || ($profile_id === $real_id)) { ?>
	            <div class="info_section">
	                <div class="section_title">Education</div>
	                <div class="section_body">
	                	<? if (count($schools) > 0) { ?>
		                    <ul>
		                    <? 
		                    foreach($schools as $sk => $sv) {
		                        echo '<li>'
		                            .'<div class="school_name">'.$sv['name'].'</div>'
		                            .'<div class="school_detail_year">Class of '.$sv['year'].'</div>'
		                            .'<div class="school_detail_major">'.$sv['major'].'</div>'
		                        .'</li>';
		                    } 
		                    ?>
		                    </ul>
	                    <? } else { 
	                    	echo '<i>You have not entered any school information</i>';
	                    } ?>
	                </div>
	            </div>
            <? } ?>
            <?  ?>
            <? if ($query->num_rows() > 0 || $profile_id === $real_id) { ?>
	            <div class="info_section">
	                <div class="section_title">Location</div>
	
	                <div class="section_body" id="location_container">
	                <?php
	                    //create list place from database
	                    if($query->num_rows()>=1){
	                        //this for change size markers
	                        $total_place=$query->num_rows();
	                        if($total_place>=7) $size='tiny';
	                        if($total_place>=3) $size='small';
	                        else $size='mid';
	
	                        $marker='';
	                        $place_name='';
	                        $place_travel_name='';
	                        foreach($query->result() as $row){
	                            if($row->options=='travel'){
	                                $marker.='&markers=icon:http://chart.apis.google.com/chart?chst=d_map_pin_icon%26chld=cafe%257C996600%7Csize:'.$size.'%7Ccolor:green%7C'.urlencode($row->place_name);
	                                $place_travel_name.= '<li class="green">'.$row->place_name.'</li>';
	                            }
	                            else{
	                                if($row->options=='current'){//this place is current location change pin color to blue
	                                    $marker.='&markers=icon:http://chart.apis.google.com/chart?chst=d_map_pin_icon%26chld=home%257C3366CC%7Csize:'.$size.'%7C'.urlencode($row->place_name);
	                                    $current_place_name.= '<li class="current">'.$row->place_name.'</li>';//create list place name if current location
	                                }
	                                else{
	                                    $marker.='&markers=icon:http://chart.apis.google.com/chart?chst=d_map_pin_icon%26chld=home%257C008000%7Csize:'.$size.'%7C'.urlencode($row->place_name);
	                                    $lived_place_name.= '<li class="green">'.$row->place_name.'</li>';//create list place name
	                                }
	                            }
	                        }
	                    	?>
	                        <? $person = ($is_my_profile) ? 'You have' : 'Has'; ?>
	                        <div class="listPlace inlinediv">
	                            <div class="subsection_title">
	                                Current Location
	                            </div>
	                            <ul class="subsection_body">
	                                <?php
	                                //show list place
	                                echo $current_place_name;
	                                ?>
	                            </ul>
	                            <div class="subsection_title">
	                                <?=$person?> lived in
	                            </div>
	                            <ul class="subsection_body">
	                                <?php
	                                //show list place
	                                echo $lived_place_name;
	                                ?>
	                            </ul>
	                            <div class="subsection_title">
	                                <?=$person?> traveled to
	                            </div>
	                            <ul class="subsection_body">
	                                <?php
	                                //show list place travel
	                                echo $place_travel_name;
	                                ?>
	                            </ul>
	                        </div>
	                        <div id="mapPlace" class="inlinediv"><img src="http://maps.googleapis.com/maps/api/staticmap?maptype=roadmap<?php echo $marker;?>&size=340x210&sensor=false" alt="map place" /></div>
	                        <div class="clr"></div>
	                    <?
	                    } else {
	                    	echo '<i>You have not entered any location data.</i>';
	                    }
	                    ?>
	                </div>
	            </div>
            <? } ?>
            <?  ?>
            <? if ($link_query->num_rows() > 0 || $profile_id === $real_id) { ?>
	            <div class="info_section">
	                <div class="section_title">Links</div>
	                <ul class="section_body">
		                <?php
		                if ($link_query->num_rows() > 0) {
			                foreach($link_query->result() as $row){
			                    //get favicon from url
			                    $fav = str_replace('http://','',$row->url);
			                    $url = explode('://',$row->url);
			                    if($url[0]=='http' || $url[0]=='https') $url = $row->url;
			                    else $url = 'http://'.$row->url;
			                    echo '<li><a href="'.$url.'" target="blank"><img src="//s2.googleusercontent.com/s2/favicons?alt=p&domain='.$fav.'" alt="" />'.$row->label.'</a></li>';
			                }
		                } else {
		                	echo '<i>You have not provided any links</i>';
		                }
		                ?>
	                </ul>
	
	                <div class="clr"></div>
	            </div>
	         <? } ?>
	         <?  ?>
        </div>
    <? }?>
    
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