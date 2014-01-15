<!-- V2 Michael, Please copy your html code and paste into this file. Include the html header and body. -->
<html>
<head>
</head>
<!--Special Header------->
<body style="background-color: #F5F5F5; width: 100%; margin: 0;">
    <div id="email_body">
	<table width="100%" cellpadding="20%">
	    <tr><td></td></tr>
	    <tr><td >
	    <!--Content Area------>
		<table width="700px" cellpadding="10" style="background: #F5F5F5; margin: 0 auto;">
		    <tr>
		    	<td>
					<table width="100%" cellpadding="0" style="border-collapse: collapse;">
					    <tr height="5">
							<td></td>
							<td></td>
							<td></td>
					    </tr>
					    <tr>
							<td></td>
							<td>
							    <table>
									<tr>
									    <td>
											<font face="lucida grande, helvetica, arial, sans-serif" size="6" color="#313232"><a href="http://www.fandrop.com"><img src="http://www.fandrop.com/images/newsletter_parts/header/fandropHeaderLogo.png" alt="Fandrop" title="Go To Fandrop" height="51" border="0" style="display: block; color: #313232;"/></a></font>
									    </td>
									</tr>
							    </table>
							</td>
							<td></td>
					    </tr>
					    <tr height="5">
							<td></td>
							<td></td>
							<td></td>
					    </tr>
					</table>
			    </td>
		    </tr>
			<tr>
				<td>
					<h2>You have a new message sent via contact form</h2>
					<p><strong>From: </strong> {from} &lt;{email}&gt; </p>
					<p><strong>Message: </strong>{message}</p>
				</td>
			</tr>
			</tr>
<!----------------------->
<!--Footer to Add with a call-->
			<tr style="background: #EDEDED;">
				<td style="color: #656565; font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif;text-shadow: 1px 1px #FFFFFF; line-height: 12px;">
				    <center>Copyright &copy;&nbsp; 2011 - <?=date("Y");?> . <a href="http://www.fandrop.com" style="color: #3366CC">Fandrop Inc</a></center><br>
				</td>
			</tr>
		</table>
		</td></tr>
	</table>
    </div>
</body>
</html>