	    <tr>
		<td>

		    <table width="100%" cellspacing="25" style="color: #656565;font: 14px 'lucida grande',tahoma,helvetica,arial,sans-serif;">
			<tr>
			    <td>
				<table width="100%" cellspacing="0">
				    <tr>
					<td style="font-weight: bold;">
					    <!-- user_name | send_to_name -->
					    <? if ($action == 'signup') { ?>
						    <?=$this->lang->line('email_templates_views_hello2_lexicon');?>
					    <? } else { ?>
						    <?=$this->lang->line('email_templates_views_dear_lexicon');?> {user_name} <? /* NAME VARIABLE GOES HERE] */ ?>,
					    <? } ?>
					</td>
				    </tr>
				    <tr>
					<td style="color: #656565; padding-bottom: 10px;">
						<? if ($action == 'signup') { ?>
							<?=$this->lang->line('email_templates_views_thx_text');?>
							<a href="{fb_link}" onmouseover="this.style.textDecoration = 'underline'" onmouseout="this.style.textDecoration = 'none'" style="color: #3366CC;">Facebook</a>
							<?=$this->lang->line('email_templates_views_and_lexicon');?>
							<a href="{twtr_link}" onmouseover="this.style.textDecoration = 'underline'" onmouseout="this.style.textDecoration = 'none'" style="color: #3366CC;">Twitter</a>
						<? } else { ?>
							<?=$this->lang->line('email_templates_views_thought_text');?>
						<? } ?>
					</td>
				    </tr>
				    <tr>
					<td style="padding-bottom: 30px;">
					    <table width="100%" bgcolor="#F0F0F0" cellpadding="0" style="color: #656565;font: 12px 'lucida grande',tahoma,helvetica,arial,sans-serif; border: 1px solid #D1D3D4; border-collapse: collapse;">
						<tr>
						    <td style="padding-right: 15px;">
							    <? if ($action == 'signup') { ?>
							    <a href="{fb_link}" style="margin-right: 20px;"><img src="<?=base_url()?>/images/facebookShare.png" alt="Facebook"></a>
								<a href="{twtr_link}" style="margin-right: 20px;"><img src="<?=base_url()?>/images/twitterTweet.png" alt="Twitter"></a>
							    <? } else { ?>
								<a href="{user_link}"><img src="{thumbnail}" height="80" style="border-right: 1px solid #D1D3D4;"></a>
							    <? } ?>
						    </td>
						    <td>
						    <? if ($action == 'signup') { ?>
						    <? } else { ?>
							<a href="{user_link}" style="color: #3366CC; text-decoration: none; font-weight: bold;">{name}</a>
						    <? } ?>
						    <? if ($action == 'reply') { ?>
							<?=$this->lang->line('email_templates_views_reply_msg');?>
						    <? } else if ($action == 'up') { ?>
							<?=$this->lang->line('email_templates_views_up_msg');?> {type}<!--<a href="{link_url}">{link_url}</a>-->
						    <? } else if ($action == 'comment_up') { ?>
							<?=$this->lang->line('email_templates_view_upvoted_comment_msg');?>
						    <? } else if ($action == 'follow_folder') { ?>
							<?=$this->lang->line('email_templates_views_follow_folder_msg');?>
						    <? } else if ($action == 'up_folder') { ?>
							<?=$this->lang->line('email_templates_views_up_folder_msg');?>							
						    <? } else if ($action == 'connection') { ?>
							    <?=$this->lang->line('email_templates_view_connection_msg');?>
						    <? } else if ($action == 'mention') { ?>
							    <?=$this->lang->line('email_templates_views_mention_msg');?> <a href="{link_url}">{link_url}</a>
						    <? } else if ($action == 'message') { ?>
							    <?=$this->lang->line('email_templates_views_message_msg');?>
						    <? } else if ($action == 'comment') { ?>
							    <?=$this->lang->line('email_templates_view_comment_msg');?>
						    <? } else if ($action == 'collaboration') { ?>
						    <?=$this->lang->line('email_templates_views_collaboration_msg');?>
						    <? } else if ($action == 'collaborator') { ?>
						    <?=$this->lang->line('email_templates_views_collaborator_msg');?>
						    <? } else if ($action == 'collaborator_email') { ?>
						    <?=$this->lang->line('email_templates_views_collaborator_msg');?>
						    <? } ?>
						    </td>
						</tr>
					    </table>
					</td>
				    </tr>
				    <? /* ?>Add logic for the e-mail sharing use case
					<tr>
					    <td>
						<table cellspacing="10" style="color: #656565;font: 12px 'lucida grande', helvetica, arial, sans-serif;">
						    <tr>
							<td width="50%" style="color: #3D3D3D; font-size: 18px; font-weight: bold;">Here at Fandrop...</td>
							<td width="5%"></td>
							<td rowspan="2">
							    <!--Featured Story Content Goes Here-->
							    <a href="{drop_url}?link_from=email_digest" target="_blank" style="text-decoration: none;">
								<table style="width: 216px; border-collapse: collapse;">
								    <tr>
									<td></td>
									<td>
									    <table width="194px">
										<tr>
										    <td width="20px" style="vertical-align: top;">
											<table>
											    <tr>
												<td style="background: #C0C0C0;">
												    <img src="{type_icon}">
												</td>
											    </tr>
											</table>
										    </td>
										    <td style="color: #656565; padding: 3px 0 5px 5px; font-size: 12px;">
											{drop_description}
										    </td>
										</tr>
									    </table>
									</td>
									<td></td>
								    </tr>
								    <tr height="5">
									<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_1.png) no-repeat; height: 5px;"></td>
									<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_2.png) repeat-x; height: 5px;"></td>
									<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_3.png) no-repeat; height: 5px;"></td>
								    </tr>
								    <tr>
									<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_4.png) repeat-y; height: 213px; width: 5px;"></td>
									<td>
									    <table width="194px" style="background: #FFFFFF; border-collapse: collapse;"><!--remove height later-->
										<tr>
										    <td height="100%">
											<table style="height: 100%; width: 100%; border-collapse: collapse;">
											    <tr>
												<td>
												    <span style="margin: 0; display: inline-block; overflow-y: hidden;">
													<img src="{drop_img}" style="display:block;">
												    </span>
												</td>
											    </tr>
											</table>
										    </td>
										</tr>
										<tr>
										    <td style="color: #8B8B8B; padding: 10px 15px 10px; font-size: 12px;">{drop_title}</td>
										</tr>
										<tr>
										    <td>
											<table height="100%" width="100%" style="background: #F2F2F2;">
											    <tr>
												<td>
												    <table style="color: #8D7C7C; font-size: 12px;">
													<tr>
													    <td style="height: 30px; width: 30px;">
														<img src="{user_img}" height="30px" width="30px" style="display:block;">
													    </td>
													    <td style="padding-left: 10px;">
														<a href="{user_url}" style="color: #8B8B8B; font-size: 12px; text-decoration: none;"><b>{user_name}</b></a> dropped this in
														<a href="{collection_url}?link_from=email_digest" style="color: #8B8B8B; font-size: 12px; text-decoration: none;"><b>{collection_name}</b></a>
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
									<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_5.png) repeat-y; height: 213px; width: 5px;"></td>
								    </tr>
								    <tr height="5">
									<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_6.png) no-repeat; height: 5px;"></td>
									<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_7.png) repeat-x; height: 5px;"></td>
									<td style="background: url(http://www.fandrop.com/images/newsletter_parts/collections/collection_8.png) no-repeat; height: 5px;"></td>
								    </tr>
								</table>
							    </a>
							    <!------------------------------------>
							</td>
						    </tr>
						    {/top_drop}
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
					    <td style="color: #656565; font-size: 16px; line-height: 23px; vertical-align: top;">
						{user_message}
					    </td>
					</tr>
				    <? */ ?>
				    <tr>
					<td style="color: #656565;"><?=$this->lang->line('email_templates_views_thx_lexicon');?></td>
				    </tr>
				    <tr style="color: #656565;">
					<td><?=$this->lang->line('email_templates_views_team_text');?></td>
				    </tr>
				    <tr height="150">
					<td></td>
				    </tr>
				</table>
			    </td>
			</tr>
		    </table>
		</td>
	    </tr>
