<?php
/**
 * Magazine Layout base class
 * 
 * @desc Forms rows and cols layout from array of posts
 * @author radil
 * 
 * $post = array(
 * 		'id'=>(int),
 * 		'media'=>(string),  have to be null, html img tag for pictures, iframe or object for videos
 * 		'title'=>(string),
 * 		'content'=>(string),
 * 		'params'=> array(           //This array will be enlarged for next versions the high the better 0 to 1000
 *          'site_priority'=>0      //integer
 *          'num_comments'=>0       //integer
 * 
 *          'media'=>0              //Auto set to 0-none,1-image,2-video depends on the media
 *          'text_length'=>0        //Auto set num of chars
 *      )
 * );
 * paramters:
 *
 */
class Magazine_Layout {
	
	private $posts = null;
	private $multipliers = array(	
		'site_priority'=>5,
		'num_comments'=>1,
		'media'=>10,
		'text_length'=>0.1,
	);
	
	private $sizes = array(
		'1.000'=>array(array(1,1)), //'1'  =>
		'0.500'=>array(array(0.5,1)), //'1/2'=>
		'0.333'=>array(array(0.333,1)), //'1/3'=>
		'0.666'=>array(array(0.666,1)), //'2/3'=>
		'0.250'=>array(array(0.5,0.5),array(0.25,1)), //'1/4'=>
		'0.750'=>array(array(0.750,1)), //'3/4'=>
		'0.167'=>array(array(0.333,0.5),array(0.5,0.333)), //'1/6'=>
		'0.833'=>array(array(0.833,1)), //'5/6'=>
		'0.125'=>array(array(0.5,0.25)), //'1/8'=>
		'0.375'=>array(array(0.5,0.75)), //'3/8'=>
		'0.625'=>array(array(0.625,1)), //'5/8'=>
		'0.875'=>array(array(0.875,1)), //'7/8'=>
		'0.111'=>array(array(0.333,0.333),array(0.5,0.222)), //'1/9'=>
		'0.222'=>array(array(0.333,0.666),array(0.5,0.444)), //'2/9'=>
		//'0.444'=>array(array(1,1)), //'4/9'=>
		//'0.555'=>array(array(1,1)), //'5/9'=>
		'0.777'=>array(array(0.777,1)), //'7/9'=>
		'0.888'=>array(array(0.888,1)), //'8/9'=>
	);
	
	public function __construct($posts=array()) {
		$this->posts = $posts;
	}
	
	public function add_post($post) {
		$this->posts[] = $post;
	}
	/**
	 * 
	 */
	public function rend($data, $level=0) {
		$out = '';
		foreach ($data as $key=>$item) {
			if (!is_int($key)) continue;
			$out .= '<div style="float:left;width:'.(isset($item['width'])?($item['width']*100):100).'%;height:'.(isset($item['height'])?($item['height']*100):100).'%" class="'.($level==0?'mag_lay-col':'mag_lay-row').' '.($item['post']['changed'] ? 'changed' : '').'">';
			if (isset($item['post'])) {
				$out .= '<div class="border">';
				$out .= '	<div class="mag_lay-content">';
				if (!$item['post']['title'] && !$item['post']['content']) {
					$media_style = 'width: 100%; max-height: 80%';
					$h3_style = 'display:none';
				} else {
					$media_style = '';
					$h3_style = '';
				}
				$link = str_replace(array('http://','https://'),'', $item['post']['link']);
				strpos($link, '/') !== false ? substr($link,0,strpos($link,'/')) : $link;
				
				$out .= '	<div class="media" style="'.$media_style.'">';
				$out .= 		$item['post']['media'];
				$out .= '	</div>';
				$out .= '	<div style="display:none">'.@$item['post']['timestamp'].'</div>';
				$out .= '	<h3 style="'.$h3_style.'" title="'.$item['post']['title'].'">'.$item['post']['title'].'</h3>';
				$out .= '	<p class="info">Shared by <a href="">'.$item['post']['user'].'</a><span class="date">'.$item['post']['created_at'].'</span></p>';
				$out .= '	<a href="http://'.$link.'" class="link" target="_blank">'.$link.'</a>';
				$out .= '	<p>'.$item['post']['content'].'</p>';
				$out .= '	</div>';
				$out .= '</div>';
			} else {
				$out .= $this->rend($item, $level+1);
			}
			$out .= '</div>';
		}
		return $out;
	}
	
	
	/**
	 * Outputs data to structured hiearhy
	 *
	 */
	public function render() {
		$this->add_autoparams();
		//die(print_r($this->posts));
		$this->add_multipliers();
		$this->set_precent();
		$data = $this->calculate();
		//die(print_r($data));
		$html = $this->rend($data);
		return '<div id="mag_lay">'.$html.'</div>';
	}
	
