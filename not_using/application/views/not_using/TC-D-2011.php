<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<link rel="icon" type="image/png" href="/images/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>TechCrunch: Disrupt 2011</title>
	<link rel="stylesheet" href="http://dmitry.fandrop.com/css/base.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="http://dmitry.fandrop.com/css/960_24_col.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="http://dmitry.fandrop.com/css/header.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?=APPPATH?>views/CSS/style_demo_TC.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="http://dmitry.fandrop.com/css/external.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="http://dmitry.fandrop.com/css/jPicTag.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="http://dmitry.fandrop.com/css/jquery-ui-1.8.14.custom.css" type="text/css" media="screen" />
	<link type="text/css" href="http://dmitry.fandrop.com/css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" /> 
    <link type="text/css" href="http://dmitry.fandrop.com/css/autocompgoogle.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="http://dmitry.fandrop.com/chatui/chatui.css" type="text/css" media="screen" />
  	<link rel="stylesheet" href="http://dmitry.fandrop.com/chatui/general.css" type="text/css" media="screen" />
    <!--<link rel="stylesheet" type="text/css" href="http://geo-autocomplete.googlecode.com/svn/trunk/lib/jquery-ui/css/ui-lightness/jquery-ui-1.8.5.custom.css" />  -->
    
 	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://dmitry.fandrop.com/js/thejs.js" type="text/javascript"></script>
	<script src="http://dmitry.fandrop.com/js/external.js" type="text/javascript" charset="utf-8"></script>
	<script src="http://dmitry.fandrop.com/js/jquery-ui-1.8.2.custom.min.js" type="text/javascript"></script>
	<script src="http://dmitry.fandrop.com/js/jPicTag-1.0.jQuery.js" type="text/javascript"></script> 
	<script type="text/javascript" src="http://dmitry.fandrop.com/js/jit.js"></script>
	
	<!--<script type="text/javascript" src="http://dmitry.fandrop.com/js/jquery-ui-1.8.13.custom.min.js"></script>-->
	<script type="text/javascript" src="http://dmitry.fandrop.com/js/jquery.mousewheel.js"></script>
	<script type="text/javascript" src="http://dmitry.fandrop.com/js/parseur.js"></script>
	<script type="text/javascript" src="http://dmitry.fandrop.com/js/utils.js"></script>
			<script type="text/javascript" src="http://dmitry.fandrop.com/js/uservoice.js"></script>
		<!--<script src="http://dmitry.fandrop.com/js/jPicTag-1.0.jQuery_item.js" type="text/javascript"></script>-->
	   <script type="text/javascript"  src="http://dmitry.fandrop.com/js/galleria-1.2.4.min.js"></script>
       
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="http://dmitry.fandrop.com/js/jquery-ui-1.8.5.custom.min.js"></script>
	<script type="text/javascript" src="http://dmitry.fandrop.com/js/ui.geo_autocomplete.js"></script>	
	
</head>

<body>
    <div id="header">
    	<div id="navigation">
            <div class="container_24">
                <h1>
                    <a href="javascript:;" title="">Fandrop</a>
                </h1>
                <div id="hdr_account">
                	<div id="account_menu" class="account_menu inlinediv menu_caller" onmousedown="javascript:;">
						<img id="account_avatar" src="https://s3.amazonaws.com/fantoon-dev/users/396/pics/thumbs/thumb.jpg" width="32" height="32" alt="nav_pic"/>
		                <a href="javascript:;" title="" id="account_link">
		                	<div id="account_name" class="inlinediv">Bill Gifford</div> 
		                    <div id="account_arrow" class="inlinediv"><span class="dropdown_menu_arrow"></span></div>
		                </a>
					</div>
                </div>
            </div>
                                
            <ul id="headerLinks" class="headerLinks">
				<li class="hdrnav_item"><a href="#" title="">Home</a> | </li>
                <li class="hdrnav_item"><a href="#" title="">What's Hot</a> | </li>
				<li class="hdrnav_item"><a href="#" id="hdrnav_profile">Profile</a></li>
			</ul>
        </div>
		
		<div id="search">
            <div class="container_24">
            	<form action="" method="post">
					<label id="header_search" class="autocomplete_input">
						<form action="http://dmitry.fandrop.com/search" method="post" accept-charset="utf-8">
							<div class="hidden">
								<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
							</div>						
							<input type="text" name="search_input" value="Search" id="header_search_box" class="box_input_placeholder" />
							<button type="submit" id="searchButton" class="search_button">Search</button>
						</form>					
					</label>
					<script type="text/javascript">
						$('.autocomplete_input #header_search_box').tokenInput("http://dmitry.fandrop.com/main_search", {
	                        placeholderText: 'Search Interests and Connections...',
	                        linkedText: false,
							singleTokenOnly: true,
							tokenLimit: 1,
							theme: 'search',
							hintText: null,
							redirectOnClick: true,
	                        showDropdownOnFocus: false,
	                        searchingText: '',
							type: 'search',
							bottomText: '<a href="/search?q=">click here to see more</a>',
							onAdd: function (item) { 
								//alert('added');
								//window.location.replace("http://www.google.com"); 
							}
						});                
					</script>                    
                </form>
				<ul class="headerIcons">
				    <li class="hdrnav_item2 menu_caller hdr_loops"><a class="loops"></a></li>
				    <li class="hdrnav_item2 menu_caller hdr_lists"><a class="lists"></a></li>
				    <li class="hdrnav_item hdr_allfriends menu_caller" id="hdr_allfriends">
						<a href="javascript:;" title="" class="friends">Friends</a>
						<em> | </em>
				    </li>
				    <ul id="requests" class="menu">
						<li class="nohover">No Connection Requests</li>
				    </ul>
				    <li class="hdrnav_item hdr_notifications menu_caller" id="hdr_notifications">
						<a href="javascript:;" title="" class="info">Info</a>
						<em> | </em>
				    </li>
				    <div id="notifications" class="menu">
						<ul>
						    <li class="nohover">Notifications</li>
						    <li class="nohover">
							<a href="#">See All Notifications</a>
						    </li>
						</ul>
				    </div>
				    <li class="hdrnav_item hdr_msgs" id="hdr_msgs";>
						<a href="javascript:;" title="" class="message">Message</a>
				    </li>
				</ul>
            </div>
        </div>
    </div>
