<? $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
<? //used in: https://www.fandrop.com/about/drop_it_button?>
<div id="bookmarklet_walkthrough_container">
    <div class="aboutHead">
	 <h4><?=$this->lang->line('footer_about_bookmarklet_title');?></h4>
    </div>
    <div id="bookmarklet_walkthrough_body" class="aboutBody">

	<div id="wrapper_container">
	    <div id="walkthrough_info_wrapper">
		<div id="bookmarklet_button" class="inlinediv">
			<div class="bookmarklet-btn inlinediv">
			    <a href="<?=Array_helper::element('href_code', $this->config->load('bookmarklet'))?>">
					<?=Html_helper::img('bookmarklet_button5.png', array('alt'=>$this->lang->line('bookmarklet_drop_alt')))?>
			    </a>
			</div>
			<div class="body_text inlinediv">
			    <?=$this->lang->line('bookmarklet_walkthrough_body_msg');?>
			</div>
		</div>
	    </div>
	    <div id="bookmarklet_container">
		<div id="animation_container" class="inlinediv">
		    <h6><?=$this->lang->line('bookmarklet_howto_header');?></h6>
		    <div id="installation_animation" class="inlinediv">
			<span id="animation_cover"></span>
			
			<? //Chrome ?>
			<object class="show_animation_video animation_video_chrome" id="animation_video" width="325" height="275">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/b7MUn7AkFMo?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="325" height="275" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			<? //Safari ?>
			<object class="show_animation_video animation_video_safari" id="animation_video" width="325" height="275">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/twj0LU0zgvI?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="325" height="275" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			<? //Firefox ?>
			<? /* ?>
			<iframe id="animation_video" class="show_animation_video animation_video_ff" type="text/html" width="440" height="325"
			  src="http://www.youtube.com/embed/1VoMbB0Xds?autoplay=1&amp;wmode=transparent&amp;version=3&amp;hl=en_US"
			  frameborder="0"></iframe>
			<? */ ?>
			<object class="show_animation_video animation_video_ff" id="animation_video" width="325" height="275">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/1VoMbB0Xdsg?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="325" height="275" allowscriptaccess="always" allowfullscreen="true"></embed>
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
			<object class="show_animation_video animation_video_ie8" id="animation_video" width="325" height="275">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/rJRNhnmJiVI?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="325" height="275" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			<? //IE9+ ?>
			<object class="show_animation_video animation_video_ie" id="animation_video" width="325" height="275">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="325" height="275" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
			
			<? //Opera ?>
			<object class="show_animation_video animation_video_opera" id="animation_video" width="325" height="275">
				<param name="movie" value="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US"></param>
				<param name="allowFullScreen" value="true"></param>
				<param name="allowscriptaccess" value="always"></param>
				<param name="wmode" value="opaque"></param>
				<embed src="https://www.youtube.com/v/7pWmoAdFv24?autoplay=1&amp;version=3&amp;hl=en_US" wmode="opaque" type="application/x-shockwave-flash" width="325" height="275" allowscriptaccess="always" allowfullscreen="true"></embed>
			</object>
		    </div>
		</div>
		<div class="bookmarklet_bar_text_container inlinediv">
		    <div class="show_bookmark_bar_text" id="bookmarklet_bar_chrome">
			<h6><?=$this->lang->line("bookmarklet_bookmark_bar_heading");?></h6>
			<div class="bookmark_bar_text">Click on <?=Html_helper::img('walkthrough/bookmarklet/chrome_wrench.png', array('class'=>"bookmarklet_bar_img", 'style'=>"height: 17px; padding: 0;"))?><?=$this->lang->line("bookmarklet_bookmark_bar_text_chrome");?></div>
		    </div>
		    <div class="show_bookmark_bar_text" id="bookmarklet_bar_ff" style="display:none">
			<h6><?=$this->lang->line("bookmarklet_bookmark_bar_heading");?></h6>
			<?/*Right-click on an empty area of the <i>Tab Strip</i> and click <i>Bookmarks Toolbar</i> in the pop-up menu or*/?>
			<div class="bookmark_bar_text"><?=$this->lang->line("bookmarklet_bookmark_bar_text_ff");?></div>
		    </div>
		    <div class="show_bookmark_bar_text" id="bookmarklet_bar_ie" style="display:none">
			<h6><?=$this->lang->line("bookmarklet_bookmark_bar_heading");?></h6>
			<div class="bookmark_bar_text"><?=$this->lang->line("bookmarklet_bookmark_bar_text_ie");?></div>
		    </div>
		    <div class="show_bookmark_bar_text" id="bookmarklet_bar_ie8" style="display:none">
			<h6><?=$this->lang->line("bookmarklet_bookmark_bar_heading");?></h6>
			<div class="bookmark_bar_text"><?=$this->lang->line("bookmarklet_bookmark_bar_text_ie8");?></div>
		    </div>
		    <div class="show_bookmark_bar_text" id="bookmarklet_bar_opera" style="display:none">
			<h6><?=$this->lang->line("bookmarklet_bookmark_bar_heading");?></h6>
			<div class="bookmark_bar_text"><?=$this->lang->line("bookmarklet_bookmark_bar_text_opera");?></div>
		    </div>
		    <div class="show_bookmark_bar_text bookmarklet_bar_safari" id="bookmarklet_bar_safari" style="display:none">
			<h6><?=$this->lang->line("bookmarklet_bookmark_bar_heading");?></h6>
			<div class="bookmark_bar_text"><?=$this->lang->line("bookmarklet_bookmark_bar_text_safari");?></div>
		    </div>
		</div>
	
	    
	    <? if (isset($signup) && $signup) { ?>
	    	<? if($this->is_mod_enabled('category_power_users')){ ?>
	    		<a class="bookmarklet_step_completed blue_bg blue_bg_tall" href="/choose_category<?=$this->input->get('next',true) ? '?next='.$this->input->get('next',true) : ''?><?=$this->input->get('action_type',true) ? '&action_type='.$this->input->get('action_type',true) : ''?>"><?=$this->lang->line('bookmarklet_next_step_btn');?></a>
	    	<? }else{ ?>
	    		<a class="bookmarklet_step_completed blue_bg blue_bg_tall" href="/preview_info<?=$this->input->get('next',true) ? '?next='.$this->input->get('next',true) : ''?><?=$this->input->get('action_type',true) ? '&action_type='.$this->input->get('action_type',true) : ''?>"><?=$this->lang->line('bookmarklet_next_step_btn');?></a>
	    	<? } ?>
	    <? } ?>
	</div><!-- End #bookmarklet_container -->
    </div><!-- End #wrapper_container -->
</div>
<?php //@update RR removed plugins/jquery.form - File upload is not used on this page?>
<?=Html_helper::requireJS(array("includes/info_dialog"))?> 