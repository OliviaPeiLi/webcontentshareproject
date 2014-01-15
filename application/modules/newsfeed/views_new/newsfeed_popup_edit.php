<? $this->load->config('newsfeed/config.php'); ?>
<? $this->lang->load('newsfeed/newsfeed_views', LANGUAGE); ?>
<div id="newsfeed_popup_edit" class="newsfeed_popup_edit newsfeed_edit_popup" style="display:none">
	<div class="modal-body">
		<?=Form_Helper::open('/newsfeed/edit', 
					array('class'=>"edit_post_form",'rel'=>"ajaxForm"),
					array('id'=>'')
		)?>
			<?php if (isset($contest)) { ?>
				<div class="form_row">
					<label>Title</label>
					<?=Form_helper::textarea('title', '', array(
						'rows'=>3, 'cols'=>67, 'maxlength'=>250, 'class'=>'media_text fd_mentions',
						'data-validate'=>"required|maxlength", 
						'data-error-required'=>"The title cannot be blank",		
					))?>
					<span class="maxLength">250</span>
					<div class="error" style="display:none;"></div>
				</div>
				<?php if ($folder->filters) { ?>
				<div class="form_row">
					<label>Type</label>
					<?=Form_helper::dropdown('sub_type', array_merge(array('none'), $folder->filters), '', array(
						'rows'=>3, 'cols'=>67, 'maxlength'=>250, 'class'=>'',
						'data-validate'=>"required|maxlength", 
						'data-error-required'=>"The title cannot be blank",		
					))?>
					<span class="maxLength">250</span>
					<div class="error" style="display:none;"></div>
				</div>					 
				<? } ?>
				<?php if ($contest->url == 'crowdfunderio') { ?>
					<div class="form_row">
						<label>Top Prize</label>
						<?=Form_Helper::input('top_prize')?>
						<span class="error"></span>
					</div>
					<div class="form_row">
						<label>Share Goal</label>
						<?=Form_Helper::input('share_goal')?>
						<span class="error"></span>
					</div>
				<? } ?>
			<?php } ?>
			<div class="form_row">
				<label><?=isset($contest) ? 'Description' : 'Title' ?></label>
				<?php $conf = array(
					'rows'=>3, 'cols'=>67, 'maxlength'=>$this->config->item('description_chars_limit'), 'class'=>'media_text fd_mentions',
				)?>
				<?php if (!isset($contest)) {
					$conf['data-validate'] = "required|maxlength";
					$conf['data-error-required'] ="The title cannot be blank";		
				} ?>
				<?=Form_helper::textarea('description', '', $conf)?>
				<span class="textLimit"><?=$this->config->item('description_chars_limit')?></span>
				<div class="error" style="display:none;"></div>
			</div>			
			<div class="form_row hashtags">
				<? $hashtags = $this->hashtag_model->top_hashtags()->get_all() ?>
				<?php foreach ($hashtags as $hashtag) {?>
					<a href="<?=$hashtag->hashtag_name?>" class="hashtag"><?=$hashtag->hashtag_name?></a>
				<?php } ?>
				<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
				<span class="parenText">(Add if not safe for work)</span>
			</div>
			<div class="form_row">
				<label>Source</label>
				<?=Form_Helper::input('link_url','', array('class'=>"media_text source_url"))?>
			</div>
			<div class="form_row">
				<a class="done_button blue_bg blueButton" href=""><?=$this->lang->line('newsfeed_views_save_lexicon');?></a>
				<a href="#delete_dialog" rel="popup" class="delete_button blue_bg greyButton timeline_delete_btn" data-delurl=""><?=$this->lang->line('newsfeed_views_delete_drop_lexicon');?></a>
				<div class="data_status" style="display:none"><?=$this->lang->line('newsfeed_views_saved_lexicon');?></div>
				<input type="submit" style="display:none"/>
			</div>
		<?=Form_Helper::close()?>
	</div>
<? if ($this->is_mod_enabled('design_ugc') && !isset($contest)) { ?>
	<?=Html_helper::requireJS(array('newsfeed/newsfeed_popup_edit_ugc'))?>
<?php } else { ?>
	<?=Html_helper::requireJS(array('newsfeed/newsfeed_popup_edit'))?>
<?php } ?>
</div>
