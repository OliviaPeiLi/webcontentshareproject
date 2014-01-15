<!DOCTYPE HTML>
<? $this->lang->load('includes/includes_views', LANGUAGE); ?>
<html xmlns:fb="http://www.facebook.com/2008/fbml" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# theshots: http://ogp.me/ns/fb/theshots#" lang="en">
<head>
	<link rel="icon" type="image/ico" href="/images/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content='The best place to find content on the web.'/>
	<meta name="keywords" content="fan drop, fandrop, fandrop.com, drop it, drop, list, viral">
	<meta property="fb:app_id" content="<?=$this->config->item('fb_app_key')?>" />
	<?=isset($head_content) ? $head_content : ''?>
	<title><? echo $title; ?></title>

	<? //ALL CSS NEEDS TO BE PLACED INSIDE CONFIG/CSS.PHP ?>

	<? //ALL JS NEEDS TO BE PLACED AFTER CSS FOR PERFORMANCE REASONS ?>
	
	<? // Init RequireJS ?>
	<script type="text/javascript" src="/js/require.js"></script>
	<script type="text/javascript">
		function save_tmp_message(msg) {
			if (!msg) return;
			if (msg.data.indexOf('{') != 0) return;
			data = eval('('+msg.data+')');
			if (!data.fandrop_message) return;
			window.tmp_message = msg;
		}
		
		try {
			if (window.addEventListener) {
				window.addEventListener('message', save_tmp_message );
			} else if (window.attachEvent) {
				window.attachEvent('onmessage', save_tmp_message);
			} else {
				window.onmessage = save_tmp_message;
			}
		} catch(err)	{}
		
		<?php 
			$js_max_v = 0; 
			if (!$this->is_mod_enabled('optimized_js')) {
				foreach ($this->css_filenames as $css_filename=>$css_filename_data) 
					if (strpos($css_filename, '/js/') === 0) $js_max_v += $css_filename_data[0];
			}
		?>

		var php = {
				'first_visit': '<?=isset($first_visit) && $first_visit?>',
				'baseUrl': '<?=Url_helper::base_url()?>',
				'userId': <?=$this->session->userdata('id') ? $this->session->userdata('id') : 0?>,
				'ip': '<?=@$_SERVER['HTTP_X_FORWARDED_FOR'] ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']?>',
				'first_name': '<?=$this->session->userdata('id') ? $this->user->first_name : '';?>',
				'last_name': '<?=$this->session->userdata('id') ? $this->user->last_name : '';?>',
				'email': '<?=$this->session->userdata('id') ? $this->user->email : '';?>',
				'userUrl': '<?=$this->session->userdata('id') ? $this->user->uri_name : '';?>',
				'redirectUrl': '<?=$this->session->userdata('id') ? '/' : '/signup'?>',
				'baseCSS': '<?=$this->css_base?>',
				'serverTime' : '<?=date("YmdH");?>',
				'serverTimestamp' : '<?=time();?>',
				'csrf': {
						'name': '<?php echo $this->security->get_csrf_token_name()?>',
						'hash': '<?php echo $this->security->get_csrf_hash();?>'
					},
				'fb_app_id': '<?=$this->config->item('fb_app_key')?>',
				'fb_app_namespace': '<?=$this->config->item('fb_app_namespace')?>',
				'kissmetrics_key': '<?=$this->config->item('km_key')?>',
				's3url': '<?=Url_helper::s3_url()?>',
				<? if ($this->is_mod_enabled('landing_ugc')) { ?>
					'landing_ugc': true,
				<? } ?>
				<? if ($this->is_mod_enabled('design_ugc')) { ?>
					'design_ugc': true,
				<? } ?>
				'lang': {
					'error': {}
				}
		};
			
		require.config({
			urlArgs: "v=<?=$js_max_v?>",
			baseUrl: "<?=$this->js_base?>",
			paths: {
				<? //bobef: loading uncompressed version of jQuery on dev for easier debugging ?>
				"jquery": "<?= ENVIRONMENT == 'development' ? 'plugins/jquery.dev' : 'jquery.min' ?>",
				"jquery-ui": "<?= ENVIRONMENT == 'development' ? 'plugins/jquery-ui' : 'jquery-ui.min'?>"
			},
			waitSeconds: 30
		});
		
		require(["jquery"], function() {
			
			$(document).ready(function() {
				save_tmp_message = function() {};
				if (window.tmp_message) {
					$(window).trigger('message');
				}
			});
			window.onerror = function(errorMsg, file, lineNumber) {
				$.post('/new_relic_error', {
							'errorMsg': errorMsg, 
							'file': file, 
							'lineNumber': lineNumber, 
							'location': window.location.href,
							'agent': navigator.userAgent,
							'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash();?>'
				}, function(data) {
					
				},'json');
			};
		});
		
		require(["<?=$this->css_filenames ? str_replace(array('.js'),'',$this->css_filenames['/js/apis.js'][2]) : 'apis' ?>"]);
		
		//Define global vars for the APIs
		var _kmq = [];

		var _gaq = _gaq || []; 
		<?php if (ENVIRONMENT == 'development') { ?>
			_gaq.push(['_setAccount', 'UA-30228249-1']);
			_gaq.push(['_setDomainName', 'none']);
		<?php } elseif (ENVIRONMENT == 'staging') { ?>
			_gaq.push(['_setAccount', 'UA-40948828-1']); /*Test.Fandrop.com*/
		<?php } else { ?>
			_gaq.push(['_setAccount', 'UA-29771355-1']); /*Fandrop.com*/
		<?php  } ?>
		//_gaq.push(['_setAccount', 'UA-32412167-1']); /*ray.fantoon.com*/

		_gaq.push(['_trackPageview'<?=$this->router->fetch_method()=='landing_page' ? ", '/landing_page'" : ""?>]);	
	</script>
	
	<?php if ($this->input->get('qunit_tests')) { ?>
		<? $this->load->view('qunit_tests')?>
	<?php } ?>
	
	<? //Core modules ?>
	<?=Html_helper::requireJS(array(
		"connection/requests", /* Follow buttons */
		"common/dropdown", /* Ft_dropdown, notifications */
		"common/bootstrap_popup",
		"common/utils", /* loadCss, getUrlParams */
		"common/ajaxForm",
		"common/ajaxButton",
		"common/popup_info",
		"common/custom_title",
		"plugins/token-list", /* Dynamyc autocomplete */
	))?>
