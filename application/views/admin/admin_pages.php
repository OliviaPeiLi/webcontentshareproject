<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/admin/admin_pages.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('admin/admin_views', LANGUAGE); ?>
<div id="main">
	<div class="clear"></div>
	<div class="container_24">
		<div><?=$this->lang->line('admin_views_interests_heading');?></div>
		<div>
			<ul>
			<? foreach($pages as $p){ ?>
				<? 	if($p['page_thumb'] == '')
					{
						$img = s3_url().'pages/default/defaultInterest/'.$p['interest_id'].'.png';
					}
					else
					{
						$img = s3_url().$p['page_thumb'];
					}
					$link = '<a href="/make_official/'.$p['page_id'].'/1">'.$this->lang->line('admin_views_approve_btn').'</a>';
					$de_link = '<a href="/make_official/'.$p['page_id'].'/0">'.$this->lang->line('admin_views_deny_btn').'</a>';
					if($p['user_thumb'] == '')
					{
						if($p['gender'] == 'm')
						{
							$user_img = s3_url()."users/default/defaultMale.png";
						}
						else
						{
							$user_img = s3_url()."users/default/defaultFemale.png";	
						}
					}
					else
					{
						$user_img = s3_url().$p['user_thumb'];
					}
				?>
				<li>
					<div class="grid_2"><img src="<?=$user_img?>" width=30></div>
					<div class="grid_2"><?=$this->lang->line('admin_views_user_id_lbl');?>: <b></b><?=$p['user_id']?></div> 
					<div class="grid_3"><b><?=$p['first_name'].' '.$p['last_name']?></b></div>
					<div class="grid_2"><img src="<?=$img?>" width=30></div>
					<div class="grid_3"><?=$this->lang->line('admin_views_page_id_lbl');?>: <b><?=$p['page_id']?></b></div>
					<div class="grid_3"><?=$this->lang->line('admin_views_page_name_lbl');?>: <b><?=$p['page_name']?></b></div>
					<div class="grid_3"><?=$this->lang->line('admin_views_new_name_lbl');?>: <b><?=$p['new_name']?></b></div>
					<div class="grid_1"><?=$this->lang->line('admin_views_count_lbl');?> <b><?=$p['hits']?></b></div>
					<div class="grid_2"><?=$link?></div>
					<div class="grid_2"><?=$de_link?></div>
					<div class="clear"></div>
				</li>
			<? } ?>
			</ul>
		</div>
		<div><a href="/admin"><?=$this->lang->line('admin_views_back_btn');?></a></div>
	</div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/admin/admin_pages.php ) -->' . "\n";
} ?>
