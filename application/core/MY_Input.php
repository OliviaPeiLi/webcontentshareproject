<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

//require APPPATH."../system/core/Input.php";
/* load the MX_Router class */
//require APPPATH."third_party/MX/Router.php";
class MY_Input extends CI_Input {
 

    
    /**
	* Fetch an item from the GET array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function get($index = NULL, $xss_clean = FALSE, $default = FALSE)
	{
		// Check if a field has been provided
		if ($index === NULL AND ! empty($_GET))
		{
			$get = array();

			// loop through the full _GET array
			foreach (array_keys($_GET) as $key)
			{
				$get[$key] = $this->_fetch_from_array($_GET, $key, $xss_clean);
			}
			return $get;
		}

		return $this->_fetch_from_array($_GET, $index, $xss_clean) !== false ? $this->_fetch_from_array($_GET, $index, $xss_clean) : $default;
	}

	// --------------------------------------------------------------------

	/**
	* Fetch an item from the POST array
	*
	* @access	public
	* @param	string
	* @param	bool
	* @return	string
	*/
	function post($index = NULL, $xss_clean = FALSE, $default = FALSE)
	{
		// Check if a field has been provided
		if ($index === NULL AND ! empty($_POST))
		{
			$post = array();

			// Loop through the full _POST array and return it
			foreach (array_keys($_POST) as $key)
			{
				$post[$key] = $this->_fetch_from_array($_POST, $key, $xss_clean);
			}
			return $post;
		}

		return $this->_fetch_from_array($_POST, $index, $xss_clean) !== false ? $this->_fetch_from_array($_POST, $index, $xss_clean) : $default;
	}
	
	// --------------------------------------------------------------------

	/**
	* Fetch an item from either the GET array or the POST
	*
	* @access	public
	* @param	string	The index key
	* @param	bool	XSS cleaning
	* @return	string
	*/
	function get_post($index = '', $xss_clean = FALSE, $default = FALSE)
	{
		if ( ! isset($_POST[$index]) )
		{
			return $this->get($index, $xss_clean, $default);
		}
		else
		{
			return $this->post($index, $xss_clean, $default);
		}
	}

	/**
	* Clean Keys
	*
	* This is a helper function. To prevent malicious users
	* from trying to exploit keys we make sure that keys are
	* only named with alpha-numeric text and a few other items.
	*
	* @access	private
	* @param	string
	* @return	string
	*/
	function _clean_input_keys($str)
	{
		if ( ! preg_match("/^[a-z0-9:_\/-]+$/i", $str)) {
			return ;
		}
		
		// Clean UTF-8 if supported
		if (UTF8_ENABLED === TRUE) {
			$str = $this->uni->clean_string($str);
		}

		return $str;
	}

}