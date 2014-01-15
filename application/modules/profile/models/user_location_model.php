<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_location_model extends MY_Model {
    
    public function get_map() {
    	$res = $this->get_all();
    	$size = count($res) >= 7 ? 'tiny' : (count($res) >= 3 ? 'mid' : 'small');
    	$marker = '';
    	
    	foreach ($res as $row) {
    		if($row->options=='travel') {
				$marker.='&markers=icon:http://chart.apis.google.com/chart?chst=d_map_pin_icon%26chld=cafe%257C996600%7Csize:'.$size.'%7Ccolor:green%7C'.urlencode($row->place_name);
			}
			else{
				if($row->options=='current'){//this place is current location change pin color to blue
					$marker.='&markers=icon:http://chart.apis.google.com/chart?chst=d_map_pin_icon%26chld=home%257C3366CC%7Csize:'.$size.'%7C'.urlencode($row->place_name);
				}
				else{
					$marker.='&markers=icon:http://chart.apis.google.com/chart?chst=d_map_pin_icon%26chld=home%257C008000%7Csize:'.$size.'%7C'.urlencode($row->place_name);
				}
			}
    	}
    	return 'http://maps.googleapis.com/maps/api/staticmap?maptype=roadmap'.$marker.'&size=340x210&sensor=false';
    }

    //get data place user
    function getPlace($user_id) {
        $this->db->where('user_id',$user_id);
        $result=$this->db->get('user_locations');
        return $result;
    }

}