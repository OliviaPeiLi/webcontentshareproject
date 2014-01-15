<?php if ($this->ban_site_model->is_banned(@$_SERVER['HTTP_REFERER'])) { ?>
	alert("This site is in our banlist. You cant clip content from here.");
<?php } else { ?>
	<?php $this->lang->load('bookmarklet/bookmarklet', LANGUAGE); ?>
	if (location.host.indexOf("fandrop.com") != -1 
			|| ( location.host.indexOf("fantoon.com") != -1 && location.host.indexOf("blog.fantoon.com") == -1)  
			|| location.host.indexOf('fantoon.local') != -1
			|| location.href.indexOf('http://ft/') != -1
	) {
		console.log('redropinfo'); 
		$('#open_redrop_info').trigger('click');
	} else if (!document.getElementById('scraping_overlay_iframe')) {
		if(('<?=ENVIRONMENT?>' != 'development' && '<?=@$_GET['qunit_tests']?>' != '') || typeof(window.console) != 'object' || typeof(console.log) == 'undefined') {
		    window.console = {};
		    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
		    if (typeof Firebug != 'undefined' && typeof Firebug.Console != 'undefined') {
		    	Firebug.Console.logRow = function() {}
		    }
		}
		//chrome test  window==top ||
	    if (document.compatMode == "BackCompat" && navigator.userAgent.indexOf('MSIE') > -1) {
			var html = document.documentElement.innerHTML;
			var iframe = document.createElement('iframe');
				iframe.width = (document.documentElement.offsetWidth-3)+'px';
				iframe.height = (document.documentElement.offsetHeight-3)+'px';
				iframe.src = 'about:blank';
			document.body.innerHTML = '';
			document.body.style.margin = document.body.style.padding = 0;
			document.body.style.overflowX = 'hidden';
			document.body.style.overflowY = 'hidden';
			document.body.appendChild(iframe);
			// http://www.msnbc.msn.com/ in IE
			html = html.replace('TExtFX-up2','textFX-up2'); 
			html = html.replace('"Explore/More news"','"Explore/More news" style="overflow:visible"');
			var iframeDoc = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;
				iframeDoc.open();
				iframeDoc.write('<!DOCTYPE html>'+html);
				iframeDoc.close();
	    } else {
	    	var fandrop_bookmarklet = function() {
	    		String.prototype.trim = null;
	    		<?php include(BASEPATH.'../application/modules/fantoon-extensions/js/plugins/jquery.min.js')?>
	    		
	    		var baseUrl = "<?=$theBaseUrl?>";
	    		var my_uri = "<?=@$this->user->uri_name?>";
	    		var php = {
	    				lang: {
	    						'intro_step_1': '<?=$this->lang->line("intro_step_1");?>',
	    						'intro_step_2': '<?=$this->lang->line("intro_step_2");?>',
	    						'point_and_click': '<?=$this->lang->line("point_and_click");?>'
	    				}
	    		}
	    		<?php $css_location = $this->is_mod_enabled('design_ugc') ? "/css/NEW/bookmarklet/external_ugc.css" : "/css/NEW/bookmarklet/external.css"; ?>
	    		var css = ['<?=$this->css_filenames ? $this->css_base.$this->css_filenames[$css_location][2]."?v=".$this->css_filenames[$css_location][0] : $theBaseUrl.$css_location ?>'];
	    		
	    		var logoIco = "<?=$this->is_mod_enabled('new_theme') ? $theBaseUrl."images/bookmarklet/logo2.png" : $theBaseUrl."images/FD.png";?>";
	    		<? $loading_ico = Html_helper::img_src('loading_icons/bigLoading_light.gif')?>
	    		var loaderIco = "<?=strpos($loading_ico, 'http') !== false ? $loading_ico : $theBaseUrl.$loading_ico?>";
	    		var preview_popup_view = '<?=str_replace(array("\r","\n"),"",$this->load->view("bookmarklet/bm_popup_form",'', true))?>';
	    		var general_layout = '<?=str_replace(array("\r","\n","'"),array("","","\'"),$this->load->view("bookmarklet/bm_general_layout",'', true))?>';
	    		var design_ugc = <?=$this->is_mod_enabled('design_ugc') ? 'true' : 'false'?>
	    		
	    		var scripts = [
	    			<?php $files = array(
	    					'bookmarklet/communicator.js',
	    					'bookmarklet/clipboard_ui_commons.js',
	    					'bookmarklet/clipboard_ui.js',
	    					'bookmarklet/mentions.js',
	    					'plugins/token-list.js',
	    			)?>
	    			<?php foreach($files as $file) { ?>
	    				<?= isset($this->css_filenames['/js/'.$file]) ? '"'.$this->js_base.$this->css_filenames['/js/'.$file][2].'"' : 'baseUrl+"js/modules/'.$file.'"'?>,
	    			<?php } ?>
	            ];
	    		
	    		<?php include(BASEPATH.'../application/modules/bookmarklet/js/bookmarklet/external.js')?>
	    		return this;
	    	}();
	    }
	}
	else
	{
		jQuery('#scraping_overlay').show();
		jQuery('#save_link').hide();
		jQuery('#fandrop_div').css('zIndex','').show();
		jQuery('#success_popup').hide('fade');
		//if (window.hide_timeout) {
		//	window.clearTimeout(window.hide_timeout);
		//}
		jQuery('#save_link iframe').unbind('load').attr('src', '<?=$theBaseUrl?>/bookmarklet/popup' );
		//fandrop_bookmarklet.iframe_ui.start();
		fandrop_bookmarklet.communicator.start();
	}
<?php } ?>