	public function rend_simple() {
		$this->add_autoparams();
		$data = array(
			array(
				'height'=>0,
			)
		);
		if (sizeof($this->posts > 1)) $data[] = array('height'=>0);
		if (sizeof($this->posts > 2)) $data[] = array('height'=>0);
		foreach ($this->posts as $post) {
			$min_key = $this->get_min_height($data);
			$data[$min_key][] = $post;
			$data[$min_key]['height'] += mb_strlen($post['title'])+mb_strlen($post['content'])+$post['params']['media']*70;
		}
		
		$out = '<div id="mag_lay" class="mag_lay-simple">';
		foreach ($data as $col) {
			$out .= '<div style="width:'.(floor(1/sizeof($data)*100)).'%">';
			foreach ($col as $key=>$item) {
				if (!is_int($key)) continue;
				$out .= $this->rend_block($item);
			}
			$out .= '</div>';
		}
		$out .= '</div>';
		return $out;
	}
	
	public function rend_arr() {
		$arr = array();
		foreach ($this->posts as $post) {
			$arr[] = $this->rend_block($post);
		}
		return $arr;
	}
	
	public function rend_json() {
		return json_encode($this->rend_arr());
	}
	
	private function rend_block($item) {
		return '<div style="width:100%"><div class="mag_lay-content">'
				.$item['media']
				.$item['title']
				.$item['content']
				.'</div></div>';
	}
	
	private function get_min_height($data) {
		$min = 9999999; $min_key = 1;
		foreach ($data as $key=>$val) {
			if ($val['height'] < $min) {
				$min = $val['height'];
				$min_key = $key;
			}
		}
		return $min_key;
	}
	
	public function rend_major() {
		$this->add_autoparams();
		$this->add_multipliers();
		$this->set_precent();
		
		$data = array(
			array(          // col 0
				'width'=>0.5,
			),
			array(          // col 1
				'width'=>0.5,
			)
		);
		$pages = '<div id="mag_lay">';
		$i = 0; $temp_data = $data; $no_media = false;
		while ($post = $this->get_max()) {
			if (!$post['params']['media']) $no_media = true;
			$temp_data[$post['params']['media'] ? (sizeof($temp_data[0]) <= 2 ? 0 : 1) : 1][] = array(
				'height'=>0.5,
				'post'=>$post
			);
			if ($i==3) {
				if ($no_media) {
					foreach ($temp_data[1] as $key=>$col) if (is_int($key)) $temp_data[1][$key]['height'] = 0.3333;
					$post = $this->get_max();
					$temp_data[1][] = array(
						'height' => 0.33,
						'post' => $post
					);
				}				
				$i = 0; $no_media = false;
				$pages .= '<div class="page">'.$this->rend($temp_data).'</div>';
				$temp_data = $data;
			} else {
				$i++;
			}
		}
		if ($temp_data != $data) {
			$pages .= '<div class="page">'.$this->rend($temp_data).'</div>';
		}
		$pages .= '</div>';
		return $pages;
	}
	
