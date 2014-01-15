<?php
/**
 * Auth class used for user login and logout
 */
require_once 'admin.php';

class Scripts extends ADMIN
{
    protected $model = 'scripts_model';
    protected $list_actions = array('edit'=>'Edit','delete'=>'Delete');
    protected $list_fields = array(
								 'id' 			 => 'primary_key',
                                 'name'          => 'string',
                                 'num_instances' => 'string',
                                 'status'		 => 'function'
                             );
                             
    protected $form_fields = array(
    							'name'          => 'string',
    							'num_instances' => 'number',
    							'status'        => 'function',
                                'script_log'    => 'function',
    							);
    
    public function get_status($row) {
    	return $this->running_instances($row->name) ? 'online' : 'offline';
    }
    
    public function get_script_log($row) {
    	$ret = '';
    	for ($i=0;$i<$row->num_instances;$i++) {
	    	$file = BASEPATH.'../../../'.str_replace(array('/','.'), '_', $row->name).'_'.$i;
	    	if (!is_file($file)) {
	    		$ret .= "File not found: ".$file."<br/>";
	    		continue;
	    	}
	    	if (!$fs = fopen($file, FOPEN_READ)) {
	    		$ret .= "Cant open file: ".$file."<br/>";
	    		continue;
	    	}
	    	$log = fread($fs, filesize($file));
	    	$ret .= '<textarea readonly="readonly" style="width: 98%; height:400px">'.$log.'</textarea>';
	    }
	    $ret .= "<script type='text/javascript'>$('.wrapper form textarea').each(function() { $(this).scrollTop(this.scrollHeight); })</script>";
	    return $ret;
    }
    
    private function parse_processes() {
		if(strpos(__DIR__, '/home/fandrop/') !== false) {
		   	$location = 'current';
		} elseif(strpos(__DIR__, '/home/test.fandrop/') !== false) {
			$location = 'current';
		} else {
		   	$location = 'public_html';
		}
	
		$ret = array();
		$proc=proc_open("ps aux", array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes); 
		fwrite($pipes[0], ''); fclose($pipes[0]); 
		$stdout=stream_get_contents($pipes[1]);fclose($pipes[1]); 
		$stderr=stream_get_contents($pipes[2]);fclose($pipes[2]); 
		$rtn=proc_close($proc); 
		$processes = explode("\n", $stdout);
		$headers = array();
		foreach (explode(" ", $processes[0]) as $key=>$header) {
			$header = trim($header); if (!$header) continue;
			$headers[$header] = '(?P<'.str_replace('%', '', $header).'>'.($header=='COMMAND' ? '.' : '[^ ]').'*?)';
		}
		unset($processes[0]);
		foreach ($processes as $key=>$line) {
			preg_match('#^'.implode("\s\s*", $headers).'$#si', $line, $proc);
			if (isset($proc['PID']) && $proc['PID']) $ret[] = $proc;
		}
		return $ret;
	}
	
	private function running_instances($command) {
		$ret = array();
		if(strpos(__DIR__, '/home/fandrop/') !== false) {
		   	$location = 'current';
		}elseif(strpos(__DIR__, '/home/test.fandrop/') !== false){
			$location = 'current';
		}elseif(strpos(__DIR__, '/home/endway/') !== false){
			$location = '/home/endway/public_html';
		}else{
		   	$location = '/home/radil/public_html';
		}
		$processes = $this->parse_processes();
		foreach ($processes as $process) {
			if (strpos($process['COMMAND'], 'php '.$location.'/scripts/'.$command) !== FALSE) {
				list(,$params) = explode($command.' ', $process['COMMAND'],2);
				$ret[$params] = true;
			}
		}
		return $ret;
	}
}
