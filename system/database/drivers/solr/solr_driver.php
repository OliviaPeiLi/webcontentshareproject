<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * MySQL Database Adapter Class
 *
 * Note: _DB is an extender class that the app controller
 * creates dynamically based on whether the active record
 * class is being used or not.
 *
 * @package		CodeIgniter
 * @subpackage	Drivers
 * @category	Database
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/database/
 */
require_once(APPPATH.'modules/fantoon-extensions/libraries/SolrPhpClient/Apache/Solr/Service.php');

class CI_DB_solr_driver extends Apache_Solr_Service {

	var $dbdriver = 'solr';
	public $connected;
	public $show_errors = true;
	
	var $autoinit		= TRUE; // Whether to automatically initialize the DB
	var $hostname;
	var $database;
	var $port;
	
	public $table = 'table';
	public $num_rows = '0';
    public $start = false;
    public $arrFacetQueries = null;
    public $arrFacetFields = null;

    /* Used to build the query */
    protected $_offset = 0;
    protected $_limit = 10;
    protected $_arrOrder = null;
    protected $_facet = false;
    protected $_from = null;
    
    protected $_arrSelect = null;
    protected $_arrParams = null;
    protected $_arrWhere = null;
    protected $_arrGroup = null;
    protected $_arrHaving = null;
    protected $_arrAs = null;
	
	protected $_count_string = 'get_num_rows'; 
	
	function __construct($params) {
		if (is_array($params))
		{
			foreach ($params as $key => $val)
			{
				$this->$key = $val;
			}
		}

		log_message('debug', 'Database Driver Class Initialized');
	}
	
	public function initialize() {
		/* No point connecting twice */
        if(!$this->connected) {
            /* Connect to Solr - strictly speaking, you don't need vars here */
        	parent::__construct($this->hostname, $this->port, '/'.$this->database);
            /* Test the connection */
            if($this->ping() !== false) {
                /* Worked - set connection */
                $this->connected = true;
                return $this->connected;
            } else {
                /* Connection failed - throw error? */
                $msg = "Solr failed to connect - host: {$this->hostname}, port: {$this->port}, path: /{$this->database}";
                log_message('debug', $msg);
            }
        }
	}
	
	/**
     * Clean
     *
     * Resets the variables
     */
    final protected function _clean() {
        $this->_arrSelect = null;
        $this->_arrParams = null;
        $this->_from = null;
        $this->_arrWhere = null;
        $this->_arrGroup = null;
        $this->_arrHaving = null;
        $this->_arrOrder = null;
        $this->_limit = 10;
        $this->_offset = 0;
        $this->_arrAs = null;
        $this->_facet = false;
    }
    
    /**
     * qPage
     *
     * Basically the same as the MySQL qPage function in
     * that it returns a paged array (or false)
     *
     * @param number $pg
     * @param number $perPage
     * @return array/false
     */
    final public function qPage($pg = 1, $perPage = 10) {
        $this->query();
        $total = $this->num_rows;
        $totalPages = ceil($total / $perPage);

        /* Make sure $pg can't be too low */
        $floor = $pg - 1;
        if($floor < 0) {
            $pg = 1;
            $floor = 0;
        }
        
        /* Make sure $pg can't be too high */
        if(($floor * $perPage) > $total) {
            $floor = floor($total / $perPage);
            $pg = $floor + 1;
        }

        /* Calculate the from */
        $from = $floor * $perPage;

        $this->limit($perPage, $from);
        $arrQuery = $this->qAssoc();
        if($arrQuery) {
            /* Results - send all the data */
            $arrReturn = array(
                'total' => $total,
                'totalPages' => $totalPages,
                'pg' => $pg,
                'perPage' => $perPage,
                'results' => $arrQuery,
            );
            return $arrReturn;
        } else {
            return false;
        }
    }

    /**
     * Get Rows Returned
     *
     * Returns the number of rows from a query. If
     * the query hasn't been executed yet, it returns
     * false
     * 
     * @return number/false
     */
    final public function count_all_results($table = '', $reset = true) {
    	if ($table) $this->from($table);
    	
    	$res = $this->query();
    	
    	if ($reset) $this->_clean();
    	
        if(!is_null($res->_objResponse)) { return $res->num_rows; }
        else { return false; }
    }

