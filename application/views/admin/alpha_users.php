<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- begin of ( application/views/admin/alpha_users.php ) --> ' . "\n";
} ?>
<SCRIPT LANGUAGE="JavaScript">
<!-- 

<!-- Begin
function CheckAll(chk)
{
for (i = 0; i < chk.length; i++)
chk[i].checked = true ;
}

function UnCheckAll(chk)
{
for (i = 0; i < chk.length; i++)
chk[i].checked = false ;
}
// End -->
</script>

<? $this->lang->load('admin/admin_views', LANGUAGE); ?>
<div id="main">
	<div class="clear"></div>
	<div class="container_24">
		<? //print_r($users); ?> 
		<a href="/admin"><?=$this->lang->line('admin_views_back2_btn');?></a>
		<div class="clear"></div>
		<? $attributes = array('name' => 'alphaform', 'id' => 'alphaform'); 
		echo Form_Helper::form_open('send_alpha_email/', $attributes); ?>
		<ul>Waiting list
			<li>
				<div class="grid_6"><?=$this->lang->line('admin_views_name_lbl');?>:</div> 
				<div class="grid_7"><?=$this->lang->line('admin_views_email_lbl');?>:</div>
				<div class="grid_6"><?=$this->lang->line('admin_views_type_lbl');?>:</div>
				<div class="grid_2">
				<!--	<input type="button" name="Check_All" value="Check All" onClick="CheckAll(document.alpha_users.check_list)">	-->
				</div>
				<div class="clear"></div>
			</li>
		<? foreach($users as $key=>$user){ ?>
			<? if($user['check'] == 0){ ?>
				<? 
					if($user['first_name'] != '')
					{
						$user_name = $user['first_name'].' '.$user['last_name'];
						$type = $this->lang->line('admin_views_email_lbl');
					}
					elseif($user['t_name'] != '')
					{
						$user_name = $user['t_name'];
						$type = $this->lang->line('admin_views_tw_type');
					}
					elseif($user['fb_firstname'] != '')
					{
						$user_name = $user['fb_firstname'].' '.$user['fb_lastname'];
						$type = $this->lang->line('admin_views_fb_type');
					}
				?>
			
				<li>
					<div class="grid_6"><b><?=$user_name?></b></div> 
					<div class="grid_7"><?=$user['email']?></div>
					<div class="grid_6">(<?=$this->lang->line('admin_views_signup_with_lexicon');?> <?=$type?>)</div>
					<div class="grid_2"><? echo form_checkbox('alpha_users[]', $user['email'], TRUE); ?> </div>
					<div class="clear"></div>
				</li>	
			<? } ?>
		<? } ?>
		<li><? 	echo Form_Helper::form_submit('submit', $this->lang->line('admin_views_submit_btn'));
        		echo form_close();?></li>
		</ul>
		<ul><?=$this->lang->line('admin_views_invited_text');?>
			<li>
				<div class="grid_6"><?=$this->lang->line('admin_views_name_lbl');?>:</div> 
				<div class="grid_7"><?=$this->lang->line('admin_views_email_lbl');?>:</div>
				<div class="grid_6"><?=$this->lang->line('admin_views_type_lbl');?>:</div>
				<div class="grid_2"><?=$this->lang->line('admin_views_status_lbl');?></div>
				<div class="clear"></div>
			</li>
			<? foreach($users as $key=>$user){ ?>
				<? if($user['check'] == 1){ ?>
					<? 
						if($user['first_name'] != '')
						{
							$user_name = $user['first_name'].' '.$user['last_name'];
							$type = $this->lang->line('admin_views_email_lbl');
						}
						elseif($user['t_name'] != '')
						{
							$user_name = $user['t_name'];
							$type = $this->lang->line('admin_views_tw_type');
						}
						elseif($user['fb_firstname'] != '')
						{
							$user_name = $user['fb_firstname'].' '.$user['fb_lastname'];
							$type = $this->lang->line('admin_views_fb_type');
						}
					?>
				
					<li>
						<div class="grid_6"><b><?=$user_name?></b></div> 
						<div class="grid_7"><?=$user['email']?></div>
						<div class="grid_6">(<?=$this->lang->line('admin_views_signup_with_lexicon');?> <?=$type?>)</div>
						<div class="grid_2"><?=$user['status']?></div>
						<div class="clear"></div>
					</li>	
				<? } ?>
			<? } ?>
		</ul>
    </div>
</div> 
<? if ($this->is_mod_enabled('view_debug')) {
	echo '<!-- end of ( application/views/admin/alpha_users.php ) -->' . "\n";
} ?>
