<div class="selfSubmission_container winsxswSubmission">
<?=Form_Helper::open('')?>
	<span class="selfSubmission_loading"></span>
	<h2>Submit Your Video Now</h2>
	<div class="selfSubmission_formRow">
		<label>Name:</label>
		<input type="text" name="title" class="half" value="<?=$this->input->post('title',true)?>" />
		<div class="error"><? print_r(Form_Helper::form_error('title')); ?></div>
	</div>
	
	<div class="selfSubmission_formRow">
		<label>Email:</label>
		<input type="text" name="sxsw_email" class="half" value="<?=$this->input->post('sxsw_email',true)?>" />
		<div class="error"><? print_r(Form_Helper::form_error('sxsw_email')); ?></div>
	</div>
	
	<div class="selfSubmission_formRow">
		<label>URL:</label>
		<input type="text" name="link_url" class="half" value="<?=$this->input->post('link_url',true)?>" />
		<div class="error"><? print_r(Form_Helper::form_error('link_url')); ?></div>
	</div>
	
	<div class="selfSubmission_formRow">
		<label>Description:</label>
		<textarea name="description" class="medium half" rows="4" maxlength="250"><?=$this->input->post('description',true)?></textarea>
		<div class="error"><? print_r(Form_Helper::form_error('description')); ?></div>
	</div>
	<div class="selfSubmission_formRow">
		<label>Youtube or Vimeo Video:</label>
		<input type="text" name="youtube_url" class="half" value="<?=$this->input->post('youtube_url',true)?>" />
		<div class="error"><? print_r(Form_Helper::form_error('youtube_url')); ?></div>
	</div>
	<div class="selfSubmission_formRow">
		<label>Logo:</label>
		<div class="hidden_upload colourless_button">
			<span class="upload_img_filename">UPLOAD IMAGE</span>
			<input type="file" name="temp_img" value="" size="20"/>
		</div>
		<input type="hidden" name="img" value=""/>
		<div class="error"><? print_r(Form_Helper::form_error('img')); ?></div>
	</div>
	<div class="selfSubmission_formRow">
		<?=Form_Helper::submit('Preview','Preview', array('class'=>'blue_bg blue_bg_tall'))?>
	</div>

<?=Form_Helper::close()?>
</div>
<?=Html_helper::requireJS(array('sxsw/submission_form'))?>