    /**
     * Error
     *
     * Logs errors from Solr.  If the config is set
     * to "show errors" then it actually shows them
     * too - useful for debugging.
     *
     * In normal "live" operation, you would want to
     * not show errors
     *
     * @param string $string
     */
    final protected function _error($string = 'There was an error in Solr') {
    	echo "ERRROR: ".$string."\r\n";
    	print_r($this);
    	die('ERROR');
        if($this->show_errors) { show_error($string); }
        error_log($string);
    }

    
    /** "DB" Style Functions are below **/

    /**
     * Select
     *
     * Does the select column function
     *
     * @param string $select
     * @param string $as
     * @return object
     */
    final public function select($select = '*', $as = null) {
        /* Clean the select - make sure AS not written in */
        $arrSelect = preg_split('/\s(\s+)?(AS)?(\s)?(\s+)?/', $select);
        /* Part 1 is select, part 2 is as */
        if(array_key_exists('0', $arrSelect)) { $select = $arrSelect[0]; }
        if(array_key_exists('1', $arrSelect)) { $as = $arrSelect[1]; }

        /* Port the 'AS' statements */
        if(!is_null($as)) {
            if(is_null($this->_arrAs) || !in_array($as, $this->_arrAs)) {
                $this->_arrAs[$select] = $as;
            }
        }

        /* Check not already in the select array */
        if(is_null($this->_arrSelect) || !in_array($select, $this->_arrSelect)) {
            $this->_arrSelect[] = $select;
        }
        return $this;
    }

    /**
     * From
     *
     * This is the same as the MySQL from statement.  Although
     * Solr doesn't actually have a "from" equivalent (because
     * all 'tables' are put in one XML file) it's a good idea
     * to prepend the 'column' names with a unique string. This
     * is what this "from" function does.
     *
     * @param string $table
     * @param string $append
     * @return object
     */
    final public function from($table = null) {
        if(!is_null($table)) { $this->_from = $table; }
        return $this;
    }

    /**
     * Where
     *
     * Builds a where.  If you have multiple, sets
     * "AND" between them.  You can enter a wildcard
     * search to be appended to the search string.
     *
     * NB. If you set the $key as a multidimensional array,
     * instead of $key and $value as strings, you will get
     * a subtly different result.  What will happen is
     * that the queries will be groups.  IE:
     *
     * Example 1:
     * $this->solr->where('title', 'birmingham coventry');
     * $this->solr->where('description', 'birmingham coventry');
     *
     * Will return where both the title AND description match
     * either "birmingham" or "coventry".
     *
     * Example 2:
     * $this->solr->where(array(
     *      'title' => 'birmingham coventry',
     *      'description' => 'birmingham coventry',
     * ));
     *
     * Will return where either title OR description match
     * either "birmingham" or "coventry"
     *
     * Operators
     *
     * By default, Solr does all it's queries using "OR".  To match
     * "AND":
     *      $this->solr->where('title =', 'birmingham');
     *
     * To match "AND NOT":
     *      $this->solr->where('title !=', 'coventry');
     *
     * @param mixed $key
     * @param mixed $value
     */
    final public function where($key, $value = null) {
        if(!is_array($key)) {
            $key = array($key => $value);
        }

        if(count($key) > 0) {
            foreach($key as $k => $v) {
                /* Text queries must be lower case */
                $k = strtolower($k);
                $v = strtolower($v);

                /* Get the operator */
                $arrQuery = $this->_operator($k);

                /* Build the rest of the prefix */
                $column = $arrQuery['prefix'].$arrQuery['column'].':';

                /* Split spaces into duplicate search fields */
                $v = preg_split('/\s(\s+)?/', $v);

                if(count($v) > 0) {
                    $arrGroup = array();
                    foreach($v as $search) {
                        $arrGroup[] = $column.$search;
                    }
                }

                $arrTerms[] = $arrGroup;

            }
        }

        $this->_arrWhere[] = $arrTerms;
        return $this;
    }

