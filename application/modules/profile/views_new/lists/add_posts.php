<? $this->load->config('newsfeed/config.php'); ?>
<div class="listHead addList_head"><h4>Post to <strong>"<?=$folder->folder_name?>"</strong></h4></div>
<div class="addList_postsStrip">
	<ul><?php for ($i=0;$i<5;$i++) { ?><li class="addList_postsItem <?=($i+1)%5 ? '' : 'last'?> <?=$i ? '' : 'active'?>" data-index="<?=$i?>">
				<span class="addList_itemNumber"><?=$i+1?></span>
				<img src="<?=$i==0&&$newsfeed ? $newsfeed->img_bigsquare : ''?>"/>
				<span class="ico"></span>
				<a href="" class="addList_itemDelete" style="<?=$i ? 'display:none' : ''?>"><span class="ico"></span></a>
				<a href="" class="addList_itemEdit disabled" style="<?=$i ? 'display:none' : ''?>"><span class="ico"></span></a>
	</li><?php } ?></ul>
</div>
<div class="addList_media">
	<div class="addList_mediaTitle"><h2>Choose What to Upload!</h2></div>
	<div class="addList_mediaButtons">
		<a href="#image" class="greyButton mediaButton imageButton active">
			<span class="ico"></span><span class="mediaText">Image</span>
		</a>
		<a href="#content" class="greyButton mediaButton linkButton">
			<span class="ico"></span><span class="mediaText">URL</span>
		</a>
		<a href="#embed" class="greyButton mediaButton videoButton">
			<span class="ico"></span><span class="mediaText">Video</span>
		</a>
		<a href="#text" class="greyButton mediaButton textButton">
			<span class="ico"></span><span class="mediaText">Text</span>
		</a>
	</div>
</div>
<div class="addList_body">
	<?=Form_Helper::open('/internal_scraper', array('rel'=>'ajaxForm', 'class' => 'temp image error','data-index'=>0), 
		array('link_type'=>'image','folder_id'=>$folder->folder_id, 'newsfeed_id' => '')
	)?>
		<textarea name="activity[link][media]" style="display:none"></textarea>
		<div class="error" style="display:none"></div>
		<div class="left">
			<div class="addList_previewContainer">
				<img src="/images/1x1.png" alt="&nbsp;" class="">
				<span class="videoIcon_container"><span class="ico"></span></span>
			</div>
			<div class="form_row image js-validate-hidden">
				<?=Form_Helper::textarea('img', '', array(
					'tabindex' => "1", 'style' => "display:none",
					'data-validate' => "required",
					'data-error-required' => 'Please select an image',
				))?>
				<div class="hidden_upload">
					<div class="blueButton uploadImage"><span class="ico"></span>Upload Image</div>
					<input type="file" name="temp_img" value="" size="20"/>
				</div>
			</div>
		</div>
		
		<div class="right">
			<div class="form_row link">
				<label class="page">Page URL:</label>
				<label class="video">Video URL:</label>
				<?=Form_Helper::textarea('link_url', '', array(
					'tabindex' => "1", 'class' => "",
					'data-validate' => "required",
					'data-error-required' => 'A URL is required',
					'data-error-url' => "The URL doesnt appear to be valid"
				))?>
				<span class="error"></span>
			</div>
			<div class="form_row title">
				<label class="description">Description:</label>
				<label class="title">Title:</label>
				<div class="error"></div>
				<?=Form_Helper::textarea('description', '', array(
					'tabindex' => "3", 'rows' => 2, 'class' => "fd_mentions",
					'placeholder' => $this->lang->line('title_box'),
					'data-validate' => "required|maxlength",
					'data-maxlength' => $this->config->item('description_chars_limit'),
					'data-error-required' => 'A Description is required',
				))?>
				<span class="textLimit"><?=$this->config->item('description_chars_limit')?></span>
			</div>
			<div class="form_row text">
				<label>Story:</label>
				<span class="error"></span>
				<?=Form_Helper::textarea('activity[link][content]', '', array(
					'tabindex' => "3", "rows" => "5", 'class' => "content",
					'placeholder' => $this->lang->line('description_text'),
					'data-validate' => "required|maxlength",
					'data-error-required' => "Story is required",
				))?>
			</div>
		</div> <!-- End .right -->
	<?=Form_Helper::close()?>
</div>
<div class="addList_actions">
	<?=Form_Helper::open('', array('rel'=>'ajaxForm'))?>
		<span class="loading">
			<span class="ico"></span>
			Loading...
		</span>
		<script type="template/html">
			<div class="sample" data-index="0" style="display:none">
				<input type="hidden" name="item[0][newsfeed_id]"/>
				<input type="hidden" name="item[0][link_type]" value="image"/>
				<input type="hidden" name="item[0][link_url]"/>
				<input type="hidden" name="item[0][img]"/>
				<input type="hidden" name="item[0][folder_id]" value="<?=$folder->folder_id?>"/>
				<textarea name="item[0][description]"></textarea>
				<textarea name="item[0][description_orig]"></textarea>
				<textarea name="item[0][activity][link][content]"></textarea>
				<textarea name="item[0][activity][link][media]"></textarea>
			</div>
		</script>
		<div class="sample" data-index="0" style="display:none">
			<input type="hidden" name="item[0][newsfeed_id]" value="<?=$newsfeed ? $newsfeed->newsfeed_id : ''?>"/>
			<input type="hidden" name="item[0][link_type]" value="<?=$newsfeed ? $newsfeed->link_type : ''?>"/>
			<input type="hidden" name="item[0][link_url]" value="<?=$newsfeed ? $newsfeed->link_url : ''?>"/>
			<input type="hidden" name="item[0][img]" value="<?=$newsfeed ? $newsfeed->img_full : ''?>"/>
			<input type="hidden" name="item[0][folder_id]" value="<?=$newsfeed ? $newsfeed->folder_id : $folder->folder_id?>"/>
			<textarea name="item[0][description]"><?=$newsfeed ? $newsfeed->description_plain : ''?></textarea>
			<textarea name="item[0][description_orig]"></textarea>
			<textarea name="item[0][activity][link][content]"><?=$newsfeed&&$newsfeed->link_type=='text' ? preg_replace( '#<br\s*/?>#', "\n", $newsfeed->activity->content ) : ''?></textarea>
			<textarea name="item[0][activity][link][media]"></textarea>
		</div>
		<input class="addList_save greyButton" type="submit" name="Save" value="Save & Manage List"/>
	<?=Form_Helper::close()?>
	<a href="" class="addList_add blueButton">Add Another Post</a>
</div>
<?=Html_helper::requireJS(array('profile/lists_add_posts'))?>
