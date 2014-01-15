<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Profiler Class
 *
 * This class enables you to display benchmark, query, and other data
 * in order to help with debugging and optimization.
 *
 * Note: At some point it would be good to move all the HTML in this class
 * into a set of template files in order to allow customization.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/general/profiling.html
 */
class CI_Profiler {

	protected $_available_sections = array(
										'benchmarks',
										'get',
										'memory_usage',
										'post',
										'uri_string',
										'controller_info',
										'queries',
										'http_headers',
										'session_data',
										'config'
										);

	protected $_query_toggle_count = 25;

	protected $CI;

	// --------------------------------------------------------------------

	public function __construct($config = array())
	{
		$this->CI =& get_instance();
		$this->CI->load->language('profiler');

		if (isset($config['query_toggle_count']))
		{
			$this->_query_toggle_count = (int) $config['query_toggle_count'];
			unset($config['query_toggle_count']);
		}

		// default all sections to display
		foreach ($this->_available_sections as $section)
		{
			if ( ! isset($config[$section]))
			{
				$this->_compile_{$section} = TRUE;
			}
		}

		$this->set_sections($config);
	}

	// --------------------------------------------------------------------

	/**
	 * Set Sections
	 *
	 * Sets the private _compile_* properties to enable/disable Profiler sections
	 *
	 * @param	mixed
	 * @return	void
	 */
	public function set_sections($config)
	{
		if (isset($config))
		{
			foreach ($config as $method => $enable)
			{
				if (in_array($method, $this->_available_sections))
				{
					$this->_compile_{$method} = ($enable !== FALSE) ? TRUE : FALSE;
				}
			}
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Auto Profiler
	 *
	 * This function cycles through the entire array of mark points and
	 * matches any two points that are named identically (ending in "_start"
	 * and "_end" respectively).  It then compiles the execution times for
	 * all points and returns it as an array
	 *
	 * @return	array
	 */
	protected function _compile_benchmarks()
	{
		$profile = array();
		foreach ($this->CI->benchmark->marker as $key => $val)
		{
			// We match the "end" marker so that the list ends
			// up in the order that it was defined
			if (preg_match("/(.+?)_end/i", $key, $match))
			{
				if (isset($this->CI->benchmark->marker[$match[1].'_end']) AND isset($this->CI->benchmark->marker[$match[1].'_start']))
				{
					$profile[$match[1]] = $this->CI->benchmark->elapsed_time($match[1].'_start', $key);
				}
			}
		}

		// Build a table containing the profile data.
		// Note: At some point we should turn this into a template that can
		// be modified.  We also might want to make this data available to be logged

		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_benchmarks">';
		$output .= "\n\n<table>\n";

		foreach ($profile as $key => $val)
		{
			$key = ucwords(str_replace(array('_', '-'), ' ', $key));
			$output .= "<tr><td>".$key."&nbsp;&nbsp;</td><td>".$val."</td></tr>\n";
		}

		$output .= "</table>\n";
		$output .= "</fieldset>";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile Queries
	 *
	 * @return	string
	 */
	protected function _compile_queries()
	{
		$dbs = array();

		// Let's determine which databases are currently connected to
		foreach (get_object_vars($this->CI) as $CI_object)
		{
			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') )
			{
				$dbs[] = $CI_object;
			}
		}
		if (!$dbs) $dbs[] = get_instance()->db;

		if (count($dbs) == 0)
		{

			$output  = "\n\n";
			$output .= '<fieldset id="ci_profiler_queries">';
			$output .= "\n\n<table>\n";
			$output .="<tr><td>".$this->CI->lang->line('profiler_no_db')."</td></tr>\n";
			$output .= "</table>\n";
			$output .= "</fieldset>";

			return $output;
		}

		// Load the text helper so we can highlight the SQL
		$this->CI->load->helper('text');

		// Key words we want bolded
		$highlight = array('SELECT', 'DISTINCT', 'FROM', 'WHERE', 'AND', 'LEFT&nbsp;JOIN', 'ORDER&nbsp;BY', 'GROUP&nbsp;BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE', 'OR&nbsp;', 'HAVING', 'OFFSET', 'NOT&nbsp;IN', 'IN', 'LIKE', 'NOT&nbsp;LIKE', 'COUNT', 'MAX', 'MIN', 'ON', 'AS', 'AVG', 'SUM', '(', ')');

		$output  = "\n\n";

		$count = 0;

		foreach ($dbs as $db)
		{

			$count++;

			$hide_queries = "";// (count($db->queries) > $this->_query_toggle_count) ? ' display:none' : '';


			$show_hide_js = '(<span onclick="var s=document.getElementById(\'ci_profiler_queries_db_'.$count.'\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\''.$this->CI->lang->line('profiler_section_hide').'\'?\''.$this->CI->lang->line('profiler_section_show').'\':\''.$this->CI->lang->line('profiler_section_hide').'\';">'.$this->CI->lang->line('profiler_section_hide').'</span>)';

			if ($hide_queries != '')
			{
				$show_hide_js = '(<span onclick="var s=document.getElementById(\'ci_profiler_queries_db_'.$count.'\').style;s.display=s.display==\'none\'?\'\':\'none\';this.innerHTML=this.innerHTML==\''.$this->CI->lang->line('profiler_section_show').'\'?\''.$this->CI->lang->line('profiler_section_hide').'\':\''.$this->CI->lang->line('profiler_section_show').'\';">'.$this->CI->lang->line('profiler_section_show').'</span>)';
			}

			$output .= '<fieldset id="ci_profiler_queries">';

			$output .= "\n\n<table id='ci_profiler_queries_db_{$count}'>\n";


			if (count($db->queries) == 0)
			{
				$output .= "<tr><td>".$this->CI->lang->line('profiler_no_queries')."</td></tr>\n";
			}
			else
			{
				foreach ($db->queries as $key => $val)
				{
					$time = number_format($db->query_times[$key], 4);

					$val = highlight_code($val, ENT_QUOTES);

					foreach ($highlight as $bold)
					{
						$val = str_replace($bold, '<strong>'.$bold.'</strong>', $val);
					}

					$output .= "<tr><td>".$time."&nbsp;&nbsp;</td><td>".$val."</td></tr>\n";
				}
			}

			$output .= "</table>\n";
			$output .= "</fieldset>";

		}

		return $output;
	}


	// --------------------------------------------------------------------

	/**
	 * Compile $_GET Data
	 *
	 * @return	string
	 */
	protected function _compile_get()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_get">';

		if (count($_GET) == 0)
		{
			$output .= "<div>".$this->CI->lang->line('profiler_no_get')."</div>";
		}
		else
		{
			$output .= "\n\n<table>\n";

			foreach ($_GET as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}

				$output .= "<tr><td>&#36;_GET[".$key."]&nbsp;&nbsp; </td><td>";
				if (is_array($val))
				{
					$output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, true))) . "</pre>";
				}
				else
				{
					$output .= htmlspecialchars(stripslashes($val));
				}
				$output .= "</td></tr>\n";
			}

