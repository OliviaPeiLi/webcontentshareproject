<!DOCTYPE html>
<html>
	<head>
		<title>Fandrop API test interface</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>
			var base_url = '<?=base_url()?>api';
			$(document).ready(function() {
				$('form').submit(function() {
					var data = {};
					var str = $("textarea[name='post']").val().split("\n");
					for (i in str) {
						str_item = str[i].split('=');
						if (str_item[0]) data[str_item[0]] = str_item[1];
					}
					if ($("select[name='method']").val() == 'POST') {
						data['<?=$this->security->get_csrf_token_name()?>'] = '<?=$this->security->get_csrf_hash()?>';
					}
					
					$.ajax($("input[name='url']").val(), {
						type: $("select[name='method']").val(),
						data: data,
						success: function(data) {
							$('#result').html('');
							format_result(data, $('#result'));
						},
						error: function (data, status) {
							$('#result').html('<li>'+status+': '+data.responseText+'</li>');
						}
					});
					return false;
				});
				
				$("select[name='controller']").bind('change', generate_url);
				$("input[name='id']").bind('change', generate_url);
				$("select[name='sub-controller']").bind('change', generate_url);
				$("select[name='method']").bind('change', generate_url);
				generate_url();
			});

			function format_result(data, item) { 
				var li, a, strong, ul;
				for (i in data) {
					li = $(document.createElement('li'));
					if (typeof data[i] == 'object' || typeof data[i] == 'array') {
						a = $(document.createElement('a'));
						a.html(i).attr('href','javascript:;');
						li.append(a);
						a.click(function() { $(this).next().toggle(); })
						ul = $(document.createElement('ul'));
						li.append(ul);
						format_result(data[i], ul);
					} else {
						strong = $(document.createElement('span'));
						strong.html(i).attr('href','javascript:;');
						li.append(strong);
						li.html(li.html()+': '+data[i]);
					}
					item.append(li);
				}
			}

			function generate_url() {
				var url = base_url;
				if ($("select[name='controller']").val()) url += '/'+$("select[name='controller']").val();
				if ($("input[name='id']").val()) url += '/'+$("input[name='id']").val();
				if ($("select[name='sub-controller']").val()) url += '/'+$("select[name='sub-controller']").val();
				
				$("input[name='url']").val(url);
				/*
				if ($("select[name='method']").val() == 'POST') {
					$('#post_params').show('fade');
				} else {
					$('#post_params').hide('fade');
				}
				*/
			}
		</script>
		<style type="text/css">
			form strong { display: block; width: 100px; float:left;}
			form input.url { width: 500px; }
			#result ul li ul {
				display:none;
			}
			#result span {
				font-weight: bold;
			}
		</style>
	</head>
	<body>
		<div>
			<form action="" method="get">
				<fieldset>
					<div>
						<strong>URL: </strong>
						<input type="text" class="url" name="url" value="Loading..."/>
						<select name="method" class="method">
							<option value="GET">GET</option>
							<option value="POST">POST</option>
							<option value="DELETE">DELETE</option>
						</select>
						<input type="submit" name="submit" value="Submit"/>
					</div>
					<div>
						<strong>Options: </strong>
						<select name="controller">
							<option value="auth">Auth</option>
							<option value="me">Me</option>
							<option value="user">User</option>
							<option value="page">Page</option>
							<option value="topic">Topic</option>
							<option value="folder">Folder</option>
							<option value="interests_list">Interests_list</option>
							<option value="connection">Connection</option>
							<option value="notification">Notification</option>
							<option value="message">Message</option>
							<option value="message/inbox">Message/Inbox</option>
							<option value="message/thread">Message/Thread</option>
							<option value="newsfeed">Newsfeed</option>
							<option value="page_user">Page_user</option>
							<option value="folder_user">Folder_user</option>
							<option value="list_page">List_page</option>
							<option value="list_users">List_users</option>
							<option value="topic_children">Topic_children</option>
							<option value="topic_page">Topic_page</option>
							<option value="comment">Comment</option>
							<option value="like">Like</option>
							<option value="folder_content">Folder_content</option>
						</select>
						<input type="text" name="id" value="" placeholder="Item ID">
						<select name="sub-controller">
							<option value="">-- Sub controller --</option>

							<option value="users">Users</option>
							<option value="pages">Pages</option>
							<option value="topics">Topics</option>
							<option value="folders">Folders</option>
							<option value="interests_list">Interests_list</option>
							<option value="followers">Followers</option>
							<option value="followings">Followings</option>
							<option value="message">Message</option>
							<option value="message/inbox">Message/Inbox</option>
							<option value="message/thread">Message/Thread</option>
							<option value="newsfeed">Newsfeed</option>
							<option value="page_users">Page_users</option>
							<option value="list_pages">List_pages</option>
							<option value="list_users">List_users</option>
							<option value="topic_children">Topic_children</option>
							<option value="topic_pages">Topic_pages</option>
							<option value="user_links">User_links</option>
							<option value="user_schools">User_schools</option>
						</select>
					</div>
					<div id="post_params">
						<strong>Post params:</strong>
						<textarea name="post" class="post" rows="7" cols="78"></textarea>
					</div>
				</fieldset>
			</form>
			<div>
				<ul id="result">
					<li>
						<h3>Overview: </h3>
						<p>
						The api is structured as a deafult REST service. It accepts GET, POST and DELETE requests.
						</p>
						<h3>Generating URLs: </h3>
						<p> 
						The urls are generated as follows:
						<code>
							<?php echo base_url()?>api/{contoller}/{item_id}/{children}
						</code>
						<ul>
							<li>
								<strong>Controller</strong> - can be any of the provided in the select element above. Controllers represent 
								the main modules in the frontend. When no other params are specified the index view of the controller is loaded.
								The index view shows a list of all items for the specified controller. Each index view accepts filter params 
								via GET.
							</li>
							<li>
								<strong>Item ID</strong> (optional) - When this parameter is set then the full view of the current item will be
								returned. The full view can hold more fields than the index view.
							</li>
							<li>
								<strong>Children</strong> (optional) - If this parameter is set then an index view will be returned, but the 
								results will be filtered only for the current parent element (the controller).
						</ul>
						<h3>Auth</h3>
						<p>
							To authenticate to the site send a POST request to the auth module.
							<code>
								<br/>url: <a href="#" onclick="$('input.url').val($(this).html()); $('select.method').val('POST'); $('textarea.post').val('email=a@a.com\npassword=123456')"><?php echo base_url()?>api/auth</a>
								<br/>type: POST
								<br/>params: email=a@a.com&password=123456
							</code>
						</p>
						<h3>Documents</h3>
						<p>
						<br/>User:
                        <br/>
                        <br/>Show user's information and email settings.
                        <br/>children option:
                        <br/>1. pages: get all the followed pages information
                        <br/>2. topics: get all the topics this user related
                        <br/>3. folders: get this user's folders and newsfeed in this folder 
                        <br/>4. followers: get all the followers information
                        <br/>5. followings: get all the followings information
                        <br/>6. page_users: get user's following page's follow information
                        <br/>7. user_links: get user's links
                        <br/>8. user_schools: get user's education information
                        <br/>9. Lists
                        <br/>
                        <br/>Page:
                        <br/>
                        <br/>Show interest's information and newsfeed on this page
                        <br/>children option:
                        <br/>1. page_users: get all the users following this interest page
                        <br/>2. list_pages: get all the list's information this page in
                        <br/>3. topic_pages: get all the topic's information this page in
                        <br/>4. discover: discover interests data
                        <br/>5. fav_5: get logged in user's fav_5
                        <br/>
                        <br/>Topic:
                        <br/>Show topic's information and parent topic's basic information, parent and children topics
                        <br/>children option:
                        <br/>1. topic_pages: get all the interest pages under this topic
                        <br/>
                        <br/>Folder:
                        <br/>Show folder's information and newsfeed, and users who follow this folder
                        <br/>children option:
                        <br/>1. folder_users: get all the followers of this folder
                        <br/>
                        <br/>Interests_list:
                        <br/>Show Interests_list basic information
                        <br/>children option:
                        <br/>1. users: get all the followers
                        <br/>2. pages: get all the pages in this list
                        <br/>
                        <br/>Newsfeed:
                        <br/>Show newsfeed
                        <br/>children option:
                        <br/>1. home: get home newsfeed
                        <br/>2. profile: get user's profile newsfeed
                        <br/>
                        <br/>Notification:
                        <br/>Show notifications
                        <br/>Children option
                        <br/>1. home: show notification of the logged in user's
                        </p>
						<h3>Examples</h3>
						You can click on the url of each of the examples to automaticaly copy to the test form.
						<p>
							Getting the info of the logged in user:
							<code>
								<br/>url: <a href="#" onclick="$('input.url').val($(this).html()); $('select.method').val('GET');"><?php echo base_url()?>api/me</a>
								<br/>type: GET
								<br/>params: none
							</code>
						</p>
						<p>
							Getting list of users:
							<code>
								<br/>url: <a href="#" onclick="$('input.url').val($(this).html()); $('select.method').val('GET');"><?php echo base_url()?>api/user</a>
								<br/>type: GET
								<br/>params: none
							</code>
						</p>
						<p>
							Searching the users list:
							<code>
								<br/>url: <a href="#" onclick="$('input.url').val($(this).html()+'?filters[first_name]=ray'); $('select.method').val('GET');"><?php echo base_url()?>api/user</a>
								<br/>type: GET
								<br/>params: filters[first_name]=Ray
							</code>
						</p>
						<p>
							Getting info for specific user:
							<code>
								<br/>url: <a href="#" onclick="$('input.url').val($(this).html()); $('select.method').val('GET');"><?php echo base_url()?>api/user/4</a>
								<br/>type: GET
								<br/>params: none
							</code>
						</p>
						<p>
							Getting the list of folders(collections) for specific user:
							<code>
								<br/>url: <a href="#" onclick="$('input.url').val($(this).html()); $('select.method').val('GET');"><?php echo base_url()?>api/user/4/folders</a>
								<br/>type: GET
								<br/>params: none
							</code>
						</p>
						<p>
							Delete a folder:
							<code>
								<br/>url: <a href="#" onclick="$('input.url').val($(this).html()); $('select.method').val('DELETE');"><?php echo base_url()?>api/folder/12345678</a>
								<br/>type: DELETE
								<br/>params: none
							</code>
						</p>
					</li>
				</ul>
			</div>
		</div>
	</body>
</html>