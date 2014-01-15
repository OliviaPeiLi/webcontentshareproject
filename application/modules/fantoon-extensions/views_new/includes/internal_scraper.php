<? $this->load->config('newsfeed/config.php'); ?>

<? $this->lang->load('includes/info_dialog', LANGUAGE); ?>
<div id="internal_scraper">
	<div class="modesShadow">
		<ul class="modes_menu">
			<li><span class="scraperTitle">Submit Drops:</span></li>
			<li>
				<a href="#image"><span class="ico image"></span><strong>Image</strong><span class="arrow"></span></a>
				<span class="vert-split"></span>
			</li>
			<li>
				<a href="#content" placeholder="<?=$this->lang->line('description_link');?>"><span class="ico <?=$this->is_mod_enabled('live_drops') ? 'RSS' : 'url'?>"></span><strong>URL</strong><span class="arrow"></span></a>
				<span class="vert-split"></span>
			</li>
			<li>
				<a href="#embed" placeholder="<?=$this->lang->line('description_video');?>"><span class="ico video"></span><strong>Video</strong><span class="arrow"></span></a>
				<span class="vert-split"></span>
			</li>
			<li>
				<a href="#text"><span class="ico text"></span><strong>Text</strong><span class="arrow"></span></a>
			</li>
		</ul>
	</div>
	<?=Form_Helper::open('internal_scraper', array('rel'=>'ajaxForm', 'class' => 'error'), array('link_type'=>''))?>
		<div class="error"></div>
		<textarea name="activity[link][media]" style="display:none"></textarea>
		<div class="internalScraper_step1">
			<div class="left">
				<div class="postbox_img_preview-left">
					<ul class="img_preview_container">
						<li class="sample">
							<img src="" alt="&nbsp;" class="">
						</li>
					</ul>
					<? /* ?>
					<div class="paginationContainer">
						<a href="#left" class="left disabled"><span class="left_contents"></span></a> 
						<span class="scraperSelection_text">
							<span class="selected">0</span>
							of
							<span class="total">0</span>
						</span>
						<a href="#right" class="right disabled"><span class="right_contents"></span></a>
					</div>
					<? */ ?>
				</div>
				<?php /* ?>
				<label class="screenshotThumb_check">
					<input type="checkbox" name="use_screenshot" value="1"/>
					<span>Use screenshot as a Coversheet</span>
				</label>
				<? */ ?>
			</div> <!-- End .left -->
			
			<ol class="right">
				<li class="form_row link">
					<label>Enter a URL</label>
					<?=Form_Helper::textarea('link_url', '', array(
						'tabindex' => "1", 'class' => "",
						'data-validate' => "required",//|url
						'data-error-required' => $this->lang->line('home_intscraper_error_no_url'),
						// 'data-error-url' => $this->lang->line('home_intscraper_error_invalid_url'),
					))?>
					<span class="error"></span>
				</li>
				<li class="form_row image">
					<div class="hidden_upload colourless_button">
						<span class="upload_img_filename">UPLOAD IMAGE</span>
						<input type="file" name="temp_img" value="" size="20"/>
					</div>
					<div class="helper">
						<a href="" class="use_an_url">Use a URL</a>
						<span>JPEG, GIF or PNG</span>
					</div>
				</li>
				<li class="form_row image_url js-validate-hidden">
					<label>Enter an image URL</label>
					<?=Form_Helper::textarea('img', '', array(
						'tabindex' => "1", 'class' => "",
						'data-validate' => "required|url",
						'data-error-required' => $this->lang->line('home_intscraper_error_no_image'),
						'data-error-url' => $this->lang->line('home_intscraper_error_invalid_url'),
					))?>
					<div class="helper">
						<a href="" class="upload_file">Upload a file</a>
						<span>JPEG, GIF or PNG</span>
					</div>
				</li>
				<li class="form_row collection">
					<span class="error"></span>
					<?=Form_Helper::collection_dropdown('folder_id', array(
						'no_results_text'=>'Create a new list',
						'style'=>'width: 250px',
						'data-validate' => 'required',
						'data-error-required' => 'Please select a list',
						'data-create_only'=>true
					))?>
				</li>
				<li class="form_row title">
					<label>Enter a title</label>
					<div class="error"></div>
					<?=Form_Helper::textarea('description', '', array(
						'tabindex' => "3", 'rows' => 2, 'class' => "fd_mentions",
						'placeholder' => $this->lang->line('title_box'),
						// RR - hashtag validation is disabled by Alexi request
						'data-validate' => "required|maxlength",
						'data-maxlength' => $this->config->item('description_chars_limit'),
						'data-error-required' => $this->lang->line('home_intscraper_error_no_title'),
						'data-error-hashtag' => $this->lang->line('home_intscraper_error_hashtags'),
						'data-error-hashtaguniq' => $this->lang->line('home_intscraper_error_hashtags_unique')
					))?>
					<span class="textLimit"><?=$this->config->item('description_chars_limit')?></span>
				</li>
				<li class="form_row text">
					<label>Write something</label>
					<span class="error"></span>
					<?=Form_Helper::textarea('activity[link][content]', '', array(
						'tabindex' => "3", "rows" => "5", 'class' => "content",
						'placeholder' => $this->lang->line('description_text'),
						'data-validate' => "required|maxlength",
						//'maxlength' => 250,
						'data-error-required' => $this->lang->line('home_intscraper_error_no_text'),
					))?>
					<? /* <span class="textLimit">250</span> */ ?>
				</li>
				<li class="form_row hashtagRow">
					<div>
						<span class="hashtags">
							<label class="microLabel">Select or type in a hashtag</label>
							<? $hashtags = $this->hashtag_model->top_hashtags()->get_all() ?>
							<?php foreach ($hashtags as $hashtag) {?>
								<a href="<?=$hashtag->hashtag_name?>" class="hashtag"><?=$hashtag->hashtag_name?></a>
							<?php } ?>
							<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
							<span class="parenText">(Add if not safe for work)</span>
							<? /* ?><span class="typeyourOwn">(Or type your own)</span><? */ ?>
						</span>
					</div>
				</li>
			</ol> <!-- End .right -->
				<? /* ?><div class="form_row">
					<span class="hashtags">
						<label class="microLabel">Select or type in a hashtag</label>
						<?php foreach ($hashtags as $hashtag) {?>
							<a href="<?=$hashtag?>" class="hashtag"><?=$hashtag?></a>
						<?php } ?>
						<span class="typeyourOwn">(Or type your own)</span>
					</span>				
				</div><? */ ?>
			
			<div class="form_row submitRow">
				<input type="submit" id="photoAndLink_submit" class="blue_bg blue_bg_tall" value="<?=$this->lang->line('bookmarklet_post_btn');?>">
				<input type="button" id="photoAndLink_cancel" class="btn-grey" value="Cancel">
			</div>
		</div>		
	<?=Form_Helper::close()?>
</div>
<?=Html_helper::requireJS(array('includes/internal_scraper'))?>
