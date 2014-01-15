<?php
class MY_Image_lib extends CI_Image_lib {
	
	
	// --------------------------------------------------------------------

	/**
	 * Image Watermark
	 *
	 * This is a wrapper function that chooses the type
	 * of watermarking based on the specified preference.
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	/*function watermark()
	{
		$method_name = $this->wm_type.'_watermark';
		if (method_exists($this, $method_name))
		{
			return $this->{$method_name}();
		}
	}*/
	

	// --------------------------------------------------------------------

	/**
	 * Watermark - Graphic Overflow Version
	 *
	 * @access	public
	 * @return	bool
	 */
	/*function overlay_overflow_watermark()
	{
		if ( ! function_exists('imagecolortransparent'))
		{
			$this->set_error('imglib_gd_required');
			return FALSE;
		}
		
		//  Fetch source image properties
		$this->get_image_properties();

		$src_img = $this->image_create_gd($this->full_src_path);
		if($this->bg_color)
		{
			$this->bg_color	= str_replace('#', '', $this->bg_color);
			$bg_r = hexdec(substr($this->bg_color, 0, 2));
			$bg_g = hexdec(substr($this->bg_color, 2, 2));
			$bg_b = hexdec(substr($this->bg_color, 4, 2));
		} else $bg_r = $bg_g = $bg_b = 0;

		//  Fetch watermark image properties
		if($this->wm_overlay_path)
		{
			$props			= $this->get_image_properties($this->wm_overlay_path, TRUE);
			$wm_img_type	= $props['image_type'];
			$wm_width		= $props['width'];
			$wm_height		= $props['height'];
		} else
		{
			if(!$this->wm_text)
			{
				$this->set_error('wm_text is required');
				return FALSE;
			}
			$this->wm_text = trim($this->wm_text);
			// Set RGB values for text and shadow
			$this->wm_font_color = str_replace('#', '', $this->wm_font_color);
	
			$this->wm_font_color = str_replace('#', '', $this->wm_font_color);
			$txt_r = hexdec(substr($this->wm_font_color, 0, 2));
			$txt_g = hexdec(substr($this->wm_font_color, 2, 2));
			$txt_b = hexdec(substr($this->wm_font_color, 4, 2));
	
			$txt_color	= imagecolorallocate($src_img, $txt_r, $txt_g, $txt_b);

			$this->wm_font_size = ($this->wm_font_size == '') ? 17 : $this->wm_font_size;
			
			$wm_width = 99999999;
			$orig_fSize = $this->wm_font_size;
			while( $wm_width > ($this->orig_width * 0.8) )
			{
				$txtDim = @imagettfbbox($this->wm_font_size, 0, $this->wm_font_path, $this->wm_text);
				$xArr = array($txtDim[0],$txtDim[2],$txtDim[4],$txtDim[6]);
				$yArr = array($txtDim[1],$txtDim[3],$txtDim[5],$txtDim[7]);
				$wm_width  = abs(max($xArr) - min($xArr));
				$wm_height = abs(max($yArr) - min($yArr)) + (abs(max($yArr) - min($yArr)) * 0.5);
				
				if($wm_width > ($this->orig_width * 0.8)) $this->wm_font_size = $this->wm_font_size - 0.5;
			}
			$this->wm_vrt_offset += abs(max($yArr) - min($yArr));
			$this->wm_padding = ($orig_fSize != $this->wm_font_size) ? $this->wm_padding / ($orig_fSize / $this->wm_font_size) : $this->wm_padding;
		}

		//  Create two image resources
		if(!isset($this->wm_text))
		{
			$wm_img = isset($wm_img) ? $wm_img : $this->image_create_gd($this->wm_overlay_path, $wm_img_type);
		}
		
		$this->wm_vrt_alignment = strtoupper(substr($this->wm_vrt_alignment, 0, 1));
		$this->wm_hor_alignment = strtoupper(substr($this->wm_hor_alignment, 0, 1));

		//  Set the base x and y axis values
		$pl_x_axis = $this->wm_hor_offset + $this->wm_padding;
		$pl_y_axis = $this->wm_vrt_offset + $this->wm_padding;
		
		$x_axis = 0;
		$y_axis = 0;
		
		//  Set the vertical position
		switch ($this->wm_vrt_alignment)
		{
			case 'T':
				$placeholder_h = $this->orig_height + $wm_height + $pl_x_axis;
				$y_axis = $wm_height + (2 * $this->wm_padding);
				break;
			case 'M':
				$pl_y_axis = ($this->orig_height / 2) - ($wm_height / 2);
				$placeholder_h = $this->orig_height;
				break;
			case 'B':
				$pl_y_axis += $this->orig_height;
				$placeholder_h = $this->orig_height + $wm_height + (2*$pl_x_axis);
				break;
		}

		//  Set the horizontal position
		switch ($this->wm_hor_alignment)
		{
			case 'L':
				switch($this->wm_vrt_alignment)
				{
					case 'T':
						$placeholder_w = $this->orig_width;
						$x_axis = 0;
						break;
					case 'M':
						$placeholder_w = $this->orig_width + $wm_width + (2*$this->wm_padding);
						$x_axis += $wm_width + (2*$this->wm_padding);
						break;
					case 'B':
						$placeholder_w = $this->orig_width;
						$x_axis = 0;
						break;
				}
				break;
			case 'C':
				$pl_x_axis = ($this->orig_width / 2) - ($wm_width / 2);
				$placeholder_w = $this->orig_width + $this->wm_hor_offset;
				break;
			case 'R':
				$pl_x_axis += $this->orig_width;
				$placeholder_w = $this->orig_width + $wm_width + (2*$this->wm_padding);
				break;
		}

		//  Build the finalized image
		if( isset($wm_img_type) )
		{
			if( $wm_img_type == 3 and function_exists('imagealphablending') )
			{
				@imagealphablending($src_img, TRUE);
			}
		}

		$placeholder_img  = imagecreatetruecolor($placeholder_w, $placeholder_h);
		$bg = @imagecolorallocate($placeholder_img, $bg_r, $bg_g, $bg_b);
		imagefill($placeholder_img, 0, 0, $bg);
		
		imagecopy($placeholder_img, $src_img, $x_axis, $y_axis, 0, 0, $this->orig_width, $this->orig_height);
		if(!$this->wm_text)
		{
			imagecopy($placeholder_img, $wm_img, $pl_x_axis, $pl_y_axis, 0, 0, $wm_width, $wm_height);
		} else
		{
			imagettftext($placeholder_img, $this->wm_font_size, 0, $pl_x_axis, $pl_y_axis, $txt_color, $this->wm_font_path, $this->wm_text);
		}

		//  Output the image
		if ($this->dynamic_output == TRUE)
		{
			$this->image_display_gd($placeholder_img);
		}
		else
		{
			if ( ! parent::image_save_gd($placeholder_img))
			{
				return FALSE;
			}
		}

		imagedestroy($src_img);
		if(isset($wm_img)) imagedestroy($wm_img);

		return TRUE;
	}*/
	
