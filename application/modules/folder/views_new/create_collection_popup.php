<? $this->load->config('folder/config.php'); ?>
<? $this->lang->load('folder/folder', LANGUAGE);?>
<div id="edit_folder_popup" style="display:none;">
	<?=Form_Helper::open('/folder_edit_basic', array('rel'=>'ajaxForm', 'id'=>'basic_form'), array('folder_id' => 0, 'edittype' => '')); ?>
		<div class="basic_section">
			<div class="popup_row form_row">
				<span class="error"></span>	
				<div class="left inlinediv"><?=$this->lang->line('folder_collection_name_lbl');?>:</div>
				<div class="inlinediv">
					<span id="collection_name" style="display:none"></span>
					<?=Form_Helper::input('folder_name','',array(
								'id'=>"collection_name_field",
								//RR 1/22/2013 - removed uniqcollection - we need custom validation for that because the same popup is used for both create and update
								//RR 2/7/2013 - removed specialchars - http://dev.fantoon.com:8100/browse/FD-3248
								'data-validate'=>"required|maxlength", 
								'data-error-uniqcollection' => "List with this name already exist",
								//'data-error-specialchars' => "Collection name cannot contain special characters",
								'data-error-required' => 'The List name cannot be blank',
								'maxlength'=>$this->config->item('folder_name_chars_limit')
					))?>
					<span class="textLimit"><?=$this->config->item('folder_name_chars_limit')?></span>
				</div>
			</div>
			
			<div class="popup_row">
				<div class="left inlinediv"><?=$this->lang->line('folder_collection_sort_by');?>:</div>
				<div class="inlinediv collection_privacy">
					<label><?=Form_Helper::form_radio('sort_by',0,TRUE)?> <?=$this->lang->line('folder_collection_sort_by_time');?></label>
					<label><?=Form_Helper::form_radio('sort_by',1,FALSE);?> <?=$this->lang->line('folder_collection_sort_by_popularity');?></label>
				</div>
			</div>
			
			<? if($this->is_mod_enabled('folders_privacy') || ($this->session->userdata('id') && $this->user->role == '2')) { ?>
				<div class="popup_row">
					<div class="left inlinediv"><?=$this->lang->line('folder_collection_privacy_lbl');?>:</div>
					<div class="inlinediv collection_privacy">
						<label><?=Form_Helper::form_radio('private',1,FALSE,'class="privacy"'); ?> <?=$this->lang->line('folder_private_lbl');?></label>
						<label><?=Form_Helper::form_radio('private',0,TRUE, 'class="privacy"'); ?> <?=$this->lang->line('folder_public_lbl');?></label>
					</div>
				</div>
			<? } ?>
			<?php if ($this->is_mod_enabled('folders_contributors')) { ?>
				<div class="popup_row">
					<div class="left inlinediv"><?=$this->lang->line('folder_collection_contribution_status');?>:</div>
					<div class="inlinediv collection_privacy">
						<label><?=Form_Helper::form_checkbox('is_open',1)?> <?=$this->lang->line('folder_collection_contribution_lbl');?></label>
					</div>
				</div>
			<?php }?>
			
			<?if($this->is_mod_enabled('exclusive_content') && ($this->session->userdata('id') && in_array($this->user->role, array('1','2')))) { ?>
				<div class="popup_row">
					<div class="left inlinediv custom-tooltip" title-pos="bottom" title-class="tab_label_1" title="The collection is viewable only when users are logged into Fandrop"><?=$this->lang->line('folder_collection_exclusive_lbl');?>:</div>
					<div class="inlinediv collection_privacy">
						<?=Form_Helper::form_checkbox('exclusive', 1, FALSE, 'style="margin:2px 2px 0"')?>
						<?=$this->lang->line('folder_exclusive_lbl');?>
					</div>
				</div>
				
				<div class="popup_row">
					<div class="left inlinediv custom-tooltip" title-pos="bottom" title-class="tab_label_1" title="Social mode for this collection is on for all users"><?=$this->lang->line('folder_collection_admin_social_lbl');?>:</div>
					<div class="inlinediv collection_privacy">
						<?=Form_Helper::form_checkbox(array('admin_social', 1, false, 'style="margin:2px 2px 0"'))?>
						<?=$this->lang->line('folder_exclusive_lbl');?>
					</div>
				</div>
			<? } ?>
			
			<? if($this->is_mod_enabled('rss_auto_port')) { ?>
				<div class="popup_row form_row rss_source_section">
					<div class="left inlinediv"><?=$this->lang->line('folder_RSS_title');?>:</div>
					<span class="error"></span>
					<div class="inlinediv">
						<?=Form_Helper::rss_sources_input('rss_source_id', array(
							'style'=>"width:320px;"
							//'data-validate' => 'url',
							//'data-error-url' => 'The url you added is invalid'
						))?>
					</div>
				</div>
			<? } else { ?>
				<!-- rss_auto_port module is not enabled -->
			<? } ?>
			<div class="clear"></div>			
		</div> <!-- End of .basic_section -->
		
		<div class="popup_row hashtag_section">
			<label class="section_title"><?=$this->lang->line('folder_hashtag_title');?>:</label>
			<?=Form_Helper::hashtags_dropdown('hashtag_id', array(
				'style'=>"width:320px", 
				'placeholder' => 'Enter Hashtag',
				'data-url'=>'/hashtags',
				'min_Chars'=> 0,
				'no_results_text'=>'No hashtags',
				'id'=>'hashtags'
				))?>
			<span class="error e_hashtag_error"></span>
		</div>
		
		<div class="collaborators_section hidden_sec">
			<div class="collaborators_section_body" style="">
				<div class="popup_row">
					<label><?=$this->lang->line('folder_collaborators_title');?>:</label>
					<div class="collaborators_message"><?=$this->lang->line('folder_collaborators_message')?></div>
					<div class="inlinediv collaborators">
							<?=Form_Helper::input('folder_contributors', '', array(
								'id'=>"collaborator_form",
								'class'=>"tokenInput",
								'data-url'=>'/ac_get_connections',
								'theme'=>"google",
								'linkedText'=>$this->lang->line('folder_collaborations_add_btn'),
								'prevent_duplicates'=>"true",
								'no_results_text'=>"No Results",
								'style'=>"width:315px;",
								'Placeholder'=>'+ '.$this->lang->line('folder_collaborations_add_btn')
							))?>
					</div>
				</div>
			</div>
		</div>

		<div class="popup_row popup_row_btn">
			<?php // take a look on http://dev.fantoon.com:8100/browse/FD-3620 before changing to "Save"; ?>
			<?=Form_Helper::submit('submit','Save', array('class'=>"blue_bg blue_bg_tall", 'id'=>"save_data"))?>
			<?php /* RR - ?!?
			<a href="" class="blue_bg blue_bg_tall delete_hashtag" data-dismiss="modal" style="display:none;"><?=$this->lang->line('folder_collection_hashtag_delete_btn');?></a>
			*/ ?>
			<a href="" class="blue_bg blue_bg_tall delete_no" data-dismiss="modal"><?=$this->lang->line('folder_collection_cancel_btn');?></a>
		</div>
 
	<?=Form_Helper::close()?>
	
</div>

<script type="text/javascript">
	php.lang.error.required = "Please enter name for the list.";
	php.lang.special_chars = "<?=$this->lang->line('folder_characters_err');?>";
	php.lang.duplicate_name = "You already have a list with that name.";
	php.lang.char_limit = "List name must be 40 characters or less.";
	php.has_fb_token = <?=(bool) $this->user->fb_token ? 'true' : 'false'?>;
	php.has_twt_id = <?=(bool) $this->user->twitter_id ? 'true' : 'false'?>;
	php.lang.create_button = 'Create';<? /* ?> Changed to lowercase http://dev.fantoon.com:8100/browse/FD-3754 <? */ ?>
	php.lang.save_button = 'Save';
</script>
<?=Html_helper::requireJS(array("folder/edit_folder_popup","common/formValidation"))?> 
