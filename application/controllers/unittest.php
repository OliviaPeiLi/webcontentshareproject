<?php
/**
 *
 * Base controller for the unittests
 * to start the desired test type the following in the console:
 * php unittests
 *
 * to run a specific module or a controller use:
 * php unittests {module_name}
 * php unittests {module_name}/{test_name}
 * php unittests {test_name}                <- for the tests in app folder
 *
 */

class Unittest extends MX_Controller
{
    public function index()
    {
        $args = array_shift($_SERVER['argv']);
        $db = $this->load->database('', TRUE);
        ob_start();
        $this->log('Preparing tests...');

        $test = $this->load->library('unittests/test_suite', 'test_'.time());

        $test->collect(APPPATH.'tests/', new Simple_Pattern_Collector('/_test.php/'), true);
        $test->collect(APPPATH.'modules/', new Simple_Pattern_Collector('/_test.php/'), true);

        $this->log('Running tests...');
        $test->run(new TextReporter(), $db);
    }

    public function log($message, $fg_color=255, $bg_color=0)
    {
        echo $message."\r\n";
        ob_flush();
    }
}