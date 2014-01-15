<?php

class Form_Helper extends Html_helper {

	public static function dropdown($name = '', $options = array(), $selected = array(), $attrs = array()) {
		$selected = (Array) ($selected ? $selected : Array_Helper::element($name, $_POST));
		if (count($selected) > 1) $attrs['multiple'] = "multiple";
		$attrs = array_merge($attrs, array(
			'name' => $name
		));
		
		$opts = '';
		foreach ($options as $key => $val) {
			$key = (string) $key;
			if (is_array($val) && ! empty($val)) {
				if (isset($val['id']) && isset($val['name'])) {
					$opt_attrs = array('value'=>$val['id']);
					if (in_array($val['id'], $selected)) $opt_attrs['selected'] = "selected";
					if (isset($val['class'])) $opt_attrs['class'] = $val['class']; 
					$opts .= self::tag('option', $opt_attrs, (String)$val['name'])."\n";
				} else {
					$sub_opts = "";
					foreach ($val as $optgroup_key => $optgroup_val) {
						$opt_attrs = array('value'=>$optgroup_key);
						if (in_array($optgroup_key, $selected)) $opt_attrs['selected'] = "selected";
						$sub_opts .= self::tag('option', $opt_attrs, (String) $optgroup_val)."\n";
					}
					$opts .= self::tag('optgroup', array('label'=>$key), $sub_opts)."\n";
				}
			} else {
				$opt_attrs = array('value'=>$key);
				if (in_array($key, $selected)) $opt_attrs['selected'] = "selected";
				$opts .= self::tag('option', $opt_attrs, (String) $val)."\n";
			}
		}
		
		return self::tag('select', $attrs, $opts);
	}


	public static function textarea($name = '', $value = '', $attrs = array()) {
		$attrs = array_merge($attrs, array(
			'name' => $name
		));
		return self::tag('textarea', $attrs, self::form_prep($value, $name));
	}

