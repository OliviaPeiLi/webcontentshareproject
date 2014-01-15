<?php
class Ft_admin
{

	public function render_list_field($row, $field, $type) {
		switch($type) {
			case 'primary_key':
				return $row->$field;
			case 'number': //for future use
			case 'string':
				return $row->$field;
			case 'precent':
				return round($row->$field * 100, 2).'%';
			case 'belongs_to':
				if($row->$field) {
					$model = $row->$field->_model;
					$model_name = Inflector_helper::plural(strtolower(str_replace('_model', '', get_class($model))));
					$primary_key = $model->primary_key();
					return '<a href="/admin/'.$model_name.'?search=true&cond['.$primary_key.']=equals&'.$primary_key.'='.$row->$field->$primary_key.'" target="_blank">'.(strlen($row->$field) > 60 ? substr($row->$field, 0, 60).'...' : $row->$field).'</a>';
				}
				break;
			case 'date':
				return date('m/d/Y', strtotime($row->$field));
			case 'datetime':
				return date('m/d/Y H:i:s', strtotime($row->$field));
			case 'function':
				return get_instance()-> {'get_'.$field}($row);
			case 'array':
				return implode(', ', $row->$field);
			case 'assoc_array':
				return '<ul>'.$this->print_assoc($row->$field).'</ul>';
			case 'link':
				return '<a href="'.$row->$field.'">'.(strlen($row->$field) > 60 ? substr($row->$field, 0, 60).'...' : $row->$field).'</a>';
			//To check these fields which of them are actualy used
			case 'string_unsortable':
				return $row->$field;
			case 'serialized_array':
				return print_r(unserialize($row->$field));
			case 'string_link':
				$link = $field.'_link';
				return '<a href="'.$row->$link.'">'.$row->$field.'</a>';
			case 'image':
				return '<img src="'.$row->$field.'" alt="'.$row->$field.'" style="max-width: 300px" />';
			case 'time':
				return $row->$field==0000-00-00 ? '' : date('d/m/Y H:i:s', strtotime($row->$field));
			case 'checkbox':
				return $row->$field == 0 ? 'Off' : 'On';
			case 'hidden':
				return;
			case 'readonly':
				return '<strong>'.$row->$field.'</strong>';
			default :
				if (strpos($type, 'image_') !== false) {
					$thumb = str_replace('image_', '', $type);
					return '<img src="'.$row->{$field.'_'.$thumb}.'" alt="'.$row->$field.'" />';
				}
				var_dump($row->$field);
		}
	}

