<? $this->lang->load('about/footer', LANGUAGE); ?>
<div id="about_specialHeader" class="about_partnersPage">
	<span class="specialHeader_backgroundImage"></span>
	<div class="container specialHeader_container">
		<span class="specialHeader_textImage"></span>
		<div class="specialHeader_text"><?=$this->lang->line('footer_about_partners_special_heading');?></div>
		<div id="partners_form" class="about_emailForm">
			<?=Form_Helper::open()?>
			<form>
				<input type="text" placeholder="Email:">
				<input type="submit" value="Sign Up" class="blueButton about_signUp">
			</form>
			<?=Form_Helper::close()?>
		</div>
	</div>
</div>
<div id="about_specialBody" class="">
	<div class="about_specialBody_text">
		<?=$this->lang->line('footer_about_partners_special_text');?>
	</div>
	<div class="about_specialIcon">
		<div class="specialIcon_ideas specialIcon_unit inlinediv">
			<div class="specialIcon_icon">
				
			</div>
			<div class="specialIcon_text"><?=$this->lang->line('footer_about_partners_special_ideas');?></div>
		</div>
		<div class="specialIcon_parameters specialIcon_unit inlinediv">
			<div class="specialIcon_icon">
				
			</div>
			<div class="specialIcon_text"><?=$this->lang->line('footer_about_partners_special_params');?></div>
		</div>
		<div class="specialIcon_launch specialIcon_unit inlinediv">
			<div class="specialIcon_icon">
				
			</div>
			<div class="specialIcon_text"><?=$this->lang->line('footer_about_partners_special_launch');?></div>
		</div>
	</div>
	<div class="about_specialHrule"></div>
	<div class="aboutPartners">
		<div class="aboutPartners_text"><?=$this->lang->line('footer_about_companies_lexicon');?></div>
		<div class="aboutPartners_container">
			<? /* ?>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-1000placestofight.png">
				</span>
			</a>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-knightwing.png">
				</span>
			</a><? */ ?>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-publicenemy.png">
				</span>
			</a><? /* ?>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-pickyeater.png">
				</span>
			</a>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-travelocafe.png">
				</span>
			</a><? */ ?>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-venturebeat.png">
				</span>
			</a><? /* ?>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-backpackmatt.png">
				</span>
			</a>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-gqtrippin.png">
				</span>
			</a>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-jackfroot.png">
				</span>
			</a><? */ ?>
			<a class="aboutPartners_partnerUnit">
				<span class="aboutPartners_partnerLogo">
					<img src="/images/about_user_case/new_partnersPress/logo-justkiddingfilms.png">
				</span>
			</a>
		</div>
	</div>
</div>
<div id="about_bottomTabs_container">
	<span class="about_moreText">More:</span>
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