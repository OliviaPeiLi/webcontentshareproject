<? $this->lang->load('profile/profile', LANGUAGE); ?>
<? $this->load->config('newsfeed/config.php'); ?>
<div class="listHead editList_head">
		<div class="list-titlez">
	<span class="ico"></span><h4>
		<?=$folder->folder_name?>
	</h4>
	</div><!--/.list-titlez-->

<div class="list-controlz">
	<a href="/manage_lists/<?=$folder->folder_id?>/edit" class="editList_editDetails"><span class="editList_editText greyButton">Edit Story Details</span></a>
	<span class="editList_bottomButtons">
		 <a href="<?=$folder->folder_url?>" class="greyButton editList_add" target="_blank">Preview Story</a>
		<?php if ($folder->private) { ?>
			<a href="/manage_lists/<?=$folder->folder_id?>/publish" class="blueButton editList_finish publish">Publish Story</a>
		<?php } else { ?>
			<a href="/manage_lists/<?=$folder->folder_id?>/unpublish" href="" class="blueButton editList_finish unpublish" >Unpublish</a>
		<?php } ?> 
	</span>

	<!--<span class="editList_status">Status: <?=$folder->private ? 'Draft' : 'Published'?></span>-->
	<div class="clear"></div>

</div>
</div><!--/.list-controlz-->

<div class="editList_link">
	<?php /* RR - as discussed with alexi we are leaving just the permalink here
	<a class="greyButton editList_view" href="">View List</a>
	<span class="editList_linkText">Permalink: </span>
	*/ ?>
	Your story URL: <input value="<?=Url_helper::base_url($folder->folder_url)?>" onclick="$(this).select();"/>
</div>
<ul class="editList_upper fd-scroll fd-autoscroll" data-template="#manage_post_item" data-url="<?=Url_helper::current_url()?>">
	<? $this->load->view('profile/lists/update_item', array('newsfeed'=>$this->newsfeed_model->sample()))?>
	<?php foreach ($newsfeeds as $k=>$newsfeed) { ?>
		<? $this->load->view('profile/lists/update_item', array('newsfeed'=>$newsfeed,"cover_newsfeed_id"=>$cover_newsfeed_id))?>
	<?php } ?>
	<?php if (count($newsfeeds) >= $per_page) { ?>
		<li class="feed_bottom">Loading more posts...</li>
	<?php } ?>
</ul>

<div class="editList_actions add">
	<?php if (count($newsfeeds) > 0) { ?>
		<a href="" class="blueButton editList_preview">+ Add another post</a>
	<?php } else { ?>
		<a href="" class="blueButton editList_preview">+ Add new post</a>
	<?php } ?>
</div>

<?=Form_Helper::open('/internal_scraper', array('rel'=>'ajaxForm', 'class' => 'temp image'), 
	array('link_type'=>'image','folder_id'=>$folder->folder_id, 'newsfeed_id' => '', 'after'=>'')
)?>
	<div class="add_new_post">
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
						'data-error' => 'popup',
						'data-validate' => "required",
						'data-error-required' => 'Please select an image',
					))?>
					<div class="hidden_upload">
						<div class="greyButton uploadImage"><span class="ico"></span>Upload Image</div>
						<input type="file" name="temp_img" value="" size="20" title="Support filetype: <?=$this->lang->line('upload_allowed_types')?> "/>
					</div>
				</div>
			</div>
			
			<div class="right">
				<div class="form_row link">
					<label class="page">Page URL:</label>
					<label class="video">Video URL:</label>
					<label class="image">Source:</label>
					<span class="error"></span>
					<?=Form_Helper::textarea('link_url', '', array(
						'tabindex' => "1", 'class' => "",
						'data-validate' => "required",
						'data-error' => 'popup',
						'data-error-required' => 'A URL is required',
						'data-error-url' => "The URL doesn\'t appear to be valid"
					))?>
				</div>
				<div class="form_row title">
					<label class="description">Description:</label>
					<label class="title">Title:</label>
					<div class="error"></div>
					<?=Form_Helper::textarea('description', '', array(
						'tabindex' => "3", 'rows' => 2, 'class' => "fd_mentions",
						'placeholder' => $this->lang->line('title_box'),
						'data-error' => 'popup',
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
						'data-error' => 'popup',
						'data-validate' => "required|maxlength",
						'data-error-required' => "A Story is required",
					))?>
				</div>
			</div> <!-- End .right -->
		</div>
	</div>
	<div class="editList_actions">
		<div class="editList_actions_inner">
			<input type="button" class="addList_cancel greyButton" name="" value="Cancel"/>
			<span class="addList_saveContainer">
				<span class="loadingElement"></span>
				<input class="addList_save greyButton" type="submit" name="Save" value="Save"/>
				<input class="addList_save_add blueButton" type="submit" name="Save" value="Save & Add new"/>
			</span>
		</div><!--/.editList_actions_inner-->
		<div class="clear"></div>
	</div>
<?=Form_Helper::close()?>

<script type="text/javascript">
	php.folder = {'folder_id': <?=$folder->folder_id?>, 'folder_uri_name': '<?=$folder->folder_uri_name?>'};
</script>
<?=Html_helper::requireJS(array('profile/lists_update'))?>