	public function rend_social($max_precent=0.25, $min_precent = 0.125) {
			
		$this->add_autoparams();
		$this->add_multipliers();
		$this->posts = $this->set_precent($this->posts);
		
		$min_key = $this->get_min();
		$this->set_pages_precent($min_precent / $this->posts[$min_key]['precent']);
		
		$data = array(
			array(
				'free'=>0.5,
				'width'=>0.5
			),
			array(
				'free'=>0.5,
				'width'=>0.5
			)
		);
		
		$buff_data = $data;
		
		$pages = array(); 
		$buff_posts = array();
		$free = 1;
		$aspect = 0;
		
		//while (($max_key = $this->get_max()) !== false) {
		//	$this->posts[$max_key]['added'] = true;
		//	$post = $this->posts[$max_key];
		foreach ($this->posts as $post) {
			
			if (sizeof($buff_posts) < 1/$max_precent || $free < 0) {
				$buff_posts[] = $post;
				if ($post['width'] != $post['height']) $aspect += ($post['width'] > $post['height'] ? 1 : -1);
				$free -= $post['precent'];
			}
			
			if (sizeof($buff_posts) >= 1/$max_precent && $free < 0) {
				$buff_posts = $this->set_precent($buff_posts);
				$buff_data = $data;
				foreach ($buff_posts as $post) {
					$col = $buff_data[0]['free'] > $buff_data[1]['free'] ? 0 : 1;
					
					$post['precent'] = $post['precent'] > $max_precent ? $max_precent : ($post['precent'] < $min_precent ? $min_precent : $post['precent']);
					
					$buff_data[$col]['free'] -= $post['precent'];
					
					$post['precent'] *= 1/$buff_data[$col]['width'];
					$post['changed'] = $aspect > 0 ? true : false;
					$buff_data[$col][] = array('post'=>$post,'height'=>round($post['precent'],4));
				}
				$buff_data[0][sizeof($buff_data[0])-3]['height'] += round($buff_data[0]['free']/$buff_data[0]['width'],4);
				$buff_data[1][sizeof($buff_data[1])-3]['height'] += round($buff_data[1]['free']/$buff_data[0]['width'],4);
				
				$pages[] = $buff_data;
				$buff_posts = array();
				$aspect = 0;
			}
		}
		
		if ($buff_posts) {
			if (sizeof($buff_posts == 1)) { //One post - add it to others
				$max_free = 0; $max_free_page = null; $max_free_col = null;
				foreach ($pages as $k=>$page) {
					foreach ($page as $k1=>$col) {
						if ($col['free'] > $max_free) {
							$max_free = $col['free']; $max_free_page = $k; $max_free_col = $k1;
						}
					}
				}
				if ($max_free_page === null) {$max_free_page = 0;}
				if ($max_free_col === null) {$max_free_col = 0;}
				
				$pages[$max_free_page][$max_free_col]['free'] = 0;
				$pages[$max_free_page][$max_free_col][] = array(
					'post'=>$buff_posts[0],
					'height'=>$max_free,
				);
				$max_precent = 0; $max_post = null;
				foreach ($pages[$max_free_page][$max_free_col] as $k=>$row) {
					if (!is_array($row)) continue;
					if ($max_precent < $row['post']['precent']) {
						$max_precent = $row['post']['precent'];
						$max_post = $k;
					}
				}
				$pages[$max_free_page][$max_free_col][$max_post]['height'] -= $max_free;
			} else { //More posts - create new page
				$buff_posts = $this->set_precent($buff_posts);
				$buff_data = $data;
				foreach ($buff_posts as $post) {
					$col = $buff_data[0]['free'] > $buff_data[1]['free'] ? 0 : 1;
					
					$buff_data[$col][] = array('post'=>$post);
					$buff_data[$col]['free'] -= $post['precent'];
				}
				$pages[] = $buff_data;
			}
		}
		$out = '<div id="mag_lay">';
		foreach ($pages as $page) {
			$out .= '<div class="page">';
			$out .= $this->rend($page);
			$out .= '</div>';
		}
		$out .= '</div>';
		return $out;
	}
	
	
	public function log($pages) {
		foreach ($pages as $key=>$col)
			foreach ($col as $key1=>$row) {
				if (is_int($row)) continue;
				foreach ($row as $key2=>$post) {
					$pages[$key][$key1][$key2]['post'] = 'POST';
				}
			}
		print_r($pages);
		die();
	}
	
	public function set_pages_precent($multiplier) {
		foreach ($this->posts as $key=>$post) {
			$this->posts[$key]['precent'] = $post['precent'] * $multiplier;
		}
		return true;
	}
	
