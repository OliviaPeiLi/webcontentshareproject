<?=$this->load->view('email_templates/email_header','',true ); ?>
<? $this->lang->load('email_templates/email_templates_views', LANGUAGE); ?>
	    <tr>
		<td>
		    <table cellspacing="10" style="color: #656565;font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif;">
			<tr>
			    <td></td>
			    <td>
				<?=$this->lang->line('email_templates_views_dear_lexicon');?> {user_name}<? /* NAME VARIABLE GOES HERE] */ ?>,
			    </td>
			</tr>
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_reset_pass');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td>
				<a href="{reset_link}" style="color: #3366CC;">{reset_link}</a>
			    </td>
			</tr>
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_hope_text');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_sincerely_lexicon');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_team_text');?></td>
			</tr>
			<tr height="150">
			    <td></td>
			</tr>
		    </table>
		</td>
	    </tr>

<?=$this->load->view('email_templates/email_footer','',true ); ?> 
