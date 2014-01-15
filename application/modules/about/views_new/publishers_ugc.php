<? $this->lang->load('about/footer', LANGUAGE); ?>
<div id="about_specialHeader" class="about_publishersPage">
	<span class="specialHeader_backgroundImage"></span>
	<div class="container specialHeader_container">
		<div class="specialheaderIcon"></div>
		<span class="specialHeader_textImage"></span>
		<div id="publishers_form" class="about_emailForm">
			<?=Form_Helper::open('', array( 'rel'=>'ajaxForm', 'class'=>'public', 'data-error'=>'popup' ) )?>
				<span class="error"></span>
				<input type="text" name="url" placeholder="<?=$this->lang->line('url');?>">
				<input type="submit" name="submit" value="<?=$this->lang->line('footer_publishers_signup_submit');?>" class="blueButton about_signUp">
			<?=Form_Helper::close()?>
		</div>
		<div id="thankyou-msg" style="display:none">
			<?=$this->lang->line('thank_you');?>!
		</div>
	</div>
</div>
<div id="about_specialBody" class="">
	<div class="about_specialBody_text">
		<?=$this->lang->line('footer_publishers_special_text');?>
	</div>
	<div class="about_specialIcon">
		<div class="specialIcon_site specialIcon_unit inlinediv">
			<div class="specialIcon_icon">
				
			</div>
			<div class="specialIcon_text"><?=$this->lang->line('footer_publishers_special_site');?></div>
		</div>
		<div class="specialIcon_api specialIcon_unit inlinediv">
			<div class="specialIcon_icon">
				
			</div>
			<div class="specialIcon_text"><?=$this->lang->line('footer_publishers_special_api');?></div>
		</div>
		<div class="specialIcon_earn specialIcon_unit inlinediv">
			<div class="specialIcon_icon">
				
			</div>
			<div class="specialIcon_text"><?=$this->lang->line('footer_publishers_special_earn');?></div>
		</div>
	</div>
	<div class="about_specialHrule"></div>
	<div class="aboutPublishers">
		<div class="aboutPublishers_text"><?=$this->lang->line('footer_publishers_trusted_title');?></div>
		<div class="aboutPublishers_container container">
			<div class="row aboutPublishers_row">
				<div class="span6">
					<img src="/images/about_user_case/new_partnersPress/logo-boston.png">
				</div>
				<div class="span6">
					<img src="/images/about_user_case/new_partnersPress/logo-cbsnews.png">
				</div>
				<div class="span6">
					<img src="/images/about_user_case/new_partnersPress/logo-pandodaily.png">
				</div>
				<div class="span6">
					<img src="/images/about_user_case/new_partnersPress/logo-sfgate.png">
				</div>
			</div>
			<div class="row aboutPublishers_row">
				<div class="span6">
					<img src="/images/about_user_case/new_partnersPress/logo-techcrunch.png">
				</div>
				<div class="span6">
					<img src="/images/about_user_case/new_partnersPress/logo-techvibes.png">
				</div>
				<div class="span12">
					<img src="/images/about_user_case/new_partnersPress/logo-wallstreetjournal.png">
				</div>
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
<?=Html_helper::requireJS(array('about/promoters'))?>