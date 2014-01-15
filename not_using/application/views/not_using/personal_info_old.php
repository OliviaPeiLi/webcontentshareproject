<div id="main">
	<div class="container_24">
	<div class="clear"></div>    
	<div class="grid_20">
		
		<!--~~~~~~~~~~~~~~~~~~~~~~ MY INFO ~~~~~~~~~~~~~~~~~~~~~~~~~~-->
		<? if ($stage === 'my_info') { ?>
			<? $attrs = array('class' => 'personal_info_form'); ?>
			<? echo form_fieldset('Update Your Personal Information', $attrs); ?>
			<!--<h2>Update Your Personal Information</h2>-->
			<ul>
				<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
				<li><? echo form_open('user_info'); ?></li>
				<li class="autocomplete_input">
                	<script type="text/javascript">
					$().ready(function() {
						$('#updatePlace').click(function(){//showing update from place
							$('body').append('<div id="UpdatePlacePop"></div>');
							$('#UpdatePlacePop').load('<?php echo base_url();?>index.php/user_locations_controller/updateplaceAjcax');//load form update place
							$('#UpdatePlacePop').slideDown();
						});
						$('#showPlace').load('<?php echo base_url();?>index.php/user_locations_controller/detailPlace');//load detail place user
					});
					</script>
                    <div id="showPlace"></div>
	                <div><a href="#" id="updatePlace">Update Place</a></div>
                    
                    <script type="text/javascript">
					$().ready(function() {
						$('#updateLinks').click(function(){
							$('body').append('<div id="UpdateLinksPop"></div>');
							$('#UpdateLinksPop').load('<?php echo base_url();?>index.php/user_links_controller/updateLinksAjcax');
							$('#UpdateLinksPop').slideDown();
						});
						$('#showLinks').html('<div class="loading"></div>');
						$('#showLinks').load('<?php echo base_url();?>index.php/user_links_controller/getDetailLinks');
					});
					</script>
                    
                    <div id="showLinks"></div> 
					<div><a href="#" id="updateLinks">Update Links</a></div>
                    
					<div class="inlinediv label">Current City</div>
					<div class="inlinediv field"><? echo Form_Helper::form_input('current_city', set_value('current_city', $info[0]['current_city']), 'id="user_current_city"');?></div>
				</li>
				<li>
					<div class="inlinediv label">Birthday</div>
					<div class="inlinediv field"><? echo form_dropdown('month', month(), $month).form_dropdown('day', day(), $day).form_dropdown('year', year(), $year);?></div>
				</li>
				<li>
					<div class="inlinediv label">Gender</div>
					<div class="inlinediv field">
						<div><?=form_radio('gender', 'm', $m_gender); ?> Male</div>
						<div><?=form_radio('gender', 'f', $f_gender); ?> Female</div>
				</li>
				<li>
					<div class="inlinediv label">About Yourself</div>
					<div class="inlinediv field"><? echo form_textarea('about', set_value('about', $info[0]['about'])); ?></div>
				</li>               
                <li>
					<div class="inlinediv label">Favorite Quotation</div>
					<div class="inlinediv field"><? echo form_textarea('fav_quote', $quotes[0]['quotes'], '');?></div>
				</li>

                <li>
                    <div class="inlinediv label">Email</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('email', $contact[0]['email'], '');?></div>
                </li>
                <li>
                    <div class="inlinediv label">IM</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('im', $contact[0]['im'], '');?></div>
                </li>
                <li>
                    <div class="inlinediv label">Phone</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('phone', $contact[0]['phone'], '');?></div>
                </li>
                <li>
                    <div class="inlinediv label">Address</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('address', $contact[0]['address'], '');?></div>
                </li>
                <li>
                    <div class="inlinediv label">Websites</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('website', $contact[0]['website'], '');?></div>
                </li>
                <div id="school_entry_list">
					<? foreach ($schools as $key => $school) { ?>
						<div class="school_entry">
                        <li><span class="form_label">School: </span><? echo $school['name']; ?></li>
                        <li><span class="form_label">Year: </span><? echo $school['year']; ?></li>
                        <li><span class="form_label">Concentration: </span><? echo $school['major']; ?></li>
                        <li><a class="editSchool" href="#">Edit</a></li>
						</div>
					<? } ?>
				</div>
						
                <li class="autocomplete_input">
                    <div class="inlinediv label">School</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('school_name', set_value('school_name', 'Name'), 'id="school_name_form"');?></div>
                </li>
                <li>
                    <div class="inlinediv label">Year</div>
                    <div class="inlinediv field"><? echo form_dropdown('school_year', $years, '', 'id="school_year_form"');?></div>
                </li>
                <li>
                    <div class="inlinediv label">Concentration</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('school_concentration', set_value('school_concentration', 'Enter Your Major'), 'id="school_major_form" class="input_placeholder"');?></div>
                </li>
                <li>
                    <div class="inlinediv label"></div>
                    <div class="inlinediv field"><a id="addSchoolForm_lnk" href="#">Add more schools</a></div>
                </li>


                <li>
                    <div class="inlinediv label"> </div>
                    <div class="inlinediv field"><? echo form_submit('submit', 'Save'); ?></div>
                </li>
                <? echo form_close();?></li>
            </ul>
            <? echo form_fieldset_close(); ?>
                
            <script type="text/javascript">
					$('.autocomplete_input #user_current_city').tokenInput("<?=base_url()?>index.php/user/get_location", {
						theme: "facebook",
						singleTokenOnly: true
					});                
			</script>
            
		   <script type="text/javascript">		    					
                $('.autocomplete_input #school_name_form').tokenInput("<?=base_url()?>index.php/user/get_school", {
                    theme: "facebook",
                    singleTokenOnly: true
                });            
            </script>
        
		<!--~~~~~~~~~~~~~~~~~~~~~~~~~ SCHOOL INFO ~~~~~~~~~~~~~~~~~~~-->
		<? }  else if($stage === 'school_info') {  ?>
			<? foreach ($schools as $key => $school) { ?>
				<div class="school_entry">
					<li><span class="form_label">School: </span><? echo $school['name']; ?></li>
					<li><span class="form_label">Year: </span><? echo $school['year']; ?></li>
					<li><span class="form_label">Concentration: </span><? echo $school['major']; ?></li>
					<li><a class="editSchool" href="#">Edit</a></li>
				</div>
        <? } ?>

		<!--~~~~~~~~~~~~~~~~~~~~~~~~ MY NETWORKS ~~~~~~~~~~~~~~~~~~~~~~~~-->	
		<? } else if ($stage === 'network') { ?>
			<? $attrs = array('class' => 'personal_info_form'); ?>
			<? echo form_fieldset('Your Networks', $attrs); ?>
			<!--<h2>Network</h2>-->
				<div id="school_entry_list">
					<? foreach ($schools as $key => $school) { ?>
						<div class="school_entry">
                        <li><span class="form_label">School: </span><? echo $school['name']; ?></li>
                        <li><span class="form_label">Year: </span><? echo $school['year']; ?></li>
                        <li><span class="form_label">Concentration: </span><? echo $school['major']; ?></li>
                        <li><a class="editSchool" href="#">Edit</a></li>
						</div>
					<? } ?>
				</div>
				<div id="school_entry_form">
					<ul>
						<? echo form_open('',array('id'=>'network')); ?>
						<li class="autocomplete_input">
							<div class="inlinediv label">School</div>
							<div class="inlinediv field"><? echo Form_Helper::form_input('school_name', set_value('school_name', 'Name'), 'id="school_name_form"');?></div>
						</li>
						<li>
							<div class="inlinediv label">Year</div>
							<div class="inlinediv field"><? echo form_dropdown('school_year', $years, '', 'id="school_year_form"');?></div>
						</li>
						<li>
							<div class="inlinediv label">Concentration</div>
							<div class="inlinediv field"><? echo Form_Helper::form_input('school_concentration', set_value('school_concentration', 'Enter Your Major'), 'id="school_major_form" class="input_placeholder"');?></div>
						</li>
						<li>
							<div class="inlinediv label"></div>
							<div class="inlinediv field"><a id="addSchoolForm_lnk" href="#">Add more schools</a></div>
						</li>
						<li>
							<div class="inlinediv label"> </div>
							<div class="inlinediv field"><? echo form_submit('submit', 'Update'); ?></div>
						</li>
						<? echo form_close();?>
					</ul>
           </div>
				<? echo form_fieldset_close(); ?>
           <script type="text/javascript">		    					
					$('.autocomplete_input #school_name_form').tokenInput("<?=base_url()?>index.php/user/get_school", {
						theme: "facebook",
						singleTokenOnly: true
					});            
				</script>
           
			<!--~~~~~~~~~~~~~~~~~~~~~~ LIFE PHILOSOPHY ~~~~~~~~~~~~~~~~~~~~~~~-->
			<? } else if ($stage === 'life_philo') { 				
				?>
				<? $attrs = array('class' => 'personal_info_form'); ?>
				<? echo form_fieldset('Life & Philosophy', $attrs); ?>
				<!--<h2>Life & Philosophy</h2>-->
				<ul>
					<? echo form_open('info_life',array('id'=>'life_philo')); ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Religion</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('religion', '', 'id="load_religion"');?></div>
					</li>
						<? echo '<input type="hidden" id="religion_n" name="religion_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Political View</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('political_view', '', 'id="load_political"');?></div>
					</li>
						<? echo '<input type="hidden" id="political_view_n" name="political_view_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Inspiration</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('inspiration', '', 'id="load_inspiration"');?></div>
					</li>
						<? echo '<input type="hidden" id="inspiration_n" name="inspiration_name" />'; ?>
					<li>
						<div class="inlinediv label">Favorite Quotation</div>
						<div class="inlinediv field"><? echo form_textarea('fav_quote', $quotes[0]['quotes'], '');?></div>
					</li>
					<li>
						<div class="inlinediv label"> </div>
						<div class="inlinediv field"><? echo form_submit(array('name'=>'submit','value'=>'Save','id'=>'life_philo_submit')); ?></div>
					</li>
					<? echo form_close(); ?>
				</ul>
				<? echo form_fieldset_close(); ?>
				<script type="text/javascript">
					//autoload
					$('.autocomplete_input #load_religion').tokenInput("<?=base_url()?>index.php/user/getter/religion", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['religion']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_religion'); }
					});
					$('.autocomplete_input #load_political').tokenInput("<?=base_url()?>index.php/user/getter/political", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['political']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_political'); }
					});
					$('.autocomplete_input #load_inspiration').tokenInput("<?=base_url()?>index.php/user/getter/inspiration", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['inspiration']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_inspiration'); }
					});
											
					//save names
					$('#life_philo_submit').live('click', function() { 
						prepare_autocomplete('#life_philo','#load_religion','#religion_n');
						prepare_autocomplete('#life_philo','#load_political','#political_view_n');
						prepare_autocomplete('#life_philo','#load_inspiration','#inspiration_n');								
						return true;
					});
				</script>
				
			<!--~~~~~~~~~~~~~~~~~~~~~ MORE INFO ABOUT ME ~~~~~~~~~~~~~~~~~~~~~~-->	
			<? } else if ($stage === 'more_about_me') { 
				?>
				<? $attrs = array('class' => 'personal_info_form'); ?>
				<? echo form_fieldset('More About Me', $attrs); ?>
				<!--<h2>More About Me</h2>-->
				<ul>
					<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
					<? echo form_open('user_info_more',array('id'=>'more_about_me')); ?>					
					<li class="autocomplete_input">
						<div class="inlinediv label">Language</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('language', '', 'id="load_language"');?></div>
					</li>
						<? echo '<input type="hidden" id="language_n" name="language_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Favorite Color</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('color', '', 'id="user_color"');?></div>
					</li>
					<li class="autocomplete_input">
						<div class="inlinediv label">Favorite Food</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('food', '', 'id="load_food"');?></div>
					</li>
						<? echo '<input type="hidden" id="food_n" name="food_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Favorite Drink</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('drink', '', 'id="load_drink"');?></div>
					</li>
						<? echo '<input type="hidden" id="drink_n" name="drink_name" />'; ?>            
					<li class="autocomplete_input">
						<div class="inlinediv label">Gadgets</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('gadgets', '', 'id="load_gadgets"');?></div>
					</li>
						<? echo '<input type="hidden" id="gadgets_n" name="gadgets_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Brands</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('brands', '', 'id="load_brands"');?></div>
					</li>
						<? echo '<input type="hidden" id="brands_n" name="brands_name" />'; ?>
					<li>
						<div class="inlinediv label"> </div>
						<div class="inlinediv field"><? echo form_submit(array('name'=>'submit','value'=>'Save','id'=>'more_submit')); ?></div>
					</li>
					<? echo form_close();?>
				</ul>
				<? echo form_fieldset_close(); ?>
				<script type="text/javascript">
					//autoload
					$('.autocomplete_input #load_language').tokenInput("<?=base_url()?>index.php/user/getter/language", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['language']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_language');  }
					});
							
					$('.autocomplete_input #load_food').tokenInput("<?=base_url()?>index.php/user/getter/food", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['food']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_food');  }
					});
					$('.autocomplete_input #load_drink').tokenInput("<?=base_url()?>index.php/user/getter/drink", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['drink']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_drink');  }
					});
					$('.autocomplete_input #load_gadgets').tokenInput("<?=base_url()?>index.php/user/getter/gadget", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['gadget']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_gadgets');  }
					});
					$('.autocomplete_input #load_brands').tokenInput("<?=base_url()?>index.php/user/getter/brand", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['brand']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_brands');  }
					});
					$('.autocomplete_input #user_color').tokenInput("<?=base_url()?>index.php/user/get_color", {
						theme: "facebook",
						singleTokenOnly: true,
						selectiveColor: true,
						prePopulate: <? echo json_encode($add_info_groups['color']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1  }
					});
					//save names
					$('#more_submit').live('click', function() { 
						prepare_autocomplete('#more_about_me','#load_language','#language_n');
						prepare_autocomplete('#more_about_me','#load_food','#food_n');
						prepare_autocomplete('#more_about_me','#load_drink','#drink_n');
						prepare_autocomplete('#more_about_me','#load_gadgets','#gadgets_n');
						prepare_autocomplete('#more_about_me','#load_brands','#brands_n');									
						return true;
					});
				</script>
				
			<!--~~~~~~~~~~~~~~ ARTS & ENTERTAINMENT ~~~~~~~~~~~~~~~~-->	
			<? } else if ($stage === 'art_entertainment') {
				?>
				<? $attrs = array('class' => 'personal_info_form'); ?>
				<? echo form_fieldset('Art & Entertainment', $attrs); ?>
				<!--<h2>Art & Entertainment</h2>-->
				<ul>
					<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>            
            	<? echo form_open('user_info_art',array('id'=>'art_entertainment')); ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Art</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('art', '', 'id="load_art"');?></div>
					</li>
						<? echo '<input type="hidden" id="art_n" name="art_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Books</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('books', '', 'id="load_books"');?></div>
					</li>
						<? echo '<input type="hidden" id="books_n" name="books_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Instruments</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('instruments', '', 'id="load_instruments"');?></div>
					</li>
						<? echo '<input type="hidden" id="instruments_n" name="instruments_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Music</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('music', '', 'id="load_music"');?></div>
					</li>
						<? echo '<input type="hidden" id="music_n" name="music_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Movies</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('movies', '', 'id="load_movies"');?></div>
					</li>
						<? echo '<input type="hidden" id="movies_n" name="movies_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Games</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('games', '', 'id="load_games"');?></div>
					</li>
						<? echo '<input type="hidden" id="games_n" name="games_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Favorite Celebrities</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('fav_celebs', '', 'id="load_celebs"');?></div>
					</li>
						<? echo '<input type="hidden" id="celebs_n" name="celebs_name" />'; ?>
					<li>
						<div class="inlinediv label"> </div>
						<div class="inlinediv field"><? echo form_submit(array('name'=>'submit','value'=>'Save','id'=>'art_entertainment_submit')); ?></div>
					</li>
					<? echo form_close();?>
				</ul>
				<? echo form_fieldset_close(); ?>
				<script type="text/javascript">
					//autoload
					$('.autocomplete_input #load_art').tokenInput("<?=base_url()?>index.php/user/getter/art", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['art']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item,'#load_art'); }
					});
					$('.autocomplete_input #load_books').tokenInput("<?=base_url()?>index.php/user/getter/book", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['book']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_books'); }
					});
					$('.autocomplete_input #load_instruments').tokenInput("<?=base_url()?>index.php/user/getter/instrument", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['instrument']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_instruments'); }
					});
					$('.autocomplete_input #load_music').tokenInput("<?=base_url()?>index.php/user/getter/music", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['music']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_music'); }
					});
					$('.autocomplete_input #load_movies').tokenInput("<?=base_url()?>index.php/user/getter/movie", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['movie']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_movies'); }
					});
					$('.autocomplete_input #load_games').tokenInput("<?=base_url()?>index.php/user/getter/game", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['game']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_games'); }
					});	
					$('.autocomplete_input #load_celebs').tokenInput("<?=base_url()?>index.php/user/getter/celebrity", {
						theme: "facebook",
						singleTokenOnly: true,
						prePopulate: <? echo json_encode($add_info_groups['celebrity']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_celebs'); }
					});
					//save names
					$('#art_entertainment_submit').live('click', function() { 
						prepare_autocomplete('#art_entertainment','#load_art','#art_n');
						prepare_autocomplete('#art_entertainment','#load_books','#books_n');
						prepare_autocomplete('#art_entertainment','#load_instruments','#instruments_n');
						prepare_autocomplete('#art_entertainment','#load_music','#music_n');
						prepare_autocomplete('#art_entertainment','#load_movies','#movies_n');
						prepare_autocomplete('#art_entertainment','#load_games','#games_n');
						prepare_autocomplete('#art_entertainment','#load_celebs','#celebs_n');
						return true;
					});
				</script>
				
			<!--~~~~~~~~~~~~~~~~~~~~ SPORTS ACTIVITIES ~~~~~~~~~~~~~~~~~~~~~~~-->
			<? } else if ($stage === 'sports_activities') { 
				?>
				<? $attrs = array('class' => 'personal_info_form'); ?>
				<? echo form_fieldset('Sports & Activities', $attrs); ?>
				<!--<h2>Sports & Activities</h2>-->
				<ul>
					<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
					<? echo form_open('user_info_sports',array('id'=>'sports_activities')); ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Sports I play</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('sports', '', 'id="load_sports"'); ?></div>
					</li>
						<? echo '<input type="hidden" id="sports_n" name="sports_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Favorite Atheletes</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('fav_athletes', '', 'id="load_athletes"');?></div>
					</li>
						<? echo '<input type="hidden" id="athletes_n" name="athletes_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Favorite Teams</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('fav_teams', '', 'id="load_teams"');?></div>
					</li>
						<? echo '<input type="hidden" id="teams_n" name="teams_name" />'; ?>
					<li class="autocomplete_input">
						<div class="inlinediv label">Interests & Activities</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('interest_activities', '', 'id="load_interest_activities"');?></div>
					</li>
						<? echo '<input type="hidden" id="interest_activities_n" name="interest_activities_name" />'; ?>
					<li>
						<div class="inlinediv label"> </div>
						<div class="inlinediv field"><? echo form_submit(array('name'=>'submit','value'=>'Save','id'=>'sports_activities_submit')); ?></div>
					</li>
	      		<? echo form_close();?>
				</ul>
				<? echo form_fieldset_close(); ?>
				<script type="text/javascript">
					//autoload
					$('.autocomplete_input #load_sports').tokenInput("<?=base_url()?>index.php/user/getter/sport", {
						theme: "facebook",
						singleTokenOnly: false	,
						prePopulate: <? echo json_encode($add_info_groups['sport']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_sports'); }
					});
					$('.autocomplete_input #load_athletes').tokenInput("<?=base_url()?>index.php/user/getter/athlete", {
						theme: "facebook",
						singleTokenOnly: false,
						prePopulate: <? echo json_encode($add_info_groups['athlete']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_athletes'); }
					});	
					$('.autocomplete_input #load_teams').tokenInput("<?=base_url()?>index.php/user/getter/team", {
						theme: "facebook",
						singleTokenOnly: false,
						prePopulate: <? echo json_encode($add_info_groups['team']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_teams'); }
					});
					$('.autocomplete_input #load_interest_activities').tokenInput("<?=base_url()?>index.php/user/getter/activity", {
						theme: "facebook",
						singleTokenOnly: false,
						prePopulate: <? echo json_encode($add_info_groups['activity']) ?>,
						onAdd: function (item) { autocomplete_changed = 1 },
						onDelete: function(item) { autocomplete_changed = 1; delete_autocomplete_item(item, '#load_interest_activities'); }
					});
					//sava names
					$('#sports_activities_submit').live('click', function() { 
						prepare_autocomplete('#sports_activities','#load_sports','#sports_n');
						prepare_autocomplete('#sports_activities','#load_athletes','#athletes_n');
						prepare_autocomplete('#sports_activities','#load_teams','#teams_n');
						prepare_autocomplete('#sports_activities','#load_interest_activities','#interest_activities_n');
						return true;
					});
				</script>
            
			<!--~~~~~~~~~~~~~~~~~~~~~~ CONTACT INFO ~~~~~~~~~~~~~~~~~~~~~~~~~~-->
			<? } else if ($stage === 'contact') { ?>
				<? $attrs = array('class' => 'personal_info_form'); ?>
				<? echo form_fieldset('Contact Info', $attrs); ?>
				<!--<h2>Contact Info</h2>-->
				<ul>
					<li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
					<li><? echo form_open('user_contact'); ?></li>
					<li>
						<div class="inlinediv label">Email</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('email', $add_info[0]['email'], '');?></div>
					</li>
					<li>
						<div class="inlinediv label">IM</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('im', $add_info[0]['im'], '');?></div>
					</li>
					<li>
						<div class="inlinediv label">Phone</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('phone', $add_info[0]['phone'], '');?></div>
					</li>
					<li>
						<div class="inlinediv label">Address</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('address', $add_info[0]['address'], '');?></div>
					</li>
					<li>
						<div class="inlinediv label">Websites</div>
						<div class="inlinediv field"><? echo Form_Helper::form_input('website', $add_info[0]['website'], '');?></div>
					</li>
					<li>
						<div class="inlinediv label"> </div>
						<div class="inlinediv field"><? echo form_submit('submit', 'Save'); ?></div>
					</li>
					<? echo form_close();?></li>
				</ul>
				<? echo form_fieldset_close(); ?>
			<? }?>
		</div>
		<div class="grid_4">    	
			<ul id="personal_info_tabs">
				<li><a href="/personal_info/my_info">My Info</a></li>
				<li><a href="/personal_info/network">Network</a></li>
				<li><a href="/personal_info/life_philo">Life & Philosophy</a></li>
				<li><a href="/personal_info/more_about_me">More about me</a></li>
				<li><a href="/personal_info/art_entertainment">Art & Entertainment</a></li>
				<li><a href="/personal_info/sports_activities">Sports & Activities</a></li>
				<li><a href="/personal_info/contact">Contact Info</a></li>
			</ul>        
		</div>
	</div>
</div>

<script type="text/javascript">
	window.onbeforeunload = unload_autocomplete;
	if (window.attachEvent) {window.attachEvent('onload', load_autocomplete);}
	else if (window.addEventListener) {window.addEventListener('load', load_autocomplete, false);}
	else {document.addEventListener('load', load_autocomplete, false);}

	
	function show_confirm()
	{
		var r=confirm("Have you saved the changes to your Personal Information? Press 'OK' to Save. Press 'Cancel' to discard your changes");
		if (r==true) {
			$('#main form input[type=submit]').trigger('click');
		} else {
		}
		
		/*
		jConfirm('Can you confirm this?', 'Confirmation Dialog', function(r) {
				jAlert('Confirmed: ' + r, 'Confirmation Results');
				if (r==true) {
					$('#main form input[type=submit]').trigger('click');
					return true;
				} else {
					return true;
				}
				//return true;
		});
		*/
	}
</script>
	
	