<?=$this->load->view('email_templates/email_header','',true ); ?>
<? $this->lang->load('email_templates/email_templates_views', LANGUAGE); ?>
	    <tr>
		<td>
		    <table cellspacing="10" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">
			<?=$this->load->view('email_templates/email_content_common', '', true); ?>
			<tr>
			    <td></td>
			    <td>
			    <?=$this->lang->line('email_templates_views_cheers_lexicon');?><br>
			    <?=$this->lang->line('email_templates_views_team_text');?>
			    </td>
			</tr>
			<tr height="150">
			    <td></td>
			</tr>
		    </table>
		</td>
	    </tr>
<?=$this->load->view('email_templates/email_footer','',true ); ?> 
