<? foreach ($schools as $key => $school) { ?>
<? /* - this should be model relations

		$mj = '[';
		if(isset($school->major'])){
			foreach($school->major as $k => $v) {
				if ($k > 0) {
					$mj = $mj.',';
				}
				$mj = $mj.'{id: '.$v['major_id'].', name: "'.$v['major'].'"}';
			} 
		}
		$mj = $mj.']'; */
		 $mj = $school->major;
		?>
	<div class="school_entry">
		<a class="removeSchool delete" type="submit">Remove</a>
		<?=Form_Helper::open('ac_add_user_school', array('id' => 'school_edit_entry_form', 'rel'=>'ajaxForm'))?>
		<ul>
			<li class="autocomplete_input">
				<div class="inlinediv label">School</div>
				<div class="inlinediv field school_view_mode school_name"><?=$school->school->name; ?></div>
				<div style="display:none" class="school_edit_mode inlinediv field">
					<?=Form_Helper::input('school', str_replace('"',"'",'[{id: '.$school->school_id.', name: "'.$school->school->name.'"}]'), array(
						'class'=>"school_form_text tokenInput",
						'data-url'=>"/ac_get_school",
						'theme'=>"google",
						'token_limit'=>"1",
						'placeholder'=>"School Name"
					))?>
				</div>
			</li>
			<li>
				<button class="editSchool edit_button_left">Edit</button>
				<div class="inlinediv label">Year</div>
				<div class="inlinediv field school_view_mode school_year" rel="<?=date('Y')+10-$school->year?>"><?=$school->year?></div>
				<div style="display:none" class="school_edit_mode inlinediv field">
					<?=Form_Helper::dropdown('school_year', range(date('Y')+10, 1900), date('Y')+10-$school->year, array('id'=>"school_year_form"))?>
				</div>
				<input type="hidden" name="year" value="" />
			</li>
			<li class="autocomplete_input">
				<div class="inlinediv label">Concentration</div>
				<div class="inlinediv field school_view_mode body school_major"><?=$school->major?></div>
				<div style="display:none" class="school_edit_mode inlinediv field">
					<?=Form_Helper::input('major', str_replace( '"', "'", $mj ), array(
						'id'=>"school_major_edit_form",
						'class'=>"school_form_text tokenInput",
						'theme'=>"google",
						'data-url'=>"/get_majors",
						'allow_insert'=>"true",
						'prevent_duplicates'=>"true",
						'placeholder'=>"Add any number of majors",
						'linkedText'=>"+ Add Major"
					))?>
				</div>
				<div style="display:none" class="major_data"><?=$mj?></div>
				<input type="hidden" id="major_names_edit" name="major_names" />
			</li>
			<li>
			 	<button class="updateSchool submit_button body blue_bg" type="submit" style="display:none;">Save</button>
			 	<button class="cancelUpdateSchool submit_button body blue_bg" type="submit" style="display:none;">Cancel</button>
			</li> 
		</ul> 
		<?=Form_Helper::close()?>
	</div>
	<div class="clear"></div>
<? } ?> 