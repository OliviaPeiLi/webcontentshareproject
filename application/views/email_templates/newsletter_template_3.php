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
									    <td>
											<font face="lucida grande, helvetica, arial, sans-serif" size="6" color="#656565"><a href="http://www.fandrop.com"><img src="http://www.fandrop.com/images/newsletter_parts/header/fandropHeaderDigest2.png" alt="Digest" title="Go To Fandrop" height="51" border="0" style="display: block; color: #656565;"/></a></font>
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
		    	<td><!--Content Goes Here------>
<!-- 			<table width="100%" style="background: #FFFFFF; border: 1px solid #E5E5E5;">
					<tr>
					    <td>
						<table width="100%" cellspacing="10" style="color:#656565;font:12px 'lucida grande',helvetica,arial,sans-serif">
						    <tr>
							<td style="color:#656565;font-size:16px;line-height:23px;vertical-align:top">
							    Fandrop was featured in an article on Techcrunch last week:<br>
							    <a href="http://techcrunch.com/2013/02/14/fandrop-debuts-a-digg-like-service-for-viral-media-hacks-its-way-to-over-1-million-pageviews-monthly/" target="_blank" style="color:#08bbe6;font-size:16px;text-decoration:none">Fandrop Debuts A Digg-Like Service For Viral Media, Hacks Its Way To Over 1 Million Pageviews Monthly</a><br>Thank you for all your support!
							</td>
						    </tr>
						</table>
					    </td>
					</tr> -->
					<table width="100%" style="border-bottom:1px solid #e6e6e6"></table>
			    		<tr><!--Here at Fandrop------>
							<td style="padding-bottom: 0px;">
				    			<table width="100%" cellspacing="10" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">

									<tr>
					    				<td width="50%" style="color: #3D3D3D; font-size: 18px; font-weight: bold;">Here at Fandrop...</td>
					    				<td width="5%"></td>
					    				{top_drop:newsfeed}
					    				<td rowspan="2">
											<!--Featured Story Content Goes Here-->
						    				<table cellpadding="0" style="width: 225px; border-collapse: collapse;">
												<tr height="5">
												    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_1.png) no-repeat; height: 5px;"></td>
												    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_2.png) repeat-x; height: 5px;"></td>
												    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_3.png) no-repeat; height: 5px;"></td>
												</tr>
												<tr>

							    					<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_4.png) repeat-y; height: 323px; width: 5px;"></td>
							    					<td>
														<table width="215" cellpadding="0" style="background: #FFFFFF; border-collapse: collapse;">
								    						<tr>
																<td>
									    							<table height="55" width="100%" cellpadding="0" style="background: #FFFFFF; border: 1px solid #E5E5E5;">
																		<tr>
										    								<td style="padding-left: 10px;">
																				<table width="100%" cellpadding="" style="color: #656565;">
											    									<tr>
																						<td style="font-size: 14px;"><b>{description}</b></td>
											    									</tr>
											    									<tr>
																						<td style="font-size: 10px;">
																						    <span>by</span> <a href="{base_url}{user_from->_url}" style="color: #08BBE6; font-size: 10px; text-decoration: none;">{user_from->full_name}</a>
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
																		<tr>
																		    <td>
																		    	<a href="{base_url}/drop/{url}?link_from=email_digest" target="_blank" style="text-decoration: none;">
																					<span style="margin: 0; display: inline-block; overflow-y: hidden; background: url({base_url}/newsfeed/thumb/{newsfeed_id}) no-repeat center center; width:215px; height:215px; background-size:215px;">
																					    <!--<img src="{base_url}/newsfeed/thumb/{newsfeed_id}" <? /* ?>height="275"<? */ ?> width="215" style="display:block;vertical-align: middle;max-width: 215px;max-height: 215px;display: -moz-inline-box;display: inline-block;"/>-->
																					</span>
																				</a>
																		    </td>
																		</tr>
																	</table>
																</td>
							    							</tr>
														</table>
						    						</td>
						    						<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_5.png) repeat-y; height: 323px; width: 5px;"></td>
												</tr>
												<tr height="5">
												    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_6.png) no-repeat; height: 5px;"></td>
												    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_7.png) repeat-x; height: 5px;"></td>
												    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_8.png) no-repeat; height: 5px;"></td>
												</tr>
					    					</table>
					    				</td>
					    				{/top_drop:newsfeed}
									</tr>


									
									<tr>
									    <td style="color: #656565; font-size: 16px; line-height: 23px; vertical-align: top;">
										{top_message}
									    </td>
									</tr>
									<tr>
									    <td></td>
									    <td></td>
									    <td></td>
									</tr>
				    			</table>
							</td>
			    		</tr>
			    		<tr>
							<td colspan="2"><table width="100%" style="border-bottom: 1px solid #E6E6E6;"></table></td>
			    		</tr><!----------------------->
			    		<tr><!--Amazing Collections-->
							<td style="padding-top: 0;">
				    			<table width="100%" cellspacing="15" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">
								<!--Row 1----------------------------------------->
									{colloection_row}
									<tr style="vertical-align: top;">
						    			{collection_item:folder}
						    			<td>
											
								    			<table cellpadding="0" style="width: 305px; border-collapse: collapse;">
													<tr height="5">
													    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_1.png) no-repeat; height: 5px;"></td>
													    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_2.png) repeat-x; height: 5px;"></td>
													    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_3.png) no-repeat; height: 5px;"></td>
													</tr>
													<tr>
									    				<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_4.png) repeat-y; height: 200px; width: 5px;"></td>
									    				<td>
															<table width="295" cellpadding="0" style="background: #FFFFFF; border-collapse: collapse;">
										    					<tr>
																	<td>
											    						<table height="65" <? /* ?>height="100%"<? */ ?> width="100%" cellpadding="0" style="background: #505050;">
																			<tr>
												    							<td style="padding-left: 10px;">
																					<table width="100%" cellpadding="0" style="color: #FFFFFF; font-size: 16px;">
													    								<tr>
																							<td><a href="{base_url}{folder_url}?link_from=email_digest" target="_blank" style="text-decoration: none;color: #fff;"><b>{folder_name}</b></a></td>
													    								</tr>
																					    <tr>
																							<td>
																							    <table style="font-size: 12px;">
																								<tr>
																								    <td style="background: url(http://www.fandrop.com/images/dropicon.png) no-repeat; height: 14px; width: 14px;"></td>
																								    <td style="padding-right: 5px;"><span style="color: #FFFFFF; font-size: 12px;">{newsfeeds_count}</span></td>
																								    <td style="background: url(http://www.fandrop.com/images/viewsicon.png) no-repeat; height: 14px; width: 14px;"></td>
																								    <td><span style="color: #FFFFFF; font-size: 12px;">{total_hits}</span></td>
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
																	<td height="100%" style="padding: 5px;">
																	    <table cellpadding="0" style="height: 100%; width: 100%; border-collapse: collapse;">
																			<tr height="95">
																			    {recent_newsfeeds}
																			    <td width="85" style="padding: 5px;">
																				<span style="margin: 0; display: inline-block; overflow-y: hidden;">
																				    <img src="{_img_bigsquare}" style="display:block; max-width:85px;max-height:85px"/>
																				</span>
																			    </td>
																			    {/recent_newsfeeds}
																			</tr>
																	    </table>
																	</td>
										    					</tr>
															</table>
									    				</td>
									    				<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_5.png) repeat-y; height: 200px; width: 5px;"></td>
													</tr>
													<tr height="5">
													    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_6.png) no-repeat; height: 5px;"></td>
													    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_7.png) repeat-x; height: 5px;"></td>
													    <td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_8.png) no-repeat; height: 5px;"></td>
													</tr>
								   				</table>
											
						    			</td>
						    			{/collection_item:folder}
									</tr>
									<tr><td></td></tr>
									{/colloection_row}
				    			</table>
							</td><!----------------------->
			    		</tr>
					    <tr>
							<td><table width="100%" style="border-bottom: 1px solid #E6E6E6;"></table></td>
					    </tr>
			    		<tr>
							<td style="padding-top: 0px;">
				    			<table width="100%" cellspacing="0" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">
									<tr>
									    <td style="color: #3D3D3D; font-size: 18px; font-weight: bold;">
										Drops You'll Love
									    </td>
									</tr>
									<tr><td></td></tr>
									<tr style="vertical-align: top;"><!--Columns------------->
					    				{popular_drop_column}
					    				<td>
											<table>
						    					{drop:newsfeed}
						    					<tr style="vertical-align:top;">
													<td>
														<table cellpadding="0" style="width: 225px; border-collapse: collapse;">
														    <tr height="5">
																<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_1.png) no-repeat; height: 5px;"></td>
																<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_2.png) repeat-x; height: 5px;"></td>
																<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_3.png) no-repeat; height: 5px;"></td>
														    </tr>
														    <tr>
																<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_4.png) repeat-y; height: 323px; width: 5px;"></td>
																<td>
																    <table width="215" cellpadding="0" style="background: #FFFFFF; border-collapse: collapse;">
																		<tr>
																		    <td>
																				<table height="55" width="100%" cellpadding="0" style="background: #FFFFFF; border: 1px solid #E5E5E5;">
																				    <tr>
																						<td style="padding-left: 10px;">
																						    <table width="100%" cellpadding="" style="color: #656565;">
																								<tr>
																								    <td style="font-size: 14px;"><b>{description}</b></td>
																								</tr>
																								<tr>
																								    <td style="font-size: 10px;"><span>by</span> <a href="{base_url}{user_from->_url}" style="color: #08BBE6; font-size: 10px; text-decoration: none;">{user_from->full_name}</a></td>
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
																				    <tr>
																						<td>
																							<a href="{base_url}/drop/{url}?link_from=email_digest" target="_blank" style="text-decoration: none;">
																							    <span style="margin: 0; display: inline-block; overflow-y: hidden; background: url({base_url}/newsfeed/thumb/{newsfeed_id}) no-repeat center center; width:215px;height:215px; background-size:215px;">
																									<!--<img src="{base_url}/newsfeed/thumb/{newsfeed_id}" <? /* ?>height="275"<? */ ?> width="215" style="display:block;vertical-align: middle;max-width: 215px;max-height: 215px;display: -moz-inline-box;display: inline-block;"/>-->
																							    </span>
																							</a>
																						</td>
																				    </tr>
																				</table>
																		    </td>
																		</tr>
																    </table>
																</td>
																<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_5.png) repeat-y; height: 323px; width: 5px;"></td>
														    </tr>
														    <tr height="5">
																<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_6.png) no-repeat; height: 5px;"></td>
																<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_7.png) repeat-x; height: 5px;"></td>
																<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_8.png) no-repeat; height: 5px;"></td>
														    </tr>
														</table>
													</td>
						    					</tr>
												{/drop:newsfeed}
						    					
											</table>
					    				</td>
					    				{/popular_drop_column}
									</tr>
				    			</table>
							</td>
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
				    <center>Copyright &copy;&nbsp; 2011 - 2012 . <a href="http://www.fantoon.com" style="color: #3366CC">Fandrop Inc</a></center><br>
				    <center>To unsubscibe from Fandrop email notification, please <a href="{base_url}account_options">click here</a></center>
				</td>
			</tr>
		</table>
		</td></tr>
	</table>
    </div>
</body>
</html> 