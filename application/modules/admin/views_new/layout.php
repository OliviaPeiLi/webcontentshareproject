<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Administry - Admin Template by Zoran Juric</title>
<meta name="description" content="Administry - Admin Template by Zoran Juric" />
<meta name="keywords" content="Admin,Template" />
<!-- Favicons --> 
<link rel="shortcut icon" type="image/png" href="/images/admin/favicons/favicon.png"/>
<link rel="icon" type="image/png" href="/images/admin/favicons/favicon.png"/>
<link rel="apple-touch-icon" href="/images/admin/favicons/apple.png" />
<!-- Main Stylesheet --> 
<link rel="stylesheet" href="/css/admin/style.css" type="text/css" />
<!-- Your Custom Stylesheet --> 
<link rel="stylesheet" href="/css/admin/custom.css" type="text/css" />
<!--swfobject - needed only if you require <video> tag support for older browsers -->
<script type="text/javascript" src="/js/admin/swfobject.js"></script>
<!-- jQuery with plugins -->
<!-- <script type="text/javascript" src="/js/admin/jquery-1.4.2.min.js"></script> -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<!-- Could be loaded remotely from Google CDN : <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> -->
<script type="text/javascript" src="/js/admin/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="/js/admin/jquery.ui.widget.min.js"></script>
<script type="text/javascript" src="/js/admin/jquery.ui.tabs.min.js"></script>

<!-- jQuery tooltips -->
<script type="text/javascript" src="/js/admin/jquery.tipTip.min.js"></script>
<!-- Superfish navigation -->
<script type="text/javascript" src="/js/admin/jquery.superfish.min.js"></script>
<script type="text/javascript" src="/js/admin/jquery.supersubs.min.js"></script>
<!-- jQuery form validation -->
<script type="text/javascript" src="/js/admin/jquery.validate_pack.js"></script>
<!-- jQuery popup box -->
<script type="text/javascript" src="/js/admin/jquery.nyroModal.custom.js"></script>
<script type="text/javascript" src="/js/admin/jquery.datepick.pack.js"></script>
<script type="text/javascript" src="/js/admin/jquery.datepick-en-GB.js"></script>
<!-- jQuery wysiwyg editor -->
<script type="text/javascript" src="/js/admin/jquery.wysiwyg.min.js"></script>
<!-- Internet Explorer Fixes --> 
<!--[if IE]>
<link rel="stylesheet" type="text/css" media="all" href="css/ie.css"/>
<script src="/js/admin//html5.js"></script>
<![endif]-->
<!--Upgrade MSIE5.5-7 to be compatible with MSIE8: http://ie7-js.googlecode.com/svn/version/2.1(beta3)/IE8.js -->
<!--[if lt IE 8]>
<script src="/js/admin//IE8.js"></script>
<![endif]-->
<!-- Token List -->
<script type="text/javascript" src="/js/plugins/token-list.js"></script>
<!-- User interface javascript load -->
<script type="text/javascript" src="/js/admin/administry.js"></script>
<!-- custom js scripts-->
<script type="text/javascript" src="/js/admin/custom.js"></script>
<script type="text/JavaScript"> 
var php = {
		'csrf': {
		      'name': '<?= $this->security->get_csrf_token_name()?>',
		      'hash': '<?= $this->security->get_csrf_hash();?>'
		     },
		}
