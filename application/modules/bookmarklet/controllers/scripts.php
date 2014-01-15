<?php
/**
 * This file is for loading the scripts from the bookmarklet 
 */
class Scripts extends MX_Controller {
	
	/**
	 * Get the latest bookmarklet version
	 */
	function js() {
		$latest = 0;
		if ($this->css_filenames && isset($this->css_filenames['/js/bookmarklet/external.js'])) {
			$latest = $this->css_filenames['/js/bookmarklet/external.js'][0];
		}
		header('Content-Type: application/x-javascript');
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		$base = Url_helper::base_url();
		if (ENVIRONMENT != 'development') {
			$base = str_replace('http:', 'https:', $base);
		}
		if (file_exists(BASEPATH.'../uploads/system/maintenance.html')) {
			$url = "load_web_scraper/maintenance.js";
		} else {
			$url = "load_web_scraper/external.js";
		}
		echo "(function(){e=document.createElement('script');e.setAttribute('id','web_scraper');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','".$base.$url."?v=".$latest."');document.body.appendChild(e);})();";
	}
	
	function external() {
		$this->output->enable_profiler(FALSE);

		header('Content-Type: application/x-javascript');
		$this->load->view('js.js', array('theBaseUrl'=>Url_helper::base_url()));
	}
	
	function maintenance() {
		$this->output->enable_profiler(FALSE);

		header('Content-Type: application/x-javascript');
		$this->load->view('maintenance.js');
	}
	
	function embed_js() {
		$this->output->enable_profiler(FALSE);

		header('Content-Type: application/x-javascript');
		$this->load->view('embed_js.js', array('theBaseUrl'=>Url_helper::base_url()));
	}
	
	function get_embed_count() {
		$this->load->model('link_model');
		$newsfeed_num = $this->link_model->count_by(array(
							'link' => $this->input->get('link'),
							'selector' => $this->input->get('selector')
						));
		echo "jQuery('#fandrop_embed_btn span').html('".$newsfeed_num."');";
	}
	
	public function pdf2html() {
		$src = $this->input->get_post('src');
		$this->load->config('uploads');
		$this->load->library('scraper');
		$uploads_conf = $this->config->item('uploads');
		$pdf_file = $uploads_conf['path'].'pages/'.uniqid().'.pdf';
		$html_file = str_replace('.pdf', '-html.html', $pdf_file);
		$contents = $this->scraper->request($src);
		file_put_contents($pdf_file, $contents);
		exec("pdftohtml -s $pdf_file");
		$contents = file_get_contents($html_file);
		$contents = str_replace('src="', 'src="'.Url_helper::base_url().'/uploads/pages/', $contents);
		unlink($pdf_file); unlink($html_file);
		
		die(json_encode(array(
			'src' => $src,
			'html' => $contents
		)));
	}
	
	/**
	 * Updates the scraper_cache
	 */
	function update_cache() {
		$post = $this->input->post();
		$this->load->library('scraper');
		Scraper::update_cache($post['link'], $post['data']);
		die(json_encode(array('status'=>true)));
	}
	
	function watchdog() {
		die('OK');
	}
	
	function refresh_cache($activity_id) {
		$this->newsfeed_model->get_by(array('type'=>'link','activity_id'=>$activity_id))->update(array('complete'=>1));
		die('OK');
	}
	
	
	
}