@ECHO OFF
SET BIN_TARGET=%~dp0\"../phpunit/phpunit/composer/bin"\phpunit
echo "%BIN_TARGET%" %*;
php "%BIN_TARGET%" %*
