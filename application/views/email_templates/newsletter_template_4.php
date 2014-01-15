<!-- V2 Michael, Please copy your html code and paste into this file. Include the html header and body. -->
<html>
<head>
    
</head>
<!--Special Header------->
<body style="background-color: #E6EAED; width: 100%; margin: 0;">
    <div id="email_body">
	<table width="100%" cellpadding="20%" style="background: #E6EAED;">
	    <tr><td></td></tr>
	    <tr><td >
	    <!--Content Area------>
		<table width="700px" cellpadding="0" style="background: #FFFFFF; border: 1px solid #D1D3D4; border-collapse: collapse; margin: 0 auto;">
		    <tr>
		    	<td>
			    <table width="100%" cellpadding="5" style="border-collapse: collapse;">
				<tr><td></td></tr>
				<tr>
				    <td>
					<table width="100%" cellpadding="0" style="border-collapse: collapse;">
					    <tr>
						<td width="20"></td>
						<td>
						    <table>
							<tr>
							    <td>
								<font face="lucida grande, helvetica, arial, sans-serif" size="6" color="#313232"><a href="http://www.fandrop.com"><img src="http://www.fandrop.com/images/newsletter_parts/header/fandropHeaderLogo.png" alt="Fandrop" title="Go To Fandrop" height="51" border="0" style="display: block; color: #313232;"/></a></font>
							    </td>
							    <td>
								<font face="lucida grande, helvetica, arial, sans-serif" size="6" color="#656565"><a href="http://www.fandrop.com"><img src="http://www.fandrop.com/images/newsletter_parts/header/fandropHeaderDigest2.png" alt="Digest" title="Go To Fandrop" height="51" border="0" style="display: block; color: #656565;"/></a></font>
							    </td>
							</tr>
						    </table>
						</td>
						<td width="74">
						    <table width="100%" cellpadding="0" cellspacing="0">
							<tr>
							    <td>
								<font face="lucida grande, helvetica, arial, sans-serif" size="1" color="#313282"><a href="https://www.facebook.com/thefandrop"><img src="http://michael.fantoon.com/images/newsletter_parts/buttons/fbNewsletterButton_large.png" alt="Facebook" title="Visit Our Facebook" height="33" width="33" border="0" style="display: block; color: #313232;"/></a></font>
							    </td>
							    <td width="5"></td>
							    <td>
								<font face="lucida grande, helvetica, arial, sans-serif" size="1" color="#31D2D2"><a href="https://twitter.com/fandrop"><img src="http://michael.fantoon.com/images/newsletter_parts/buttons/twitNewsletterButton_large.png" alt="Twitter" title="Visit Our Twitter" height="33" width="33" border="0" style="display: block; color: #313232;"/></a></font>
							    </td>
							</tr>
						    </table>
						</td>
						<td width="20"></td>
					    </tr>
					</table>
				    </td>
				</tr>
				<tr><td></td></tr>
			    </table>
			</td>
		    </tr>
		    
		    <tr>
		    	<td><!--Content Goes Here------>
			    <table width="100%" style="border-bottom:1px solid #e6e6e6"></table>
				<tr><!--Here at Fandrop------>
				    <td style="padding-bottom: 0px;">
					<table width="100%" cellpadding="5" cellspacing="0" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif; border-collapse: collapse;">
					    <tr>
						<td>
						    <table cellpadding="0" style="border-collapse: collapse;">
							<tr><td style="height:15px"></td></tr>
							<tr>
							    <td width="20"></td>
							    <td style="color: #3D3D3D; font-size: 18px; font-weight: bold;">Here at Fandrop...</td>
							</tr>
						    </table>
						</td>
					    </tr>
					    <tr>
						<td>
						    <table cellpadding="0" style="border-collapse: collapse;">
							<tr>
							    <td width="20"></td>
							    <td style="color: #656565; font-size: 16px; line-height: 23px; vertical-align: top;">
								{top_message}
							    </td>
							</tr>
						    </table>
						</td>
					    </tr>
					</table>
				    </td>
				</tr>
				<tr><!--Amazing Collections-->
				    <td style="padding-top: 0;">
					<table width="100%" cellspacing="25" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">
					    <!--Row 1----------------------------------------->
					    {colloection_row}
					    <tr style="vertical-align: top;">
					    {collection_item:folder}
						<td>
						    <a href="{base_url}{folder_url}?link_from=email_digest" target="_blank" style="text-decoration: none;">
							<table cellpadding="0" style="width: 310px; border: 1px solid #D1D3D4; border-collapse: collapse;">
							    <tr>
								<td>
								    <table width="310" cellpadding="0" style="background: #FFFFFF; border-collapse: collapse;">
									<tr>
									    <td>
										<table height="60" width="100%" cellpadding="0" style="border-collapse: collapse;">
										    <tr>
											<td style="padding-left: 10px;">
											    <table width="100%" cellpadding="0" style="color: #656565; font-size: 16px;">
												<tr>
												    <td><a href="{base_url}{folder_url}?link_from=email_digest" target="_blank" style="text-decoration: none;color: #656565;"><b>{folder_name}</b></a></td>
												</tr>
												<tr>
												    <td>
													<table style="font-size: 12px;">
													    <tr>
														<td style="color: #909090; font-size: 10px;">
														    <span>By</span> <a href="{base_url}/{user->uri_name}" style="color: #08BBE6; font-size: 10px; text-decoration: none;">{user->full_name}</a>
														</td>
													    </tr>
													</table>
												    </td>
												</tr>
											    </table>
											</td>
										    </tr>
										</table>
									    </td>
									</tr>
									<tr>
									    <td height="100%">
										<table cellpadding="0" style="height: 100%; width: 100%; border-collapse: collapse;">
										    <tr height="197">
											<td colspan="3" style="padding: 0 5px 5px;">
											    <div style="max-height: 192px; overflow: hidden;">
												<img src="{_newsfeed_top->_img_310l}" onerror="if (this.src.indexOf('_310l') > -1) this.src = this.src.replace('_310l','_thumb')" style="display: block; background: #4583AA; max-width: 300px;"/>
											    </div>
											</td>
										    </tr>
										    <tr height="98">
											<td style="padding: 0 5px 5px;">
											    <table style="border-collapse: collapse;">
												<tr>
												    {_recent_newsfeeds_notop}
												    <td width="98">
													<span style="margin: 0; display: inline-block; overflow-y: hidden;">
													    <img src="{_img_100}" onerror="if (this.src.indexOf('_100') > -1) this.src = this.src.replace('_100','_bigsquare')" style="display:block; background: #4583AA; max-width:98px;max-height:98px;"/>
													</span>
												    </td>
												    {/_recent_newsfeeds_notop}
												</tr>
											    </table>
											</td>
										    </tr>
										</table>
									    </td>
									</tr>
								    </table>
								</td>
							    </tr>
							</table>
						    </a>
						</td>
					    {/collection_item:folder}
					    </tr>
					    <tr><td></td></tr>
					    {/colloection_row}
					</table>
				    </td><!----------------------->
				</tr>
			    </table>
		    	</td>
		    </tr>
<!----------------------->
<!--Drops You'll Love---->
		    
<!----------------------->
<!--Footer to Add with a call-->
			<tr style="background: #EDEDED;">
				<td style="color: #656565; font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif;text-shadow: 1px 1px #FFFFFF; line-height: 12px;">
				    <center>Copyright &copy;&nbsp; 2011 - 2013 . Fandrop - a product of <a href="http://www.fantoon.com" style="color: #3366CC">Fandrop Inc</a></center><br>
				    <center>To unsubscibe from Fandrop email notification, please <a href="{base_url}/account_options">click here</a></center>
				</td>
			</tr>
		</table>
		</td></tr>
	</table>
    </div>
</body>
</html> 