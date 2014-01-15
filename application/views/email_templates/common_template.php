<?=$this->load->view('email_templates/email_header','',true ); ?>

	    <tr>
		<td>
		    <table cellspacing="10" style="color: #656565;font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif;">
			
			<tr>
			    <td></td>
			    <td>{message}</td>
			</tr>

			
			<tr height="150">
			    <td></td>
			</tr>
		    </table>
		</td>
	    </tr>

<?=$this->load->view('email_templates/email_footer','',true ); ?> 