			$output .= "</table>\n";
		}
		$output .= "</fieldset>";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile $_POST Data
	 *
	 * @return	string
	 */
	protected function _compile_post()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_post">';

		if (count($_POST) == 0)
		{
			$output .= "<div>".$this->CI->lang->line('profiler_no_post')."</div>";
		}
		else
		{
			$output .= "\n\n<table>\n";

			foreach ($_POST as $key => $val)
			{
				if ( ! is_numeric($key))
				{
					$key = "'".$key."'";
				}

				$output .= "<tr><td>&#36;_POST[".$key."]&nbsp;&nbsp; </td><td>";
				if (is_array($val))
				{
					$output .= "<pre>" . htmlspecialchars(stripslashes(print_r($val, TRUE))) . "</pre>";
				}
				else
				{
					$output .= htmlspecialchars(stripslashes($val));
				}
				$output .= "</td></tr>\n";
			}

			$output .= "</table>\n";
		}
		$output .= "</fieldset>";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Show query string
	 *
	 * @return	string
	 */
	protected function _compile_uri_string()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_uri_string">';

		if ($this->CI->uri->uri_string == '')
		{
			$output .= "<div>".$this->CI->lang->line('profiler_no_uri')."</div>";
		}
		else
		{
			$output .= "<div>".$this->CI->uri->uri_string."</div>";
		}

		$output .= "</fieldset>";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Show the controller and function that were called
	 *
	 * @return	string
	 */
	protected function _compile_controller_info()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_controller_info">';

		$output .= "<div>".$this->CI->router->fetch_class()."/".$this->CI->router->fetch_method()."</div>";

		$output .= "</fieldset>";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile memory usage
	 *
	 * Display total used memory
	 *
	 * @return	string
	 */
	protected function _compile_memory_usage()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_memory_usage">';

		if (function_exists('memory_get_usage') && ($usage = memory_get_usage()) != '')
		{
			$output .= "<div>".number_format($usage).' bytes</div>';
		}
		else
		{
			$output .= "<div>".$this->CI->lang->line('profiler_no_memory')."</div>";
		}

		$output .= "</fieldset>";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile header information
	 *
	 * Lists HTTP headers
	 *
	 * @return	string
	 */
	protected function _compile_http_headers()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_http_headers">';

		$output .= "\n\n<table id='ci_profiler_httpheaders_table'>\n";

		foreach (array('HTTP_ACCEPT', 'HTTP_USER_AGENT', 'HTTP_CONNECTION', 'SERVER_PORT', 'SERVER_NAME', 'REMOTE_ADDR', 'SERVER_SOFTWARE', 'HTTP_ACCEPT_LANGUAGE', 'SCRIPT_NAME', 'REQUEST_METHOD',' HTTP_HOST', 'REMOTE_HOST', 'CONTENT_TYPE', 'SERVER_PROTOCOL', 'QUERY_STRING', 'HTTP_ACCEPT_ENCODING', 'HTTP_X_FORWARDED_FOR') as $header)
		{
			$val = (isset($_SERVER[$header])) ? $_SERVER[$header] : '';
			$output .= "<tr><td>".$header."&nbsp;&nbsp;</td><td>".$val."</td></tr>\n";
		}

		$output .= "</table>\n";
		$output .= "</fieldset>";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile config information
	 *
	 * Lists developer config variables
	 *
	 * @return	string
	 */
	protected function _compile_config()
	{
		$output  = "\n\n";
		$output .= '<fieldset id="ci_profiler_config">';

		$output .= "\n\n<table id='ci_profiler_config_table'>\n";

		foreach ($this->CI->config->config as $config=>$val)
		{
			if (is_array($val))
			{
				$val = print_r($val, TRUE);
			}

			$output .= "<tr><td>".$config."&nbsp;&nbsp;</td><td>".htmlspecialchars($val)."</td></tr>\n";
		}

		$output .= "</table>\n";
		$output .= "</fieldset>";

		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Compile session userdata
	 *
	 * @return 	string
	 */
	private function _compile_session_data()
	{
		if ( ! isset($this->CI->session))
		{
			return;
		}

		$output = '<fieldset id="ci_profiler_csession">';
		$output .= "<table id='ci_profiler_session_data'>";

		foreach ($this->CI->session->all_userdata() as $key => $val)
		{
			if (is_array($val))
			{
				$val = print_r($val, TRUE);
			}

			$output .= "<tr><td>".$key."&nbsp;&nbsp;</td><td>".htmlspecialchars($val)."</td></tr>\n";
		}

		$output .= '</table>';
		$output .= "</fieldset>";
		return $output;
	}

	// --------------------------------------------------------------------

	/**
	 * Run the Profiler
	 *
	 * @return	string
	 */
	public function run_json() {
		return '';
	}
	
	public function run()
	{
		if (!get_instance()->output->get_output() || substr(get_instance()->output->get_output(), 0, 1) == '[') {
			return $this->run_json();
		}
		$output = "<div id='codeigniter_profiler'>";
		if ($this->CI->css_filenames) {
			$file = str_replace(array('.js'),'',$this->CI->css_filenames['/js/profiler.js'][2]);
			$output .= "<script type='text/javascript'>require(['".$file."'])</script>";
			$output .= '<link rel="stylesheet" href="'.$this->CI->css_base.$this->CI->css_filenames['/css/profiler.css'][2].'" type="text/css"/>'."\r\n";
		} elseif($this->CI->uri->segment(1)=='admin'){
			$output .= "<script type='text/javascript'>require(['../js/modules/profiler'])</script>";
			$output .= '<link rel="stylesheet" href="/css/profiler.css" type="text/css"/>'."\r\n";

		}else {
			$output .= "<script type='text/javascript'>require(['profiler'])</script>";
			$output .= '<link rel="stylesheet" href="/css/profiler.css" type="text/css"/>'."\r\n";
		}
		$fields_displayed = 0;

		$output .= "<ul>";
		foreach ($this->_available_sections as $section) {
			if (!$this->_compile_{$section}) continue;
			$output .= '<li><a href="" rel="ci_profiler_'.$section.'">'.ucfirst($section).'</a></li>';
		}
		$output .= '<li class="dev-toolbar-handler"><a href=""><img src="/images/profiler/settings.png" alt="show/hide"/></a></li>';
		$output .= "</ul>";
		
		foreach ($this->_available_sections as $section)
		{
			if ($this->_compile_{$section} !== FALSE)
			{
				$func = "_compile_{$section}";
				$output .= $this->{$func}();
				$fields_displayed++;
			}
		}

		if ($fields_displayed == 0)
		{
			$output .= '<p>'.$this->CI->lang->line('profiler_no_profiles').'</p>';
		}

		$output .= '</div>';

		return $output;
	}
}

// END CI_Profiler class

/* End of file Profiler.php */
/* Location: ./system/libraries/Profiler.php */
