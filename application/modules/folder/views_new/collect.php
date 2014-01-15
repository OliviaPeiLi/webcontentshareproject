<? $this->load->config('newsfeed/config.php'); ?>

<? $this->lang->load('folder/folder', LANGUAGE); ?>
<div id="collect_popup" style="display:none;" class="modal">
	<div class="modal-body">
		<?=Form_Helper::open('/collect_into_folder', array('id'=>'collect_into_folder_form', 'rel'=>'ajaxForm'), array(
			'newsfeed_id'=>''
		)); ?>
			<div class="form_row">
				<label><?=$this->lang->line('folder_collection_heading');?>:</label>
				<div>
					<?=Form_Helper::collection_dropdown('folder_id', array(
						'style'=>'width: 353px',
						'data-validate'=>'required',
						'data-error'=>'popup',
						'data-error-required'=>'Choose a story')); ?>
					<span class="error"></span>
				</div>
			</div>
			<div class="form_row" style="padding-top:5px">
				<label>Write a description:</label>
				<textarea name="description" 
						<?php // RR - hashtag validation is disabled by Alexi request?>
						data-validate="required|maxlength"
						data-error="popup" 
						data-error-hashtag="You need to use at least one hashtag"
						data-error-hashtaguniq="Selected hashtag should be unique"
						data-error-required="The title cannot be blank"
						id="redrop_description" maxlength="<?=$this->config->item('description_chars_limit')?>"
						cols="59"
						rows="2"
						class="inlinediv" style="width: 100%; box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; -webkit-box-sizing: border-box;"><?=$this->lang->line('folder_collection_default');?></textarea>
							<span class="textLimit"><?=$this->config->item('description_chars_limit')?></span>
				<div class="error" style="display:none;"></div>
			</div>
			<? /* ?><div class="form_row">
				<label>3. Add a hashtag</label>
				<span class="hashtagHolder">
					<? $hashtags = $this->hashtag_model->top_hashtags()->get_all()?>
					<?php foreach ($hashtags as $hashtag) {?>
						<a href="<?=$hashtag->hashtag_name?>" class="hashtag"><?=$hashtag->hashtag_name?></a>
					<?php } ?>
					<span class="typeyourOwn">(Or type your own)</span>
				</span>
			</div><? */ ?>
			<div class="form_row" style="padding-top:5px;">
				<?=Form_Helper::submit('submit',$this->lang->line('folder_redrop_submit_lbl'),array('class'=>'blue-btn blueButton redropSubmit'))?>
			</div>
		<?=Form_Helper::close()?>
	</div>
</div>
<div id="collect_success_popup" class="modal fade" style="display:none">
	<div class="modal-body">
		<div id="collect_success">You have successfully shared this post in <a href="{folder_url}">{folder_name}</a></div>
	</div>
</div>
<? if ($this->is_mod_enabled('design_ugc')) : ?>
	<?=Html_helper::requireJS(array("folder/collect_ugc"))?>
<?php else : ?>
	<?=Html_helper::requireJS(array("folder/collect"))?> 
<?php endif; ?>
