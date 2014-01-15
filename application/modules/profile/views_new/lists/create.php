<? $this->load->config('folder/config.php'); ?>
<div class="listHead newList_head">
	<span class="ico"></span><h4><?=$folder ? 'Edit '.$folder->folder_name : 'Create a new story'?></h4>
</div>
<div class="newList_body">
	<?=Form_Helper::open('', 
		array(
			'rel'=>'ajaxForm',
			'data-progress' => 'Saving story details...',
			'success' => 'Changes saved. Redirecting to \'Add posts\'',
		), 
		array('folder_id' => $folder ? $folder->folder_id : ''))?>
		<div class="form_row newList_formRow">
			<label>
				<span class="form_rowTitle">Title:</span>
				<span class="error"><?=Form_Helper::validation_errors()?></span>
				<?=Form_Helper::input('folder_name', $folder ? $folder->folder_name : '', array(
					'placeholder' => 'title',
					'class' => 'form_rowInput',
					'data-error' => 'popup',
					'data-validate'=>"required|uniqcollection|maxlength", 
					'data-error-uniqcollection' => "A story with this name already exists",
					'data-error-required' => 'The story name cannot be blank',
					'maxlength'=>$this->config->item('folder_name_chars_limit')
				))?>
				<span class="textLimit"><?=$this->config->item('folder_name_chars_limit')?></span>
			</label>
		</div>
		<div class="form_row newList_formRow hashtags">
			<span class="error"></span>
			<label>
				<span class="form_rowTitle">Add Hashtags:</span>
				<input class="form_rowInput" id="hashtags_input" name="" value=""/>
			</label>
			<div class="newList_hashText"><em>Separate hashtags with commas</em></div>
			<ul class="newList_hashes">
				<?='<script type="template/html" id="hashtags_template" data-value="strong, input @value">'?>
					<li>
						<a href="#"><span class="ico"></span>x</a><strong>#Sample hashatag</strong>
						<input type="hidden" name="folder_hashtags[]" value=""/>
					</li>
				<?='</script>'?>
				<?php if ($folder) foreach ($folder->folder_hashtags as $i=>$folder_hashtag) { if (!$folder_hashtag->hashtag) continue;?>
					<li>
						<a href="#"><span class="ico"></span>x</a><strong><?=$folder_hashtag->hashtag->hashtag_name?></strong>
						<input type="hidden" name="folder_hashtags[]" value="<?=$folder_hashtag->hashtag->hashtag_name?>"/>
					</li>
				<?php } ?>
			</ul>
		</div>
		<div class="form_row newList_formRow">
			<span class="newList_bottomText"><em>You can create a story with no posts or as many posts as you like</em></span>
			<span class="newList_bottomButtons">
				<input class="greyButton newList_finish" type="submit" name="finish" value="Finish"/>
				<span class="newList_addContainer">
					<span class="loadingElement"></span>
					<input class="blueButton newList_add" type="submit" name="add_posts" value="Add Posts"/>
				</span>
				<?php if ($folder) { ?>
					<a href="/manage_lists/<?=$folder->folder_id?>/delete" class="greyButton newList_delete" data-confirm="Delete <?=$folder->folder_name?>?"><span class="ico"></span>Delete</a>
				<?php } ?>
			</span>
			<div class="clear"></div>
		</div>
	<?=Form_Helper::close()?>
</div>
<?=Html_helper::requireJS(array('profile/lists_create'))?>
