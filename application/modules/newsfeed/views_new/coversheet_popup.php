<div id="newsfeed_edit" style="display:none">
	<div class="modal-header">
		<h3>asd</h3>
		<button class="new_close" data-dismiss="modal"></button>
	</div>
	<div class="modal-body">
		<?=Form_Helper::open('/drop-edit-thumb', array('id'=>'thumbnails-form', 'rel'=>'ajaxForm'), array( 
			'selected_thumb' => 'newsfeed',
			'url'=>'', 
			'newsfeed_id' => '',
			'img_newsfeed' => '', 'img_collection' => '',
			'img_newsfeed_x'=>'','img_newsfeed_y'=>'','img_newsfeed_w'=>'','img_newsfeed_h'=>'',
			'img_collection_x'=>'','img_collection_y'=>'','img_collection_w'=>'','img_collection_h'=>'',
		))?>
			<div class="left">
				<span class="cropTitles">Preview Coversheet</span>
				<ul class="thumbs-list">
					<li class="newsfeed-thumb selected" data-thumb_group="newsfeed">
						<?=Html_helper::img("loading_icons/bigRoller32x32.gif", array('alt'=>"thumb group 1", 'width'=>"200"))?>
					</li>
				</ul>
				<a href="" class="crop"><span class="crop_contents_wrapper"><span class="crop_contents"></span></span><span class="actionButton_text">Crop</span></a>
			</div> <!-- End .left -->
			<div class="right">
				<div class="errors"></div>
				<div class="editSection_One" style="height: 0">
					<label>
						<strong>Description: </strong>
						<textarea name="activity[caption]" class="fd_mentions caption" rows="1" cols="100"></textarea>			
						<div id="description_err" class="error" style="display:none">Description cannot be empty.</div>
					</label>
					<label>
						<strong>Source: </strong>
						<input type="text" name="activity[link]" value=""/>
					</label>
					<label>
						<a href="#change_image" class="blue_bg inlinediv imageEdit_moreOptions">Change Coversheet</a>
					</label>
				</div>
				<div class="editSection_Two">
					<div class="form_row">
						<strong>Upload Coversheet: </strong>
						<div id="imgupload_newimg_pane">
							<div class="hidden_upload">
								<div id="choose_file_btn" class="blue_bg" style="color:white; display: inline-block;">Choose File</div>
								<input type="file" name="img" value="" size="20">
							</div>
							<div style="display:none" class="upload_err"></div>
						</div>
					</div>
					<?php /* Ray Comment out google images temp
					<div class="form_row">
						<strong>Google recommendations</strong>
						<input class="google-query" value=""/>
						<ul class="google-list">
							<li class="sample">
								<a href=""><img src="" alt=""/></a>
							</li>
						</ul>
					</div>
					*/ ?>
				</div>
			</div> <!-- End .right -->
			<div class="editSection_Four">
				<div class="data_status"><?=$this->lang->line('newsfeed_views_saved_lexicon');?></div>
				<input type="submit" name="save" value="Save" class="blue_bg" style="display:none"/>
				<a href="javascript:;" class="cancel_button blue_bg">Cancel</a>
			</div>
		<?=Form_Helper::close()?>
	</div>
	<?=Html_helper::requireJS(array('newsfeed/coversheet_popup'))?>
</div>
