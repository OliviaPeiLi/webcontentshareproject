<?=$this->load->view('email_templates/email_header','',true ); ?>
<? $this->lang->load('email_templates/email_templates_views', LANGUAGE); ?>
	    <tr>
		<td>
		    <table cellspacing="10" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">
			<tr>
			    <td colspan="3">
				<?=$this->lang->line('email_templates_views_dear_lexicon');?> {user_name},
			    </td>
			</tr>
			<tr>
			    <td colspan="2">
				<?=$this->lang->line('email_templates_views_welcome_text');?>
			    </td>
			</tr>
			<tr>
			    <td colspan="2">
				<?=$this->lang->line('email_templates_views_sxsw_invite_msg');?>
			    </td>
			</tr>
			<tr>
			    <td colspan="2">
			    <?=$this->lang->line('email_templates_views_not_work_text');?>
			    </td>
			</tr>
			<tr>
			    <td><?=$this->lang->line('email_templates_views_thx_lexicon');?></td>
			</tr>
			<tr>
			    <td><?=$this->lang->line('email_templates_views_team_text');?></td>
			</tr>
			<tr height="30">
			    <td></td>
			</tr>
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
	    
	    <tr style="background: #EDEDED;">
		<td style="color: #656565; font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif;text-shadow: 1px 1px #FFFFFF;">
		    <center><?=$this->lang->line('email_templates_view_footer_copyright');?></center>
		</td>
	    </tr>
	</table>
    </div>
</body>
</html>
