<div id="folder_ugc_top" class="container">
	<? 
	if(!$cache = $this->cache->get($cache_name)) {
		ob_start(); ?>
		<div class="row" style="overflow:hidden">
			<div class="span16 bigBox">
				<? $this->load->view('folder/folder_ugc_item', array('folder'=>$folders[0],'size'=>'full'))?>
			</div>
			<div class="span8">
				<div class="mediumBox">
					<? $this->load->view('folder/folder_ugc_item', array('folder'=>$folders[1],'size'=>'320'))?>
				</div>
				<div class="mediumBox">
					<? $this->load->view('folder/folder_ugc_item', array('folder'=>$folders[2],'size'=>'320'))?>
				</div>
			</div>
		</div>
		<div class="row ugc_top_lowerSection" style="overflow: hidden">
			<div class="span8 mediumBox">
				<? if (isset($folders[4])&&$folders[4]) $this->load->view('folder/folder_ugc_item', array('folder'=>$folders[3],'size'=>'320'))?>
			</div>
			<div class="span8 mediumBox">
				<? if (isset($folders[5])&&$folders[5]) $this->load->view('folder/folder_ugc_item', array('folder'=>$folders[4],'size'=>'320'))?>
			</div>
			<div class="span8 mediumBox">
				<? if (isset($folders[6])&&$folders[6]) $this->load->view('folder/folder_ugc_item', array('folder'=>$folders[5],'size'=>'320'))?>
			</div>		
		</div>
		<? 
		$cache = ob_get_clean();
		$this->cache->save($cache_name, $cache);
	} ?>
	<? print($cache); ?>
</div>