<?php

class Undefined_var_model extends MY_Model
{
    protected $_table = "undefined_var";
    protected $primary_key = "id";

    protected $debug = true;

    protected $undefined_var = array();

    /**
     * undefined_var_add 
     *		added a var to undefined list
     *		checking if it exists before adding
     *
     * @param mixed $var 
     */
    public function undefined_var_add($var)
    {
    	if (!in_array($var, $this->undefined_var))
    	{
    		$this->undefined_var[] = $var;
    	}
    }

    /**
     * str2arr 
     *		split a string to array
     *
     */
    public function str2arr($row)
    {
    	$row['var'] = preg_split("/,/", $row['variable']);
    	if ($row['var'] == null)
    	{
				$row['var'] = $row['variable'];
    	}
    	return $row;
    }

    /**
     * get_var 
     *		get required variables in database
     *		split the string to array
     * 
     * @param mixed $view			: view page
     */
    public function get_var($view)
    {
    	$this->db->select('*');
    	$this->db->where('view_page', $view);
    	$this->db->from($this->_table);
    	$query = $this->db->get();
    	if ( $query->num_rows() > 0 )
    	{
    		$row = $query->result_array();
    	  return $this->str2arr($row[0]);
    	}
    	else
    	{
    		$row['var'] = array();
				return $row;	//empty string
			}
    }

    /**
     * get_undefined_var 
     *		check if variables in $view is defined in
     *		$data
     *
     *		output: return array of undefined var
     * 
     * @param mixed $view  : view page field of table
     * @param mixed $data  : data to be confirmed, it is passed var from 
     *											controller to the view
     */
    public function get_undefined_var($view, $data)
    {
    	// initial undefined var
    	$this->undefined_var = array();

    	// get vars in database
    	$row = $this->get_var($view);
    	$vars = $row['var'];

    	// compare each var in database with $data
    	// has to confirm below cases
    	// for tetsing only
    	//$this->load->library('debugger');
    	foreach ($vars as $var)
    	{
				// case 1: only var name (ex: $var)
				//$this->debugger->set_undefined_var('var='.$var);
				if (count(preg_split("/\[/", $var)) <= 1 &&
				    count(preg_split("/->/", $var)) <= 1)
				{
					//$this->debugger->set_undefined_var('data[$var]='.$data[$var]);
					if (!isset($data[$var]))
					{
						//$undefined_var[] = $var;
						$this->undefined_var_add($var);
					}
				}
				else if (count($arrs=preg_split("/\[/", $var)) > 1)
				{
					// case 2: var is array index (ex: $var['id'])
					if (!isset($data[$arrs[0]]))
					{
						//$this->undefined_var_add('[' . $arrs[0] . ']');
						$this->undefined_var_add( $arrs[0] );
					}
					else
					{
						$arritem = $data[$arrs[0]];
						$arrstr = $arrs[0];
						for ($i=1; $i<count($arrs); $i++)
						{
							// remove ] at end
							$arr = preg_split("/\]/", $arrs[$i]);
							$b = $arr[0];
							$a = str_replace('\'', '', $b);
							if (!isset($arritem[$a]))
							{
								//$this->undefined_var_add( $arrstr . '[' . $a . ']' );
								$this->undefined_var_add( $arrstr . '[' . $a . ']' );
								break;
							}
							//$this->undefined_var_add('-('.$i.')-'.$arr[0].'--'.$b.'--'.$a.'--');
							//$this->undefined_var_add('..'.$arritem[$a].'..');
							$arrstr .= '[' . $arritem[$a] . ']';
							$arritem = $arritem[$a];
						}
					}
				}
				else if (count($obj=preg_split("/->/", $var)) > 1)
				{
					// case 3: var is object (ex: $var->id)
					if (!isset($data[$obj[0]]))
					{
						//$this->undefined_var_add("(" . $obj[0] . ")");
						$this->undefined_var_add( $obj[0] );
					}
					else
					{
						$objitem = $data[$obj[0]];
						$objstr = $obj[0];
						for ($i=1; $i < count($obj); $i++)
						{
							if (!isset($objitem->$obj[$i]))
							{
								//$undefined_var[] = "(" . $objstr . "->" . $obj[$i] . ")";
								//$this->undefined_var_add( $objstr . "->" . $obj[$i] );
								//break;
								continue;
							}
							$objitem = $objitem->$obj[$i];
							$objstr .= '->' . $obj[$i];
						}
						if (!isset($objitem))
						{
							$this->undefined_var_add( $objstr );
						}
					}
				} // ->
    	}

    	return $this->undefined_var;
    }

    /**
     * set_debugger 
     *		save checking result to Debugger class
     *		so that Profiler could access
     * 
     * @param mixed $viewpath 
     * @param mixed $vars 
     * @access public
     * @return void
     */
    public function set_debugger($viewpath, $vars) {
    	$ci = get_instance();
		// undefined variables checker
		if($ci->is_mod_enabled('undefined_var_checker')) {
			$this->load->library('debugger');

			$data['view'] = $viewpath;

			// required var inside database
			$required_var = $this->get_var($viewpath);
			$data['required_var'] = $required_var['var'];

			// passed var
			$data['passed_var'] = $passed_var = $vars;

			// undefined var
			$data['undefined_var'] = $undefined_var = $this->get_undefined_var($viewpath, $passed_var);

			//if there is undefined variable
			if (is_array($undefined_var) && (isset($undefined_var[0]) && $undefined_var[0] != null))
			{
				$this->debugger->set_undefined_var_html($data);		// save to variable for profiler
				$this->debugger->set_undefined_var_log($data);		// write to log file
			} else {
				if ($this->debug)
				{
					//always save information to log file in debug mode
					$this->debugger->set_undefined_var_log($data);		// write to log file
				}
			}

		}
    }

}
?>