<script>
$(document).ready(function() {
	$( "#sortable" ).sortable({
		handle : '.ui-state-default',
		update : function () {
		//var order = $('#sortable').sortable('serialize');
		order = [];
		$('#sortable').children('div').each(function(idx, elm) {
		order.push(elm.id.split('_')[1])
		});
		$.post('/sort_components/2662//', { ci_csrf_token: $("input[name=ci_csrf_token]").val(),'order[]': order});

		}
	});

});
</script>
<div id="main">
	<!-- if page is just being created, go to upload image and invite people wizard -->
	<div id="p_id" style="display:none">2662</div>

        <div id="container" class="container_24">
            <div id="interests_main" class="grid_18 alpha">
            	<div class="interests_top">
                    <h2>TechCrunch Disrupt</h2>
                </div>
				
				
                <div class="interests_container">
                	<div id="page_misc_info">
						<!--~~~~~~~~~~~~~~~~~~~~~ Page Topics ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
						<div id="topics_area">
							<ul id="page_topics" class="inlinediv">
								Topics: 
								<li class="page_topic">
								    <a href="#">Tech</a>
								</li>
								<li class="page_topic">
								    <a href="#">News</a>
								</li>
								<li class="page_topic">
								    <a href="#">Start Ups</a>
								</li>
								<li class="page_topic">
								    <a href="#">SF</a>
								</li>
								<li class="page_topic">
								    <a href="#">Hack-a-thon</a>
								</li>
								</ul>
									<div id="add_page_topic" class="inlinediv"><a>Add Topic</a></div>
									<div id="lock_topics" class="inlinediv">
										<a href="#">Lock topics</a>
									</div>
																						
									<div id="new_topic" class="autocomplete_input" style="display: none;">
								<form action="http://dmitry.fandrop.com/new_topic/2662/interests" method="post" accept-charset="utf-8">
									<div class="hidden">
										<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
									</div>
									<input type="text" name="topic_id" value="" id="load_topics" />
									<input type="hidden" id="topic_names" name="topic_name" />
									<input type="hidden" name="redirect_url" value="http://dmitry.fandrop.com/interests/Madonna/2662"/>
									<input type="submit" name="submit" value="Submit" id="page_new_topic_submit" />
								</form>
							</div>
							<script type="text/javascript">
								var tkin = $('.autocomplete_input #load_topics').tokenInput("/get_topics", {
									theme: "facebook",
									singleTokenOnly: false,
									preventDuplicates: true,
		                            allowInsert: true
								});
							</script>
						</div>	
						<div id="feature_interests_area">													
							<div id="new_alias" style="display: none;">
								<form action="http://dmitry.fandrop.com/apply_alias/2662/interests" method="post" accept-charset="utf-8">
									<div class="hidden">
										<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
									</div>
									<input type="text" name="alias" value="Enter new alias" class="input_placeholder" />
									<input type="hidden" name="url" value="http://dmitry.fandrop.com/interests/Madonna/2662" />
									<input type="submit" name="submit" value="Submit" id="page_new_alias_submit" />
								</form>
							</div>
							
							<div id="new_feature" style="display: none;">
								<form action="http://dmitry.fandrop.com/add_feature/2662/interests" method="post" accept-charset="utf-8">
									<div class="hidden">
										<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
									</div>
									<input type="text" name="page" value="Enter feature interests" class="input_placeholder" />
									<input type="hidden" name="url" value="http://dmitry.fandrop.com/interests/Madonna/2662" />
									<input type="submit" name="submit" value="Submit"  />
								</form>
							</div>
						</div>
					
					</div>
                	<div class="interests_tabs" id="page_tabs_row">
	                	<ul>
                            <li id="wall_tab" class="page_tab"><a href="#">Channel</a></li>
                            <li id="events_tab" class="page_tab"><a href="#">Events</a></li>
                            <li id="info_tab" class="page_tab"><a href="#">Info</a></li>
                            <li id="photos_tab" class="page_tab"><a href="#">Photos</a></li>
                            <li id="videos_tab" class="page_tab"><a href="#">Videos</a></li>
                            <li id="pr_tab"><a href="#">PR</a></li>
                            <li id="custom_tab1"><a href="#">Flickr</a></li>
                            <li id="custom_tab2"><a href="#">QWiki</a></li>
	               			<li id="more_tabs" class="page_tab" style="display:none" onclick="javascript:open_tab_menu()">
	               				<a href="#">More…</a>
	               			</li>
	               			<li id="add_new_tab"><a href="#" onclick="return false" onmousedown="javascript:view_hide('tabs');">+</a></li>
	               			<ul style="display:none" id="more_tabs_list" class="menu site_menu"></ul>
	                        </ul>
							<div id="page_add_custom_tab" style="display: none;">
								<form action="http://dmitry.fandrop.com/new_tab/2662/Madonna?header=none" method="post" accept-charset="utf-8">
									<div class="hidden">
										<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
									</div>
									
									Tab Name
									<input type="text" name="tab_name" value=""  />
									<input type="submit" name="submit" value="Submit"  />
								</form>
							</div>
	                    </div>
						<!--~~~~~~~~~~ Page main content (wall/info/photos/videos, etc...) & Other page recommendations ~~~~~~~~~-->
							
						<!-- ATTN: The below FOR loop may not be required ~~~~~~~~~~~~-->
						
	                    <div id="page_tab_maincontent" class="interests_comments">
							<div id="box">
							    <ul id="column_titles">
									<li class="first">Thread / Thread Starter</li>
									<li>Last Post</li>
									<li>Replies</li>
									<li>Views</li>
							    </ul>
							    <div class="topic_thread">
									<div class="thread_icon"></div>
									<div class="thread_body inlinediv">
									    <div class="topic_name">
											<a href="">Disrupt SF Thread</a>
								    	</div>
								    	<div class="body">
											<a href="#">What's the best start up?</a>
								    	</div>
									</div>
									<div class="last_post inlinediv">
									    <div class="timestamp inlinediv">20 minutes ago</div>
									    <div class="user_name">
											by <a href="#">Cherilyn Sarkisian</a>
									    </div>
									</div>
									<div class="replies inlinediv">3,000</div>
									<div class="views inlinediv">3,034</div>
							    </div>
							
							    <div class="topic_thread">
									<div class="thread_icon"></div>
									<div class="thread_body inlinediv">
									    <div class="topic_name">
										<a href="">Disrupt NYC Thread</a>
									    </div>
									    <div class="body">
										<a href="#">Venture Capital, Venture Debt, and Angels</a>
									    </div>
									</div>
									<div class="last_post inlinediv">
									    <div class="timestamp inlinediv">35 minutes ago</div>
									    <div class="user_name">
										by <a href="#">Arthur Dent</a>
									    </div>
									</div>
									<div class="replies inlinediv">900</div>
									<div class="views inlinediv">1,234</div>
							    </div>
						    
							    <div class="topic_thread">
									<div class="thread_icon"></div>
									<div class="thread_body inlinediv">
									    <div class="topic_name">
											<a href="">TechCrunch Speakers Thread</a>
									    </div>
									    <div class="body">
											<a href="#">When is Ashton Kutcher scheduled to speak?</a>
									    </div>
									</div>
									<div class="last_post inlinediv">
									    <div class="timestamp inlinediv">1 hour ago</div>
									    <div class="user_name">
											by <a href="#">Montgomery Scott</a>
									    </div>
									</div>
									<div class="replies inlinediv">100</div>
									<div class="views inlinediv">334</div>
							    </div>
							    
							    <div class="topic_thread">
									<div class="thread_icon"></div>
									<div class="thread_body inlinediv">
								    	<div class="topic_name">
											<a href="">TechCrunch related Thread</a>
								    	</div>
								    	<div class="body">
											<a href="#">Congratulations Hackathon winners!</a>
								    	</div>
									</div>
									<div class="last_post inlinediv">
									    <div class="timestamp inlinediv">4 hours ago</div>
									    <div class="user_name">
											by <a href="#">Johnny Fife</a>
									    </div>
									</div>
									<div class="replies inlinediv">555</div>
									<div class="views inlinediv">9,001</div>
							    </div>
							</div>
						</div>
				    

