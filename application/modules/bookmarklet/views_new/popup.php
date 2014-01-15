<? $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<div id="drop_page">
	<?=Form_Helper::open('/bookmarklet/add_image', array('id'=>'scraper_form'), array(
		'link_type' => 'content',
		'link_url' => $url,
		'img_width' => 0,
		'img_height' => 0,
	))?>
		<div class="form_row title">
			<span class="inline_edit" rel=title></span>
			<input type="text" name="title" value="" placeholder="Drop Title" style="display:none">
		</div>
		<div class="form_row double">
			<div class="form_left">
				<?=Form_Helper::collection_dropdown('folder_id', array('style'=>'width: 388px;','data-create_only'=>true))?>
				<? /* ?><span class="error" style="display:none; z-index:1"></span><? */ ?>
			</div>
			<div class="right">
				<? /* ?><label class="form_right_title">2. Write a description</label><? */ ?>
				<textarea name="description" class="fd_mentions" maxlength="255" placeholder="<?=$this->lang->line('bookmarklet_desc_ph');?>" autofocus></textarea>
				<span class="maxLength">255</span>
			</div>
			<span class="error description" style="display:none; z-index:1"></span>
		</div>
		<?=Form_Helper::validation_errors(); ?>
		<div class="form_row form_bottom">
			<label class="form_bottom_title">Select or type in a hashtag</label>
			<?php foreach ($hashtags as $hastag) {?>
				<a href="<?=$hastag?>" class="hashtag"><?=$hastag?></a>
			<?php } ?>
			<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
			<span class="parenText">(Add #NSFW if not safe for work)</span>
			<? /* ?><span class="typeyourOwn">(Or type your own)</span><? */ ?>
		</div>
		<div class="form_row form_submit_button">
			<input type="submit" class="blue_btn" name="Collect" value="DROP IT!"/> 
		</div>
	<?=Form_Helper::close()?>
</div>
<div id="bookmark_status_popup" style="display:none"><h1>Collecting link...</h1></div>
<?=Html_helper::requireJS(array('bookmarklet/popup'))?>