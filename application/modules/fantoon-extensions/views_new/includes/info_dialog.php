<? $this->lang->load('includes/info_dialog', LANGUAGE); ?>
<? //$disp = (!isset($first_visit) || !$first_visit) ? '' : 'style="display:none;"' ?>
<? $disp = ''; ?>

<?

// add user because many caching info regarding to language
$cache_name = 'info_dialog_' . LANGUAGE;

/* RR - commenting out because its not working fine
if(isset($cache_name)){
	if (!$cache = $this->cache->get($cache_name)){
		ob_start();
	}
}
*/
?>

<div id="get_bookmarklet_dialog" style="display:none">
	<div id="close_popup" data-dismiss="modal"></div>
	<div id="add_popup_step1">
		<div class="top">
			<div class="topBox">
				<div class="bookmarklet-btn">
					<a href="<?=Array_helper::element('href_code', $this->config->load('bookmarklet'))?>">
						<?=Html_helper::img('bookmarklet_button4.png', array('id'=>"bookmarklet-btn_img", 'alt'=>$this->lang->line('bookmarklet_drop_alt')))?>
					</a>
				</div>
				<div class="body_text">
					<?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') !== false): //Just make it harder ?>
						<?=$this->lang->line("bookmarklet_if_ie8_message");?>
					<?php else: ?>
						<?=$this->lang->line("bookmarklet_not_ie8_message");?>
					<?php endif;?>
					(<a href="/about/drop_it_button"><strong>?</strong></a>)
				</div>
			</div>
			<div class="sharrow_new"></div>
			
		   <div class="show_bookmark_bar_text" id="bookmarklet_bar_chrome">
				<div class="dont_see_bookmarks_bar">Don't see the bookmark bar?</div>
				<div class="bookmarks_bar_instructions">
					Click on <span class="chrome_wrench"></span> <strong> > Bookmarks > Always show the bookmarks bar</strong> or press the <strong>CTRL + Shift + B</strong> keys.
				</div>
			</div>
			<div class="show_bookmark_bar_text" id="bookmarklet_bar_ff" style="display:none">
				<div class="dont_see_bookmarks_bar">Don't see the bookmark bar?</div>  <? /*Right-click on an empty area of the <i>Tab Strip</i> and click <i>Bookmarks Toolbar</i> in the pop-up menu or*/?>
				<div class="bookmarks_bar_instructions">
					Click on the <strong>Firefox</strong> button <strong>> Options > Bookmarks Toolbar</strong>.
				</div>
			</div>
			<div class="show_bookmark_bar_text" id="bookmarklet_bar_ie" style="display:none">
				<div class="dont_see_bookmarks_bar">Don't see the bookmark bar? </div>
				<div class="bookmarks_bar_instructions">
					Right-click on the <i>Tab bar</i> (empty area near Tabs). Click on <i>Favorites Bar</i> or <?/*...<br>*/?>
					by press the <strong>ALT</strong> key, then click on <strong>View > Toolbars menu.</strong>
				</div>
			</div>
			<div class="show_bookmark_bar_text" id="bookmarklet_bar_ie8" style="display:none">
				<div class="dont_see_bookmarks_bar">Don't see the bookmark bar? </div>
				<div class="bookmarks_bar_instructions">
					Click on <strong>Tools > Toolbars</strong> and then do one of the following:
					To hide the <strong>Favorites Bar</strong>, click on <strong>Favorites Bar</strong> to clear the check mark.
				</div>
			</div>
			<div class="show_bookmark_bar_text" id="bookmarklet_bar_opera" style="display:none">
				<div class="dont_see_bookmarks_bar">Don't see the bookmark bar? </div>
				<div class="bookmarks_bar_instructions">
					Click on <strong>O</strong> Menu <strong> > Toolbars > Bookmarks Bar</strong>.
				</div>
			</div>
			<div class="show_bookmark_bar_text bookmarklet_bar_safari" id="bookmarklet_bar_safari" style="display:none">
				<div class="dont_see_bookmarks_bar">Don't see the bookmark bar? </div>
				<div class="bookmarks_bar_instructions">
					Press the <strong>Shift + CMND + B</strong> keys.
				</div>
			</div>			
			<div class="videoContainer">
				<div class="animation_title">Using the "Drop It" button</div>
				<div id="installation_animation">
					<span id="animation_cover"></span>
					<? //Chrome ?>
					<object class="show_animation_video animation_video_chrome" id="animation_video" width="332" height="253" type="application/x-shockwave-flash">
						<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US">
						<param name="allowFullScreen" value="true">
						<param name="allowscriptaccess" value="always">
						<param name="wmode" value="opaque">
						<? /* ?><embed src="https://www.youtube.com/v/W6y7iRbR88g?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="332" height="253" allowscriptaccess="always" allowfullscreen="true"></embed><? */ ?>
					</object>
					
					<? //Safari ?>
					<object class="show_animation_video animation_video_safari" id="animation_video" width="332" height="253" type="application/x-shockwave-flash">
						<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US">
						<param name="allowFullScreen" value="true">
						<param name="allowscriptaccess" value="always">
						<param name="wmode" value="opaque">
						<? /* ?><embed src="https://www.youtube.com/v/NWBOIV6mtwo?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="332" height="253" allowscriptaccess="always" allowfullscreen="true"></embed><? */ ?>
					</object>
					
					<? //Firefox ?>
					<object class="show_animation_video animation_video_ff" id="animation_video" width="332" height="253">
						<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?hl=en_US&amp;autoplay=1&amp;version=3&amp;rel=0"></param>
						<param name="allowFullScreen" value="true"></param>
						<param name="allowscriptaccess" value="always"></param>
						<param name="wmode" value="opaque"></param>
						<embed src="https://www.youtube.com/v/7pWmoAdFv24?hl=en_US&amp;autoplay=1&amp;version=3&amp;rel=0" wmode="opaque" type="application/x-shockwave-flash" width="332" height="253" allowscriptaccess="always" allowfullscreen="true"></embed>
					</object>
					
					<? //IE8-- ?>
					<object class="show_animation_video animation_video_ie8" id="animation_video" width="332" height="253" type="application/x-shockwave-flash">
						<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US">
						<param name="allowFullScreen" value="true">
						<param name="allowscriptaccess" value="always">
						<param name="wmode" value="opaque">
						<? /* ?><embed src="https://www.youtube.com/v/oPamAkoMLbg?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="332" height="253" allowscriptaccess="always" allowfullscreen="true"></embed><? */ ?>
					</object>
					
					<? //IE9+ ?>
					<object class="show_animation_video animation_video_ie" id="animation_video" width="332" height="253" type="application/x-shockwave-flash">
						<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US">
						<param name="allowFullScreen" value="true">
						<param name="allowscriptaccess" value="always">
						<param name="wmode" value="opaque">
						<? /* ?><embed src="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="332" height="253" allowscriptaccess="always" allowfullscreen="true"></embed><? */ ?>
					</object>
					
					<? //Opera ?>
					<object class="show_animation_video animation_video_opera" id="animation_video" width="332" height="253" type="application/x-shockwave-flash">
						<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US">
						<param name="allowFullScreen" value="true">
						<param name="allowscriptaccess" value="always">
						<param name="wmode" value="opaque">
						<? /* ?><embed src="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="332" height="253" allowscriptaccess="always" allowfullscreen="true"></embed><? */ ?>
					</object>
		
					<?/*<iframe id="animation_video" width="340" height="260" data-url="http://www.youtube.com/embed/7pWmoAdFv24?&autoplay=1" frameborder="0"></iframe>*/ ?>
					<?/*<?=Html_helper::img('walkthrough/bookmarklet/animation.gif')?>*/?>
				</div>
			</div>
			
			<? /* show_bookmarklet_bar_text used to be here */?>
			
	    </div>
	</div> <!-- End add_popup_step 1 -->
	
</div>

<? /* RR - commenting out because its not working fine
	if(isset($cache_name)){
	if(!$cache){
		$cache = ob_get_clean(); 
		$this->cache->save($cache_name, $cache, $this->config->item('cache_expire'));
	}
	echo $cache;
}*/
?>
<?=Html_helper::requireJS(array("includes/info_dialog"))?> 