<? include('tc_votes_inline.php'); ?>

                                    </div>
                <!-- Empty div just for the bottom background of containers-->
                <div style="margin-top:-10px;" class="interests_bot"></div>
            </div>
            <div id="profile_info" class="grid_6 omega">
                <div class="profile_pic">
                    <h2 class="change_pic">
                        <span></span>
                        <a href="javascript:;" title="">More</a>
                    </h2>
                    <div id="page_profilePic">
                        <a href="#" id="link_to_edit_photo" >Change Profile Picture</a><a href="#" id="link_to_profile_album"><img src="/images/tc.png" id="page_pic_auth"/></a>                        <div class="clear"></div>
                    </div>
                    <div class="profile_interests">
                        <h4>People in this interest (<a href="#" onclick="return false" onmousedown="javascript:view_hide('page_user_list');">3</a>)</h4>
                        	                        <dl>
	                            <dt><em>Your Connections:</em></dt>
                                		                            <dd>
										<div class="avatar inlinediv tc_show_badge user_avatar badge_left"style="float: left;">
                                                                                        											<a href="#">
												<div class="img_tight_wrapper">
													<img src="https://s3.amazonaws.com/fantoon-dev/users/1/pics/thumbs/thumb.jpg" width=30 />
												</div>
											</a>
											<div class="obj_id" style="display:none;">420</div>
										</div>
									</dd>
									    <dd>
										<div class="avatar inlinediv tc_show_badge user_avatar badge_left"style="float: left;">
                                                                                        											<a href="#">
												<div class="img_tight_wrapper">
													<img src="https://s3.amazonaws.com/fantoon-dev/users/2/pics/thumbs/thumb.jpg" width=30 />
												</div>
											</a>
											<div class="obj_id" style="display:none;">1</div>
										</div>
									</dd>
									    <dd>
										<div class="avatar inlinediv tc_show_badge user_avatar badge_left"style="float: left;">
                                                                                        											<a href="#">
												<div class="img_tight_wrapper">
													<img src="https://s3.amazonaws.com/fantoon-dev/users/238/pics/thumbs/thumb.jpg" width=30 />
												</div>
											</a>
											<div class="obj_id" style="display:none;">424</div>
										</div>
									</dd>
									    <dd>
										<div class="avatar inlinediv tc_show_badge user_avatar badge_left"style="float: left;">
                                                                                        											<a href="#">
												<div class="img_tight_wrapper">
													<img src="https://s3.amazonaws.com/fantoon-dev/users/4/pics/thumbs/thumb.jpg" width=30 />
												</div>
											</a>
											<div class="obj_id" style="display:none;">426</div>
										</div>
									</dd>
									    
                                                                            <a id="page_admin_link" style="display:none;" href="#">make admin</a>
                                                                                                                    <a id="page_admin_link" style="display:none;"href="#"> remove user</a>
                                                                            									                        </dl>
                                                <dl>
               				                            
                        </dl>
                        <div class="clear"></div>
                                   				           					                            <dl>
                            	<dt><em>
	                                	                                	Other People:
	                                                                </em></dt>
                                                         			
                                 	<dd class="user_avatar avatar inlinediv tc_show_badge badge_left">
                                 		<a href="#" title="">
                                 			<div class="img_tight_wrapper">
                                 				<img src="https://s3.amazonaws.com/fantoon-dev/users/228/pics/thumbs/thumb.jpg" width=30 />
                                 			</div>
                                 		</a>
                                 		<div class="obj_id" style="display:none;">2</div>
                                                                                    <a id="page_admin_link" style="display:none;" href="#">make admin</a>
                                                                                                                                <a id="page_admin_link" style="display:none;"href="#"> remove user</a>
                                                                                                                     	</dd>
					<dd class="user_avatar avatar inlinediv tc_show_badge badge_left">
                                 		<a href="#" title="">
                                 			<div class="img_tight_wrapper">
                                 				<img src="https://s3.amazonaws.com/fantoon-dev/users/230/pics/thumbs/thumb.jpg" width=30 />
                                 			</div>
                                 		</a>
                                 		<div class="obj_id" style="display:none;">1</div>
                                                                                    <a id="page_admin_link" style="display:none;" href="#">make admin</a>
                                                                                                                                <a id="page_admin_link" style="display:none;"href="#"> remove user</a>
                                                                                                                     	</dd>
					<dd class="user_avatar avatar inlinediv tc_show_badge badge_left">
                                 		<a href="#" title="">
                                 			<div class="img_tight_wrapper">
                                 				<img src="https://s3.amazonaws.com/fantoon-dev/users/232/pics/thumbs/thumb.jpg" width=30 />
                                 			</div>
                                 		</a>
                                 		<div class="obj_id" style="display:none;">2</div>
                                                                                    <a id="page_admin_link" style="display:none;" href="#">make admin</a>
                                                                                                                                <a id="page_admin_link" style="display:none;"href="#"> remove user</a>
                                                                                                                     	</dd>
					<dd class="user_avatar avatar inlinediv tc_show_badge badge_left">
                                 		<a href="#" title="">
                                 			<div class="img_tight_wrapper">
                                 				<img src="https://s3.amazonaws.com/fantoon-dev/users/237/pics/thumbs/thumb.jpg" width=30 />
                                 			</div>
                                 		</a>
                                 		<div class="obj_id" style="display:none;">428</div>
                                                                                    <a id="page_admin_link" style="display:none;" href="#">make admin</a>
                                                                                                                                <a id="page_admin_link" style="display:none;"href="/rm_user/2662/2"> remove user</a>
                                                                                                                     	</dd>
                                                            </dl>
						                        <div class="clear"></div>
                    </div>
                    <div id="page_options">
                                            </div>
                    <div class="clear"></div>                    
                    <div class="profile_bot"></div>
                </div>
            </div>
        </div>        
        
        
        
        
        
        
        
        

	</div>
