<?php

require_once '../vendor/autoload.php';

class LandingPage extends Sauce\Sausage\WebDriverTestCase
{
    public static $browsers = array(
        // run FF15 on Windows 8 on Sauce
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'version' => '15',
                'platform' => 'Windows 2012',
            )
        )//,
        // run Chrome on Linux on Sauce
        //array(
            //'browserName' => 'chrome',
            //'desiredCapabilities' => array(
                //'platform' => 'Linux'
          //)
        //),
        // run Chrome locally
        //array(
            //'browserName' => 'chrome',
            //'local' => true,
            //'sessionStrategy' => 'shared'
        //)
    );

    public function setUp()
    {
        parent::setUp();
        $this->setBrowserUrl('http://saucelabs.com/test/guinea-pig');
    }

    public function testTitle()
    {
        $this->url('http://saucelabs.com/test/guinea-pig');
        $this->assertContains("I am a page title", $this->title());
    }

    public function testLink()
    {
        $this->url('http://saucelabs.com/test/guinea-pig');
        $link = $this->byId('i am a link');
        $link->click();
        $this->assertContains("I am another page title", $this->title());
    }

    public function testTextbox()
    {
        $this->url('http://saucelabs.com/test/guinea-pig');
        $test_text = "This is some text";
        $textbox = $this->byId('i_am_a_textbox');
        $textbox->click();
        $this->keys($test_text);
        $this->assertEquals($textbox->value(), $test_text);
    }

    public function testSubmitComments()
    {
        $this->url('http://saucelabs.com/test/guinea-pig');
        $comment = "This is a very insightful comment.";
        $this->byId('comments')->value($comment);
        $this->byId('submit')->submit();
        $driver = $this;

        $comment_test = function() use ($comment, $driver) {
            $text = $driver->byId('your_comments')->text();
            return $text == "Your comments: $comment";
        };

        $this->spinAssert("Comment never showed up!", $comment_test);

    }

}
