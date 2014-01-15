<? $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<? //used in: https://www.fandrop.com/about/drop_it_button?>
<div id="bookmarklet_walkthrough_container" class="container_24">
    <div id="bookmarklet_walkthrough_body">

	<div id="wrapper_container">
	    <div class="show_bookmark_bar_text" id="bookmarklet_bar_chrome">
		Don't see the bookmark bar? Click on <?=Html_helper::img('walkthrough/bookmarklet/chrome_wrench.png', array('class'=>"bookmarklet_bar_img", 'style'=>"height: 17px; padding: 0;"))?><strong> > Bookmarks > Always show the bookmarks bar</strong> or press the <strong>CTRL + Shift + B</strong> keys.
	    </div>
	    <div class="show_bookmark_bar_text" id="bookmarklet_bar_ff" style="display:none">
		Don't see the bookmark bar?  <?/*Right-click on an empty area of the <i>Tab Strip</i> and click <i>Bookmarks Toolbar</i> in the pop-up menu or*/?>
		Click on the <strong>Firefox</strong> button <strong>> Options > Bookmarks Toolbar</strong>.
	    </div>
	    <div class="show_bookmark_bar_text" id="bookmarklet_bar_ie" style="display:none">
		Don't see the bookmark bar? Right-click on the <i>Tab bar</i> (empty area near Tabs). Click on <i>Favorites Bar</i> or <?/*...<br>*/?>
		by press the <strong>ALT</strong> key, then click on <strong>View > Toolbars menu.</strong>
	    </div>
	    <div class="show_bookmark_bar_text" id="bookmarklet_bar_ie8" style="display:none">
		Don't see the bookmark bar?	Click on <strong>Tools > Toolbars</strong> and then do one of the following:
		To hide the <strong>Favorites Bar</strong>, click on <strong>Favorites Bar</strong> to clear the check mark.
	    </div>
	    <div class="show_bookmark_bar_text" id="bookmarklet_bar_opera" style="display:none">
		Don't see the bookmark bar?  Click on <strong>O</strong> Menu <strong> > Toolbars > Bookmarks Bar</strong>.
	    </div>
	    <div class="show_bookmark_bar_text bookmarklet_bar_safari" id="bookmarklet_bar_safari" style="display:none">
		Don't see the bookmark bar? Press the <strong>Shift + CMND + B</strong> keys.
	    </div>
	    <div id="walkthrough_info_wrapper">
		<div id="bookmarklet_button">
			<div class="bookmarklet-btn">
			    <a href="<?=Array_helper::element('href_code', $this->config->load('bookmarklet'))?>">
					<?=Html_helper::img('bookmarklet_button4.png', array('alt'=>$this->lang->line('bookmarklet_drop_alt')))?>
			    </a>
			</div>
			<div class="body_text">
            	<?=$this->lang->line('bookmarklet_walkthrough_body_msg');?>
			</div>
			<? /*> <div class="sharrow_big"></div> <*/ ?>
		</div>
		<div class="clear"></div>
	    </div>
	    
	    <div class="clear"></div>
	    <? /* ?>
	    <? if (isset($signup) && $signup) { ?>
		<a class="bookmarklet_step_completed_top blue_bg blue_bg_tall" href="/preview_info">Go to Last Step</a>
	    <? } ?>
	    <div class="clear"></div>
		<? */ ?>
	
	    <h6><?=$this->lang->line('bookmarklet_howto_header');?></h6>
	    <div id="bookmarklet_container">
		<div id="animation_container">
		    <div id="installation_animation">
			<span id="animation_cover"></span>
			
			<? //Chrome ?>
			<object class="show_animation_video animation_video_chrome" id="animation_video" width="440" height="325">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/b7MUn7AkFMo?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="440" height="325" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			<? //Safari ?>
			<object class="show_animation_video animation_video_safari" id="animation_video" width="440" height="325">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/twj0LU0zgvI?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="440" height="325" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			<? //Firefox ?>
			<? /* ?>
			<iframe id="animation_video" class="show_animation_video animation_video_ff" type="text/html" width="440" height="325"
			  src="http://www.youtube.com/embed/1VoMbB0Xds?autoplay=1&amp;wmode=transparent&amp;version=3&amp;hl=en_US"
			  frameborder="0"></iframe>
			<? */ ?>
			<object class="show_animation_video animation_video_ff" id="animation_video" width="440" height="325">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/1VoMbB0Xdsg?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="440" height="325" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			<? /* ?>
			<object class="show_animation_video animation_video_ff" id="animation_video" width="440" height="325">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/1VoMbB0Xds?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="440" height="325" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			<? */ ?>
			
			<? //IE8-- ?>
			<object class="show_animation_video animation_video_ie8" id="animation_video" width="440" height="325">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/rJRNhnmJiVI?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="440" height="325" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			<? //IE9+ ?>
			<object class="show_animation_video animation_video_ie" id="animation_video" width="440" height="325">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="440" height="325" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			<? //Opera ?>
			<object class="show_animation_video animation_video_opera" id="animation_video" width="440" height="325">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="440" height="325" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			
			<?/*<iframe id="animation_video" width="440" height="325" src="http://www.youtube.com/embed/7pWmoAdFv24?&autoplay=1" frameborder="0"></iframe>*/?>
			<?/*<?=Html_helper::img('walkthrough/bookmarklet/animation.gif'))?>*/?>
		    </div>
		</div>
	    <? /*>
            <?=$this->lang->line('bookmarklet_howto_paragraph_1');?>
            <?=Html_helper::img('walkthrough/bookmarklet/zero.png'))?>
            <?=$this->lang->line('bookmarklet_howto_paragraph_2');?>
            <?=Html_helper::img('walkthrough/bookmarklet/one.png'))?>
            <?=$this->lang->line('bookmarklet_howto_paragraph_3');?>
            <?=Html_helper::img('walkthrough/bookmarklet/two.png'))?>
            <?=$this->lang->line('bookmarklet_howto_paragraph_4');?>
            <?=Html_helper::img('walkthrough/bookmarklet/three.png'))?>
	    </div>
	    <*/?>
	    
	    <? if (isset($signup) && $signup) { ?>
	    	<? if($this->is_mod_enabled('category_power_users')){ ?>
	    		<a class="bookmarklet_step_completed blue_bg blue_bg_tall" href="/choose_category<?=$this->input->get('next',true) ? '?next='.$this->input->get('next',true) : ''?><?=$this->input->get('action_type',true) ? '&action_type='.$this->input->get('action_type',true) : ''?>"><?=$this->lang->line('bookmarklet_next_step_btn');?></a>
	    	<? }else{ ?>
	    		<a class="bookmarklet_step_completed blue_bg blue_bg_tall" href="/preview_info<?=$this->input->get('next',true) ? '?next='.$this->input->get('next',true) : ''?><?=$this->input->get('action_type',true) ? '&action_type='.$this->input->get('action_type',true) : ''?>"><?=$this->lang->line('bookmarklet_next_step_btn');?></a>
	    	<? } ?>
	    <? } ?>
	    <? if (!(isset($signup) && $signup) && $this->is_mod_enabled('embed_bookmarklet')) { ?>
		<h6><?=$this->lang->line('bookmarklet_for_websites');?></h6>
		<form id="embed-form" class="inlinediv">
			<div>
			    <div class="embed-button-style">
				<fieldset>
				    <legend>Select button style</legend>
				    <label class="inlinediv">
					<input type="checkbox" name=""/>
					<span>Include drops count</span>
				    </label>
				    <ul>
					<li class="selected inlinediv"><a href=""><div class="embed-style-0"><span style="display:none">123</span></div></a></li>
					<li class="inlinediv" rel="theme-1"><a href=""><div class="embed-style-1"><span style="display:none">123</span></div></a></li>
					<li class="inlinediv" rel="theme-2"><a href=""><div class="embed-style-2"><span style="display:none">123</span></div></a></li>
					<li class="inlinediv" rel="theme-3"><a href=""><div class="embed-style-3"><span style="display:none">123</span></div></a></li>
					<li class="inlinediv" rel="theme-4"><a href=""><div class="embed-style-4"><span style="display:none">123</span></div></a></li>
					<li class="inlinediv" rel="theme-5"><a href=""><div class="embed-style-5"><span style="display:none">123</span></div></a></li>
					<li class="inlinediv" rel="theme-6"><a href=""><div class="embed-style-6"><span style="display:none">123</span></div></a></li>
					<li class="inlinediv" rel="theme-7"><a href=""><div class="embed-style-7"><span style="display:none">123</span></div></a></li>
					<li class="inlinediv" rel="theme-8"><a href=""><div class="embed-style-8"><span style="display:none">123</span></div></a></li>
				    </ul>
				</fieldset>
			    </div>
			    <div class="embed-selectors">
			    	<fieldset>
				    <legend>Personalization</legend>
				    <label>
					<span class="embed_label_text">Selector <em>*</em></span>
					<input type="text" name="selector" placeholder="jQuery Selector"/>
					<strong class="custom-title" title="The selectors define what will get dropped when someone clicks on the button. They must be a jQuery-compatible selector (e.g. #content .article:first).">?</strong>
				    </label>
				    <label>
					<span class="embed_label_text">Link</span>
					<input type="text" name="link" placeholder="Enter URL (optional)"/>
					<strong class="custom-title" title="optional, sets the URL that the clip will link to; if not given, the window's location will be used">?</strong>
				    </label>
				    <label>
					<span class="embed_label_text">Title</span>
					<input type="text" name="title" placeholder="Enter Title (optional)"/>
					<strong class="custom-title" title="optional, sets the default title for the clip; if not given, the document’s title will be used">?</strong>
				    </label>
				    <label>
					<span class="embed_label_text">Description</span>
					<input type="text" name="dscription" placeholder="Enter a description (optional)"/>
					<strong class="custom-title" title="optional, sets a default description for the clip">?</strong>
				    </label>
			    	</fieldset>
			    </div>
			    <div class="embed-code">
			    	<p id="dropIt_code_desc">Anywhere you want a button, add this code to your page. You can also apply inline styles to this code if you need further control on the appearance of the button.</p>
			    	<textarea id="dropIt_code">
				    <script type="text/javascript">
					(function() {
					    var t = document.createElement('script'); t.type = 'text/javascript'; t.async = true;
					    t.src = 'http://test.fandrop.com/bookmarklet_embed.js?v=1.09';
					    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(t, s);
					  })();
				    </script> 
				    <div id="fandrop_embed_btn" data-style="default" data-count="true" data-content=".cnn_shdsectbin"></div>		
			    	</textarea>
			    </div>
			</div>
		</form>
	    <? } ?>
	</div><!-- End #bookmarklet_container -->
    </div><!-- End #wrapper_container -->
</div>
<?php //@update RR removed plugins/jquery.form - File upload is not used on this page?>
<?=Html_helper::requireJS(array("includes/info_dialog"))?> 