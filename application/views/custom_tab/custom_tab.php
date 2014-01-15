<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/custom_tab/custom_tab.php ) --> ' . "\n";
} ?>
<script type="text/javascript">
	php.segment3 = '<?=$this->uri->segment(3)?>';
	php.segment4 = '<?=$this->uri->segment(4)?>';
</script>
<?=requireJS(array("jquery","custom_tab/customtab"))?>

<?php die('TO DO: not used')?>
	
<div id="main">
    <? if($owner_priv == 1 || $admin_priv == 1 || $tab[0]['activated'] == 1) {?>
	<? //--~~~~~~~~~~~~~~~~ Page Tab Control Placeholders ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ?>
	<div class="tab_actions">
	    <? foreach($tab as $tab_k => $tab_v){?>
	    <? //--~~~~~~~~ Tab Edit Options (Add/Delete/Edit) placeholder ~~~~~~~ ?>
	    <div id="tab_edit_tools">
		<? if($owner_priv == 1 || $admin_priv == 1) {?>
		    Tab Edit tools: 
		    <? if($tab_v['activated'] == '0'){?>
			<a href="/activate/<? echo $this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(2); ?>">Activate</a>
		    <? }else{ ?>
			<a href="/dectivate/<? echo $this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(2); ?>">Deactivate</a>
		    <? } // if activated statement?>
		    | <a href="#" class="ft-dropdown" rel="edit_tab">Edit Tab Name</a>
		    | <a href="/del_tab/<? echo $this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(2);?>">Erase Tab</a>
		<? } ?>
	    </div>
	    <div id="edit_tab">
	    	<? 
		    echo form_open('tab_name/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(2));
		    echo 'Tab Name'. Form_Helper::form_input('new_tab_name', set_value('new_tab_name', $tab_v['tab_name'])); 
		    echo form_submit('submit', 'Submit', 'class=blue_bg');
		    echo form_close();
    		?>
	    </div>
	    <div id="new_components">
		New Components: 
		<a href="#" onclick="$('#new_text').show('fade'); return false">Add</a>
	    </div>
	    <div id="new_text" style="display:none;">
		<? echo form_open('add_text/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(2).'?header=none'); ?>
		    <div>
			<div class="form_label">Type:</div>
			<div class="form_field tabs_components_form">
			    <? echo form_dropdown('type_add', array(
				'text'  => 'Text',
				'google_map'  => 'Google Map',
				'youtube_video'  => 'Youtube Video',
				'twitter'  => 'Twitter'));
			    ?>
			</div>
		    </div>
		    <div>
			<div class="form_label">Content:</div>
			<div class="form_field">
			    <textarea name="new_content" class="tab_new_text"></textarea>
			</div>
		    </div>
		    <div>
			<div class="form_label"> </div>
			<div class="form_field">
			<? echo form_submit('submit', 'Submit', 'class="blue_bg"'); ?>
			</div>
		    </div>
		    <? echo form_close(); ?>
		    </div>
		    <? ?>
		    <div id="sortable">
		    <? foreach($tab_content as $content_k => $content_v){
			if($content_v['type'] == 'youtube_video') {
			    $display_content = 	youtube($content_v['content']);
			} else if($content_v['type'] == 'google_map') {
			    $display_content = 	google_maps($content_v['content']);
			} else if($content_v['type'] == 'twitter') {
			    $display_content = 	twitter($content_v['content']);
			} else if($content_v['type'] == 'text') {
			    $display_content = 	$content_v['content'];
			}
		    ?>
		    <div id="list_<?=$content_v['component_id'];?>" class="component_tab">
			<? if($owner_priv == 1 || $admin_priv == 1) {?>
			    <div>
				<img src="/images/arrow.png" alt="move" width="16" height="16" class="ui-state-default" />
				<a href="#" class="ft-dropdown" rel="edit<?=$content_v['component_id']?>">Edit</a> |
				<a href="/del_component/<? echo $this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$content_v['component_id'].'/'.$this->uri->segment(2);?>">Remove</a>
			    </div>
			<? }?>
			<div>
			    <? echo $display_content;?>
			</div>
		    </div>
		    <div class="edit_content" id="edit<?=$content_v['component_id']?>">
			<?
			    echo form_open('component/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$content_v['component_id'].'/'.$this->uri->segment(2));
			    echo 'Update Content'. form_textarea('new_tab_content', set_value('new_tab_content', $content_v['content']));
			    echo form_submit('submit', 'Submit', 'class="blue_bg"');
			    echo form_close();
			?>
		    </div>
		<? }?>
	    </div>
	    <div id="info"></div>
	<? } //end foreach loop containing tab data ?>
    </div>
    <?
	/* } // if current tab statement */
	} //end if activated
	else {
	    echo '<div class="error">No Tabs are currently active</div>';
	}
    ?>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/custom_tab/custom_tab.php ) -->' . "\n";
} ?>
