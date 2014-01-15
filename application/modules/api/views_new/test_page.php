<!DOCTYPE html>
<html>
	<head>
		<title>Fandrop API test interface</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script>
			var base_url = '<?=Url_helper::base_url()?>api';
			$(document).ready(function() {
				$('form').submit(function() {
					var data = $("textarea[name='post']").val().replace(/\n/g,'&');
					/*for (i in str) {
						str_item = str[i].split('=');
						if (str_item[0]) {
							data[str_item[0]] = str_item[1];
						}
					}*/
					if ($("select[name='method']").val() == 'POST') {
						//data['<?=$this->security->get_csrf_token_name()?>'] = '<?=$this->security->get_csrf_hash()?>';
						data += '&<?=$this->security->get_csrf_token_name()?>=<?=$this->security->get_csrf_hash()?>';
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

				$('code a').on('click', function() {
					var link = $(this).text();
					var method = $(this).parent().text().match(/type\: .*/mgi)[0].replace('type: ','');
					var params = $(this).parent().text().match(/params\: .*/mgi)[0].replace('params: ','').replace(/&/g,'\n');
					console.info(link, method, params);
					$('input.url').val(link);
					$('select.method').val(method);
					$('textarea.post').val(params);	
				});
				
				$("select[name='controller']").bind('change', generate_url);
				$("input[name='id']").bind('change', generate_url);
				$("select[name='sub-controller']").bind('change', generate_url);
				//$("select[name='method']").bind('change', generate_url);
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
						a.click(function() { $(this).next().toggle(); });
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
							<option value="PUT">PUT</option>
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
							<option value="comment">Comment</option>
							<option value="like">Like</option>
							<option value="folder_content">Folder_content</option>
						</select>
						<input type="text" name="id" value="" placeholder="Item ID">
						<select name="sub-controller">
							<option value="">-- Sub controller --</option>

							<option value="users">Users</option>
							<option value="pages">Pages</option>
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
							<option value="user_links">User_links</option>
							<option value="user_schools">User_schools</option>
							<optgroup label="Newsfeed">
								<option value="downloadhtml">downloadhtml</option>
							</optgroup>
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
							<?=base_url()?>api/{contoller}/{item_id}/{children}
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
						<h3>Formats</h3>
						<p>
							The default format is json to change it you need to provide a <em>format</em> parameter in the request.<br/>
							Supported formats by the api are: json, xml, jsonp. <br/>
							When using jsonp format you will need to provide additional <em>method</em> parameter defining the method
							to be used. <br/>
							For example to login the jsonp url will look like this:
							 <code>
							 	<br/>url: <?=base_url()?>auth?format=jsonp&method=post&callback=callback&email=a%40a.com&password=123456
							 </code>
						</p>
						<h3>Listing params</h3>
						<ul>
							<li><strong>page_limit</strong> Default: 10 - Items to show per page</li>
							<li><strong>page</strong> Default: 1 - Current page to show</li>
							<li><strong>sort_by</strong> Default: (primary_key) - Sort column </li>
							<li><strong>sort_order</strong> Default: desc - Sort results ascending (asc) or descending (desc)</li>
						</ul>
						<h3>Auth</h3>
						<p>
							To authenticate to the site send a POST request to the auth module.
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/auth</a>
								<br/>type: POST
								<br/>params: email=a@a.com&password=123456
							</code>
						</p>
						<h3>Modules</h3>
						<h4>Foder</h4>
						<p>
							Lists
							<br/>All lists
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Create a list
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder</a>
								<br/>type: POST
								<br/>params: folder_name=my_new_folder
							</code>
							<br/>Popular lists
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/popular</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>
							<br/>Delete List
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->get_by(array('user_id'=>$this->user->id))->folder_id?></a>
								<br/>type: DELETE
								<br/>params: 
							</code>
							<br/>List Drops
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->has_newsfeeds()->get_by(array('private'=>'0'))->folder_id?>/newsfeeds</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>List Followers
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->has_followers()->get_by(array('private'=>'0'))->folder_id?>/folder_users</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Follow a list
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->not_followed($this->user->id)->get_by(array('private'=>'0'))->folder_id?>/folder_users</a>
								<br/>type: PUT
								<br/>params: 
							</code>
							<br/>Unfollow a list
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->filter_followed($this->user->id)->get_by(array('private'=>'0'))->folder_id?>/folder_users</a>
								<br/>type: DELETE
								<br/>params: 
							</code>
							<br/>
							<br/>List Contributors
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->filter_has_contributors()->get_by(array('private'=>'0'))->folder_id?>/folder_contributors</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Update (add/remove) Contributors
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->get_by(array('private'=>'0','user_id'=>$this->user->id))->folder_id?>/folder_contributors</a>
								<br/>type: POST
								<br/>params: user_id[]=<?=implode('&user_id[]=', $this->user_model->limit(3)->dropdown('id','id'))?>
							</code>
							<br/>List likes
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->has_likes()->get_by(array('private'=>'0'))->folder_id?>/likes</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Like a list
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->not_liked($this->user)->get_by(array('private'=>'0'))->folder_id?>/likes</a>
								<br/>type: PUT
								<br/>params: 
							</code>
							<br/>Unlike a list
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/folder/<?=$this->folder_model->order_by('ranking','desc')->filter_liked($this->user)->get_by(array('private'=>'0'))->folder_id?>/likes</a>
								<br/>type: DELETE
								<br/>params: 
							</code>
						</p>
						<h4>Newsfeed</h4>
						<p>
							All newsfeed
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Post newsfeed item (Html)
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed</a>
								<br/>type: POST
								<br/>params: folder_id=<?=$this->user->get('folders')->get_by(array())->folder_id?>&description=Test&link_type=html&activity[link][content]=<?=urlencode('<h1>Some content</h1>')?>
							</code>
							<br/>Post newsfeed item (Live link)
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed</a>
								<br/>type: POST
								<br/>params: folder_id=<?=$this->user->get('folders')->get_by(array())->folder_id?>&description=Test&link_type=content&link_url=google.com
							</code>
							<br/>Post newsfeed item (Video)
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed</a>
								<br/>type: POST
								<br/>params: folder_id=<?=$this->user->get('folders')->get_by(array())->folder_id?>&description=Test&link_type=embed&link_url=<?=urlencode("http://www.youtube.com/watch?v=QH2-TGUlwu4")?>
							</code>
							<br/>Post newsfeed item (Text)
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed</a>
								<br/>type: POST
								<br/>params: folder_id=<?=$this->user->get('folders')->get_by(array())->folder_id?>&description=Test&link_type=text&activity[link][content]=Sample%20text
							</code>
							<br/>Post newsfeed item (Image)
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed</a>
								<br/>type: POST
								<br/>params: folder_id=<?=$this->user->get('folders')->get_by(array())->folder_id?>&description=Test&link_type=image&img=<?=urlencode("http://www.fandrop.com/images/panda.jpg")?>
							</code>
							<br/>Get newsfeed likes
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed/<?=$this->newsfeed_model->has_likes()->order_by('newsfeed_id','desc')->get_by(array())->newsfeed_id?>/likes</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Like a newsfeed
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed/<?=$this->newsfeed_model->not_liked($this->user)->order_by('newsfeed_id','desc')->get_by(array())->newsfeed_id?>/likes</a>
								<br/>type: PUT
								<br/>params: 
							</code>
							<br/>Unlike a newsfeed
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed/<?=$this->newsfeed_model->filter_liked($this->user)->order_by('newsfeed_id','desc')->get_by(array())->newsfeed_id?>/likes</a>
								<br/>type: DELETE
								<br/>params: 
							</code>
							<br/>Get newsfeed comments
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed/<?=$this->newsfeed_model->has_comments()->order_by('newsfeed_id','desc')->get_by(array())->newsfeed_id?>/comments</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Add newsfeed comment
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/newsfeed/<?=$this->newsfeed_model->order_by('newsfeed_id','desc')->get_by(array())->newsfeed_id?>/comments</a>
								<br/>type: PUT
								<br/>params: comment=Sample%20comment
							</code>
						</p>
						<h4>Comment</h4>
						<p>
							<br/>Delete a comment
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/comment/<?=$this->comment_model->order_by('comment_id','desc')->get_by(array('user_id_from'=>$this->user->id))->comment_id?></a>
								<br/>type: DELETE
								<br/>params: 
							</code>
							<br/>Comment likes
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/comment/<?=$this->comment_model->order_by('comment_id','desc')->has_likes()->get_by(array())->comment_id?>/likes</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Like a comment
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/comment/<?=$this->comment_model->order_by('comment_id','desc')->not_liked($this->user)->get_by(array())->comment_id?>/likes</a>
								<br/>type: PUT
								<br/>params: 
							</code>
							<br/>Unlike a comment
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/comment/<?=$this->comment_model->order_by('comment_id','desc')->filter_liked($this->user)->get_by(array())->comment_id?>/likes</a>
								<br/>type: DELETE
								<br/>params: 
							</code>
						</p>
						<h4>User</h4>
						<p>
							List users
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Register
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user</a>
								<br/>type: POST
								<br/>params: email=apitest@fandrop.com&uri_name=apitest&first_name=api&last_name=test&password=123456
							</code>
							<br/>Get a user (provides more details)
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('followers_count','desc')->get_by(array())->user_id?></a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Get user user_followers
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('followers_count','desc')->get_by(array())->user_id?>/user_followers</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Get user user_followings
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('followings_count','desc')->get_by(array())->user_id?>/user_followings</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Follow a user
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('followers_count','desc')->get_by(array())->user_id?>/user_followers</a>
								<br/>type: PUT
								<br/>params: 
							</code>
							<br/>Unfollow a user
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('followers_count','desc')->get_by(array())->user_id?>/user_followers</a>
								<br/>type: DELETE
								<br/>params: 
							</code>
							<br/>User lists
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('followers_count','desc')->get_by(array())->user_id?>/folders</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>User Drops
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('drops_count','desc')->get_by(array())->user_id?>/newsfeeds</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>User Upvotes
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('drops_count','desc')->get_by(array())->user_id?>/likes</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>User Mentions
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/user/<?=$this->user_stats_model->order_by('drops_count','desc')->get_by(array())->user_id?>/mentions</a>
								<br/>type: GET
								<br/>params: 
							</code>
						</p>
						<h4>ME</h4>
						<p>
							Me - Exending user module and returns some private data. Extends are like: /me/folders, /me/mentions, /me/likes etc.
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/me</a>
								<br/>type: GET
								<br/>params: 
							</code>
							<br/>Notifications
							<code>
								<br/>url: <a href="#"><?=base_url()?>api/notification</a>
								<br/>type: GET
								<br/>params: 
							</code>
							
					</li>
				</ul>
			</div>
		</div>
	</body>
</html> 