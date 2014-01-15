<? $this->load->config('newsfeed/config.php'); ?>

<div id="full_scraper">
	<div class="scraper_header" id="colection_dropdown_wrap">
		<div class="scraper_headContainer">
			<span class="scraper_mainIcon"></span><h1>Add Multiple Drops To:</h1>
		</div>
		<?=Form_Helper::collection_dropdown('folder_id', array('no_results_text'=>'Create a new list','style'=>'width: 375px','data-create_only'=>true))?>
	</div>
	<div class="type_to_add">
		<h2>Select Type To Add:</h2>
		<ul class="modes_menu">
			<li class="mode_button">
				<a href="#fullscraper_add_image"><span class="ico image"></span><strong>Image</strong></a>
				<span class="vert-split"></span>
			</li>
			<li class="mode_button">
				<a href="#fullscraper_add_url" placeholder="<?=$this->lang->line('description_link');?>"><span class="ico <?=$this->is_mod_enabled('live_drops') ? 'RSS' : 'url'?>"></span><strong>URL</strong></a>
				<span class="vert-split"></span>
			</li>
			<li class="mode_button">
				<a href="#fullscraper_add_video" placeholder="<?=$this->lang->line('description_video');?>"><span class="ico video"></span><strong>Video</strong></a>
				<span class="vert-split"></span>
			</li>
			<li class="mode_button">
				<a href="#fullscraper_add_text"><span class="ico text"></span><strong>Text</strong></a>
			</li>
		</ul>		
	</div>
	<div id="full_scraper_templates">
		<!-- here are the templates before choose any type now there are in #drops_elements to get styled -->
		<?=Form_Helper::open('internal_scraper', array('rel'=>'ajaxForm', 'class' => 'upload_image scraper_template','id'=>'fullscraper_add_image'), 
			array('link_type'=>'image','type'=>'image','folder_id'=>'-1','activity[link][content]'=>'','activity[link][media]'=>'','activity[link][link]'=>'','link_url'=>"")
		)?>				
			<fieldset>
				<dl>
					<dt class="image titleBanner">Image<a href="#" class="close">Close</a></dt>
					<dd class="js_image_upload form_row">
						<div class="hidden_upload">
							<span class="upload_img_filename blue_bg blue_bg_tall">UPLOAD IMAGE</span>
							<input type="file" name="temp_img" value="" data-validate="required" data-error-required="You have to upload a file." data-max_mb="<?=$upload_mb;?>"/>
						</div>
						<div class="helper">
							<a href="" class="use_an_url">Use a URL</a>
							<span>JPEG, GIF or PNG</span>
						</div>
						<div class="error halfwidth"></div>
					</dd>
					<dd class="js_url_upload form_row">
						<label>Enter an image URL</label>
						<?=Form_Helper::textarea('img', '', array(
							'tabindex' => "1", 'class' => "",
							'placeholder'=>"Image Url",
							'data-validate' => "required|url",
							'data-error-required' => "Url is required",
							'data-error-url' => "Invalid Url",
						))?>
						<div class="helper">
							<a href="" class="upload_file">Upload a file</a>
							<span>JPEG, GIF or PNG</span>
						</div>
						<div class="error"></div>
					</dd><dd class="form_row">
						<?=Form_Helper::textarea('description', '', array(
							'tabindex' => "3", 'rows' => 2, 'class' => "fd_mentions",
							'placeholder' => "Image Description",
							'data-validate' => "required|maxlength",
							'data-maxlength' => $this->config->item('description_chars_limit'),
							'data-error-required' => "A Description is required",
						))?>
						<span class="textLimit"><?=$this->config->item('description_chars_limit')?></span>
						<div class="error"></div>
					</dd>
					<dd class="form_row hashtagRow halfwidth">
						<div>
							<span class="hashtags">
								<label class="microLabel">Select or type in a hashtag</label>
								<?php foreach ($hashtags as $hashtag) {?>
									<a href="<?=$hashtag->hashtag_name?>" class="hashtag"><?=$hashtag->hashtag_name?></a>
								<?php } ?>
								<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
								<span class="parenText">(Add if not safe for work)</span>
								<? /* ?><span class="typeyourOwn">(Or type your own)</span><? */ ?>
							</span>
						</div>
					</dd>
					<dd class="preview">
						<div class="image_preview">
							<img src="/images/screenshotIcon.png" width="100" class="screenshot_template"/>
							<span class="preloaders">
								<img src="images/loading_icons/bigRoller_32x32.gif" width="32" height="32" class="preloader" />
								<img src="/images/verified.png" width="16" height="16" class="status_ok" />
							</span>
						</div>
					</dd>
					<dd class="error"></dd>
					<dd class="success">Image was uploaded.</dd>
					<dd class="submit_buttons">						
