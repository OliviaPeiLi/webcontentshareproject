<?php

class Time extends MX_Controller {

	private $zonelist = array(
		'Etc/GMT+12' => -12.00, 
		'Etc/GMT+11' => -11.00, 
		'Etc/GMT+10' => -10.00, 
		'Etc/GMT+9' => -9.00, 
		'Etc/GMT+8' => -8.00, 
		'Etc/GMT+7' => -7.00, 
		'Etc/GMT+6' => -6.00, 
		'Etc/GMT+5' => -5.00, 
		'America/Caracas' => -4.30, 
		'Etc/GMT+4' => -4.00, 
		'America/St_Johns' => -3.30, 
		'Etc/GMT+3' => -3.00, 
		'Etc/GMT+2' => -2.00, 
		'Etc/GMT+1' => -1.00, 
		'Etc/GMT' => 0, 
		'Etc/GMT-1' => 1.00, 
		'Etc/GMT-2' => 2.00, 
		'Etc/GMT-3' => 3.00, 
		'Asia/Tehran' => 3.30, 
		'Etc/GMT-4' => 4.00, 
		'Etc/GMT-5' => 5.00, 
		'Asia/Kolkata' => 5.30, 
		'Asia/Katmandu' => 5.45, 
		'Etc/GMT-6' => 6.00, 
		'Asia/Rangoon' => 6.30, 
		'Etc/GMT-7' => 7.00, 
		'Etc/GMT-8' => 8.00, 
		'Etc/GMT-9' => 9.00, 
		'Australia/Darwin' => 9.30, 
		'Etc/GMT-10' => 10.00, 
		'Etc/GMT-11' => 11.00, 
		'Etc/GMT-12' => 12.00, 
		'Etc/GMT-13' => 13.00
	);

	function set_client_time() {

		$time_offset = $this->input->get("timeoffset",false);
		$hours_offset = $this->input->get("time",false);

		if ($time_offset)	{

		    $index = array_keys( $this->zonelist, $time_offset );

			$this->session->set_userdata("time", array(
				"time_offset" => $time_offset,
				"hours_offset" => round( time() / $hours_offset ),
				"string_offset" => $index[0]
			));

		}

	}
}