	public function calculate() {
		$data = array(
			'free'=>1,
			array(          // col 0
				'width'=>1,
				'free'=>1,
			)
		);
		
		//die(print_r($this->posts));
		$i = sizeof($this->posts);
		while ($i) {
			$min = 99999999; $min_col = -1; $min_key = -1;
			foreach ($data as $col_key=>$col) {
				if (!is_int($col_key)) continue;
				if (!$col['free']) continue;
				$test_post = $this->get_nearest_post($col['width'] * $col['free']);
				if (abs($test_post['precent']-$col['width'] * $col['free']) < $min) {
					$min = abs($test_post['precent']-$col['width'] * $col['free']);
					$min_key = $test_post;
					$min_col = $col_key;
				}
			}
			if ($min_key == -1) {
				$i--; continue;
			}
			
			$this->posts[$min_key]['added'] = true;
			$post = $this->posts[$min_key];
			
			$size = $this->get_nearest($post['precent']);
			list($w, $h) = $this->get_size($size, $data[$min_col]['width']);
			
			//echo "post: $min_key / col: $min_col / size: $size\r\n";

			if ($data[$min_col]['width'] == $w) {
				$h = $data[$min_col]['free'] < $h ? $data[$min_col]['free'] : $h;
				$new_row = array(
					'height' => $h,
					'post' => $post
				);
				if ($data[$min_col]['free'] == 1) {
					$data['free'] -= $data[$min_col]['width'];
				}
				$data[$min_col]['free'] -= $h;
				$data[$min_col][] = $new_row;
				
			} else {
				$data['free'] -= $w;
				$new_col = array(
					'width' => $w,
					'free' => 1 - $h,
					array(
						'height' => $h,
						'post' => $post
					)
				);
				$data[$min_col]['width'] -= $w;
				$data[] = $new_col;
			}
			$i--;
		}
		return $data;
	}
	
	private function get_size($size, $width) {
		foreach ($this->sizes[$size] as $test_size) {
			if ($test_size[0] == $width) return $test_size;
		}
		if ($this->sizes[$size][0][0] > $width) {
			return array($width, $size/$width);
		}
		return $this->sizes[$size][0];
	}
	
	private function get_nearest($val, $free=1) {
		$min_dist = 999999999; $min_dist_size = 1;
		foreach ($this->sizes as $size=>$data) {
			if ($size > $free) continue;
			if (abs($size-$val) < $min_dist) {
				$min_dist = abs($size - $val);
				$min_dist_size = $size;
			}
		}
		return $min_dist_size;
	}
	
	private function get_nearest_post($val) {
		$min_dist = 999999999; $min_dist_key = -1;
		foreach ($this->posts as $key=>$post) {
			if ($post['added']) continue;
			if (abs($post['precent']-$val) < $min_dist) {
				$min_dist = abs($post['precent']-$val);
				$min_dist_key = $key;
			}
		}
		//$this->posts[$min_dist_key]['added'] = true;
		//return $min_dist_key != -1 ? $this->posts[$min_dist_key] : false;
		return $min_dist_key != -1 ? $min_dist_key : false;
	}
	
	private function get_max() {
		$max = 0; $max_key = null;
		foreach ($this->posts as $key=>$post) {
			if ($post['added']) continue;
			if ($max < $post['total']) {
				$max = $post['total'];
				$max_key = $key;
			}
		}
		return $max_key === null ? false : $max_key;
	}
	
	private function get_min() {
		$min = 100000000; $min_key = -1;
		foreach ($this->posts as $key=>$post) {
			if ($post['added']) continue;
			if ($min > $post['precent']) {
				$min = $post['precent'];
				$min_key = $key;
			}
		}
		return $min_key == -1 ? false : $min_key;
	}
	
	private function add_autoparams() {
		foreach ($this->posts as &$post) {
			$post['added'] = false;
			$post['params']['media'] = $post['media'] ? (strpos($post['media'],'<iframe') !== false ? 2 : 1 ) : 0;
			$post['params']['text_length'] = mb_strlen($post['content'])+mb_strlen($post['title']);
		}
	}
	
	private function get_media_mult($src) {
		/*
		preg_match('#src="(.*?)"#',$src, $matches);
		if (isset($matches[1])) $src = $matches[1];
		list($w, $h) = @getimagesize($src);
		return ($w*$h)/(300*300);
		*/
		return 0.1;
	}
	
	private function add_multipliers() {
		foreach ($this->posts as $key=>$post) {
			foreach ($post['params'] as $k=>$v) {
				$this->posts[$key]['params'][$k] *= isset($this->multipliers[$k]) ? $this->multipliers[$k] : 1;
			}
		}
	}
	
	private function set_precent($posts) {
		$sum = 0;
		foreach ($posts as $key=>$post) {
			$sub_sum = 0;
			foreach ($post['params'] as $k=>$v) $sub_sum += $v;
			$posts[$key]['total'] = $sub_sum;
			$sum += $sub_sum;
		}
		
		foreach ($posts as $key=>$post) {
			$posts[$key]['precent'] = $post['total']/$sum;
		}
		return $posts;
	}
	
}