<!-- 					<input type="button" class="light_grey_bg light_grey_bg_tall cancel_button" value="Cancel">
						<input type="submit" class="blue_bg blue_bg_tall" value="Drop It!"> -->
					</dd>
				</dl>
			</fieldset>
		</form>
		<?=Form_Helper::close()?>
		<?=Form_Helper::open('internal_scraper', array('rel'=>'ajaxForm', 'class' => 'add_video scraper_template','id'=>'fullscraper_add_video','link_url'=>""), 
			array('link_type'=>'embed','img'=>'','folder_id'=>'-1','activity[link][content]'=>'','activity[link][media]'=>'')
		)?>
			<fieldset>
				<dl>
					<dt class="video titleBanner">Video<a href="#" class="close">Close</a></dt>
					<dd class="form_row">
						<?=Form_Helper::textarea('activity[link][link]', '', array(
							'tabindex' => "1", 'class' => "",
							'data-validate' => "required",
							'placeholder'=>"Video Url",
							'data-error-required' => "URL of Video is required",
							'class'=>"with_preloader"
						))?>
<!-- 						<span class="preloaders">
							<img src="/images/loading_icons/loading_basic.gif" width="16" height="16" class="preloader" />
							<img src="/images/verified.png" width="16" height="16" class="status_ok" />
						</span> -->
						<div class="error"></div>
					</dd><dd class="form_row">
						<?=Form_Helper::textarea('description', '', array(
							'tabindex' => "3", 'rows' => 2, 'class' => "fd_mentions",
							'placeholder' => "Video Description",
							'data-validate' => "required|maxlength",
							'data-maxlength' => $this->config->item('description_chars_limit'),
							'data-error-required' => "A Description is required",
							'class'=>"with_preloader"
						))?>
						<span class="textLimit"><?=$this->config->item('description_chars_limit')?></span>
						<div class="error"></div>
					</dd>
					<dd class="form_row hashtagRow halfwidth">
						<div>
							<span class="hashtags">
								<label class="microLabel">Select or type in a hashtag</label>
								<?php foreach ($hashtags as $hashtag) {?>
									<a href="<?=$hashtag->hashtag_name?>" class="hashtag"><?=$hashtag->hashtag_name?></a>
								<?php } ?>
								<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
								<span class="parenText">(Add if not safe for work)</span>
								<? /* ?><span class="typeyourOwn">(Or type your own)</span><? */ ?>
							</span>
						</div>
					</dd>					
					<dd class="preview">
						<div class="image_preview">
							<img src="/images/screenshotIcon.png" width="100" class="screenshot_template" />
							<span class="preloaders">
								<img src="images/loading_icons/bigRoller_32x32.gif" width="32" height="32" class="preloader" />
								<img src="/images/verified.png" width="16" height="16" class="status_ok" />
							</span>							
						</div>
					</dd>					
					<dd class="error"></dd>
					<dd class="success">Video was uploaded.</dd>
					<dd class="submit_buttons">
