<? $this->load->view('newsfeed/drop_page') ?>
<div class="selfSubmission_save_top">
<?=Form_Helper::open('winsxsw/self_submission/save', array('method'=>'post'), $form_data) ?>
	<a href="javascript:history.back()" class="selfSubmission_back colourless_button">
	    <span class="selfSubmission_backIcon"></span>
	    <span class="selfSubmission_backText">Back</span>
	</a>
	<span><h1>Preview</h1></span>
	<?=Form_Helper::submit('Save', 'Save', array('class'=>'blue_bg blue_bg_tall selfSubmission_saveButton'))?>
<?=Form_Helper::close()?>
</div>