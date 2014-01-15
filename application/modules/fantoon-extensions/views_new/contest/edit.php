<div id="contest_edit_popup" style="display:none; width: 300px; height: ">
	<div class="modal-body">
		<?=Form_Helper::open('/contest/edit', array('class'=>"edit_post_form",'rel'=>"ajaxForm"), array('id'=>'', 'logo'=>''))?>
			<div class="form_row">
				<label>Contest Name: </label>
				<?=Form_helper::input('url', '', array(
					'maxlength'=>250,
					'data-validate'=>"required|maxlength", 
					'data-error-required'=>"The name can not be blank",		
				))?>
				<div class="error" style="display:none;"></div>
			</div>
			<div class="form_row">
				<label>Logo: </label>
				<span class="hidden_upload blue_bg blueButton">
					<span class="upload_img_filename">Upload Image</span>
					<input type="file" name="temp_logo" value="" size="20"/>
					<span class="loadingElement"></span>
				</span>
				<div class="error" style="display:none;"></div>
			</div>
			
			<div class="form_row" style="padding-top: 20px;">
				<?=Form_Helper::submit('submit','Save', array('class'=>"blue_bg blueButton", 'id'=>"save_data"))?>				
				<a href="" class="btn-grey greyButton" data-dismiss="modal">Cancel</a>
			</div>
		<?=Form_Helper::close()?>
	</div>
	<?=Html_helper::requireJS(array('contest/edit'))?>
</div>