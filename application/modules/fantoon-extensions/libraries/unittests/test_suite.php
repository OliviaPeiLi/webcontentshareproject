<?php
require_once(APPPATH.'third_party/simpletest/unit_tester.php');
require_once(APPPATH.'third_party/simpletest/reporter.php');
require_once(APPPATH.'third_party/simpletest/collector.php');
require_once(APPPATH.'third_party/simpletest/mock_objects.php');
require_once(APPPATH.'third_party/simpletest/web_tester.php');
require_once(dirname(__FILE__) . '/database_interface.php');

class Test_Suite extends TestSuite {
	
	private $config;
	
	function run($reporter, $db) {
		$this->config = $this->load_config();
		
    	$db_interface = new DatabaseInterface();
    	$db_interface->db = $db;
    	$db_interface->config = $this->config;
    	$reporter->paintGroupStart($this->getLabel(), $this->getSize());
        for ($i = 0, $count = count($this->test_cases); $i < $count; $i++) {
        	if (is_string($this->test_cases[$i])) {
                $class = $this->test_cases[$i];
                $test = new $class();
                $test->db_interface = $db_interface;
                $test->config = $this->config;
                $test->run($reporter);
                unset($test);
            } else {
                $this->test_cases[$i]->run($reporter, $db);
            }
        }
        $reporter->paintGroupEnd($this->getLabel());
        $db_interface->clean();
        return $reporter->getStatus();
    }
    
	private function load_config() {
		include BASEPATH.'../application/config/unittests.php';
		$config['base_url'] = rtrim($config['base_url'],'/').'/';
		return $config;
	}
    
    function collect($path, $collector, $recusrive = false) {
        $collector->collect($this, $path, $recusrive);
    }
    
	function addFile($test_file) {
        $extractor = new Simple_File_Loader();
        $this->add($extractor->load($test_file));
    }
    
	public function log($message, $fg_color=255, $bg_color=0) {
		echo $message."\r\n"; ob_flush();
	}
}

class Web_Test_Case extends WebTestCase {
	public $config = array();
	
	public function setUp() {  // site login
		if (isset($this->config['pre_login'])) $this->pre_login();
		parent::setUp();
	}
	
	public function pre_login() {
		$this->get('');
		$this->authenticate($this->config['pre_login']['user'], $this->config['pre_login']['password']);
	}
	
	public function logout() {
		$this->get('logout');
	}
	
	public function login() {
		$this->get('signin');
        $this->assertFieldByName('email');
        $this->assertFieldByName('password');
        $this->setFieldByName('email', $this->config['login']['email']);
        $this->setFieldByName('password', $this->config['login']['password']);
        $this->assertSubmit('LOG IN');
        // $this->submitFormById('login_form');
        $this->clickSubmit('LOG IN');
        $this->assertCookie('ci_session',new PatternExpectation('#id%22%3Bs%3A'.strlen($this->config['login']['id']).'%3A%22'.$this->config['login']['id'].'#i'));
        return $this->config['login'];
	}
	
	public function get($url) {
		return parent::get($this->config['base_url'].$url);
	}
	
	public function post($url) {
		return parent::post($this->config['base_url'].$url);
	}
	
	public function assertRedirect($page) {
        $this->assertResponse(array(301, 302, 303, 307));
        $this->assertHeader('Location', $this->config['base_url'].$page);
	}
}

class Simple_File_Loader extends SimpleFileLoader {
	
	function createSuiteFromClasses($title, $classes) {
        if (count($classes) == 0) {
            $suite = new BadTestSuite($title, "No runnable test cases in [$title]");
            return $suite;
        }
        SimpleTest::ignoreParentsIfIgnored($classes);
        $suite = new Test_Suite($title);
        foreach ($classes as $class) {
            if (! SimpleTest::isIgnored($class)) {
                $suite->add($class);
            }
        }
        return $suite;
    }
    
}

class Simple_Collector extends SimpleCollector {
	
 	/**
     * Scans the directory and adds what it can.
     * @param object $test    Group test with {@link GroupTest::addTestFile()} method.
     * @param string $path    Directory to scan.
     * @see _attemptToAdd()
     */
    function collect(&$test, $path, $recusrive = false) {
        $path = $this->removeTrailingSlash($path);
        if ($handle = opendir($path)) {
            while (($entry = readdir($handle)) !== false) {
                if ($this->isHidden($entry)) {
                    continue;
                }
                if (is_dir($path . DIRECTORY_SEPARATOR . $entry) && $recusrive) {
                	$this->collect($test, $path . DIRECTORY_SEPARATOR . $entry, $recusrive);
                } else {
                	$this->handle($test, $path . DIRECTORY_SEPARATOR . $entry);
                }
            }
            closedir($handle);
        }
    }
    
}

class Simple_Pattern_Collector extends Simple_Collector {
    private $pattern;

    /**
     *
     * @param string $pattern   Perl compatible regex to test name against
     *  See {@link http://us4.php.net/manual/en/reference.pcre.pattern.syntax.php PHP's PCRE}
     *  for full documentation of valid pattern.s
     */
    function __construct($pattern = '/php$/i') {
        $this->pattern = $pattern;
    }

    /**
     * Attempts to add files that match a given pattern.
     *
     * @see SimpleCollector::_handle()
     * @param object $test    Group test with {@link GroupTest::addTestFile()} method.
     * @param string $path    Directory to scan.
     * @access protected
     */
    protected function handle(&$test, $filename) {
        if (preg_match($this->pattern, $filename)) {
            parent::handle($test, $filename);
        }
    }
}