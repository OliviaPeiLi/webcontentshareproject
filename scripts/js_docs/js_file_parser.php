<?php
/**
 * Parses a docBlock
 */
class DocBlock {
	public $overview = '';
	public $data = array();
	private $log = false;
	
	public function __construct($contents, $offset = null, $log = false) {
		if ($log) $this->log = $log;
		if ($offset) {
			$contents = $this->get_function_docblock($contents, $offset);
		}
		$contents = $this->get_links($contents);
		$contents = $this->get_uses($contents);
		$contents = $this->get_params($contents);
		$this->overview = trim($contents);
	}
	
	public function get_function_docblock($contents, $offset) {
		$start = strrpos($contents, '/*', $offset-strlen($contents));
		$end = strrpos($contents, '*/', $offset-strlen($contents))+2;
		if ($this->log) {
			echo "space: ".substr($contents, $end, $offset-$end);
			echo "code: ".substr($contents, $start+2, $end-$start-4);
		}
		if (preg_replace('#[\r\n\s\t]*[ ]*#', '', substr($contents, $end, $offset-$end))) { //not own comment
			return '';
		}
		
		$doc_block_contents = substr($contents, $start+2, $end-$start-4);
		
		if (preg_match('#[=]{5,}#', $doc_block_contents)) { //structure head
			return '';
		}
		$doc_block_contents = trim(preg_replace('#^[ \t]*\*[ \t]*#msi', '', $doc_block_contents));
		return $doc_block_contents;
	}
	
	public function get_links($str) {
		preg_match_all('#^@link[ -]*(.*?)$#msi', $str, $matches);
		if (!isset($matches[1]) || !$matches[1]) return $str;
		$str = preg_replace('#^@link[ -]*(.*?)$#msi', '', $str);
		foreach ($matches[1] as $link) {
			$this->data['links'][] = trim($link);
		}
		return $str;
	}
	
	public function get_uses($str) {
		preg_match_all('#^@uses[ -]+(.*?)$#msi', $str, $matches);
		if (!isset($matches[1])) return ;
		$str = preg_replace('#^@uses[ -]+(.*?)$#msi', '', $str);
		foreach ($matches[1] as $dependency) {
			preg_match('#^(.*?)[ -]+(.*?)$#', $dependency, $matches);
			if (isset($matches[1])) {
				$this->data['uses'][$matches[1]] = $matches[2];
			} else {
				$this->data['uses'][$dependency] = 'Add some description here...';
			}
		}
		return $str;
	}
	
	public function get_params($str) {
		preg_match_all('#^@param[ -]+(.*?)$#msi', $str, $matches);
		if (!isset($matches[1])) return ;
		$str = preg_replace('#^@param[ -]+(.*?)$#msi', '', $str);
		foreach ($matches[1] as $param) {
			preg_match('#^(.*?)[\s-]+(.*?)$#', $param, $matches);
			if (isset($matches[1])) {
				$this->data['params'][$matches[1]] = $matches[2];
			} else {
				$this->data['params'][$param] = 'Add some description here...';
			}
		}
		return $str;
	}
}
/**
 * Parses a js file
 */
class Js_file_parser {
	
	private $contents;
	private $clean_contents;
	private $location;
	private $overview;
	private $dependencies = array();
	private $links = array();
	private $vars = array();
	private $functions = array();
	private $events = array();
	public function __construct($location) {
		$this->location = $location;
		$this->contents = file_get_contents($location);
		$this->clean_contents = $this->clean_function_contents($this->contents);
	}
	
	/**
	 * Gets just abstract code
	 */
	private function clean_function_contents($str) {
		$str = preg_replace('#define[\s\t]\([^{]*{#', '', rtrim($str,'});') ); //remove the outer {}
		$str = preg_replace('#{}#msi', '', $str); //clean empty {}
		while (preg_match('#{([^{}]+?)}#msi', $str)) {   //clear non empty {...}
			$str = preg_replace('#{[^{}]+}#msi', '', $str);
		}
		return $str;
	}
	
	/**
	 * Gets the top file doc block
	 */
	public function get_overview() {
		preg_match('#/[*]*(.*?)[*]*/[\r\n\s\t]*define#msi', $this->contents, $matches);
		if (!isset($matches[1])) return '';
		$this->clean_contents = str_replace(str_replace('define', '', $matches[0]), '', $this->clean_contents);
		$contents = preg_replace('#^[\s\t]*\*[\s\t]*#msi', '', $matches[1]);
		$docBlock = new DocBlock($contents);
		$this->overview = $docBlock->overview;
		if (isset($docBlock->data['links'])) $this->links = $docBlock->data['links'];
		$deps = $this->get_dependencies();
		foreach ($deps as $dep) {
			$this->dependencies[$dep] = isset($docBlock->data['uses'][$dep]) ? $docBlock->data['uses'][$dep] : 'Please add a desciption...';
		}
		return $this->overview;
	}
	
	public function get_dependencies() {
		preg_match('#^define\(\[(.*?)\],#msi', $this->contents, $matches);
		if (!isset($matches[1])) return array();
		if (strpos($matches[1], ',') !== false) $deps = explode(',', $matches[1]); else $deps = array($matches[1]);
		foreach ($deps as &$dep) {
			$dep = trim($dep, ' ,"\'');
		}
		return $deps;
	}
	
