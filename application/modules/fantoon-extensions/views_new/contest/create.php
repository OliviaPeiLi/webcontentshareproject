<div class="selfSubmission_container createContest">
<?=Form_Helper::open('')?>
	<span class="selfSubmission_loading"></span>
	<div class="createContest_title">
		<h1>Create a contest</h1>
	</div>
	<span class="error"><?=Form_Helper::validation_errors()?></span>
	<div class="form_row">
		<span class="contestNumber">1</span>
		<span class="form_row_container">
			<label>Choose a URL:</label>
			<span class="URLPrefix">www.fandrop.com/</span>
			<?=Form_Helper::input('url', '', array(
				'class' => 'half contestURL',
				'autocomplete' => 'off',
				'data-validate' => 'required|contest',
				'data-error-required' => 'You need to add contest url',
				'data-error-contest' => 'This url is already taken',
			))?>
			<span class="loading"></span>
			<span class="error"></span>
			<?php /*<span class="valid"></span>*/ ?>
		</span>
	</div>
	
	<div class="form_row js-validate-hidden">
		<div class="left">
			<span class="contestNumber">2</span>
			<span class="form_row_container">
				<label>Select Custom Header Image:</label>
				<?=Form_Helper::hidden('logo', '', array(
					'data-validate' => 'required',
					'data-error-required' => 'Please upload a logo'
				))?>
				<span class="hidden_upload blue_bg">
					<span class="upload_img_filename">Upload Image</span>
					<input type="file" name="temp_logo" value="" size="20"/>
				</span>
				<span style="margin-left: 15px;" class="formCaption">Must be at least 300x80</span>
				<div class="error error_under"></div>
			</span>
		</div>
		<div class="right">
			<span class="form_row_container">
				<span class="contestUpload_holder">
					<?=Html_helper::img('1x1.png', array('alt'=>'preview'))?>
				</span>
				<span class="formCaption">
					Preview
				</span>
			</span>
		</div>
	</div>
	
	<div class="form_row">
		<div class="left">
			<span class="contestNumber">3</span>
			<span class="form_row_container">
				<label>Add Create Categories:</label>
				<input type="text" class="add_category"/>
				<input type="button" value="Add"/ class="blue_bg">
				<div class="error"></div>
			</span>
		</div>
		<div class="right">
			<span class="form_row_container">
				<input type="text" name="categories[]" class="half tokenInput" allow_insert="true" value=""/>
			</span>
		</div>
	</div>
	
	<div class="form_row">
		<span class="contestNumber">4</span>
		<span class="form_row_container">
			<label for="is_open_checkbox">Allow Users to Add Content:</label>
			<input type="checkbox" id="is_open_checkbox" name="is_open" value="1"/>
			<span class="choiceLabel">yes</span>
			<div class="error"></div>
		</span>
	</div>
	
	<div class="form_row">
		<?=Form_Helper::submit('Save', "I'm Done!", array('class'=>'blue_bg blue_bg_huge'))?>
	</div>

<?=Form_Helper::close()?>
</div>
<?=Html_helper::requireJS(array('contest/create'))?>