<? $this->lang->load('about/footer', LANGUAGE);?>
<div id="fandrop_intro_video_wrap" class="js-video_init video_init modal hide" style="display:none;width:854px;height:526px;max-width:1280px;max-height:720px;background:#000;">
	http://www.youtube.com/watch?v=_REaOwNNgFA
	<iframe id="fandrop_intro_video" data-videourl="http://www.youtube.com/embed/_REaOwNNgFA?wmode=opaque&amp;hd=1&amp;feature=player_embedded" src="http://www.youtube.com/embed/_REaOwNNgFA?wmode=opaque&amp;hd=1&amp;feature=player_embedded" width="854" height="510" style="width:854px;height:510px;max-width:1280px;max-height:720px" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
</div>

<div id="about_motto">
    <?=$this->lang->line('footer_about_main_title');?>
</div>
<div id="about_description">

    <span id="about_page_video_wrapper">
	<a id="about_page_video" data-access="all" rel="popup" title="" href="#fandrop_intro_video_wrap">
	    <?=Html_helper::img('videoThumb2.png', array('id'=>"about_page_video_preview", 'alt'=>""))?>
	    <span id="about_page_play_button"></span>
	</a>
    <?=$this->lang->line('footer_watch_intro_lexicon');?>
    </span>
    <p>
    <?=$this->lang->line('footer_about_main_text1');?>
    </p>
    
    <div class="example_right">
		<?=Html_helper::img('about_user_case/video.png', array('alt'=>""))?>
		<div class="example_text">
		    <h4><?=$this->lang->line('footer_example_text1_title');?></h4>
		    <p><?=$this->lang->line('footer_example_text1_text');?></p>
		</div>
    </div>
    <div class="example_left">
		<?=Html_helper::img('about_user_case/game.png', array('alt'=>""))?>
		<div class="example_text">
		    <h4><?=$this->lang->line('footer_example_text2_title');?></h4>
		    <p><?=$this->lang->line('footer_example_text2_text');?></p>
		</div>
    </div>
    <div class="example_right">
		<?=Html_helper::img('about_user_case/sports.png', array('alt'=>""))?>
		<div class="example_text">
		    <h4><?=$this->lang->line('footer_example_text3_title');?></h4>
		    <p><?=$this->lang->line('footer_example_text3_text');?></p>
		</div>
    </div>
    <div class="example_left">
		<?=Html_helper::img('about_user_case/collab.png', array('alt'=>""))?>
		<div class="example_text">
		    <h4><?=$this->lang->line('footer_example_text4_title');?></h4>
		    <p><?=$this->lang->line('footer_example_text4_text');?></p>
		</div>
    </div>
    <? /* ?>
    <div class="example_right">
		<img src="/images/about_user_case/quotes-5.png">
		<div class="example_text">
		    <h4><?=$this->lang->line('footer_example_text5_title');?></h4>
		    <p><?=$this->lang->line('footer_example_text5_text');?></p>
		</div>
    </div>
    <? */ ?>
    <div class="example_left">
		<?=Html_helper::img('about_user_case/webpages.png', array('alt'=>""))?>
		<div class="example_text">
		    <h4><?=$this->lang->line('footer_example_text6_title');?></h4>
		    <p><?=$this->lang->line('footer_example_text6_text');?></p>
		</div>
    </div>
    <div class="example_right">
		<?=Html_helper::img('about_user_case/vacation.png', array('alt'=>""))?>
		<div class="example_text">
		    <h4><?=$this->lang->line('footer_example_text7_title');?></h4>
		    <p><?=$this->lang->line('footer_example_text7_text');?></p>
		</div>
    </div>
</div>
<?=Html_helper::requireJS(array("jquery",'common/video_init'))?> 