	/**
	 * Image Trim
	 * 
	 * this function will trim the edges of the image filling a static color
	 */
	function trim() {
		$protocol = 'image_process_'.$this->image_library;

		if (preg_match('/gd2$/i', $protocol))
		{
			$protocol = 'image_process_gd';
		}

		return $this->$protocol('trim');
	}
	
	function image_process_gd($action = 'resize') {
		if ($action == 'crop') {
			return parent::image_process_gd($action);
		} elseif ($action == 'resize') {
			return parent::image_process_gd($action);
		} 
		//  Create the image handle
		if ( ! ($im = $this->image_create_gd()))
		{
			return FALSE;
		}
		
	    // Get the image width and height.
	    $imw = $this->width;
	    $imh = min(array($this->height, $this->orig_height));
	
	    // Set the X variables.
	    $xmin = $imw; $ymin = $imh;
	    $xmax = 0; $ymax = 0;
	    $bg = imagecolorat($im, $imw-1, $imh-1);
	    $bgr = ($bg >> 16) & 0xFF; $bgg = ($bg >> 8) & 0xFF; $bgb = $bg & 0xFF;
	    //echo "GET color at: ".$imw.' '.$imh.'<br/>';
	     
	    // Start scanning for the edges.
	    for ($iy=0; $iy<$imh; $iy++){
	        for ($ix=0; $ix<$imw; $ix++){
	            $ndx = imagecolorat($im, $ix, $iy);
	    		$r = ($ndx >> 16) & 0xFF; $g = ($ndx >> 8) & 0xFF; $b = $ndx & 0xFF;
	            if (abs($bgr-$r) > 1 || abs($bgg-$g) > 1 || abs($bgb-$b) > 1){
	                if ($xmin > $ix){ $xmin = $ix; }
	                if ($xmax < $ix){ $xmax = $ix; }
	                if ($ymin > $iy){ $ymin = $iy; }
	                if ($ymax < $iy){ $ymax = $iy; }
	            }
	        }
	    }
	    
	    if (!isset($ymin)) return false; //blank image
	
	    // The new width and height of the image. (not including padding)
	    $imw = 1+$xmax-$xmin; // Image width in pixels
	    $imh = 1+$ymax-$ymin; // Image height in pixels
	    //echo $r.' '.$g.' '.$b.' '.$xmin.'-'.$xmax.' '.$ymin.'-'.$ymax.'<br/>';
	
	    if ($this->image_library == 'gd2' AND function_exists('imagecreatetruecolor'))
		{
			$create	= 'imagecreatetruecolor';
			$copy	= 'imagecopyresampled';
		}
		else
		{
			$create	= 'imagecreate';
			$copy	= 'imagecopyresized';
		}
	    //echo "Driver: ".$create.' '.$copy.'<br/>';
		
	    // Make another image to place the trimmed version in.
	    $im2 = $create($imw, $imh);
	    // Make the background of the new image the same as the background of the old one.
	    //$bg2 = imagecolorallocate($im2, ($bg >> 16) & 0xFF, ($bg >> 8) & 0xFF, $bg & 0xFF);
	    //imagefill($im2, 0, 0, $bg2);
	    
		if ($this->image_type == 3) // png we can actually preserve transparency
		{
			imagealphablending($im2, FALSE);
			imagesavealpha($im2, TRUE);
		}

	    // Copy it over to the new image.
	    $copy($im2, $im, 0, 0, $xmin, $ymin, $imw, $imh, $imw, $imh);
	    
	    if ( ! $this->image_save_gd($im2)) {
			return FALSE;
		}

		//  Kill the file handles
		imagedestroy($im);
		imagedestroy($im2);

		return array($xmin, $ymin);
	}
	
}