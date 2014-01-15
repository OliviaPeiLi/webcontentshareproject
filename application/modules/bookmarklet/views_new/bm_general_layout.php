<?php $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<div id="scraping_overlay"></div>
<div class="clipboard-popup" id="success_popup" style="display:none">
	<div class="clipboard-popup-header"></div> 
	<a href="" class="clipboard-popup-close"></a>
	<div class="clipboard-popup-content">
		<iframe src="" id="success-iframe" allowTransparency="true" frameborder="0"></iframe>
	</div>
</div>
<div class="clipboard-popup" id="save_link" style="display:none">
	<div class="clipboard-popup-header"><?=Html_helper::img((ENVIRONMENT=='development'?Url_helper::base_url().'images/':'').'bookmarklet/logo2.png', array('alt'=>'logo'))?></div> 
	<a href="" class="clipboard-popup-close"></a>
	<div class="clipboard-popup-content">
		<iframe src="" id="bookmark-page" allowTransparency="true" frameborder="0"></iframe>
	</div>
</div>
<div id="fandrop_div" style="display:none">
	<div id="ft_error_msg">
		<h3>Error occured while sharing content</h3><strong class="message"></strong>
	</div>
	<div class="ft-loader"><?=Html_helper::img((ENVIRONMENT=='development'?Url_helper::base_url().'images/':'').'bookmarklet/logo2.png', array('alt'=>'loader'))?><div id="ft-loading_text">Loading...</div></div>
	<iframe id="scraping_overlay_iframe" src="" allowtransparency="true" frameborder="0"></iframe>
	<div class="ui-tooltip tooltip-5" style="right: 42px; bottom: 103px; width: 230px;" tracking="false" role="alert" aria-live="polite" aria-atomic="false" aria-describedby="ui-tooltip-1-content" aria-hidden="false">
		<div class="ui-tooltip-content" aria-atomic="true"><?=$this->lang->line("bookmarklet_go_profile_msg");?></div>
		<div class="ui-tooltip-tip right_tip"></div>
	</div>
	<div class="ui-tooltip tooltip-6" style="right: 5px; bottom: 103px; width: 160px;" tracking="false" role="alert" aria-live="polite" aria-atomic="false" aria-describedby="ui-tooltip-1-content" aria-hidden="false">
		<div class="ui-tooltip-content" aria-atomic="true"><?=$this->lang->line("bookmarklet_go_home_msg");?></div>
		<div class="ui-tooltip-tip right_tip"></div>
	</div>
	<div class="ui-tooltip tooltip-7" style="right: 10px; bottom: 103px; width: 195px;" tracking="false" role="alert" aria-live="polite" aria-atomic="false" aria-describedby="ui-tooltip-1-content" aria-hidden="false">
		<div class="ui-tooltip-content" aria-atomic="true"><?=$this->lang->line("bookmarklet_close_msg");?></div>
		<div class="ui-tooltip-tip far_right_tip"></div>
	</div>
</div>
<?php if ($this->is_mod_enabled('bookmarklet_image_mode')) { ?>
<div id="ft_image_mode">
	<?='<script type="text/html">'?>
		<li>
			<a href="">
				<span><img src="" alt=""/>
					<span class="hoverOverlay"><span class="blue_bg">Drop It!</span></span>
				</span>
			</a>
		</li>
	<?='</script>'?>
	<ul>
	</ul>
</div>
<?php } ?>
<?php if ($this->is_mod_enabled('design_ugc')) { ?>
<div id="ft_video_mode">
	<?='<script type="text/html">'?>
		<li>
			<a href="">
				<span><img src="" alt=""/>
					<span class="hoverOverlay"><span class="blue_bg">Drop It!</span></span>
				</span>
			</a>
		</li>
	<?='</script>'?>
	<ul>
	</ul>
</div>

<?php } ?>