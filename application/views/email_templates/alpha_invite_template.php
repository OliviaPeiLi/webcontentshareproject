<?=$this->load->view('email_templates/email_header','',true ); ?>
<? $this->lang->load('email_templates/email_templates_views', LANGUAGE); ?>
	    <tr style="background: #FFFFFF;">
		<td>
		    <table cellspacing="10" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_welcome_msg');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_invite_msg');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_fandrop_rules');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td>
				<?=$this->lang->line('email_templates_views_email_us_text');?>
			    </td>
			</tr>
			<tr>
			    <td></td>
			    <td>
			    <?=$this->lang->line('email_templates_views_drop_it');?><br>
			    <?=$this->lang->line('email_templates_views_team_text');?>
			    </td>
			</tr>
			<?=$this->load->view('email_templates/email_templates_views_click_text', '', true); ?>
			<tr>
			    <td></td>
			    <td></td>
			</tr>
			<?=$this->load->view('email_templates/email_templates_views_activate', '', true); ?>
			<tr height="150">
			    <td></td>
			</tr>
		    </table>
		</td>
	    </tr>
	    
<?=$this->load->view('email_templates/email_footer','',true ); ?> 
