<div class="<? /* ?>demo_topBox<? */ ?> cite_topBox" style="margin: 20px; auto;">
<?php if (!$this->input->get('filter') || $this->input->get('filter') == 'Sponsors') { ?>
	<h2>SPONSOR TAB</h2>
	<? if ($folder->can_edit(@$this->user->id)) { ?>
		<?php $this->load->view('folder/contest_popup')?>
		<a href="#edit_folder_popup" rel="popup" title="Edit" data-title="<?=$this->lang->line('folder_edit_link_title');?>" 
			class="edit_folder_btn edit_button folder_edit_btn standalone_btn"
			<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'ends_at', 'info', 'is_open'))?>
		>
			<?=$this->lang->line('folder_edit_link_btn');?>
		</a>
		<a href="#delete_folder" rel="popup" class="del_folder_btn del_button folder_del_btn standalone_btn" data-folder_id="<?=$folder->folder_id?>">
			<?=$this->lang->line('folder_delete_collection_btn');?>
		</a>
	<? } ?>
	
	<ul class="contestPrize_list">
		<li style="display:none;"></li>
		<li class="contestPrize_item">CITE Conference & Expo sponsors share their views on current and future trends on mobility, BYOD, the social enterprise and host of other topics related to consumerization of IT in the enterprise.</li>
	</ul>
<?php } if (!$this->input->get('filter') || $this->input->get('filter') == 'Attendees') { ?>
	<h2>ATTENDEE TAB</h2>
	<div class="contest-blurb">
		<ul class="contestPrize_list">
			<li class="contestPrize_item">Attendees to the CITE Conference & Expo take a break to answer some fun and interesting questions about tech products on their personal and business short list; preferred tablet vendor; favorite classic video game and much more!</li>
		</ul>
	</div>
<?php } ?>
</div>
<?php if (isset($include_folder_top)) { ?>
	<?=Html_helper::stylesheet('/NEW/profile/profile')?>
	<?=Html_helper::requireJS(array('folder/folder'))?>
	<div id="folder_top" class="top_container sxswTop_Container" style="display:none">
		<div class="sxswParagraph sxswIndent">
			<div id="contest-prizes" style="display:none">
				<h1>How It Works</h1>
				<div class="contest-blurb">
					<ul class="contestPrize_list">
						<li class="contestPrize_item">Each startup gets points for views and shares they receive from their fans. Each unique view = 1 point and tweet = 10 points.</li>
						<li><small>*please note: points from views are verified and do not appear immediately.</small></li>
					</ul>
				</div>
			</div>
			<div id="contest-sponsors" style="display:none">
				<h1>Press</h1>
				<div class="contest-sponsors_sponsorContainer">
					<div class="contest-sponsors_pressContainer">
					<!--
						<a href="http://www.sfgate.com/business/prweb/article/DEMO-MOBILE-Hosts-Startup-Pitch-Contest-at-MBCC-4444531.php">http://www.sfgate.com/business/prweb/article/DEMO-MOBILE-Hosts-Startup-Pitch-Contest-at-MBCC-4444531.php</a>
						<br><br>
						<a href="http://sip-trunking.tmcnet.com/news/2013/04/18/7072000.htm">http://sip-trunking.tmcnet.com/news/2013/04/18/7072000.htm</a>
						<br><br>
						<a href="http://www.idgenterprise.com/press/demo-mobile-2013-conference-launches-latest-mobile-innovations-for-consumer-enterprise-social-commerce-landscapes">http://www.idgenterprise.com/press/demo-mobile-2013-conference-launches-latest-mobile-innovations-for-consumer-enterprise-social-commerce-landscapes</a>
						<br><br>
						<a href="http://online.wsj.com/article/PR-CO-20130417-917252.html?mod=googlenews_wsj">http://online.wsj.com/article/PR-CO-20130417-917252.html?mod=googlenews_wsj</a>
						<br><br>
						<a href="http://article.wn.com/view/2013/04/18/DEMO_MOBILE_Hosts_Startup_Pitch_Contest_at_MBCC/#/related_news">http://article.wn.com/view/2013/04/18/DEMO_MOBILE_Hosts_Startup_Pitch_Contest_at_MBCC/#/related_news</a>
						<br><br>
						<a href="http://sip-trunking.tmcnet.com/news/2013/04/17/7070629.htm">http://sip-trunking.tmcnet.com/news/2013/04/17/7070629.htm</a>
						<br><br>
						<a href="http://www.computershoptoday.com/7339/demo-mobile-hosts-startup-pitch-contest-at-mbcc-2/">http://www.computershoptoday.com/7339/demo-mobile-hosts-startup-pitch-contest-at-mbcc-2/</a>
						<br><br>
						<a href="http://www.financialeveryday.com/demo-mobile-2013-conference-launches-latest-mobile-innovations-for-consumer-enterprise-social-commerce-landscapes/">http://www.financialeveryday.com/demo-mobile-2013-conference-launches-latest-mobile-innovations-for-consumer-enterprise-social-commerce-landscapes</a>
						<br><br>
						<a href="http://www.streetinsider.com/press+releases/corenet">http://www.streetinsider.com/press+releases/corenet</a>
						<br><br>
						<a href="http://www.so-co-it.com/post/250184/demo-mobile-2013-conference-launches-latest-mobile-innovations-for-consumer-enterprise-social-commerce-landscapes.html/">http://www.so-co-it.com/post/250184/demo-mobile-2013-conference-launches-latest-mobile-innovations-for-consumer-enterprise-social-commerce-landscapes.html/</a>
						<br><br>
						<a href="http://www.firmenpresse.de/pressrelease250184.html">http://www.firmenpresse.de/pressrelease250184.html</a>
						<br><br>
						<a href="http://www.iconperformingarts.com/component/option,com_newsfeeds/task,view/feedid,14/Itemid,135/">http://www.iconperformingarts.com/component/option,com_newsfeeds/task,view/feedid,14/Itemid,135/</a>
						<br><br>
						<a href="http://www.businesspress24.com/pressrelease1217552.html">http://www.businesspress24.com/pressrelease1217552.html</a>
						-->
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>