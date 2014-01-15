<? $this->lang->load('profile/profile', LANGUAGE); ?>
<?=$this->load->view('contest/top')?>
	
<div id="container" class="main_profile container_24">
	<div id="main_profile_content" class="grid_24">
		<?php if ($contest->url == 'demo') { ?>
			<? $this->load->view('contest/topbox_demo', array('include_folder_top'=>true))?>
		<?php } elseif ($contest->url == 'fndemo') { ?>
			<? $this->load->view('contest/topbox_fndemo', array('include_folder_top'=>true))?>
		<?php } elseif ($contest->url == 'crowdfunderio') { ?>
			<? $this->load->view('contest/topbox_crowdfund', array('include_folder_top'=>true))?>
		<?php } elseif ($contest->url == 'cite') { ?>
			<? $this->load->view('contest/topbox_cite', array('include_folder_top'=>true))?>
		<?php } ?>
		<div class="comments" id="comments_mid">
			<div id="show_newsfeeds">
				<?=Modules::run('profile/profile_folder/contest_collections', $contest->id)?>
			</div>
		</div> <!-- End comments -->
		<?php if ($contest->url == 'demo') { ?>
			<div style="text-align: center">
				<?=Html_helper::img('contestFiles/demoMobile/infographic.png')?>
			</div>
		<?php } ?>
	</div> <!--  End profile_main_content -->
</div>

<script type="text/javascript">
	
</script>
<?//=Html_helper::requireJS(array("profile/profile"))?> 
