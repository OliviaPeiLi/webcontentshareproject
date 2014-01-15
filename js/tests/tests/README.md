Files
=====

### tests.json ###
Default index of all thests. Each test_*() function from the former qunit.php should be put here.
The file has the following format:
```
[
	{
		"path": undefined|"server relative path for the page to be tested",
		"args": undefined|object,
		"login": undefined|false|object,
		"selenium": undefined|"filename"
	}
]
```
*"path"* - URL path to be loaded, without the host, so the host can be configured locally.
If this is not provided then "selenium" should be provided and the test is expected to load its URL.

*"login"* - used with "path"; if false no login parameters will be passed to the test script,
if undefined the default test user and pass will be appended as arguments.
Could be object with credentials.
```{ "user": "string", "pass": "string" }```
Will be appended to the query string as "qu_user" and "qu_pass".

*"args"* - used with "path"; optional list of query arguments that the script may need.
Arguments will be appended to query string.

Argument "qunit_tests=true" will always be appended to the query string of tests with "path".

*"selenium"* - a file relative to this directory with node WD script exported by Selenium Builder.
If specified the tests of this file will be performed, possibly in the context of "path".

### selenium_[header|footer].js ###
When Selenium Builder exports a test script in Node WD format we want only
the actual tests steps from it. We don't want the initialization part because we
are doing it ourselves. These files contain the part that we don't need, which is constant,
so we know to strip it. Needs to be kept in sync if Selenium Builder changes it export format.

### example.js ###
Generated from Selenium Builder source ../tmp/example.json.  
Searches google for fandrop and clicks the first link.

### example2.js ###
Generated from Selenium Builder source ../tmp/example2.json.  
Loads http://saucelabs.com/test/guinea-pig and clicks the link there.

### example3.js ###
Generated from Selenium Builder source ../tmp/example3.json.  
Loads http://saucelabs.com/test/guinea-pig2.html and tries to click a link,
but since this page gives 404 the test fails (on purpose).

### *.php ###
If a test needs some PHP logic, for example creating a database entry, clean up, etc.
this logic should be placed in a file with the named of the module with .php extension.
The file will be included by the site when ran in quint mode.


Tova e account koito taman napravih:
sauce labs account
user:alexi_ned 
pass:oeXUjadWdlNirUu