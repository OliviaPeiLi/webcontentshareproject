About
=====

This directory contains the platform hosts for the test runner.

Each platform host is Node.js module implementing the same host interface.
The host is responsible for doing preparation so WebDriver can work for that platform.


Platforms
=========

### GhostDriver.js ###
Local testing via PhantomJS.

This host will start one or more PhantomJS instances in WebDriver mode.
The phantom instances are killed once the testing is complete.
By default this host will run as many tests in parallel as there are CPUs on the machine.


### SauceLabs.js ###
Remote testing via SauceLabs.

This host will initilize Sauce connect and shut it down when the tests are complete.
By default it will run as many tests in parallel as the SauceLabs account supports.