    /**
     * Where Date Range
     *
     * Run a where on two dates
     *
     * @param string $field
     * @param string $from
     * @param string $to
     * @return object
     */
    final public function where_date_range($field, $from, $to) {
        /* Get the operator */
        $arrQuery = $this->_operator($field);

        /* Build the query */
        $query = $arrQuery['prefix'].$arrQuery['column'].':';
        $query .= "[{$from} TO {$to}]";

        $this->_arrWhere[] = $query;
        return $this;
    }

    /**
     * Order By
     *
     * The sort order for the result. Solr sorts
     * be relevancy ("score") by default so no
     * need to do that here
     *
     * @param string $column
     * @param string $direction
     * @return object
     */
    final public function order_by($column, $direction = 'ASC') {
        $arrDirection = array(
            'asc',
            'desc'
        );
        $direction = strtolower($direction);
        if(in_array($direction, $arrDirection)) {
            $column = strtolower($column);
            $sort = $column.' '.$direction;
            if(is_null($this->_arrOrder) || !in_array($sort, $this->_arrOrder)) {
                $this->_arrOrder[] = $sort;
            }
        } else {
            $this->_error("{$direction} is not a valid order_by direction");
        }
        return $this;
    }

    /**
     * Limit
     *
     * Performs the limit/offset
     * 
     * @param number $limit
     * @param number $offset
     */
    public function offset($offset=0) {
    	$this->_offset = $offset;
    } 
    final public function limit($limit, $offset = '0') {
        if(is_numeric($limit)) {
            $this->_limit = $limit;
            if(is_numeric($offset)) { $this->_offset = $offset; }
        }
        return $this;
    }

    /**
     * Prepare Params
     *
     * Prepare the parameters for passing to the
     * Solr connection
     *
     * @param array $params
     * @return array
     */
    final protected function _prepare_params(array $params = null) {
        /* Import passed params - probably won't be used */
        if(!is_null($params)) { $this->_arrParams = $params; }

        /* Do the select */
        $this->set_param('fl', implode(',', $this->_arrSelect));

        /* Do the sort */
        if(!is_null($this->_arrOrder)) {
            $this->set_param('sort', implode(',', $this->_arrOrder));
        }
        return $this->_arrParams;
    }

    /**
     * Set Param
     *
     * Sets the param array
     *
     * @param string $key
     * @param string $value
     */
    final protected function set_param($key, $value) { $this->_arrParams[$key] = $value; }

    /**
     * Prepare Query
     *
     * Prepares the query (aka, the "where"
     * statement)
     *
     * @return string
     */
    final protected function _prepare_query() {
        $return = null;
        if(!is_null($this->_arrWhere) && count($this->_arrWhere) > 0) {
            foreach($this->_arrWhere as $where) {
                if(is_array($where)) {
                    /* Is it grouped together */
                    if(is_array(current($where))) {
                        /* Group queries in this array */
                        if(count($where) > 0) {
                            $return .= '+(';
                            foreach($where as $gWhere) {
                                $return .= '('.implode(' ', $gWhere).') ';
                            }
                            $return .= ') ';
                        }
                    }
                } else {
                    $return .= $where.' ';
                }
            }
        }

        /* Set the from - not needed for Solr, but recommended */
        if(!is_null($this->_from)) { $return .= ' +'.$this->table.':'.$this->_from; }
        else { $this->_error('No from statement given'); }
        return $return;
    }
	
