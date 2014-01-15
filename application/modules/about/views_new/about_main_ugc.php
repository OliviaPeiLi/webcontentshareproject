<div id="about_specialHeader" class="about_mainPage">
	<span class="specialHeader_backgroundImage"></span>
	<div class="container specialHeader_container">
		<div class="specialheaderIcon_container">
			<div class="specialheaderIcon1 inlinediv"></div>
			<div class="specialheaderIcon2 inlinediv"></div>
		</div>
		<span class="specialHeader_textImage"></span>
	</div>
</div>
<div id="about_specialBody" class="about_mainPage">
	<div class="container">
		<div class="row">
			<div class="span4 offset4">
				<div class="about_specialImage1"></div>
			</div>
			<div class="about_specialText_internalContainer1 span12">
				<div><?=$this->lang->line("footer_special_text1");?></div>
				<div><?=$this->lang->line("footer_special_text2");?></div>
			</div>
		</div>
		<div class="row">
			<div class="about_specialText_internalContainer2 span12 offset"><?=$this->lang->line("footer_special_text3");?></div>
			<div class="offset1 span3">
				<div class="about_specialImage2"></div>
			</div>
		</div>
	</div>
</div>
<div id="about_bottomTabs_container">
	<span class="about_moreText"><?=$this->lang->line('footer_more_lexicon');?></span>
	<ul id="about_bottomTabs" class="">
		<li id="about_tab_main">
			<a href="/about/"><?=$this->lang->line('footer_main_title');?></a>
		</li>
		<!--  <li id="about_tab_team">
			<a href="/about/team"><?=$this->lang->line('footer_about_team_title');?></a>
		</li>  -->
		<? /* ?><li id="about_tab_promoters">
			<a href="/promoters"><?=$this->lang->line('footer_about_promoters_title');?></a>
		</li><? */ ?>
		<li id="about_tab_partners">
			<a href="/about/partners"><?=$this->lang->line('footer_about_partners_title');?></a>
		</li>
		<li id="about_tab_publishers">
			<a href="/publishers"><?=$this->lang->line('footer_about_publishers_title');?></a>
		</li>
		<? /* ?><li id="about_tab_contactus">
			<a href="/about/contactus"><?=ucwords($this->lang->line('contact_us'));?></a>
		</li>
		<li id="about_tab_jobs">
			<a href="/about/jobs"><?=$this->lang->line('footer_jobs2_title');?></a>
		</li>
		<?if($this->session->userdata('id')):?>
			<li id="about_tab_bookmarklet">
			<a href="/about/drop_it_button"><?=$this->lang->line('footer_drop_it_btn');?></a>
			</li>
		<? endif; ?>
		<li id="about_tab_privacy">
			<a href="/about/privacy"><?=$this->lang->line('footer_about_privacy_title');?></a>
		</li>
		<li id="about_tab_copyright">
			<a href="/about/copyright"><?=$this->lang->line('copyright_privacy');?></a>
		</li><? */ ?>
	</ul>
</div>