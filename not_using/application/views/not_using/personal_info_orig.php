
<!--~~~~~~~~~~~~~~~~~~~~~~ MY INFO ~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<? if ($stage === 'edit_info') { ?>
    <? $attrs = array('class' => 'personal_info_form'); ?>
    <? echo form_fieldset('Update Your Personal Information', $attrs); ?>
    <!--<h2>Update Your Personal Information</h2>-->
    <div class="info_section">
        <div class="section_title">
             Basic Information
        </div>
        <ul class="section_body">
            <li style="color:red"><?php echo validation_errors('<p class="error">'); ?></li>
            <? echo form_open('user_info'); ?>
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

            <li class="autocomplete_input">
                <div class="inlinediv label">Favorite Color</div><?=$info[0]['color_id']?>
                <div class="inlinediv field"><? echo Form_Helper::form_input('color', '', 'id="user_color"');?></div>
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
                <div class="inlinediv label"> </div>
                <div class="inlinediv field"><? echo form_submit('submit', 'Save Basic Info'); ?></div>
            </li>
            <? echo form_close();?>
        </ul>
    </div>
    <div class="info_section">
        <div class="section_title">
            Education
        </div>
        <div class="section_body">
            <ul>
                <div id="school_entry_list">
                    <? foreach ($schools as $key => $school) { ?>
                        <div id="user_school_<?=$school['id']?>" class="school_entry">
                        <? $attrs = array('id' => 'school_edit_entry_form'); ?>
                        	<? echo form_open('edit_school', $attrs); ?>
                            <li class="autocomplete_input">
                            	<span class="form_label">School: </span>
                            	<span class="school_view_mode school_name"><? echo $school['name']; ?></span>
                            	<span style="display:none" class="school_edit_mode"><? echo Form_Helper::form_input('school_name', $school['name']); ?></span>
                            </li>
                            <li>
                            	<span class="form_label">Year: </span>
                            	<span class="school_view_mode school_year" rel="<?=date('Y')+10-$school['year']?>"><? echo $school['year']; ?></span>
                            	<span style="display:none" class="school_edit_mode"><? echo form_dropdown('school_year', $years, date('Y')+10-$school['year'], 'id="school_year_form"');?></span>
                            </li>
                            <li class="autocomplete_input">
                            	<span class="form_label">Concentration: </span>
                            	<span class="school_view_mode school_major"><? echo $school['major']; ?></span>
                            	<span style="display:none" class="school_edit_mode"><? echo Form_Helper::form_input('school_concentration', $school['major'], 'id="school_major_edit_form" class="input_placeholder"'); ?></span>
                            	<?
                            	$mj = '[';
                            	foreach($school['major_data'] as $k => $v) {
                            		if ($k > 0) {
                            			$mj = $mj.',';
                            		}
                            		$mj = $mj.'{id: '.$v['major_id'].', name: "'.$v['major'].'"}';
                            	} 
                            	$mj = $mj.']';
                            	?>
                            	<span style="display:none" class="major_data"><?=$mj?></span>
                            	<input type="hidden" id="major_names_edit" name="major_names" />
                            </li>
                            <li>
                            	<a class="editSchool school_view_mode" href="#">Edit</a>
                            	<a style="display:none" class="saveSchool school_edit_mode" href="#">Save</a> | 
                            	<a class="removeSchool" href="#">Remove</a>
                            </li>
	                        <? echo form_close(); ?>
	                        <div class="school_id" style="display:none;"><?=$school['school_id']?></div>
                        </div>
                    <? } ?>
                </div>
                <? $attrs = array('id' => 'school_entry_form'); ?>
                <? echo form_open('ac_add_user_school', $attrs); ?>
                <li class="autocomplete_input">
                    <div class="inlinediv label">School</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('school_name', set_value('school_name', 'Name'), 'id="school_name_form"');?></div>
                </li>
                <li>
                    <div class="inlinediv label">Year</div>
                    <div class="inlinediv field"><? echo form_dropdown('school_year', $years, '', 'id="school_year_form"');?></div>
                </li>
                <li class="autocomplete_input">
                    <div class="inlinediv label">Concentration</div>
                    <div class="inlinediv field"><? echo Form_Helper::form_input('school_concentration', set_value('school_concentration', 'Enter Your Major'), 'id="school_major_form" class="input_placeholder"');?></div>
                    <input type="hidden" id="major_names" name="major_names" />
                </li>
         <!--       <li>
                    <div class="inlinediv label"></div>
                    <div class="inlinediv field"><a id="addSchoolForm_lnk" href="#">Add more schools</a></div>
                </li>		-->
                <li>
                    <div class="inlinediv label"> </div>
                    <div class="inlinediv field"><? echo form_submit('submit', 'Update Schools', 'id="submit_school"'); ?></div>
                </li>
                <? echo form_close(); ?>
            </ul>
        </div>
    </div>
    
    <script type="text/javascript">

        $('.autocomplete_input input[name=school_concentration]').each(function() {
        	if ($(this).val() !== '' && $(this).val() !== 'Enter Your Major') {
        		var sch_majs = $(this).closest('.autocomplete_input').find('.major_data').text();
		        $(this).tokenInput("<?=base_url()?>get_majors", {
		            theme: "facebook",
					singleTokenOnly: false,
					allowInsert:true,
					preventDuplicates: true,
		            prePopulate: eval(sch_majs)
		        });        	
        	} else {  
		        $(this).tokenInput("<?=base_url()?>get_majors", {
		            theme: "facebook",
		            singleTokenOnly: true
		        });
	        }        
        });
		$(function() {
			
		});
	</script>
    
    <?  ?>
    <div class="info_section">
        <div class="section_title">
            Location
        </div>
        <ul class="section_body">
            <li class="autocomplete_input">
                <script type="text/javascript">
                $().ready(function() {
                    $('#updatePlace').click(function(){//showing update from place
                        $('body').append('<div id="UpdatePlacePop"></div>');
                        $('#UpdatePlacePop').load('<?php echo base_url();?>update_place');//load form update place
                        $('#UpdatePlacePop').slideDown();
                    });
                    console.log('909090909090');
                    $('#showPlace').load('<?php echo base_url();?>place_detail/edit');//load detail place user
                });
                </script>
                <div id="showPlace"></div>
            </li>
        </ul>
    </div>
    <?  ?>
    <div class="info_section">
        <div class="section_title">
            Links
        </div>
        <ul class="section_body">
            <li>
                <script type="text/javascript">
                $().ready(function() {
                    $('#editLinks').html('<div class="loading"></div>');
                    $('#editLinks').load('<?php echo base_url();?>update_link');
                });
                </script>

                <div id="editLinks"></div>

            </li>
        </ul>
    </div>
    <? echo form_fieldset_close(); ?>

    <script type="text/javascript">
        //locations
        $('.autocomplete_input #user_current_city').tokenInput("<?=base_url()?>ac_get_location", {
            theme: "facebook",
            singleTokenOnly: true
        });
        //colors
        $('.autocomplete_input #user_color').tokenInput("<?=base_url()?>ac_get_color", {
            theme: "facebook",
            singleTokenOnly: true,
            selectiveColor: true,
            prePopulate: <? echo json_encode($add_info_groups['color']) ?>,
            onAdd: function (item) { autocomplete_changed = 1 },
            onDelete: function(item) { autocomplete_changed = 1  }
        });
        //schools
        $('.autocomplete_input input[name=school_name]').each(function() {
        	if ($(this).val() !== '' && $(this).val() !== 'Name') {
        		var sch_id = $(this).closest('.school_entry').find('.school_id').text();
        		var sch_name = $(this).val();
		        $(this).tokenInput("<?=base_url()?>ac_get_school", {
		            theme: "facebook",
		            singleTokenOnly: true,
		            prePopulate: [{id: sch_id, name: sch_name}]
		        });        	
        	} else {  
		        $(this).tokenInput("<?=base_url()?>ac_get_school", {
		            theme: "facebook",
		            singleTokenOnly: true
		        });
	        }        
        });


    </script>
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

<!--~~~~~~~~~~~~~~~~~~~~~~~~~ SCHOOL INFO ~~~~~~~~~~~~~~~~~~~-->
<? }  else if($stage === 'school_info') {  ?>
    <? foreach ($schools as $key => $school) { ?>
        <div id="user_school_<?=$school['id']?>" class="school_entry">
        	<? echo form_open('edit_school'); ?>
            <li class="autocomplete_input">
            	<span class="form_label">School: </span>
            	<span class="school_view_mode"><? echo $school['name']; ?></span>
            	<span style="display:none" class="school_edit_mode"><? echo Form_Helper::form_input('school_name', $school['name']); ?></span>
            </li>
            <li>
            	<span class="form_label">Year: </span>
            	<span class="school_view_mode"><? echo $school['year']; ?></span>
            	<span style="display:none" class="school_edit_mode"><? echo Form_Helper::form_input('school_year', $school['year']); ?></span>
            </li>
            <li class="autocomplete_input">
            	<span class="form_label">Concentration: </span>
            	<span class="school_view_mode"><? echo $school['major']; ?></span>
            	<span style="display:none" class="school_edit_mode"><? echo Form_Helper::form_input('school_concentration', $school['major']); ?></span>
            </li>
            <li>
            	<a class="editSchool school_view_mode" href="#">Edit</a>
            	<a style="display:none" class="saveSchool school_edit_mode" href="#">Save</a> | 
            	<a class="removeSchool" href="#">Remove</a>
            </li>
            <? echo form_close(); ?>
            <div class="school_id" style="display:none;"><?=$school['school_id']?></div>
        </div>
    <? } ?>
<? } ?>

