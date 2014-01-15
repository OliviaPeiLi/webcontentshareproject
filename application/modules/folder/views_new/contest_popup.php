<? $this->load->config('folder/config.php'); ?>
<? $this->lang->load('folder/folder', LANGUAGE);?>
<div id="edit_folder_popup" style="display:none;">
	<?=Form_Helper::open('/folder_edit_basic', array('rel'=>'ajaxForm', 'id'=>'basic_form'), 
		array('folder_id' => 0, 'type' => 2, 'contest_id' => $contest->id, 'logo'=>'')
	) ?>
		<div class="popup_row form_row">
			<span class="error"></span>	
			<div class="left inlinediv"><?=$contest->is_simple ? 'Contest name:' : 'Category name:'?></div>
			<div class="inlinediv">
				<span id="collection_name" style="display:none"></span>
				<?=Form_Helper::input('folder_name','',array(
							'class'=>"contest_nameEntry",
							'data-validate'=>"required|maxlength", 
							'data-error-required' => 'The contest name cannot be blank',
							'data-error-maxlength' => "List name must be " . $this->config->item('folder_name_chars_limit') . " characters or less.",
							'maxlength'=>$this->config->item('folder_name_chars_limit'),
				))?>
				<span class="textLimit"><?=$this->config->item('folder_name_chars_limit')?></span>
			</div>
		</div>
		<div class="popup_row form_row">
			<span class="error"></span>	
			<div class="left inlinediv">Ends at:</div>
			<div class="inlinediv">
				<span id="collection_name" style="display:none"></span>
				<?=Form_Helper::input('ends_at[date]','',array('class'=>"contest_endDate", 'min'=>date('Y-m-d'), 'max'=>date('Y-m-d', time()+60*60*24*356*4)))?>
				<input type="hidden" name="ends_at[date]" class="contest_replaced_ends_date" value=""/>
				<?=Form_Helper::input('ends_at[time]','',array('class'=>"contest_endTime"))?>
			</div>
		</div>
		<div class="popup_row form_row">
			<span class="error"></span>	
			<div class="left inlinediv">Info:</div>
			<div class="inlinediv">
				<span id="collection_name" style="display:none"></span>
				<?=Form_Helper::textarea('info','',array('class'=>"contest_infoField"))?>
			</div>
		</div>
		<div class="popup_row form_row">
			<span class="error"></span>	
			<div class="left inlinediv">Logo:</div>
			<div class="inlinediv">
				<span class="hidden_upload blue_bg">
					<span class="upload_img_filename">Upload Image</span>
					<input type="file" name="temp_logo" value="" size="20"/>
				</span>
			</div>
		</div>
		<div class="popup_row form_row">
			<span class="error"></span>	
			<div class="left inlinediv">Allow users to add content:</div>
			<div class="inlinediv contest_checkbox">
				<span id="collection_name" style="display:none"></span>
				<?=Form_Helper::hidden('is_open',0)?>
				<?=Form_Helper::checkbox('is_open', 1, false, array())?>
			</div>
		</div>

		<div class="popup_row popup_row_btn">
			<?=Form_Helper::submit('submit','Save', array('class'=>"blue_bg blue_bg_tall", 'id'=>"save_data"))?>
			<a href="" class="blue_bg blue_bg_tall delete_no" data-dismiss="modal"><?=$this->lang->line('folder_collection_cancel_btn')?></a>
		</div>
 
	<?=Form_Helper::close()?>
	
</div>

<script type="text/javascript">
	php.lang.special_chars = "<?=$this->lang->line('folder_characters_err');?>";
	php.lang.duplicate_name = "You already have a list with that name.";
	
	php.lang.create_button = 'Create';
	php.lang.save_button = 'Save';
</script>
<?=Html_helper::requireJS(array("folder/contest_popup"))?> 
