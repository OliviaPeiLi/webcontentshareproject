<? $this->lang->load('folder/folder', LANGUAGE); ?>
<? $this->load->view('fantoon-extensions/contest/top')?>

<? if ($contest->url == 'demo') { ?>
	<? $this->load->view('contest/topbox_demo')?>
<? } else if ($contest->url == 'fndemo') { ?>
	<? $this->load->view('contest/topbox_fndemo')?>
<? } else if ($contest->url == 'crowdfunderio') { ?>
	<? $this->load->view('contest/topbox_crowdfund')?>
<? } else if ($contest->url == 'cite') { ?>
	<? $this->load->view('contest/topbox_cite')?>
<?php } else { ?>
	<div id="folder_top" class="top_container sxswTop_Container">
		<div class="sxswParagraph">
			<h1><?=$folder->folder_name?></h1>
			<? if ($folder->can_edit(@$this->user->id)) { ?>
				<?php $this->load->view('folder/contest_popup')?>
				<a href="#edit_folder_popup" rel="popup" title="Edit" data-title="<?=$this->lang->line('folder_edit_link_title');?>" 
					class="edit_folder_btn edit_button folder_edit_btn standalone_btn"
					<?=Html_helper::item_data($folder, array('folder_id', 'folder_name', 'info', 'is_open'))?>
					data-ends_at = "<?=date("m/d/Y H:i:s",strtotime($folder->ends_at))?>"
				>
					<?=$this->lang->line('folder_edit_link_btn');?>
				</a>
				<a href="#delete_folder" rel="popup" class="del_folder_btn del_button folder_del_btn standalone_btn" data-folder_id="<?=$folder->folder_id?>">
					<?=$this->lang->line('folder_delete_collection_btn');?>
				</a>
			<? } ?>
		</div>
		<div class="sxswParagraph sxswIndent" >
			<h2>Vote for your favorite video. <span class="ends_at" style="<?=$folder->ends_at!='0000-00-00 00:00:00' ? '' : 'display:none'?>">(Ends <span><?=$folder->ends_at_formatted?></span>)</span></h2>
			<div class="info">
				<?=$folder->info?>
			</div>
		</div>
	</div>
<?php } ?>
<ul id="list_options">
	<? /* ?>
	<li class="sxswSort_title">Sort By</li>
	<li style="display:inline-block" class="<?=($this->input->get('sort_by') == 'share' || !$this->input->get('sort_by'))?'active':''?>">
		<a href="?sort_by=<?=in_array($contest->url, array('fndemo','crowdfunderio','cite')) ? 'points' : 'share'?>">
	       <? if(!$this->input->get('sort_by') || $this->input->get('sort_by') == 'share'){ ?>
		       <span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
	       <? } else { ?>
		       <span class="non_current_item"></span>
	       <? } ?>
	       <?=in_array($contest->url, array('fndemo','crowdfunderio','cite')) ? 'Points Count' : 'Share Count'?>
		</a>
	</li>
	<li style="display:inline-block" class="<?=$this->input->get('sort_by') == 'time'?'active':''?>">
		<a href="?sort_by=time">
	       <? if($this->input->get('sort_by') == 'time'){ ?>
		       <span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
	       <? } else { ?>
		       <span class="non_current_item"></span>
	       <? } ?>
	       Time
		</a>
	</li>
	<li style="display:inline-block" class="<?=$this->input->get('sort_by') == 'title'?'active':''?>">
		<a href="?sort_by=title&order=ASC">
		    <? if($this->input->get('sort_by') == 'title'){ ?>
				<span class="current_item_icon"><span class="current_item_icon_contents"></span></span>
		    <? } else { ?>
				<span class="non_current_item"></span>
		    <? } ?>
		    Title
		 </a>
	</li><? */ ?>
	<li>
		<a href="javascript:;" class="ft-dropdown ft-dropdown-hover" rel="contest_filter_menu">
			Sort By <span class="ico"></span><?=$this->input->get('filter') ? ': '.$this->input->get('filter') : ''?>
		</a>
		<ul id="contest_filter_menu" style="display:none">
			<?php foreach ($folder->filters as $filter) { ?>
			<li><a href="?filter=<?=$filter?>"><?=$filter?></a>
			<?php } ?>
		</ul>
	</li>
</ul>
<?php if ($folder->can_add($this->user)) { ?>
	<div class="submitStartup_container">
		<?php /*<div class="submitStartup_text">You can upload your own video here without having to be interviewed.</div>*/ ?>
		<a href="/<?=$contest->url?><?=$contest->is_simple ? '' : '/'.$folder->folder_uri_name?>/submit" class="submitStartup_button red_bg red_bg_tall">Submit Your Video</a>
	</div>
<? } ?>
<script type="text/javascript">
	<? if ($this->user) { ?> 
		php.back_url = '/contests/<?=$this->user->uri_name?>';
	<? } ?>
</script>