	public static function input($name='', $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'name' => $name,
			'value' => self::form_prep($value, $name)
		));
		if (!isset($attrs['type'])) $attrs['type'] = 'text';

		return self::tag('input', $attrs);
	}
	
	/* HTML5 inputs */
	public static function date($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'date',
		));
		return self::input($name, $value, $attrs);
	}

	public static function datetime($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'datetime',
		));
		return self::input($name, $value, $attrs);
	}
	public static function datetime_local($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'datetime_local',
		));
		return self::input($name, $value, $attrs);
	}
	public static function email($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'email',
		));
		return self::input($name, $value, $attrs);
	}
	public static function month($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'month',
		));
		return self::input($name, $value, $attrs);
	}
	public static function number($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'number',
		));
		return self::input($name, $value, $attrs);
	}
	public static function range($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'range',
		));
		return self::input($name, $value, $attrs);
	}
	public static function search($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'search',
		));
		return self::input($name, $value, $attrs);
	}
	public static function time($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'time',
		));
		return self::input($name, $value, $attrs);
	}
	public static function tel($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'tel',
		));
		return self::input($name, $value, $attrs);
	}
	public static function url($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'url',
		));
		return self::input($name, $value, $attrs);
	}
	public static function week($name, $value='', $attrs=array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'week',
		));
		return self::input($name, $value, $attrs);
	}
	/* End HTML5 inputs */
	
	public static function submit($name = '', $value = '', $attrs = array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'submit',
		));
		return self::input($name, $value, $attrs);
	}
	
	public static function password($name = '', $value = '', $attrs = array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'password',
		));
		return self::input($name, $value, $attrs);
	}
	
	public static function upload($name = '', $value = '', $attrs = array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'file',
		));
		return self::input($name, $value, $attrs);
	}
	
	public static function checkbox($name = '', $value = '', $checked = FALSE, $attrs = array()) {
		$attrs = array_merge($attrs, array(
			'type' => 'checkbox'
		));
		
		if ($checked) $attrs['checked'] = 'checked';

		return self::input($name, $value, $attrs);
	}
	
	public static function hidden($name='',$value='', $attrs=array()) {
		if (is_array($name)) {
			$ret = '';
			foreach ($name as $name=>$val) {
				$ret .= self::hidden($name, $val, $attrs);
			}
			return $ret;
		}
		if (is_array($value)) {
			$ret = '';
			foreach ($value as $sub_name=>$sub_val) {
				$ret .= self::hidden($name.'['.$sub_name.']', $sub_val);
			}
			return $ret;
		}
		return self::input($name, $value, array_merge($attrs, array('type' => 'hidden')));
	}


	/**
	 * Form Declaration
	 *
	 * Creates the opening portion of the form.
	 *
	 * @access	public
	 * @param	string	the URI segments of the form destination
	 * @param	array	a key/value pair of attributes
	 * @param	array	a key/value pair hidden data
	 * @return	string
	 */
	public static function open($action = '', $attributes = array(), $hidden = array()) {
		$CI =& get_instance();
		if (!isset($attributes['method'])) $attributes['method'] = 'post';
		
		// If no action is provided then set to the current url
		//$action OR $action = $CI->config->site_url($CI->uri->uri_string());
		$action = $action ? $action : $CI->uri->uri_string();
		if($action[0] != '/') $action = '/'.$action;
		$attributes['action'] = $action;

		// Add CSRF field if enabled, but leave it out for GET requests and requests to external websites	
		//if ($CI->config->item('csrf_protection') === TRUE AND ! (strpos($action, $CI->config->site_url()) === FALSE OR strpos($form, 'method="get"')))
		if ($CI->config->item('csrf_protection') === TRUE AND strtolower($attributes['method']) != 'get')	
		{
			$hidden[$CI->security->get_csrf_token_name()] = $CI->security->get_csrf_hash();
		}

		$form = self::tag('form', $attributes);
		if (is_array($hidden) AND count($hidden) > 0) {
			$form .= self::tag('div', array('style'=>'display:none'), self::hidden($hidden)); 
		}

		return $form;
	}

	public static function close() {
		return '</form>';
	}

	public function collection_dropdown($name, $attrs=array()) {
		$CI = get_instance();
		if (!$CI->session->userdata('id')) return '';
		
		$intscrp_lastCollection = $CI->input->cookie('intscrp_lastCollection');
		if ( $intscrp_lastCollection === false ) {
			$intscrp_lastCollection = '';
		}
		
		$cache_name = 'folders_dropdown_' . $CI->session->userdata('id') . '_nolimit';
        $options = $CI->folder_model->select_list($CI->session->userdata('id'));

		foreach ( $options as $key => $value ) {
			$options[$key]['name'] = htmlspecialchars($options[$key]['name']);
			if ( $intscrp_lastCollection == $value['name'] ) {
				$intscrp_lastCollection = $key;
				break;
			}
		}

		$attrs = array_merge(array(
			'class' => "tokenInput",
			'data-validate' => 'required',
			'data-error-required' => 'Please choose or create list first',
			'token_limit' => "1",
			'allow_insert' => "true",
			'no_results_text' => 'Create a new list',
			'placeholder' => "Click to Add",
			'style' => "width:335px"
		), $attrs);

		return self::dropdown($name, $options, $intscrp_lastCollection, $attrs);
	}

	public function hashtags_dropdown($name, $attrs=array()) {

		$CI = get_instance();

		if (!$CI->session->userdata('id')) return '';

		$CI->load->model('hashtag_model');
        $options = $CI->hashtag_model->popular_top_dropdown();
        $options = array_slice($options, 1);// remove Select one label

		$attrs = array_merge(array(
			'class' => "tokenInput",
			'token_limit' => "1",
			'allow_insert' => "true",
			'style' => "width:335px",
			'placeholder' => 'Enter Hashtag',
			'alpha_sort' => 'false',
			'data-default_data'=> "(" . str_replace("\"","'",json_encode($options)) . ")",
		), $attrs);

		return self::input($name, '', $attrs ); //self::dropdown($name, $options, null, $attrs); // 
	}

	public function rss_sources_dropdown($name, $attrs=array()) {
		$CI = get_instance();
		if (!$CI->session->userdata('id')) return '';
		
        $options = $CI->rss_source_model->popular_dropdown();
		
		$attrs = array_merge(array(
			'class' => "tokenInput",
			'token_limit' => "1",
			'allow_insert' => "true",
			'allow_null_select' => "true",
			'style' => "width:335px",
			'placeholder' => 'Enter RSS URL',
			'data-url' => '/search/rss_source'
		), $attrs);
		return self::dropdown($name, $options, null, $attrs);
	}

	public function rss_sources_input($name, $attrs=array()) {
		$CI = get_instance();
		if (!$CI->session->userdata('id')) return '';
				
		$attrs = array_merge(array(
			'class' => "tokenInput",
			'token_limit' => "1",
			'placeholder' => 'Enter RSS URL',
			'data-url' => '/search/rss_source',
			'no_results_text' => '',
			'style' => "width:335px",
			), $attrs);
		return self::input($name, "0", $attrs);
	}
	
	public static function main_search($name, $attrs = array()) {
		$attrs = array_merge(array(
			'id' => "header_search_box",
			'class' => "tokenInput",
			'theme' => "search",
			'data-url' => "/main_search",
			'hint_text' => "",
			'bottom_text' => get_instance()->lang->line('includes_views_bottom_text'),
			'bottom_link' => "/search?q={val}",
			'placeholder' => get_instance()->lang->line('includes_views_search_lexicon'),
			'min_Chars' => "2",
			'alpha_sort' => false
		), $attrs);
		return self::input($name, get_instance()->input->get($name), $attrs);
	}
}