<div class="clear"></div>
<!--~~~~~~~~~~~ User info upon hover ~~~~~~~~~~~~~~~~~-->
<div id="page_userinfo_hover" style="display:none" tabindex=100>
	<div id="userinfo_container">
		<div id="avatar_id"></div>
		<a id="page_hover_admin_link" href=""></a>
	</div>
	<img src="/images/tabArrow.png" />
</div>
<div class="clear"></div>

<!--~~~~~~~~~~~~~~~ Dialog for Uploading Profile Pictures (hidden) ~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="upload_profilepic_dlg">
	<div id="imgupload_preview">
		<iframe id="postframe" name="postframe" style="display: none;"></iframe> 
		<div id="preview" style="width: 400px; margin-right: 20px;">
			<img src="https://s3.amazonaws.com/fantoon-dev/pages/default/defaultInterest.png" style="float: left; margin-right: 100px; width: 400px; height: 400px;" >
			<div id="act_width" style="display: none"></div>
			<div id="act_height" style="display: none"></div>
		</div>
	</div>
	<div id="imgupload_options">
				<form action="http://dmitry.fandrop.com/profile_pic" method="post" accept-charset="utf-8" name="thumbnail" id="thumb_form">
<div class="hidden">
<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
</div>		<input type="hidden" name="ispage" id="ispage" value="1" />
		<input type="hidden" name="page" id="page" value=2662/>
		<input type="hidden" name="src_img" id="src_img" />
		<input type="submit" name="upload_thumbnail" value="Save" id="save_preview" style="display: none;" />		</form>	
		<div>
			<a id="upload_newimg_lnk" style="display:block;" href="">Upload new image</a>
			<div id="imgupload_newimg_pane" style="display: none;">
				<form action="http://dmitry.fandrop.com/upload_photo_profile/2662" id="orig_img_upload_form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<div class="hidden">
