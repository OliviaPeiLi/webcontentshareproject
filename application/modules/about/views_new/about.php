<? $this->lang->load('about/footer', LANGUAGE); ?>
<div id="about_container" class="container_24" style="font-family: Arial,Helvetica,sans-serif; font-size:18px;">
    <div class="grid_24">
        <div id="about_title">
            About <? if($page === 'contactus') { echo ' / '.ucwords($this->lang->line('contact_us'));}else if($page === 'jobs') { echo ' / '.$this->lang->line('footer_jobs2_title');} ?>
        </div>
        <div class="grid_4 alpha border"></div>
    </div>
    <div class="grid_4">
        <ul id="about_tabs">
            <li id="about_tab_main" <? if($page === 'main') { echo "class=\"about_tabs_selected\""; }?>>
                <a href="/about/"><?=$this->lang->line('footer_main_title');?></a>
                <? if($page === 'main') { echo "<div class=\"special_effects\"></div>"; }?>
            </li>
	<!--   	<li id="about_tab_team" <? if($page === 'team') { echo "class=\"about_tabs_selected\""; }?>>
                <a href="/about/team"><?=$this->lang->line('footer_about_team_title');?></a>
                <? if($page === 'team') { echo "<div class=\"special_effects\"></div>"; }?>
            </li>-->
            <li id="about_tab_contactus" <? if($page === 'contactus') { echo "class=\"about_tabs_selected\""; }?>>
                <a href="/about/contactus"><?=ucwords($this->lang->line('contact_us'));?></a>
                <? if($page === 'contactus') { echo "<div class=\"special_effects\"></div>"; }?>
            </li>
            <li id="about_tab_jobs" <? if($page === 'jobs') { echo "class=\"about_tabs_selected\""; }?>>
                <a href="/about/jobs"><?=$this->lang->line('footer_jobs2_title');?></a>
                <? if($page === 'jobs') { echo "<div class=\"special_effects\"></div>"; }?>
            </li>
            <?if($this->session->userdata('id')):?>
    	    	<li id="about_tab_bookmarklet" <? if($page === 'drop_it_button') { echo "class=\"about_tabs_selected\""; }?>>
                    <a href="/about/drop_it_button"><?=$this->lang->line('footer_drop_it_btn');?></a>
                    <? if($page === 'drop_it_button') { echo "<div class=\"special_effects\"></div>"; }?>
                </li>
            <? endif; ?>
            <?/*<li id="about_tab_privacy" <? if($page === 'privacy') { echo "class=\"about_tabs_selected\""; }?>>
                <a href="/about/privacy"><?=$this->lang->line('footer_about_privacy_title');?></a>
                <? if($page === 'privacy') { echo "<div class=\"special_effects\"><div>"; }?>
            </li>*/ ?>
            <li id="about_tab_copyright" <? if($page === 'copyright') { echo "class=\"about_tabs_selected\""; }?>>
                <a href="/about/copyright"><?=$this->lang->line('footer_about_copyright_and_privacy_title');?></a>
                <? if($page === 'copyright') { echo "<div class=\"special_effects\"></div>"; }?>
            </li>
	    <li id="about_tab_partners" <? if($page === 'partners') { echo "class=\"about_tabs_selected\""; }?>>
                <a href="/about/partners"><?=$this->lang->line('footer_about_partners_title');?></a>
                <? if($page === 'partners') { echo "<div class=\"special_effects\"></div>"; }?>
            </li>
        </ul>
    </div>
    <div class="grid_19 prefix_1 omega">
        <? $this->load->view($page == 'drop_it_button' ? 'bookmarklet/walkthrough' : 'about/about_'.$page)?>
    </div>
</div> 