	public function get_private_vars() {
		preg_match_all('#^[\s\t]*var[ ]+(.*?)[;\r\n]#msi', $this->clean_contents, $matches, PREG_OFFSET_CAPTURE);
		if (!isset($matches[1])) return ;
		foreach ($matches[1] as $key => $var) {
			list($var, $offset) = $var;
			$docBlock = new DocBlock($this->clean_contents, $offset-4);
			if (strpos($var, ',') !== false) $vars = explode(',', $var); else $vars = array($var);
			foreach ($vars as $var) {
				$default = null;
				if (strpos($var, '=') !== false) list($var, $default) = explode('=', $var);
				$this->vars['private'][trim($var)] = array(
					'default' => $default,
					'overview' => $docBlock->overview,
					'data' => $docBlock->data
				);
			}
		}
	}
	public function get_private_functions() {
		preg_match_all('#^[\s\t]*function[ ]+(.*?)[;\r\n]#msi', $this->clean_contents, $matches, PREG_OFFSET_CAPTURE);
		if (!isset($matches[1])) return ;
		foreach ($matches[1] as $key => $func) {
			list($func, $offset) = $func;
			$docBlock = new DocBlock($this->clean_contents, $offset-9);
			preg_match('#\(([^)]*)\)#', $func, $params);
			if (!isset($params[1])) continue;
			$func = trim(str_replace($params[0], '', $func));
			if (strpos($params[1], ',') !== false) $params = explode(',', $params[1]); else $params = array($params[1]);
			$func_params = array();
			foreach ($params as $param) {
				$func_params[trim($param)] = isset($docBlock->data['params'][trim($param)]) ? $docBlock->data['params'][trim($param)] : 'Add a description...';
			}
			unset($docBlock->data['params']);  
			$this->functions['private'][trim($func)] = array(
				'overview' => $docBlock->overview,
				'params' => $func_params,
				'data' => $docBlock->data
			);
		}
	}
	
	public function get_public_vars_funcs() {
		preg_match_all('#this\.(.*?)[;\r\n]#msi', $this->clean_contents, $matches, PREG_OFFSET_CAPTURE);
		if (!isset($matches[1])) return ;
		foreach ($matches[1] as $key => $var) {
			list($var, $offset) = $var;
			$docBlock = new DocBlock($this->clean_contents, $offset);
			if (preg_match('#[ ]*=[ ]*function[ ]*\(#', $var)) {
				$this->get_public_func($var, $docBlock);
			} else {
				$this->get_public_var($var, $docBlock);
			}
		}
	}
	
	private function get_public_func($func, $docBlock) {
		preg_match('#\(([^)]*)\)#', $func, $params);
		if (!isset($params[1])) continue;
		$func = trim(str_replace(array($params[0], 'function', '='), '', $func));
		if (strpos($params[1], ',') !== false) $params = explode(',', $params[1]); else $params = array($params[1]);
		$func_params = array();
		foreach ($params as $param) {
			$func_params[trim($param)] = isset($docBlock->data['params'][trim($param)]) ? $docBlock->data['params'][trim($param)] : 'Add a description...';
		}
		unset($docBlock->data['params']);  
		$this->functions['public'][trim($func)] = array(
			'overview' => $docBlock->overview,
			'params' => $func_params,
			'data' => $docBlock->data
		);
	}
	
	private function get_public_var($var, $docBlock) {
		if (strpos($var, ',') !== false) $vars = explode(',', $var); else $vars = array($var);
		foreach ($vars as $var) {
			$default = null;
			if (strpos($var, '=') !== false) list($var, $default) = explode('=', $var);
			$this->vars['public'][trim($var)] = array(
				'default' => $default,
				'overview' => $docBlock->overview,
				'data' => $docBlock->data
			);
		}
	}
	
	public function get_events() {
		$this->clean_contents = preg_replace('#$\(function#', '$(document).ready(function', $this->clean_contents); //for consistency
		//preg_match_all('#(\$|jQuery)\([^)]+\)([\s\r\n\t]*\.[a-zA-Z0-9_]+\(([\r\n\s\t]*([\'"][\w]+[\'"][ ]*,[ ]*)*function[ ]*\([^)]*\)[ ,]*)+[ ]\))+#msi', $this->clean_contents, $matches);
		preg_match_all('#\.[a-zA-Z0-9_]+\(([\r\n\s\t]*([\'"][\w]+[\'"][ ]*,[ ]*)*function[ ]*\([^)]*\)[ ,]*)+[ ]\)+#msi', $this->clean_contents, $matches, PREG_OFFSET_CAPTURE);
		if (!isset($matches[0])) return;
		foreach($matches[0] as $match) {
			list($match, $offset) = $match;
			$start = max(array(
				strrpos($this->clean_contents, '$(', $offset-strlen($this->clean_contents)),
				strrpos($this->clean_contents, 'jQuery(', $offset-strlen($this->clean_contents))
			));
			$code = substr($this->clean_contents, $start, $offset-$start);
			preg_match('#(\$|jQuery)\(([^)]*)\)#msi', $code, $selector);
			
			if (!isset($selector[1])) {
				echo "Selector not found for event: ".$match." in ".basename($this->location)."\r\n";
				echo "Offset: "; var_dump($start); echo "\r\n";
				echo "Searched in: ".$code."\r\n";
				continue;
			}
			
			$docBlock = new DocBlock($this->clean_contents, $offset);
			if (!$docBlock->overview) {
				$docBlock = new DocBlock($this->clean_contents, $start);
			}
			
			$this->events[$selector[0]][$match] = array(
				'overview' => $docBlock->overview,
				'data' => $docBlock->data
			);
		}
	}
	
	public function parse() {
		$this->get_overview();
		$this->get_private_vars();
		$this->get_private_functions();
		$this->get_public_vars_funcs();
		$this->get_events();
		return array(
			'location' => $this->location,
			'overview' => trim($this->overview),
			'dependencies' => $this->dependencies,
			'links' => $this->links,
			'vars' => $this->vars,
			'functions' => $this->functions,
			'events' => $this->events
		);
	}
}