	public function get($table = '', $limit = null, $offset = null)
	{
		if ($table) $this->_from = $table;
		return $this->query();
	}
    /**
     * Search
     *
     * Extends the search function so it sets number found
     * etc to this class for ease.  You can still use the
     * ordinary Apache_Solr_Service::search() function if you set your
     * query directory in $query
     *
     * @param string $query
     * @param int $offset
     * @param int $limit
     * @param array $params
     * @param mixed $method
     * @return object
     */
    final public function query($query = null, $offset = false, $limit = false, array $params = null, $method = false) {
        
        /* Check we've got a connection */
        /* TRY 5 times to connect */
        for($i=1;$i<=5;$i++)
        {
            if(!$this->connected) { $this->connect(); }
        }

        /* Confirm we've made a connection before running query */
        if($this->connected) {
            /* Select the score by default */
            $this->_arrSelect[] = 'score';

            /* Generate the query */
            if(is_null($query)) { $query = $this->_prepare_query(); }

            /* Get the offset and limit */
            if($offset === false) { $offset = $this->_offset; }
            if($limit === false) { $limit = $this->_limit; }

            /* Get the params */
            $params = $this->_prepare_params($params);

            /* So it runs the Apache_Solr_Service::query() properly */
            if($method === false) { $method = Apache_Solr_Service::METHOD_GET; }

            /* Run the query */            
            $objResponse = $this->search($query, $offset, $limit, $params, $method);
            $objResponse->_arrAs = $this->_arrAs;
            
            /* Convert the response so we can use it usefully */
        	
            /*$objResponse->_objHeader = $res->responseHeader; // Header object
            $objResponse->_objResponse = $res->response; // Docs returned
            
            $objResponse->num_rows = $res->response->numFound; // Total number of rows
            $objResponse->start = $res->response->start;  // This should be the same as $offset
            $objResponse->limit = $limit; // The limit
			*/

            /* Check for facetting */
            if($this->_facet) {
                $objResponse->_objFacet = $res->facet_counts;
                $objResponse->arrFacetQueries = (array) $res->facet_counts->facet_queries;
                
                /* Get the fields */
                $field = $this->_arrParams['facet.field'];
                $objResponse->arrFacetFields = (array) $res->facet_counts->facet_fields->$field;
            }
            /* Return the object created the parent */
            return $objResponse;
        }
    }
    
	protected function _sendRawGet($url, $timeout = FALSE)
	{
		$httpTransport = $this->getHttpTransport();
		$httpResponse = $httpTransport->performGetRequest($url, $timeout);
		$driver = $this->load_rdriver();
		
		$solrResponse = new $driver($httpResponse, $this->_createDocuments, $this->_collapseSingleValueArrays);

		if ($solrResponse->getHttpStatus() != 200)
		{
			echo $url."\r\n";
			print_r($solrResponse->getRawResponse());
			echo "\r\n";
			die(print_r($solrResponse));
			throw new Apache_Solr_HttpTransportException($solrResponse);
		}

		return $solrResponse;
	}
	
	function load_rdriver() {
		$driver = 'CI_DB_'.$this->dbdriver.'_result';

		if ( ! class_exists($driver))
		{
			include_once(BASEPATH.'database/DB_result.php');
			include_once(BASEPATH.'database/drivers/'.$this->dbdriver.'/'.$this->dbdriver.'_result.php');
		}

		return $driver;
	}

    /**
     * Operator
     *
     * Generates the operator from the MySQL
     * operator type
     * 
     * @param string $query
     * @return array
     */
    final protected function _operator($query) {
        /* The operators and their replacements */
        $arrOpSwap = array(
            '=' => '+',
            '!=' => '!',
        );

        /* See if there's an operator */
        $prefix = '';
        if(preg_match('/(\w+)(\s+)(.+)/', $query, $operator)) {
            /* Get the operator */
            $operator = $operator['3'];
            if(array_key_exists($operator, $arrOpSwap)) {
                $prefix = $arrOpSwap[$operator];
                $query = stripWhitespace(preg_replace("/{$operator}/", '', $query));
            }
        }

        return array(
            'prefix' => $prefix,
            'column' => $query,
        );
    }

    /**
     * Facet
     *
     * Facetting is the most difficult part of Solr
     *
     * @param string $field
     * @param string $query
     * @return objct
     */
    final public function facet($field) {
        $this->start_facetting();
        $this->set_param('facet.field', $field);
        return $this;
    }

    /**
     * Facet Query
     *
     * Only one query can be sent due to a limitation in
     * the Solr-PHP-Client.  If you need to add multiple
     * queries, will need to rewrite line 954.
     *
     * @param string $query
     * @return object
     */
    final public function facet_query($query) {
        $this->start_facetting();
        $this->set_param('facet.query', $query);
        return $this;
    }

    /**
     * Start Facetting
     *
     * Enables facetting on this Solr query
     */
    final protected function start_facetting() {
        if(!$this->_facet) {
            $this->_facet = true;
            /* Create facet query */
            $this->set_param('facet', 'true');
            /* Only return facetted fields that have count > 0 */
            $this->set_param('facet.mincount', '1');
        }
    }
	
	
}


/* End of file mysql_driver.php */
/* Location: ./system/database/drivers/mysql/mysql_driver.php */