</script>
</head>
<body>
	<!-- Header -->
	<header id="top">
		<div class="wrapper">
			<!-- Title/Logo - can use text instead of image -->
			<div id="title">
				<a href="/" target="_blank">
					<img src="/images/fandropHeaderLogo.png" alt="Administry" /><!--<span>Administry</span> demo-->
				</a>
			</div>
			
			<?php if ($this->user) { ?>
				<!-- Top navigation -->
				<div id="topnav">
					<a href="/admin/users/<?=$this->user->id?>">
						<img class="avatar" src="<?=$this->user->avatar_42?>" alt="avatar" />
					</a>
					Logged in as <b>Admin</b>
					<span>|</span> <a href="#">Settings</a>
					<span>|</span> <a href="/logout">Logout</a><br />
					<?//<small>You have <a href="#" class="high"><b>1</b> new message!</a></small>?>
				</div>
				<!-- End of Top navigation -->
				
				<!-- Main navigation -->
				<nav id="menu">
					<ul class="sf-menu">
				    	<?php $admin_menus = array(  //RR this may be in the config
				    	    //url  => array(title, array(roles))
				    		'dashboard'		=>array('roles'=>array(1,2)),
				    		/*'site' 			=> array('roles'=>array(1,2)),*/
				    		'users' 		=> array('roles'=>array(1,2), 'sub_menus'=>array(
											    		//'badges'           => array('roles'=>array(2), 'parent'=>'users'),
											    		//'add_user'           => array('roles'=>array(1,2), 'parent'=>'users'),
				    									//'users_subscription' => array('roles'=>array(1,2), 'parent'=>'users'),
				    									'alpha_user' 	=> array('roles'=>array(2)),
				    									//'fb_drop'		=> array('roles'=>array(2)),
				    									'comments' 		=> array('roles'=>array(1,2)),
				    							)),
				    		'folders' 		=> array('roles'=>array(1,2)),
				    		'newsfeed' 		=> array('roles'=>array(1,2)),
				    		/*'link' 			=> array('roles'=>array(1,2)),*/
				    		/*'pages' 		=> array('roles'=>array(2)),*/
				    		'stats' 		=> array('roles'=>array(1,2)),
				    		/*'beanstalk' 	=> array('roles'=>array(2), 'sub_menus'=>array(*/
				    		'scripts' 	=> array('roles'=>array(2), 'sub_menus'=>array(
										    		'beanstalk_jobs' => array('roles'=>array(2), 'parent'=>'beanstalk'),
										    		'beanstalk_jobs_graph'=> array('roles'=>array(2), 'parent'=>'beanstalk'),
				    							)),
				    		'ban_site'		=> array('roles'=>array(2)),
				    		'system_notification'		=> array('roles'=>array(2)),
				    		'modules_config'=> array('roles'=>array(2)),
				    		/*'admin_requests'=> array('roles'=>array(2)),*/
				    		'newsletters'=> array('roles'=>array(2)),
				    	)?>
				    	<?php foreach ($admin_menus as $url=>$admin_menu_data) { ?>
				    		<?php if (!in_array($this->user->role, $admin_menu_data['roles'])) continue; ?>
							<li class="<?=$this->router->class==$url?'current':''?>">
								<a href="/admin/<?=$url?>"><?=$this->lang->line('admin_menu_'.$url);?></a>
								<?php if (isset($admin_menu_data['sub_menus'])) { ?>
								<ul>
									<? foreach($admin_menu_data['sub_menus'] as $url2 => $info) { ?>													
								    		<?php if (!in_array($this->user->role, $info['roles'])) continue; ?>
											<li><a href="/admin/<?=$url2?>"><?=$this->lang->line('admin_menu_'.$url2)?></a></li>
									<? } ?>
								</ul>
								<?php } ?>
							</li>
				    	<?php } ?>					
					</ul>
				</nav>
				<!-- End of Main navigation -->
	            
				<!-- Aside links -->
				<?php //<aside><b>English</b> &middot; <a href="#">Spanish</a> &middot; <a href="#">German</a></aside> ?>
				<!-- End of Aside links -->
			<?php } ?>
		</div>
	</header>
	<!-- End of Header -->
	<!-- Page title -->
	<div id="pagetitle">
		<div class="wrapper-login"></div>
	</div>
	<!-- End of Page title -->
	
	<!-- Page content -->
	<div id="page">
		<? $this->load->view($view)?>
	</div>
	<!-- End of Page content -->
	
	<!-- Page footer -->
	<footer id="bottom">
		<div class="wrapper-login">
			<p>Copyright &copy; 2012 <b><a href="http://www.fandrop.com" title="Fandrop">Fandrop</a></b> | Icons by <a href="http://www.famfamfam.com/">FAMFAMFAM</a></p>
		</div>
		
	</footer>
	<!-- End of Page footer -->
	<div id="debug"></div>
	<div id="loader"><img id="loaderimg" src="/images/admin/loading_animation.gif" alt="loader" /></div>
</body>
</html> 