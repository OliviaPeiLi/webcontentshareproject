Overview
========

The idea is to be able to run the tests with no modifications (or with minimal such)
both locally and on SauceLabs. Locally they will still run via PhantomJS but should not
make any PhantomJS call as PhantomJS will not execute the scripts directly as it does now
but via GhostDriver (that is in WebDriver mode). To run on SauceLabs we will utilize
Sauce Connect - this is a proxy for SauceLabs so their VMs can open our local server.

Additionally, after the current tests are ported, we should be able to migrate most tests
to Selenium and not write them manually. Selenuim can export them to JS or whatever.


<a name="5"></a>^5 Incompatibilities with the old tests
=======================================

The tests were calling PhantomJS directly to upload files. Since the WebDriver communication
is over HTTP REST, it is not possible for the tested site to call back to WebDriver. The
only possible solution is for WebDriver to poll if the site wants to change the value of a file input,
but this is too ugly so tests that need to upload files will be ported to Selenium.

Additionally PhantomJS doesn't have gui and it ignores some alert dialogs like the onbeforeunload confirm box,
but SauceLabs has gui and the alerts blocks JS and thus QUnit. Selenium can check the 
redirected URL so onbeforeunload is not needed for selenium tests.

### How to port the QUnit tests ###

- The difference now is that there is additional layer between PhantomJS (or possibly SauceLabs)
and the test code. Thus all PhantomJS specific code should be converted to standard JS (if possible).
- Additionally it should be considered that alerts like onbeforeunload can not be tested with QUnit alone.
- Tests are described in json files now. See tests/README.md for the format.
- qunit.php is not used anymore. If tests need special PHP logic it should be moved to another file in tests/[module].php.
- Some test need to be migrated to Selenium Builder and exported to Node WD and run this way.

Notes
=====
- Testing with SauceLabs on localhost loads Sauce Connect. Sauce Connect is Java thing that takes ages to start and shutdown,
  so be patient on startup and shutdown (takes around ~30s on my machine).
