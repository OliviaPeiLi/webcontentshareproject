<?php //RR NOTE for the Michael - please try to use more classes instead of ids so the styles are reusable?>
<div id="drop_into_folder_popup" style="display:none;">
	<div class="step1">
		<?=Form_Helper::open('/internal_scraper', array('rel'=>'ajaxForm'))?>
			<span class="error" style="display:none"><?=$this->lang->line("bookmarklet_invalid_url_err");?></span>
			<textarea name="url" class="postbox empty required" placeholder="<?=$this->lang->line('bookmarklet_paste_link_title');?>" cols="97" rows="1"></textarea>
			<div class="inlinediv">
				<input type="submit" value="+"/>
			</div>
		<?=Form_Helper::close()?>
	</div>
	<div class="step2">
		<?=Form_Helper::open('internal_scraper', array('rel'=>'ajaxForm'), array('activity[link][link]'=>'','activity[link][img]'=>'','folder_id'=>''))?>
			<div id="internalScraper_body1">
		    	<div class="postbox_img_preview">
		    		<img src="" data-loader="/images/loading_icons/bigRoller_32x32.gif" alt="preview image">
					<div id="paginationContainer1">
						<a href="" class="left disabled"><span class="left_contents"></span></a> 
						<span id="scraperSelection_text1">
							<span class="selected">0</span>
							of
							<span class="total">0</span>
						</span>
						<a href="" class="right"><span class="right_contents"></span></a>
					</div>
		    	</div>
		    	<div id="postbox_img_preview_right1">
		    		<span class="error" style="display:none"></span>
					<label id="screenshotThumb_check1">
		    			<input type="checkbox" name="use_screenshot" value="1"/>
		    			<span>Use screenshot as a Coversheet</span>
		    		</label>
		    		<div class="clear"></div>
		    		<span class="title">Loading...</span>
		    		<textarea name="activity[link][text]" class="postbox_img_description" tabindex="-1" title="<?=$this->lang->line('bookmarklet_enter_comment_title');?>" contenteditable="true" autocomplete="off" placeholder="<?=$this->lang->line('bookmarklet_enter_comment_title');?>" cols="51" rows="3"></textarea>
					<div class="clear"></div>
				</div>
				<input type="submit" id="postbox_img_submit" class="blue_bg blue_bg_tall" value="<?=$this->lang->line('bookmarklet_post_btn');?>">
				<div class="clear"></div>
			</div>
		<?=Form_Helper::close()?>
	</div>
</div>
<?=Html_helper::requireJS(array('folder/drop_into_folder_popup'))?> 