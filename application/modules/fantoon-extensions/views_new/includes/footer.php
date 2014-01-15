<? $this->lang->load('includes/includes_views', LANGUAGE); ?>
<div class="clear"></div>
<? // disable footer requested by http://dev.fantoon.com:8100/browse/FD-2656 ?>
<? if (!@$hide_footer) { ?>
	<div id="footer">
		<div id="about_bottomTabs_container">
			<span class="about_moreText">More:</span>
			<ul id="about_bottomTabs" class="">
				<li id="about_tab_main">
					<a href="/about/">About Fandrop</a>
				</li>
				<li id="about_tab_partners">
					<a href="/about/partners">Partners</a>
				</li>
				<li id="about_tab_publishers">
					<a href="/publishers">Publishers</a>
				</li>
			</ul>
		</div>
		<!--
		<div class="inner_footer">
			<span>More:</span>
			<ul>
				<li><a href="/about/">About Us</a> | </li> 
				<li><a href="http://blog.fantoon.com">Blog</a> | </li>
				<li><a href="/about/contactus">Contact Us</a> | </li>
				<li><a href="/about/jobs">Jobs</a> | </li>
				<li><a href="/index.php/about/terms">Terms of Use</a> | </li>
				<li><a href="/index.php/about/privacy">Privacy</a></li>
			</ul>
			<span style="text-align: center"><?=$this->lang->line('includes_views_footer_text');?></span>
		</div>
		-->
		<div class="clear"></div>
	</div>
<? } ?>
<div class="clear"></div>
<script type="text/javascript">
	var bad_drop = "<?=Url_helper::s3_url()?>links/bad_drop_thumb.png";
	
	var links = document.getElementsByTagName('a');
	for (var i=0; i<links.length; i++) {
		var a = links[i];
		if (a.rel == 'ajaxButton' || a.rel == 'popup' || (a.getAttribute('href') && a.getAttribute('href').indexOf('#') > -1)) {
			a.onclick = function(e) { return false }; 
			//a.addEventListener("click", function(e){ e.preventDefault(); }, false);
		}
	}

	//BP: #FD-2216
	//disable the rel=popup attribute until the code that populates the poup is loaded, it will be re-enabled there
	if ( !window.__ft_drop_preview_loaded ) {
		links = document.querySelectorAll( 'a[href="#preview_popup"][rel="popup"], div[data-url="#preview_popup"][rel="popup"], li[data-url="#preview_popup"][rel="popup"]' );
		for ( var i = links.length - 1; i >= 0; --i ) {
			links[i].setAttribute( 'rel', 'popup-disabled' );
		}
	}
	//end of #FD-2216

	function _set_img_size(img) {
		console.info("EXEC", img);
		if (img.className.indexOf('imgLoaded') == -1) {
			img.className += ' imgLoaded';
		} else {
			return;
		}
		//img.parentNode.offsetWidth
		if (img.width < 576) {
			img.parentNode.parentNode.className = img.parentNode.parentNode.className.replace('watermarked','');
			img.className = img.className.replace('has_zooming','');
		} else {
			img.className = img.className.replace('has_zooming','zooming');
		}
		
		if (img.width < img.parentNode.parentNode.offsetWidth) {
			img.width = Math.min(img.parentNode.parentNode.offsetWidth, img.width*1.2);
		}
		
		//var margin = Math.max(0, (img.parentNode.parentNode.offsetHeight - img.parentNode.offsetHeight)/2);
		//img.parentNode.style.marginTop = margin+'px';
	}

	function exec_img_correction() {
		//RR - IE8 does not support getElementsByClassName. However, it does support querySelectorAll
		var imgs = document.querySelectorAll('#folder_ugc_top .bigBox img, .newsfeed_upperContent .photo-container img, .newsfeed_entry .photo-container img, .tile_new_entry .drop-preview-img, .postcard_entry .drop-preview-img');
		for (var i=0;i<imgs.length;i++) {
			var img = imgs[i];
			img.onload = function() { _set_img_size(this); };
			if (img.width > 20 && img.height > 20) {
				_set_img_size(img);
			}
		}

		/* Center text contents in newsfeed */
		var newsfeed = document.getElementById('list_newsfeed');
		if (newsfeed) {
			var txts = newsfeed.querySelectorAll('.text_wrapper, .textContainer');
			console.info(txts);
			for (var i=0; i < txts.length; i++) {
				txts[i].style['margin-top'] = ((txts[i].parentNode.offsetHeight - txts[i].offsetHeight)/2)+'px';
			}
		}
	}
	exec_img_correction();
</script>