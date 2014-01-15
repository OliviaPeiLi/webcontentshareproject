<?php
class MY_Parser extends CI_Parser {
	
	public function parse_objs($template, $data, $return = FALSE) {
		
		$data = $this->process_data($data);

		$data['base_url'] = rtrim( Url_helper::base_url(),"/");

		return parent::parse($template, $data, $return);
	}
	
	public function get_vars($template) {

		$CI = get_instance();

		$contents = $CI->load->view($template, array(), TRUE);
		$data = $this->find_pair($contents);
		return $data;
	}
	
	private function find_pair($contents) {
		$ret = array();
		for ($i=0;$i<100;$i++) {
			preg_match('#\{[a-zA-z0-9_:]+\}#msi', $contents, $matches, PREG_OFFSET_CAPTURE);
			if (!isset($matches[0])) return $ret;
			$end = strpos($contents, str_replace('{', '{/', $matches[0][0]), $matches[0][1]);
			if ($end !== false) {
				$sub_content = substr($contents, $matches[0][1]+strlen($matches[0][0]), $end-$matches[0][1]-strlen($matches[0][0]));
				$contents = substr($contents, 0, $matches[0][1]).substr($contents, $end+strlen($matches[0][0])+1);
				$ret[trim($matches[0][0], '{}')] = $this->find_pair($sub_content);
			} else {
				$contents = str_replace($matches[0][0], '', $contents);
				$ret[trim($matches[0][0], '{}')] = true;
			}
		}
		return $ret;
	}
	
	private function process_data($data) {
		
		foreach ($data as $key=>$val) {
			
			if (strpos($key, ':') !== false) {
			
				list($name, $model) = explode(':', $key, 2);
				$model = $model.'_model';

				foreach ($val as $k1=>$v1)	{

					$val = get_instance()->$model->get($v1);

					if ($val) {
						$single_object = end(get_instance()->$model->jsonfy(array($val)));

						if (isset($single_object->description))	{
							$single_object->description = str_replace('href="/','href="'.base_url(),$single_object->description);
						}

						foreach ($single_object as $k=>$v)	{
							if (is_object($v))	{
								foreach ($v as $sk=>$sv)	{
									$single_object->{$k . "->" .$sk} = $sv;
								}
							unset($single_object->$k);
							}
						}
						$data[$key][$k1] = $this->object_to_array($single_object);
					} else {
						$data[$key][$k1] = 'Not found';
					}
				
				}
			
			} elseif (is_array($val)) {
				$data[$key] = $this->process_data($val);
			}
		
		}

		return $data;
	}
	
	function object_to_array($obj,$prev_obj = false) {
	
	    if(is_object($obj)) {
	    	$obj = (array) $obj;
	    }
	    
	    if(is_array($obj)) {
	        $new = array();
	        foreach($obj as $key => $val) {
	            $new[$key] = $this->object_to_array($val,$obj);
	        }
	    }
	    
	    else $new = $obj;
	    
	    return $new;       
	}	
	
}