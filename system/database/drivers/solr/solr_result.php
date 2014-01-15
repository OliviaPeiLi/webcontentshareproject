<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CI_DB_solr_result extends Apache_Solr_Response {
	
	public function result($type='array') {
		if (!$this->_isParsed) {
			$this->_parseData();
			$this->_isParsed = true;
		}
		$arrQuery = array();
		if (! $this->_parsedData->response->numFound) return array();
		
		foreach($this->_parsedData->response->docs as $doc) {
			/* Oddly, the docs object returns keys and values separately */
			$arrKeys = $doc->getFieldNames();

			/* Remove any prepends for name crashes */
			if(!is_null($this->_from)) {
				$regex = $this->_from.'\_';
				/* Avoid the primary key being replaced */
				$arrKeys = preg_replace("/^({$regex}id)/", $this->_from.'_\\1', $arrKeys);
				$arrKeys = preg_replace("/^({$regex})/", '', $arrKeys);
			}

			/* Replace key names */
			if(is_array($this->_arrAs) && count($this->_arrAs) > 0) {
				foreach($this->_arrAs as $old => $new) {
					$oldId = array_search($old, $arrKeys);
					$arrKeys[$oldId] = $new;
				}
			}
			$arrQuery[] = array_combine($arrKeys, $doc->getFieldValues());
		}
		return $arrQuery;
	}
	
	public function result_field($field) {
		if (!$this->_isParsed) {
			$this->_parseData();
			$this->_isParsed = true;
		}
		$arrQuery = array();
		if (! $this->_parsedData->response->numFound) return array();
		
		foreach($this->_parsedData->response->docs as $doc) {
			$arrQuery[] = $doc->$field;
		}
		return $arrQuery;
	}

}


/* End of file mysql_result.php */
/* Location: ./system/database/drivers/mysql/mysql_result.php */