</head>

<body class="<?=$this->session->userdata('id') ? '' : 'logged_out'?> <?=$this->router->fetch_method()=='landing_page' ? 'landing' : ''?>">

	<div id="fb-root" style="display:none"></div>
	<?php if (!in_array($this->uri->segment(1), array('bookmarklet_walkthrough','choose_category','drop'))) { ?>
		<?php if ($this->is_mod_enabled('design_ugc')) : ?>
	    	<?=$this->load->view('newsfeed/drop_preview_popup_ugc')?>
		<?php else: ?>
			<?=$this->load->view('newsfeed/drop_preview_popup')?>
		<?php endif;?>
	<?php } ?>
	
	<? $this->load->view('share/newsfeed_share_email') ?>
	    
	<div id="fb-share-success-popup" class="modal" style="display:none; ">
		<div class="success-msg"><?=$this->lang->line('includes_views_fb_share_success')?></div>
	</div>
	
	<? if(!$this->session->userdata('invite_popup_shown') && !$this->session->userdata('id') && !$this->is_mod_enabled('open_signup') && empty( $_COOKIE['wli'] )){ ?>
	 	<? $this->session->set_userdata('invite_popup_shown', true) ?>
		<a href="#emailInvite_box" rel="popup" id="requestInvitePopup_trigger" style="display:none" title="">request invite</a>
	<? } ?>
	
	<?php if ($this->session->userdata('id')) { ?>
		<? $this->load->view('newsfeed/newsfeed_popup_edit')?>
		<? $this->load->view('newsfeed/newsfeed_delete_popup')?>
		<div id="confirm" style="display:none">
			<div class="modal-body">
				<span class="ico"></span>
				<p>Are you sure you want to delete?</p>
				<div class="form_row">
					<a href="javascript:;" rel="ajaxButton" data-dismiss="modal" class="blueButton confirmButton">Delete</a>
					<a href="javascript:;" data-dismiss="modal" class="greyButton cancelButton">Cancel</a>
				</div>
			</div>
		</div>
		<?=Html_helper::requireJS(array('common/confirm'))?>
	<?php } else { ?>
		<? $this->load->view('signup/request_invite_popup', array(
			'is_collection_page' => $this->router->fetch_class() == 'folder_controller' && $this->router->fetch_method() == 'folder'
		))?>	
	<? }?>
	
	<?= $this->load->view('includes/'.(isset($header) && $header ? $header : 'header')) ?>

	<?= $this->load->view($main_content)?>

	<?= $this->load->view('includes/'.(isset($footer) && $footer ? $footer : 'footer'))?>
	 
</body>
</html>
