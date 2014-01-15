<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tools extends MX_Controller
{
    function kill_session_data()
    {

        $this->load->library('session');
        $this->session->unset_userdata('view_page');
        //echo 'works';
        //$session_name = $this->input->post('session_name',true);
        //print_r($session_name);
        /*	foreach($session_name as $k=>$v)
        	{
        		//echo $v;
        		//$name = $v;
        		$this->session->unset_userdata("$v");  
        	}
        */
        echo 'works';
        /*
        	if (isset($_SERVER['HTTP_X_REQUESTED_WITH') && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'))
        	{
        		$this->session->unset_userdata('view_page');
        		$this->load->library('session');
        		$session_name = $this->input->post('session_name[]',true);
        		foreach($session_name as $name)
        		{
        			$this->session->unset_userdata($name);  
        		}
        	}*/
    }
}
?>