<!-- 					<input type="button" class="light_grey_bg light_grey_bg_tall cancel_button" value="Cancel">
						<input type="submit" class="blue_bg blue_bg_tall" value="Drop It!"> -->
					</dd>					
				</dl>
			</fieldset>
		<?=Form_Helper::close()?>
		<?=Form_Helper::open('internal_scraper', array('rel'=>'ajaxForm', 'class' => 'add_url scraper_template','id'=>'fullscraper_add_url'), 
			array('link_type'=>'content','img'=>'','folder_id'=>'-1','activity[link][content]'=>'','activity[link][media]'=>'','link_url'=>"")
		)?>
			<fieldset>
				<dl>
					<dt class="url titleBanner">Url<a href="#" class="close">Close</a></dt>
					<dd class="form_row">
						<?=Form_Helper::textarea('activity[link][link]', '', array(
							'tabindex' => "1", 'class' => "with_preloader",
							'data-validate' => "required|url",
							'placeholder'=>"Page Url",
							'data-error-required' =>"A URL is required",
							'data-error-url'=>'Invalid Url'
						))?>
						<span class="preloaders">
							<img src="/images/loading_icons/loading_basic.gif" width="16" height="16" class="preloader" />
							<img src="/images/verified.png" width="16" height="16" class="status_ok" />
						</span>
						<div class="error"></div>
					</dd><dd class="form_row">
						<?=Form_Helper::textarea('description', '', array(
							'tabindex' => "3", 'rows' => 2, 'class' => "fd_mentions",
							'placeholder' => "Url Description",
							'data-validate' => "required|maxlength",
							'data-maxlength' => $this->config->item('description_chars_limit'),
							'data-error-required' => "A Description is required",
						))?>
						<span class="textLimit"><?=$this->config->item('description_chars_limit')?></span>
						<div class="error"></div>
					</dd>
					<dd class="form_row hashtagRow">
						<div>
							<span class="hashtags">
								<label class="microLabel">Select or type in a hashtag</label>
								<?php foreach ($hashtags as $hashtag) {?>
									<a href="<?=$hashtag->hashtag_name?>" class="hashtag"><?=$hashtag->hashtag_name?></a>
								<?php } ?>
								<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
								<span class="parenText">(Add if not safe for work)</span>
								<? /* ?><span class="typeyourOwn">(Or type your own)</span><? */ ?>
							</span>
						</div>
					</dd>					
					<dd class="error"></dd>
					<dd class="success">Url was dropped.</dd>
					<dd class="submit_buttons">
<!-- 					<input type="button" class="light_grey_bg light_grey_bg_tall cancel_button" value="Cancel">
						<input type="submit" class="blue_bg blue_bg_tall" value="Drop It!"> -->
					</dd>					
				</dl>
			</fieldset>
		<?=Form_Helper::close()?>
		<?=Form_Helper::open('internal_scraper', array('rel'=>'ajaxForm', 'class' => 'add_text scraper_template','id'=>'fullscraper_add_text'), 
			array('link_type'=>'text','img'=>'','folder_id'=>'-1','activity[link][link]'=>'','activity[link][media]'=>'','link_url'=>"")
		)?>		
			<fieldset>
				<dl>
					<dt class="text titleBanner">Text<a href="#" class="close">Close</a></dt>
					<dd class="form_row">
						<?=Form_Helper::textarea('description', '', array(
							'tabindex' => "3", 'rows' => 2, 'class' => "fd_mentions",
							'placeholder' => "Title",
							'data-validate' => "required|maxlength",
							'data-maxlength' => $this->config->item('description_chars_limit'),
							'data-error-required' => "Title is required"
						))?>
						<span class="textLimit"><?=$this->config->item('description_chars_limit')?></span>
						<div class="error"></div>			
					</dd><dd class="form_row hashtagRow">
						<div>
							<span class="hashtags">
								<label class="microLabel">Select or type in a hashtag</label>
								<?php foreach ($hashtags as $hashtag) {?>
									<a href="<?=$hashtag->hashtag_name?>" class="hashtag"><?=$hashtag->hashtag_name?></a>
								<?php } ?>
								<a href="#NSFW" class="hashtag hashNSFW">#NSFW</a>
								<span class="parenText">(Add if not safe for work)</span>
								<? /* ?><span class="typeyourOwn">(Or type your own)</span><? */ ?>
							</span>
						</div>
					</dd>
					<dd class="form_row">
							<?=Form_Helper::textarea('activity[link][content]', '', array(
								'tabindex' => "3", "rows" => "5", 'class' => "content",
								'placeholder' => "Text Description",
								'data-validate' => "required|maxlength",
								'data-error-required' => "A Description is required"
							))?>
							<div class="error"></div>
					</dd>
					<dd class="error"></dd>
					<dd class="success">Text was dropped.</dd>					
					<dd class="submit_buttons">
<!-- 					<input type="button" class="light_grey_bg light_grey_bg_tall cancel_button" value="Cancel">
						<input type="submit" class="blue_bg blue_bg_tall" value="Drop It!"> -->
					</dd>					
				</dl>
			</fieldset>
		<?=Form_Helper::close()?>		
</div>
<div id="drops_elements">
	<!-- here goes the elements after choose type -->
	<div class="submit_all">
		<input type="button" class="light_grey_bg light_grey_bg_tall cancel_button" value="Cancel">
		<input type="submit" class="blue_bg blue_bg_tall" value="Drop It!" data-default="Drop It!" data-loading="Dropping..." data-validating="Validating...">
	</div>
</div>
<?=Html_helper::requireJS(array('fullscraper/full_scraper'))?>