<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
</div>				
<input type="hidden" name="album" value="profile" />
				
<input type="hidden" name="ispage" value="1" />
				
<input type="hidden" name="page_id" value="2662" />
				
<input type="hidden" name="ajax" value="1" />
				<input type="file" name="userfile" value="" size="20" />				</form>			</div>
		</div>
		<div>
			<a id="upload_editthumb_lnk" style="display: block;" href="">Edit Thumbnail</a>
			<div id="imgupload_thumb_pane" style="display: none;">
				<div id="thumbnail" style="width:30px; height: 30px; border:1px #e5e5e5 solid; overflow:hidden; ">
					<img src="https://s3.amazonaws.com/fantoon-dev/pages/default/default_pic.jpg" style="vertical-align: top; position: relative; width: 30px; height: 30px;"/>
				</div>
								<form action="http://dmitry.fandrop.com/cropped_image" method="post" accept-charset="utf-8" name="thumbnail" id="thumb_form">
<div class="hidden">
<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
</div>				<input type="hidden" name="x1" id="x1" />
				<input type="hidden" name="y1" id="y1" />
				<input type="hidden" name="w" id="w" />
				<input type="hidden" name="h" id="h" />
				<input type="hidden" name="user" id="user" value=2662/>
				<input type="hidden" name="src_img" id="src_img" />
				<input type="hidden" name="ispage" id="ispage" value="1"/>
				<input type="hidden" name="page" id="page" value=2662/>
				<input type="submit" name="upload_thumbnail" value="Save Thumbnail" id="upload_thumb" />				</form>				<div id="thumb_saved" style="display:none; font-color: green; font-weight: bold;">New thumb saved</div>
				<div>Please Select an area on the picture for cropping</div>
			</div>
		</div>
	</div>
