<? $this->lang->load('about/footer', LANGUAGE); ?>
<div id="about_container" class="container" style="font-family: Arial,Helvetica,sans-serif; font-size:18px;">
    <div class="row">
	<div class="span5">
	    <ul id="about_tabs">
		<li id="about_tab_main" <? if($page === 'main') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/about/"><?=$this->lang->line('footer_main_title');?><? if($page === 'main') { echo "<div class=\"ico\"></div>"; }?></a>
		</li>
	    <!--  <li id="about_tab_team" <? if($page === 'team') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/about/team"><?=$this->lang->line('footer_about_team_title');?><? if($page === 'team') { echo "<div class=\"ico\"></div>"; }?></a>
		</li>  -->
		<li id="about_tab_promoters" <? if($page === 'promoters') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/promoters"><?=$this->lang->line('footer_about_promoters_title');?><? if($page === 'promoters') { echo "<div class=\"ico\"></div>"; }?></a>
		</li>
		<li id="about_tab_publishers" <? if($page === 'publishers') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/publishers"><?=$this->lang->line('footer_about_publishers_title');?><? if($page === 'publishers') { echo "<div class=\"ico\"></div>"; }?></a>
		</li>
		<li id="about_tab_contactus" <? if($page === 'contactus') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/about/contactus"><?=ucwords($this->lang->line('contact_us'));?><? if($page === 'contactus') { echo "<div class=\"ico\"></div>"; }?></a>
		</li>
		<li id="about_tab_jobs" <? if($page === 'jobs') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/about/jobs"><?=$this->lang->line('footer_jobs2_title');?><? if($page === 'jobs') { echo "<div class=\"ico\"></div>"; }?></a>
		</li>
		<?if($this->session->userdata('id')):?>
		    <li id="about_tab_bookmarklet" <? if($page === 'drop_it_button') { echo "class=\"about_tabs_selected\""; }?>>
			<a href="/about/drop_it_button"><?=$this->lang->line('footer_drop_it_btn');?><? if($page === 'drop_it_button') { echo "<div class=\"ico\"></div>"; }?></a>
		    </li>
		<? endif; ?>
		<?/*<li id="about_tab_privacy" <? if($page === 'privacy') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/about/privacy"><?=$this->lang->line('footer_about_privacy_title');?><? if($page === 'privacy') { echo "<div class=\"ico\"><div>"; }?></a>
		</li>*/ ?>
		<li id="about_tab_copyright" <? if($page === 'copyright') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/about/copyright"><?=$this->lang->line('copyright_privacy');?><? if($page === 'copyright') { echo "<div class=\"ico\"></div>"; }?></a>
		</li>
		<li id="about_tab_partners" <? if($page === 'partners') { echo "class=\"about_tabs_selected\""; }?>>
		    <a href="/about/partners"><?=$this->lang->line('footer_about_partners_title');?><? if($page === 'partners') { echo "<div class=\"ico\"></div>"; }?></a>
		</li>
	    </ul>
	</div>
	<div class="span18 offset1 about_contentColumn">
	    <? $this->load->view($page == 'drop_it_button' ? 'bookmarklet/walkthrough' : 'about/about_'.$page)?>
	</div>
    </div>
</div> 