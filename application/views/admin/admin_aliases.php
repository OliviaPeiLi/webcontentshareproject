<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/admin/admin_aliases.php ) --> ' . "\n";
	} ?>
<? $this->lang->load('admin/admin_views', LANGUAGE); ?>
<div id="main">
	<div class="clear"></div>
	<div class="container_24">
		<div><?=$this->lang->line('admin_views_aliases_heading');?></div>
		<div>
			<ul>
			<? foreach($aliases as $a){ ?>
				<? 	if($a['page_thumb'] == '')
					{
						$page_img = s3_url().'pages/default/defaultInterest/'.$a['interest_id'].'.png';
					}
					else
					{
						$page_img = s3_url().$a['page_thumb'];
					}
					if($a['official_url'] == '')
					{
						$link = '<a href="/interests/'.$a['page_uri'].'/'.$a['page_id'].'">'.$a['page_name'].'</a>';
					}
					else
					{
						$link = '<a href="/'.$a['official_url'].'">'.$a['page_name'].'</a>';
					}
					if($a['user_thumb'] == '')
					{
						if($a['gender'] == 'm')
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
						$user_img = s3_url().$a['user_thumb'];
					}
				?>
				<li>
					<div class="grid_2"><img src="<?=$user_img?>" width=30></div>
					<div class="grid_2"><?=$this->lang->line('admin_views_user_id_lbl');?>: <b></b><?=$a['user_id']?></div> 
					<div class="grid_3"><b><?=$a['first_name'].' '.$a['last_name']?></b></div>
					<div class="grid_2"><img src="<?=$page_img?>" width=30></div>
					<div class="grid_3"><?=$this->lang->line('admin_views_page_id_lbl');?>: <b><?=$a['page_id']?></b></div>
					<div class="grid_3"><?=$this->lang->line('admin_views_page_name_lbl');?>: <b><?=$a['page_name']?></b></div>
					<div class="grid_3"><?=$this->lang->line('admin_views_alias_lbl');?>: <b><?=$a['alias']?></b></div>
					<div class="grid_1"><?=$this->lang->line('admin_views_count_lbl');?> <b><?=$a['hits']?></b></div>
					<div class="grid_3"><a href="/proc_alias/<?=$a['r_id']?>/approve"><?=$this->lang->line('admin_views_approve_btn');?></a></div>
					<div class="grid_3"><a href="/proc_alias/<?=$a['r_id']?>/deny"><?=$this->lang->line('admin_views_deny_btn');?></a></div>
					<div class="clear"></div>
				</li>
			<? } ?>
			</ul>
		</div>
		<div><a href="/admin"><?=$this->lang->line('admin_views_back_btn');?></a></div>
	</div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/admin/admin_aliases.php ) -->' . "\n";
} ?>
