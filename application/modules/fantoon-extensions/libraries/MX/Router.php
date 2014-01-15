<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX core module class */
require dirname(__FILE__).'/Modules.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library extends the CodeIgniter router class.
 *
 * Install this file as application/third_party/MX/Router.php
 *
 * @copyright	Copyright (c) 2011 Wiredesignz
 * @version 	5.4
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Router extends CI_Router {
	protected $module;
	protected $debug=false;
	
	public function fetch_module() {
		return $this->module;
	}
	
	public function is_homepage() {
		return $this->fetch_module() == 'homepage' && $this->fetch_class() == 'main' && $this->fetch_method() == 'index';
	}
	
	public function _validate_request($segments) {
		if (count($segments) == 0) return $segments;
		
		/* locate module controller */
		if ($located = $this->locate($segments)) return $located;
		
		/* use a default 404_override controller */
		if (isset($this->routes['404_override']) AND $this->routes['404_override']) {
			$segments = explode('/', $this->routes['404_override']);

			if ($located = $this->locate($segments)) return $located;
		}
		/* no controller found */
		show_404();
	}

	/**
	 *  locate: it is originally from third party
	 *  but contain some modification for Fantoon
	 *
	 *  Modification points:
	 *	Add stage:
	 *	  stage=0: searching URL in all modules
	 *	  stage=1: searching URL in common routes.php
	 *	Add debug mode: if it is set as "true", some information is shown
	 */
	public function locate($segments, $stage=0) {		
		$this->module = '';
		$this->directory = '';
		$ext = $this->config->item('controller_suffix').EXT;

		// store original segments for next stage
		$segments_org = $segments;

		/* use module route if available */
		if ($this->debug) echo "--stage=$stage--;";
		if ( $stage > 0 ) {
			if ($this->debug) echo "looking for [". implode('/', $segments)."] in common routes.php;<br /> ";
			if (isset($segments[0]) AND $routes = Modules::parse_routes($segments[0], implode('/', $segments))) {
				$segments = $routes;
			}
		} else {
			// search for all modules' route
			if ($this->debug) echo "looking for [".implode('/', $segments)."] in each module;<br /> ";
			foreach (Modules::list_modules() as $module) {
				if ($this->debug) echo "$module->";
				if ( $route = Modules::parse_routes($module, implode('/', $segments)) ) {
					$segments = $route;
					array_shift($segments);
					if ($this->debug) echo "(found)[" . implode('/', $segments) . "];<br />";
					break;
				}
			}
		}

		/* get the segments array elements */
		list($module, $directory, $controller) = array_pad($segments, 3, NULL);
	
		/* check modules */
		foreach (Modules::$locations as $location => $offset) {
		
			/* module exists? */
			if (is_dir($source = $location.$module.'/controllers/')) {
			
				$this->module = $module;
				$this->directory = $offset.$module.'/controllers/';
			
				/* module sub-controller exists? */
				if($directory AND is_file($source.$directory.$ext)) {
					if ($this->debug) { echo "FOUND:"; var_dump(array_slice($segments, 1)); }
					return array_slice($segments, 1);
				}
						
				/* module sub-directory exists? */
				if($directory AND is_dir($source.$directory.'/')) {
				
					$source = $source.$directory.'/'; 
					$this->directory .= $directory.'/';
				
					/* module sub-directory controller exists? */
					if(is_file($source.$directory.$ext)) {
						if ($this->debug) { echo "FOUND:"; var_dump(array_slice($segments, 1)); }
						return array_slice($segments, 1);
					}
				
					/* module sub-directory sub-controller exists? */
					if($controller AND is_file($source.$controller.$ext))	{
						if ($this->debug) { echo "FOUND:"; var_dump(array_slice($segments, 2)); }
						return array_slice($segments, 2);
					}
				}
					
				/* module controller exists? */			
				if(is_file($source.$module.$ext)) {
					if ($this->debug) { echo "FOUND:"; var_dump($segments); }
					return $segments;
				}
			}
		}
		
		/* application controller exists? */			
		if (is_file(APPPATH.'controllers/'.$module.$ext)) {
			if ($this->debug) { echo "FOUND:"; var_dump($segments); }
			return $segments;
		}
		
		/* application sub-directory controller exists? */
		if($directory AND is_file(APPPATH.'controllers/'.$module.'/'.$directory.$ext)) {
			$this->directory = $module.'/';
			if ($this->debug) { echo "FOUND:"; var_dump(array_slice($segments, 1)); }
			return array_slice($segments, 1);
		}
		
		/* application sub-directory default controller exists? */
		if (is_file(APPPATH.'controllers/'.$module.'/'.$this->default_controller.$ext)) {
			$this->directory = $module.'/';
			if ($this->debug) { echo "FOUND:"; var_dump( array($this->default_controller) ); }
			return array($this->default_controller);
		}
	
		// if can not find out route in common route
		// find to all modules' route
		if ($this->debug) echo "Can not find out -> looking for all modules " . implode('/', $segments) . ";<br /> ";
		if ( $stage == 0 ) {
			return $this->locate($segments_org, $stage+1);
		} else {
		  // not found, stop for debugging
		  if ($this->debug) { exit(); }
		}
	}

	public function set_class($class) {
		$this->class = $class.$this->config->item('controller_suffix');
	}
}
