<?php

class Debugger extends MX_Controller
{
	private $html_info = "";		// contain HTML content of Undefined_var PROFILER

  /**
   * clean 
   *		initialize $html_info variable
   * 
   */
  public function clean()
  {
  	$this->html_info = "";

  }

  /**
   * get_all 
   *		is executed by Profiler, return HTML
   *		so that Profiler could show
   */
  public function get_all()
  {
  	$data = $this->html_info;
  	$this->clean();

  	return $data;
  }

  /**
   * set_undefined_var_html 
   *		setting $html_info variable
   *		layout
   *		-----------------------------------------------------------
   *		| view					| view path
   *		|---------------|------------------------------------------
   *		| required var	| variable required (stored in database)
   *		|---------------|------------------------------------------
   *		| passed var		| variable passed from controller to view
   *		|---------------|------------------------------------------
   *		| undefined var	| undefined variable
   *		-----------------------------------------------------------
   */
  public function set_undefined_var_html($data)
  {
  	// getting parameters
  	$view = $data['view'];
  	$required_var = $data['required_var'];
  	$passed_var = $data['passed_var'];
  	$undefined_var = $data['undefined_var'];

		// view path
  	$this->html_info .= '<tr class="row"><td>View</td><td>'.$view.'</td></tr>';

		// required var
    if (is_array($required_var) && $required_var[0] != null)
    {
			$this->html_info .= '<tr><td>required var('.count($required_var).')</td><td>'.implode(", ", $required_var).'</td></tr>';
    } else {
      $this->html_info .= '<tr><td>required var('.count($required_var).')</td><td>'. $required_var .'</td></tr>';
    }

		// passed var
    if (is_array($passed_var) && $passed_var != null)
    {
			$passed_var = implode(", ", array_keys($passed_var));
      $this->html_info .= '<tr><td>passed var('.count($passed_var).')</td><td>'.$passed_var.'</td></tr>';
    } else {
      $this->html_info .= '<tr><td>var passed to view(0)</td></tr>';
    }

    // undefined var
    $this->html_info .= '<tr><td>undefined var('.count($undefined_var).')</td><td class="item">'.implode(", ",$undefined_var).'</td></tr>';
  }

  /**
   * set_undefined_var_log 
   *		as same as set_undefined_var_html, but the input
   *		is used to save to log file
   *
   *		Note: passed_var is variable passed from controller to view
   *		it is complex type (mixed between object, variable, array,..)
   *
   *		In HTML, we simply it for reading
   *		In LOG file, we use serialize to make it clear
   *
   *		LAYOUT (csv)
   *		view, required variables, passed variables, undefined variables
   */
  public function set_undefined_var_log($data)
  {
  	// get input data
  	$view = $data['view'];


		// required var
  	if (is_array($data['required_var'])) {
  		$required_var = implode(",", $data['required_var']);
  	} else {
  		$required_var = $data['required_var'];
  	}

		if ($this->config->item('undefined_var_serialize'))
		{
  		$passed_var = serialize($data['passed_var']);
  	} else {
			if (is_array($data['passed_var']) || (isset($data['passed_var'][0]) && $data['passed_var'][0] != null)) {
	  		//$passed_var = implode(",", array_keys($data['passed_var']));
	  		$passed_var = implode(",", array_keys($data['passed_var']));
	  	} else {
	  		$passed_var = $data['passed_var'];
	  	}
  	}

		// undefined var
  	if (is_array($data['undefined_var'])) {
  		$undefined_var = implode(",", $data['undefined_var']);
  	} else {
  		$undefined_var = $data['undefined_var'];
  	}

  	// write to file
		$fp = fopen($this->config->item('undefined_var_log'), 'a+');
		fputcsv($fp, array('-----------------'));
		fputcsv($fp, array('controller:' . $this->router->fetch_class(),'method:'.$this->router->fetch_method()));
		fputcsv($fp, array('view:', $view));
		fputcsv($fp, array('required var:',$required_var));
		fputcsv($fp, array('passed var:', $passed_var));
		fputcsv($fp, array('undefined var:', $undefined_var));
		fclose($fp);
  }
}

?>