	public function render_form_field($name, $row, $type) {
		$type = !is_array($type)?array($type): $type;
		switch($type[0]) {
			case 'primary_key':
				return $row->$name;
				break;
			case 'url': //difeerent type for the validation
			case 'string':
				return '<input type="text" name="'.$name.'" class="half" value="'.$row->$name.'" />';
				break;
			case 'true_value':
			return '<input type="text" name="'.$name.'" class="half" value="true" />';
			break;
			case 'time':
				return '<input type="date" id="datepick" name="'.$name.'" class="" value="'.$row->$name.'" name="datepick"/>';
				break;
			case 'text':
				return '<textarea name="'.$name.'" class="medium half" >'.$row->$name.'</textarea>';
				break;
			case 'html':
				return '<textarea name="'.$name.'" class="large full" ></textarea>';
				break;
			case 'boolean':
				return '<br/>
					   <input type="radio" id="'.$name.'_rb1" class="" value="1" name="'.$name.'" '.($row->$name == 1 ? 'checked="checked"' : '').' />
					   <label class="choice" for="'.$name.'_rb1">1</label><br/>
					   <input type="radio" id="'.$name.'_rb2" class="" value="0" name="'.$name.'" '.($row->$name == 0 ? 'checked="checked"' : '').' />
					   <label class="choice" for="'.$name.'_rb2">0</label>';
				break;
			case 'checkbox':
				return '<input type="checkbox" value="1" name="'.$name.'" '.($row->$name == 1 ? 'checked="checked"' : '').' />';
				break;
			case 'time':
				return '<br/>'.date('d/m/Y H:i:s', strtotime($row->$name));
				break;
			case 'hidden':
				break;
			case 'readonly':
				if (strpos($name, '->') !== false) {
					list($field, $sub_field) = explode('->', $name);
					$val = $row->$field->$sub_field;
				} else {
					$val = $row->$name;
				}
				return '<strong>'.$val.'</strong>';
				break;
			case 'function':
				return get_instance()-> {'get_'.$name}($row);
				break;
			case 'token_list':
				$conf = array(
						'id'					=> $name,
						'class'					=> "tokenInput half",
						'theme'					=> "admin",
						'no_results_text'		=> "No people found.",
						'prevent_duplicates'	=> "true",
				);
				
				$selected = array();
				$data = $row->$name;
				if (is_array($data) && isset($data[0])) {
					if ($data[0] instanceof Model_Item) {
						$selected = get_instance()-> {$name.'_get'}($row);
						$model = $row->_model;
						$model_name = Inflector_helper::plural(strtolower(str_replace('_model', '', get_class($model))));
						$conf['data-url'] = "/admin/".$model_name."/".$name;
					} else {
						$selected = $data;
						$conf['allow_insert'] = 'true';
						$conf['data-url'] = '[]';
					}
				}
				return Form_Helper::input($name, $selected ? json_encode($selected) : '', $conf);
				break;
			case 'img':
				return '<img src="'.$row->{$name}.'"><input type="file" name="'.$name.'" size="20"/>';
				break;
			case 'select':
				return Form_Helper::dropdown($name, $type['options'], $row->$name);
			case 'external_text':
				$field_name = str_replace(array('[link]','[',']'),array('','->',''),$name);
				$field_array = explode('->',$field_name);
				$field_value = $row;
				foreach($field_array as $field){
					try{
						$field_value = @$field_value->$field;
					}
					catch(Exception $e){
						$field_value = null;
					}
				}
				return  '<textarea name="'.$name.'" class="medium half" >'.$field_value.'</textarea>';
				break;
			default :
				var_dump($row->$name);
		}
	}


	public function render_filter_field($name, $type) {
		$num_conditions = array('equals'=>'=', 'not'=>'!=', 'more'=>'>', 'more_e' => '>=', 'less'=>'<', 'less_e'=>'<=');
		$str_conditions = array('equals' => 'Equals', 'starts' => 'Starts with', 'ends' => 'Ends', 'contains' => 'Contains');
		if (is_array($type)) {
			 return Form_Helper::dropdown("cond[$name]", $num_conditions)
			 	   .Form_Helper::dropdown($name, $type, isset($_GET['search']) ? $_GET[$name] : '');
		}
		switch($type) {
		case 'primary_key':
		case "number":
			return Form_Helper::dropdown("cond[$name]", $num_conditions, array('class'=>'cond'))
				  .Form_Helper::input($name, isset($_GET['search']) ? $_GET[$name] : '', array('class'=>'half'));
			break;
		case 'string':
			return
				Form_Helper::dropdown("cond[$name]", $str_conditions, array('class'=>'cond'))
				.Form_Helper::input($name, isset($_GET['search']) ? $_GET[$name] : '', array('class'=>'half'));
			break;
		case 'time':
			return
				Form_Helper::dropdown("cond[$name]", $num_conditions, array('class'=>'cond'))
			   .Form_Helper::input($name, isset($_GET['search']) ? $_GET[$name] : date('Y-m-d H:i:s'), array('class'=>'datepick half'));
			break;
		default :
			var_dump($type);
		}
	}

	public function render_submit_form_field($name, $type) {
		switch($type) {
			case 'primary_key':
			case "number":
				return
					'<input type="text" name="'.$name.'" class="half" value="" />';
				break;
			case 'string':
				return
					'<input type="text" name="'.$name.'" class="half" value="" />';
				break;
			case 'time':
				return
					'<input type="date" id="datepick" name="'.$name.'" class="half" value="'.date('Y-m-d H:i:s').'" name="datepick"/>';
				break;
			case 'image':
				return
					'<input type="file" name="'.$name.'" class="half" size="20" />';
				break;
			case 'hidden_true':
				return
					'<input type="hidden" name="'.$name.'" value=TRUE />';
				break;
			case 'checkbox':
				return
					'<input type="checkbox" value="1" name="'.$name.'" />';
				break;
			default :
				var_dump($form_value);
		}
	}

