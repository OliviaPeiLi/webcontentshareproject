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
class MY_Profiler extends CI_Profiler {

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
										'config',
										'undefined_var'
										);

	// --------------------------------------------------------------------
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->CI->load->library('Debugger');
	}

	// --------------------------------------------------------------------

	/**
	 * _compile_undefined_var 
	 *		Profiler for undefined variables 
	 * @access protected
	 * @return void
	 */
	protected function _compile_undefined_var()
	{
		$logvars = "\n\n";
		$logvars .= '<fieldset id="ci_profiler_undefined_var">';
		$logvars .= '<table>';

		$html = $this->CI->debugger->get_all();
		$logvars .= $html;

		$logvars .= '</table>';
		$logvars .= '</fieldset>';

		return $logvars;
	}
}

// END MY_Profiler class

/* End of file MY_Profiler.php */
/* Location: ./system/libraries/MY_Profiler.php */
