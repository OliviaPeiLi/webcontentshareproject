    <? $this->lang->load('includes/includes_views', LANGUAGE); ?>
	<li class="hdrnav_item">
    	<a href="#get_bookmarklet_dialog" id="add_collect" rel="popup" data-position="top" class="test_addcollect <?=$this->session->userdata('invite_more')? 'addCollect_highlight' : ''?>"><?=$this->lang->line('includes_views_add_btn');?></a>
    	<? if ($this->session->userdata('invite_more')) { ?>
			<div id="dropit-message" class="tab_label_2">
				<span class="left"></span>
				Behold, the Drop It! button...
				<a href="" class="close_btn"></a>
			</div>
		<? } ?>
    </li>