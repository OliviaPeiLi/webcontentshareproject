var wd = require('wd')
  , _ = require('underscore')
  , fs = require('fs')
  , path = require('path')
  , uuid = require('uuid-js');
var VARS = {};

var b = wd.promiseRemote();

b.on('status', function(info){console.log('[36m%s[0m', info);});b.on('command', function(meth, path, data){  console.log(' > [33m%s[0m: %s', meth, path, data || '');});
b.init({
  browserName:'firefox'
})
.then(function () { return b.get("http://saucelabs.com/test/guinea-pig2.html"); })
.then(function () { return b.elementById("i am a link"); })
.then(function (el) { return b.clickElement(el); })
.then(function () { return b.elementByTagName('html'); })
.then(function (el) { return el.text(); })
.then(function (text) {
  var bool = text.indexOf("I am not just another div") != -1;
if (!bool) {
  b.quit(null);
  console.log('verifyTextPresent failed');
}
})
.fin(function () {
b.quit();
}).done();