</div>

<div id="add_people_to_interest" style="display:none;">

    <div class="desc">
        Tech Crunch Disrupt 2011 has been added to your list of interests.
    </div>
    <div class="desc">
        You may want to be sharing this interest with your connections.
    </div>

        <form action="http://dmitry.fandrop.com/share_page_people" method="post" accept-charset="utf-8" id="share_page_people">
<div class="hidden">
<input type="hidden" name="ci_csrf_token" value="87ad4dcaa00f5bf3d6e5464296df0b72" />
</div>    
<input type="hidden" name="page_id" value="2662" />
    <div class="autocomplete_input">
        <span class="form_label">Share with</span>
        <span class="form_field"><input type="text" name="with_people" value="Add connection" id="interest_with_people" /></span>
    </div>
    <div>
        <span class="form_label">Description</span>
        <span class="form_field"><textarea id="with_description" class="input_placeholder" name="with_description" rows="4" cols="50">Describe your involvement</textarea></span>
    </div>
    <div>
        <span class="form_label"></span>
        <span>
            <input type="submit" name="submit" value="Save Changes"  />            <button id="skip" class="button">Skip this step</button>
        </span>
    </div>
    </form>
</div>

<script type="text/javascript">
/*Similarity Match Bar*/
get_page_SimilarityMatch();
function get_page_SimilarityMatch() { 
		//alert('similarity match');
	var val=$('#similarityScore_2').text(); 
	if(val>=75){
		$('#minisimilaritybar_2').removeClass().addClass('q75').animate({width:val+'%'},1000);
	}
	else if(val>=50){
		$('#minisimilaritybar_2').removeClass().addClass('q50').animate({width:val+'%'},1000);
	}
	else if(val>=25){
		$('#minisimilaritybar_2').removeClass().addClass('q25').animate({width:val+'%'},1000);
	}
	else{
		$('#minisimilaritybar_2').removeClass().addClass('q1').animate({width:val+'%'},1000);
	}
	}
</script>

