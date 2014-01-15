<html>
<head>
</head>
<body style="background-color: #FAFAFA; width: 100%; margin: 0;">
    <div id="email_body">
	<table width="100%" cellpadding="10">
	    <tr style="background: #F5F5F5;">
		<td colspan="2"><a href="http://www.fandrop.com"><font face="lucida grande, helvetica, arial, sans-serif" size="6" color="#313232"><strong><img src="http://www.fandrop.com/images/fandropHeaderLogo_new.png" alt="Fandrop" title="Go To Fandrop" border="0" height="51" style="display: block;"/></strong></font></a></td>
	    </tr> 
	    <tr>
		<td>
		    <table cellspacing="10" style="color: #656565;font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif;">
			<tr>
			    <td></td>
			    <td>
			    	<blockquote>
					<?=$username;?>, found a list that may be interesting for you
					</blockquote>
				</td>
			</tr>
			<?php if ($message != "") : ?>
			<tr>
			    <td></td>
			    <td>
					<blockquote>
						<strong><?=$username;?> says:</strong><br />
						<i><?=$message;?></i>
					</blockquote>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
			    <td></td>
			    <td>
				<table width="320" bgcolor="#F0F0F0" style="color: #656565;font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif;">
				    <tr>
					<td>
					    <a href="<?=$folder_url;?>"><img src="<?=$image;?>" height="35"></a>
					</td>
					<td>
			    	    <a href="<?=$folder_url;?>" style="color: #3366CC; text-decoration: none; font-weight: bold;"><?=$folder_title;?></a>
					</td>
				    </tr>
				</table>
			    </td>
			</tr>		
			<tr>
			    <td></td>
			    <td>Thanks,</td>
			</tr>
			<tr>
			    <td></td>
			    <td>The Fandrop Team</td>
			</tr>
			<tr height="150">
			    <td></td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr style="background: #EDEDED;">
		<td style="color: #656565; font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif;text-shadow: 1px 1px #FFFFFF; line-height: 9px;">
		    <center>Copyright &copy;&nbsp; 2011 - 2012 . Fandrop - a product of <a href="<?=Url_helper::base_url();?>" style="color: #3366CC">Fantoon Labs</a></center><br>
		</td>
	    </tr>
	</table>
    </div>
</body>
</html>
