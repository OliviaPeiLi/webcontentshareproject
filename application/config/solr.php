<?php
/**
 * solr.php
 *
 * @author Simon Emms <simon@bigeyedeers.co.uk>
 */

/* Basic connection details */
/* Other config */
if (ENVIRONMENT == 'production') {
	$config['solr']['address'] = 'solr://192.168.0.8:8080/solr';
} else if (ENVIRONMENT == 'staging') {
	// $config['solr']['address'] = 'solr://hn2.fandrop.com:9735/solr/core0';
	$config['solr']['address'] = 'solr://localhost:8080/solr';
} elseif(strpos(BASEPATH, 'quangphan') !== false) {
	$config['solr']['address'] = 'solr://54.243.131.84:8080/solr';
} elseif(strpos(BASEPATH, 'fantoon.loc') !== false || $_SERVER['HTTP_HOST'] == 'localhost'){
	$config['solr']['address'] = 'solr://localhost:8080/solr';
} else {
	$config['solr']['address'] = '';
}