<script type="text/javascript">



    function open_tab_menu() {
    	//console.log('open_tab_menu');
        var l_left = $('#more_tabs').offset().left;
        //var l_top = $('#more_loops').offset().top+$('#more_loops').height()+10;
        //alert(l_left+' '+l_top);
        $('#more_tabs_list').css('left',l_left+'px');
        view_hide('more_tabs_list');
    }

    $(function() {
        var excluded_tabs = ['add_new_tab','more_tabs'];
        var tabs_width_limit = $('#page_tabs_row > ul').width()-$('#add_new_tab').width()-$('#more_tabs').width()-10;
        //console.log($('#page_tabs_row > ul').width());
        init_tabs($('#more_tabs'), $('#page_tabs_row > ul'), $('#more_tabs_list'), tabs_width_limit, excluded_tabs);


		//Highlight proper tab
		$('.page_tab').removeClass('active_tab');
		var tab = 'interests';
		//alert(tab);
		switch(tab) {
			case 'events':
				$('#events_tab').addClass('active_tab');
				break;
			case 'page_info':
				$('#info_tab').addClass('active_tab');
				break;
			case 'photo_albums':
				$('#photos_tab').addClass('active_tab');
				break;
			case 'videos':
				$('#videos_tab').addClass('active_tab');
				break;
			case 'pr_page':
				$('#pr_tab').addClass('active_tab');
				break;
			default:
				$('#wall_tab').addClass('active_tab');
		}

		    var hoverintent_config = {
		        over: tc_show_badge,
		        timeout:200,
		        interval: 300,
		        out: tc_hide_badge
		    };
		    $('.tc_show_badge').hoverIntent(hoverintent_config);

/*
        $('#page_add_post .select_topics, #page_upload_photo .select_topics, #page_add_link .select_topics').tokenInput('/get_topics', {
            theme: "google",
            preventDuplicates: true,
            singleTokenOnly: false,
            queryParam: "q",
            searchDelay: 50,
            linkedText: true,
            placeholderText: "+ Add Topic",
            allowInsert: true,
            showDropdownOnFocus: false,
            onAdd: function(item) {
                $('#profile_add_post form').append('<input type="hidden" name="topic_id['+item.id+']" class="ac_topic_id" id="topic_id_'+item.id+'" value="'+item.id+'">');
                $('#profile_add_post form').append('<input type="hidden" name="topic_name['+item.id+']" class="ac_topic_name" id="topic_name_'+item.id+'" value="'+item.name+'">');
            },
            onDelete: function(item) {
                $('#profile_add_post').find('#topic_id_'+item.id).remove();
                $('#profile_add_post').find('#topic_name_'+item.id).remove();
            }
        });

		$('.autocomplete_input #interest_with_people').tokenInput("/ac_get_connections", {
			theme: "facebook",
			singleTokenOnly: false,
			preventDuplicates: true
		});

        $('#page_options #add_page').live('click',function() {
            $.get('/add_page/2662', function(data) {
                $('#add_people_to_interest').dialog({
                    width: 550,
                    height: 250,
                    modal: true
                });
                $('.autocomplete_input .form_field input').blur();
            });
            $(this).remove();
            return false;
        });

        $('#add_people_to_interest #skip').live('click', function() {
            $('#add_people_to_interest').dialog('close');
            return false;
        });

        $('.page_topic .remove_icon').live('click',function() {
            var item = $(this).closest('li');
            var url = $(this).parent().attr('href');
            $.get(url, function(data) {
                item.hide('fade').remove();
            });
            return false;
        });

        $('#page_new_topic_submit').live('click', function() {
            var form = $(this).closest('form');
            var url = form.attr('action');
            var topics = $('.autocomplete_input #load_topics').tokenInput('get');
            //prepare_autocomplete('#new_topic','#load_topics','#topic_names');
            var data = {
                topics: JSON.stringify(topics),
                ci_csrf_token: $("input[name=ci_csrf_token]").val()
            };
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(msg) {
                    $('#new_topic').hide('blind');
                    msg = $(msg);
                    msg.find('li').hide();
                    $('#page_topics').append(msg);
                    msg.find('li').show('fade');
                    $('.autocomplete_input #load_topics').tokenInput('clear');
                }
            });
            return false;
        });
*/

    });
	//Enables Adding new Tab to page
	$('#add_new_tab').live('click', function() {
		var new_tab_form = $('#page_add_custom_tab');
		if (new_tab_form.css('display') === 'none')
			new_tab_form.show('blind');
		else
			new_tab_form.hide('blind');
	});

	//$('.newsfeed_entry_options').hide();
	//$('.newsfeed_entry_comments').hide();
	$('.newsfeed_entry_add_comment').hide();


function tc_show_badge() {
    if($('.badge').text()!='') {$('.badge').remove();return false;}
    //e.stopPropagation();
    var badge_style = $(this).find('.badge_style').text();

    var obj_id = $(this).find('.obj_id').text();
    var x = $(this).find('.offset .x').text();
    var y = $(this).find('.offset .y').text();
    //console.log('['+x+','+y+']');
    var more_info = $(this).find('.more_info').html();

    if ($(this).hasClass('interests_item')) {
        //alert('aaa');
        th=$(this);
        th.append('<div class="badge"></div>');
        obj_id = $(this).attr('rel');
        $(this).find('.thumb').append('<div class="badge"></div>');
        var top = $(this).offset().top+$(this).height();
        var left = $(this).offset().left;
        $('.badge').offset({top: top,left: left});
    } else {
        th=$(this);
        th.append('<div class="badge"></div>');
    }
    console.log($(this).offset());
    if ($(this).find('.offset').length !== 0) {
        var top = parseInt($(this).offset().top)+parseInt(y);
        var left = parseInt($(this).offset().left)+parseInt(x);
        $('.badge').offset({top: top, left: left});
    }

    var type = ($(this).hasClass('user_avatar')) ? 'user' : 'page';
    //$('.badge').append('<div class="loading">Loading...</div>');
    if ($(this).find('.badge_style').length !== 0) {
        $('.badge').addClass(badge_style);
    } else {
        $('.badge').addClass('badge_light');
    }
    var badge_dir = $(this).find('.badge_direction').text();
    var link_disable = $('#link_disable').text();
    console.log(badge_dir);
    if ($(this).find('.badge_direction').length !== 0) {
        $('.badge').addClass(badge_style+'_'+badge_dir);
    }
    $('.badge').load('/tc_badge/'+type+'/'+obj_id+'/'+link_disable, function() {
        //var badge_src = $(this).closest('.show_badge');
        if (more_info !== null && more_info !== undefined) {
            more_info = $(more_info);
            $('.badge').prepend(more_info).css('padding-top','0px');
            more_info.show();
        }
        $('.badge').fadeIn(500);
    });
}
function tc_hide_badge() {
    $('.badge').fadeOut('fast',function(){
        $('.badge').remove();
    });
}



