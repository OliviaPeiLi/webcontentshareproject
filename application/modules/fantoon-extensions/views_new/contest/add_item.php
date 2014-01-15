<div id="contestAdd_item" class="selfSubmission_container">
<?=Form_Helper::open('')?>
	<span class="selfSubmission_loading"></span>
	<h2>Submit Your Video Now</h2>
	<div class="form_row">
		<label>Name:</label>
		<?=Form_Helper::input('title',$this->input->post('title',true), array(
			'class'=>'half',
			'data-validate' => 'required',
			'data-error-required' => 'Name is required'
		))?>
		<div class="error" style="display:<?=Form_Helper::form_error('title')?'block':'none'?>"><?=Form_Helper::form_error('title')?></div>
	</div>
	
	<div class="form_row">
		<label>Email:</label>
		<?=Form_Helper::input('sxsw_email',$this->input->post('sxsw_email',true), array(
			'class'=>'half',
			'data-validate' => 'required|email',
			'data-error-required' => 'Email is required',
			'data-error-email' => 'Email doesnt appear to be valid'
		))?>
		<div class="error" style="display:<?=Form_Helper::form_error('sxsw_email')?'block':'none'?>"><? print_r(Form_Helper::form_error('sxsw_email')); ?></div>
	</div>
	
	<div class="form_row">
		<label>Company URL:</label>
		<?=Form_Helper::input('link_url',$this->input->post('link_url',true), array(
			'class'=>'half',
			//'data-validate' => 'required',
			//'data-error-required' => 'URL is required'
		))?>
		<div class="error" style="display:<?=Form_Helper::form_error('link_url')?'block':'none'?>"><? print_r(Form_Helper::form_error('link_url')); ?></div>
	</div>
	
	<div class="form_row">
		<label>Description:</label>
		<?=Form_Helper::textarea('description',$this->input->post('description',true), array(
			'class'=>'medium half',
			//'data-validate' => 'required',
			//'data-error-required' => 'Description is required'
		))?>
		<div class="error" style="display:<?=Form_Helper::form_error('description')?'block':'none'?>"><? print_r(Form_Helper::form_error('description')); ?></div>
	</div>
	<div class="form_row">
		<label>Youtube or Vimeo Video:</label>
		<?=Form_Helper::input('youtube_url', $this->input->post('youtube_url',true), array(
			'class' => 'half',
			'data-validate' => 'required|embed',
			'data-error-required' => 'Please enter video url',
			'data-error-embed' => 'The link doesnt appear to be valid video',
		))?>
		<div class="error" style="display:<?=Form_Helper::form_error('youtube_url')?'block':'none'?>"><? print_r(Form_Helper::form_error('youtube_url')); ?></div>
		<div class="loading">Validating...</div>
	</div>
	<div class="form_row js-validate-hidden">
		<label>Logo:</label>
		<div class="hidden_upload colourless_button">
			<span class="upload_img_filename">UPLOAD IMAGE</span>
			<input type="file" name="temp_img" value="" size="20"/>
		</div>
		<input type="hidden" name="img" value="<?=$this->input->post('img',true)?>" data-validate="required" data-error-required="Please select a logo"/>
		<div class="error" style="display:<?=Form_Helper::form_error('img')?'block':'none'?>"><? print_r(Form_Helper::form_error('img')); ?></div>
		<div class="preview">
			<?=Html_helper::img('1x1.png', array('alt'=>'preview'))?>
		</div>
	</div>
	<div class="form_row">
		<a href="/<?=$contest->url?>" class="btn-grey btn-grey-tall">Cancel</a>
		<?=Form_Helper::submit('Preview','Preview', array('class'=>'blue_bg blue_bg_tall'))?>
	</div>

<?=Form_Helper::close()?>
</div>
<?=Html_helper::requireJS(array('contest/add_item'))?>