	private function print_assoc($arr) {
		$ret = '';
		foreach ($arr as $key=>$val) {
			if (false && is_array($val) || is_object($val)) {
				$ret .= '<li>'.$key.': <ul>'.$this->print_assoc($val).'</ul></li>';
			} else {
				$ret .= '<li>'.$key.':'.substr($val, 0, 50).'</li>';
			}
		}
		return $ret;
	}

	public function render_actions($actions=null, $row, $primary_key=null) {
		$ret = '';
		$ci = get_instance();
		foreach ($actions as $url=>$action) {
			$base_url = '/admin/'.$ci->router->fetch_class().'/'.$row->$primary_key;
			if (!is_array($action)) $action = array('title'=>$action);
			if (is_array($action) && isset($action['condition']) && !$this->check_condition($row, $action['condition'])) continue;
			if (!isset($action['title'])) $action['title'] = $url;
			$class = $url=='delete' ? ' btn-red' : ' btn-blue';
			if (!isset($action['attrs'])) {
				$action['attrs'] = array('class' => isset($action['class']) ? $action['class'].$class : $class);
			} else {
				$action['attrs'] = array_merge(array('class' => isset($action['class']) ? $action['class'].$class : $class), $action['attrs']);
			}
			$action['attrs']['class'] .= ' btn';
			if ($url == 'save') {
				$ret .= ' <input type="submit" value="'.$action['title'].'" class="btn btn-blue" />';
				continue;
			}
			switch($url) {
				case 'stats':
					$url = '#tabs-1';
					break;
				case 'edit':
					$url = '';
					break;
				case 'delete':
					$action['attrs']['onclick'] = 'return confirmDelete()';
					break;
				case 'index':
					$base_url = '/admin/'.$ci->router->fetch_class();
					break;
				default:					
			}
			$ret .= Html_helper::anchor($base_url.'/'.$url, $action['title'], $action['attrs']);
		}
		return $ret;
	}
	
	private function check_condition($row, $full_condition) {
		if (strpos($full_condition, '||') !== false) {
			$full_condition = explode('||', $full_condition);
		} else {
			$full_condition = array($full_condition);
		}
		foreach ($full_condition as $condition) {
			if (strpos($condition, '&&') !== false) {
				$sub_conditions = explode('&&', $condition);
			} else {
				$sub_conditions = array($condition);
			}
			$sub_condition_met = true;
			foreach ($sub_conditions as $sub_condition) {
				$sub_condition = str_replace(' ', '', $sub_condition);
				if (strpos($sub_condition, '==') !== false) {
					list($field, $val) = explode('==', $sub_condition);
					if ($row->$field != $val) $sub_condition_met = false;
				} elseif (strpos($condition, '<') !== false) {
					list($field, $val) = explode('<', $sub_condition);
					if ($row->$field >= $val) $sub_condition_met = false;
				} elseif (strpos($condition, '>') !== false) {
					list($field, $val) = explode('>', $sub_condition);
					if ($row->$field <= $val) $sub_condition_met = false;
				} elseif (strpos($condition, '<=') !== false) {
					list($field, $val) = explode('<=', $sub_condition);
					if ($row->$field > $val) $sub_condition_met = false;
				} elseif (strpos($condition, '>=') !== false) {
					list($field, $val) = explode('>=', $sub_condition);
					if ($row->$field < $val) $sub_condition_met = false;
				} elseif (strpos($condition, '!=') !== false) {
					list($field, $val) = explode('!=', $sub_condition);
					if ($row->$field == $val) $sub_condition_met = false;
				}
			}
			if ($sub_condition_met) return true;
		}
		return false;
	}
}