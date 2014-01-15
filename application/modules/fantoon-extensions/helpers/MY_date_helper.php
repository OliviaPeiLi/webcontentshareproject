<?php
class Date_Helper extends Helper {
	
	public function time_ago($time, $levels=1) {
		if (!is_int($time)) $time = strtotime($time);
		$ago = $time < time();
		
		$a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
		            30 * 24 * 60 * 60       =>  'month',
		            24 * 60 * 60            =>  'day',
		            60 * 60                 =>  'hour',
		            60                      =>  'minute',
		            1                       =>  'second'
		            );
		$ret = '';
		
		for ($i=1;$i<=$levels;$i++) {
			$etime = abs(time() - $time);
			if ($etime < 1) {
			    return 'just now';
			}

			foreach ($a as $secs => $str) {
			    $d = floor($etime / $secs);
			    unset($a[$secs]);
			    if ($d >= 1 || ($levels > 1 && $i > 1)) {
			        $ret .= $d . ' ' . $str . ($d > 1 ? 's ' : ' ');
			        break;
			    }
			}
			
			$time -= $secs*floor($d);
			
		}
		
		return $ret .($ago ? 'ago' : '');
	}
	
}