</script>
<script type="text/javascript" src="http://dmitry.fandrop.com/js/jquery.watermarkinput.js"></script>
<script type="text/javascript">
	// <![CDATA[
	$(document).ready(function(){var key_typing=0; // thats sets variable called typing - it is needed to see how much times a keyboard button is clicked - if it is 1
		$('#url_submit').live('click', function(){
            var foundurl=$('#url').val(); // button clicked - parse the value to a var foundurl
		    if(foundurl!=''){ // make sure that there is foundurl
		        if(!isValidURL(foundurl)) // isValidURL is external function,which is at the bottom of the page. It checks if the found url looks like real url
			    {return false;} // do nothing if the url is bad
			    else
			    {
                    $.post("/application/views/test_url.php?url="+foundurl,function(alive){
                        //if(alive==='works' && $('.url').length==0){ // so here we use test_url.php ! That tests if the foundurl is alive by using ajax.There is also a small rule that checks if previous url has already loaded.
                        if(alive==='works'){ // so here we use test_url.php ! That tests if the foundurl is alive by using ajax.There is also a small rule that checks if previous url has already loaded.
                            $('#user_text').show('blind');
                            $('#load').show(); // show loading image
                            //alert('about to fetch link');
                            $.post("/fetch_link?url="+foundurl, { // make ajax request to the fetch.php with the foundurl
                            }, function(response){ // ajax have returned a content
                                $('#loader').html($(response).fadeIn('slow')); //show the ajax returned content
                                $('.images img').hide(); //hide all images found
                                $('#load').hide(); //content already loaded - no need of loading bar anymore
                                $('img#1').load(function(){$('img#1').fadeIn().onerror(function(){$('img#1').hide();});}); // when the first image loads see if it doesn't give error or actually exists.If there is a problem just hide the image field
                                var totimg=$('.images img').length; // all images count
                                $('#total').html(totimg); // show the count of images
                                var currentimg=1; //set the first image to 1
                                if(totimg>0){$('#current').html('1');}else{$('#current').html('0');} // some small fix for showing if there is 1 or 0 images available
                                if(totimg==1){$('#navi').hide()} // thats remove the navigation (next and prev images) if the image is only 1

                                $('#next').click(function(){if((currentimg)<totimg){currentimg=currentimg+1; $('img#'+(currentimg-1)).hide(); $('img#'+currentimg).fadeIn(); $('#current').html(currentimg);}}); // just change the id to +1 if the next button is clicked.Also apply fade effects.
                                $('#prev').click(function(){if(currentimg!=1 || currentimg==2){currentimg=currentimg-1; $('img#'+(currentimg+1)).hide(); $('img#'+currentimg).fadeIn(); $('#current').html(currentimg);}}); // just change the id to -1 if the next button is clicked.Also apply fade effects.
                                $('#post_loop_info').show();

                            });
	                    }
		            });
		        }
            }
        });

		// watermark input fields (you know the yellow hover- thats left from the 99 site example)
		jQuery(function($){

		   $("#url").Watermark("http://");
		});
		jQuery(function($){

		    $("#url").Watermark("watermark","#369");

		});
		function UseData(){
		   $.Watermark.HideAll();
		   $.Watermark.ShowAll();
		}

	});

function isValidURL(url){
		var RegExp = /(\.){1}\w/;

		if(RegExp.test(url)){
			return true;
		}else{
			return false;
		}
	}

	// ]]>
</script>

<div class="clear"></div>
<div id="footer">
			<div class="container_24">
			<div class="grid_24">
			<a href="http://dmitry.fandrop.com/about/">About Us</a>
			<a href="http://blog.fantoon.com">Blog</a>
			<a href="/contactus">Contact Us</a>
			<a href="http://dmitry.fandrop.com/about/jobs">Jobs</a>
			<a href="/index.php/footer_controller/location/terms">Terms of Use</a>
			<a href="/index.php/footer_controller/location/privacy">Privacy</a>
			</div>
			<div class="clear">
			</div>
			<div class="grid_24">
			&copy;&nbsp;2011 - Fandrop
			</div>
		</div>
	</div>      
</body>
</html>