- Testing with SauceLabs on localhost requires Java to be installed, see also [-sauce-noconnect option](#4).

Roadmap
=======

1. Migrate the tests to local GhostDriver

	1. ~~PhantomJS should be started in WD mode, possibly several instances to run tests in parallel, one instance for each CPU~~
	2. ~~The tests in quinit.php should be made into a node script that can tell GhostDriver to load the URL of the tests (later in parallel too)~~ 
	3. ~~QUnit.js should be modified to notify the WD host when it is finished~~
	4. ~~When WD host is notified by QUinit that the test is over it should print the results or put them in the SauceLabs job (see [^1](#1))~~

2. ~~Add parallel tests support~~

3. ~~Add SauceLabs support~~

4. Port some of the old tests

  1. Fix or port or disable qunit test scripts which use window.callPhantom() and phantom.*(),
     also scripts with onbeforeunload and possibly with alerts.
     For file uploads to work the WD node module must be extended to upload files when
     calling sendKeys() on a file element, see [^2](#2) and [^3](#3) and [^5](#5).
  2. If some tests need special PHP logic (DB preparation/cleanup/etc) it must be moved from qunit.php to js/tests/tests/[module].php
  3. Would be useful if we store the console output with the test. This can be achieved by storing the console.*() calls
  and sending them to SauceLabs/shell the same way qunit results are sent ( see lib/platforms/*.js/reportResults )

5. **(current) When running scrits exported by Selenium Builder, besides removing header/footer we should remove b.quit() and console.log() and replace it with callback. This type of code is generated is when verifying something.**

6. ~~Revise this readme. Remove roadmaps and similar and place info how to use the test runner with examples.~~

7. *Polish, external bug?:* Loading unsupported browser combination in Sauce leaves the script waiting forever (no error),
   killing it with Ctrl+C leaves Connect running.

8. *Polish, external bug:* Loading tests/example.js on SauceLabs takes several minutes. Although the site loads properly SauceLabs doesn't think so.

9. Use, polish, repeat.

Current status
--------------
The test runner can run selenium tests on localhost or on SauceLabs, supports running tests in parallel and on different browsers.  
Some QUnit tests that doesn't need porting also work.

Files
=====

### test_runner.js ###
A Node.js script that does all the work. It loads the appropriate platform host and starts the required tests.
Node.js is chosen over PHP for this task because node is easier for this kind of stuff, node knows the number of CPUs,
can easily spawn processes and handle their output, JS is easier and here we are not building a class library so PHP has no benefit over JS,
more packages are available for node for this kind of scripting work, which means better community, node packages are easy to install unlike PHP.

Should be run from the same directory.

#### Quick start ####

First of all, to do local testing you need to create a file named **fandrop.host** and put the host there,
or you can pass the **-host** option manually.

Use one of the platform shortcuts (mac, windows, linux) for your platform with the desired options.
Here are some examples and for full list of available options see below.

Runs the tests described in tests/tests.json on localhost via (PhantomJS/GhostDriver), using all available CPU-s.
```
./run-mac.sh
```

Same as above but runs on one CPU (no parallel testing) and prints debug info.
```
./run-mac.sh -concurrency=1 -debug
```

Runs different set of tests.
```
./run-mac.sh -suite=tests/tests2.json
```

Runs the tests on SauceLabs, without recording a video.
```
./run-mac.sh -platform=SauceLabs -sauce-novideo
```

Runs the tests on SauceLabs, with different browsers. The available browsers are easy to find in the SauceLabs docs.
```
./run-mac.sh -platform=SauceLabs -browsers=tests/exampleBrowsers.json
```

Runs a test that doesn't need connection to localhost, i.e. public site, reads the options from config rather than command line line.
```
./run-mac.sh -config=exampleConfig.json
```

##### Usage #####
```
node test_runner.js [OPTIONS...]
```

Or via the platform specific shortcut:
```
./run-mac.sh [OPTIONS...]
./run-windows.bat [OPTIONS...]
./run-linux.sh [OPTIONS...]
```
*Only the mac version is tested, if some of the other versions doesn't run you need to edit the shell script that launches the node executable*

<a name="4"></a>
**OPTIONS:**  
```
-config=filename.json
  If provided will read command line options from a json file.
  Command line options will overwrite options in the config file.

-host=fandrophost
  The fandrop hostname, defaults to the contents of the file fandrop.host, if present, or http://localhost

-suite=filename.json
  Test suite to load, defaults to tests/tests.json.
  This argument may appear multiple times.

-browsers=filename.json
  List of browsers to test against. The file should contain array of objects with browser specification.

-platform=GhostDriver|SauceLabs
  Defaults to GhostDriver.

-concurrency=integer
  Preferred number of tests to run in parallel, default is platform specific.
  Default for GhostDriver is the same as the number of CPUs (or CPU cores) on the system.
  Default for SauceLabs depends on the subscription.

-debug
  Prints extra debug info.

-debugmore
  Prints excessive amount of debug info, needs -debug.

-sauce-noconnect
  Prevent the use of Sauce Connect when running on SauceLabs. This option
  will (probably) increase performance by not driving traffic through localhost.
  Sauce Connect is not needed if testing public sites and not transferring secret info.

-sauce-novideo
  Disables video recording.

-sauce-novideo-pass
  Disables saving video for successful tests.

-sauce-noshots
  Disables taking screenshots.

-sauce-capture-html
  Makes SauceLabs capture the HTML of each step similar to how it takes screenshots.
```

### run-mac.sh ###
Shortcut to run test_runner.js on Mac.  
Should be run from the same directory.  

### run-windows.bat ###
Shortcut to run test_runner.js on Windows.  
Should be run from the same directory.

### run-linux.sh ###
Shortcut to run test_runner.js on Linux.  
Should be run from the same directory.



Progress
========

**28-03-2013**  
  Added some examples how to use test runner.

**27-03-2013**  
  Improved parallel tests logic to use SauceLabs VMs more sparingly.  
  Now the test runner is able to run the tests on PhantomJS or on SauceLabs, both can run tests in parallel
  and SauceLabs can run in any browser.  
  Now able to run QUnit tests - https://saucelabs.com/tests/99340504faf142d0b92203d6e1dd74ce

**26-03-2013**  
  Added the ability to load scripts exported by Selenium Builder.  
  Added error display for errors in the JsonWireProtocol.  
  Handling the case where GhostDriver initialization may fail.  
  Handling command line arguments.  
  Progress with the SauceLabs implementation.

**25-03-2013**  
  Finished milestone 1.2, working on 1.3.  
  Able to run tests in parallel.

**24-03-2013**  
  Finished milestone 1.1, working on 1.2.

**23-03-2013**  
  Created unitests-webdriver branch and preparing WD environment for the tests,
  also putting together all the info in this file.

**21-03-2013**  
  Successfully ran a WD test with Node.js on SauceLabs - https://saucelabs.com/tests/976553d03a2a4d2a924cc253d71471db



Resources
=========

<a name="2"></a> ^2 How to make the WebDriver select a file for upload:  
  http://code.google.com/p/selenium/wiki/FrequentlyAskedQuestions#Q:_Does_WebDriver_support_file_uploads?

<a name="3"></a> ^3 How to upload files to remote WebDriver:  
  http://stackoverflow.com/questions/10559728/uploading-files-remotely-on-selenium-webdriver-via-php  
  http://code.google.com/p/selenium/source/browse/py/selenium/webdriver/remote/webelement.py  

Selenium working with multiple windows:  
  http://sauceio.com/index.php/2010/05/selenium-tips-working-with-multiple-windows/

Sauce Connect:  
  https://saucelabs.com/docs/connect

The Node.js package which SauceLabs recommends for WD (and also seems frequently updated):  
  https://github.com/admc/wd  
  https://github.com/admc/wd/issues/created_by/bobef?state=open  

Node.js package for working the SauceLabs account:  
  https://github.com/holidayextras/node-saucelabs

Docs for JsonWireProtocol, the protocol used to talk to remote web driver:  
  http://code.google.com/p/selenium/wiki/JsonWireProtocol

<a name="1"></a>~~^1 How to bridge QUnit to SauceLabs:~~  
  ~~https://saucelabs.com/docs/javascript-unit-tests-integration~~

~~Can I reuse a session within multiple tests?~~  
  ~~https://saucelabs.com/docs/faq~~

~~Node.js package for parallel WD:~~  
  ~~https://github.com/OniOni/parallel-wd~~  
  ~~https://github.com/vvo/selenium-runner~~  

Quick and dirty debugging of Selenium tests exported to Node WD:
```
.then( function () {
  return b.takeScreenshot( function ( err, img ) {
    if ( err ) {
      console.trace( err );
      return;
    }
    try {
      console.log( GLOBAL.require( 'fs' ).writeFileSync( './tmp/shot.png', new Buffer( img, 'base64' ) ) );
    }
    catch ( e ) {
      console.trace( e );
    } 
  } ); 
} )
.then( function () {
  return b.source( function ( err, src ) {
    if ( err ) {
      console.trace( err );
      return;
    }
    try {
      console.log( GLOBAL.require( 'fs' ).writeFileSync( './tmp/src.html', src ) );
    }
    catch ( e ) {
      console.trace( e );
    } 
  } ); 
} )
```
This saves screenshot and the source of the current page.


Of less interest
----------------
	
Uploading files to remove WD with Java:  
  http://sauceio.com/index.php/2012/03/selenium-tips-uploading-files-in-remote-webdriver/

Selenium distributed testing:  
  http://selenium-grid.seleniumhq.org/index.html

