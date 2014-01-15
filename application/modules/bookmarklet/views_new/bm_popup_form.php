<? $this->load->config('newsfeed/config.php'); ?>
<form id="clipboard-popup-controls" data-error="popup">
	<fieldset>
		<div class="form_row">
			<div class="form_left">
				<? /* ?><label class="form_left_title">1. Select a Collection</label><? */ ?>
				<input type="text" name="folder_id" class="select_folder" placeholder="Select a list" allow_insert="true" token_limit="1" data-custom_create="true"/>
			</div>
			<div class="form_right">
				<? /* ?><label class="form_right_title">2. Write a description</label><? */ ?>
				<textarea name="description" class="fd_mentions" maxlength="<?=$this->config->item('description_chars_limit')?>" placeholder="Describe this drop." data-error="popup"></textarea>
				<span class="maxLength"><?=$this->config->item('description_chars_limit');?></span>
			</div>
			<div style="clear: both;"></div>
		</div>
		<span class="error">Required</span>
		<div class="form_row form_bottom">
			<label class="form_bottom_title">Select or type in a hashtag</label>
			<a href="{#sample}" class="hashtag sample">{hash_tag}</a>
			<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
			<span class="parenText">(Add #NSFW if not safe for work)</span>
			<? /* ?><span class="typeyourOwn">(Or type your own)</span><? */ ?>
		</div>
		<div class="form_row submit">
			<input type="submit" value="Post" class="save"/>
		</div>
	</fieldset>
</form>
<div id="post_button_container"></div>
<div id="notification_bar" style="display: none;">Description Field is required.</div>
