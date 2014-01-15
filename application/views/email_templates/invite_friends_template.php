<? include( APPPATH.'views/email_templates/email_header.php' ); ?>
<? $this->lang->load('email_templates/email_templates_views', LANGUAGE); ?>
	    <tr>
		<td>
		    <table cellspacing="10" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">
			<tr>
			    <td></td>
			    <td>
				<!--Dear {full_name}-->
			    </td>
			</tr>
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_hi_there_lexicon');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td>{message}
			    </td>
			</tr>
			<tr>
			    <td></td>
			    <td></td>
			</tr>
			<tr>
			    <td></td>
			    <td><?=$this->lang->line('email_templates_views_thx_lexicon');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td>{invitor}</td>
			</tr>
			<tr>
			    <td></td>
			    <td></td>
			</tr>
			<tr>
			    <td></td>
			    <td style="font-size: 11px;font-weight: bold;"><?=$this->lang->line('email_templates_views_click_text');?></td>
			</tr>
			<tr>
			    <td></td>
			    <td style="background: #C9D9F9; padding: 10px 15px; border: 1px solid #B9C9F0;border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;">
				<a href="{alpha_link}" style="color: #3366CC">
				    <?=$this->lang->line('email_templates_views_activate');?>
				</a>
			    </td>
			</tr>
			<tr height="150">
			    <td></td>
			</tr>
		    </table>
		</td>
	    </tr>
	    
<? include( APPPATH.'views/email_templates/email_footer.php' ); ?> 
