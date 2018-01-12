<?php
namespace tonisormisson\browsercomposer;

header("Content-type: text/html");
header('X-Accel-Buffering: no');

//
// Run composer with a PHP script in browser
//
// http://stackoverflow.com/questions/17219436/run-composer-with-a-php-script-in-browser
error_reporting(E_ALL);
ini_set('display_errors', 1);
// 600 seconds = 10 minutes
set_time_limit(600);
ini_set('max_execution_time', 600);
// https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors
ini_set('memory_limit', '-1');



// needs COMPOSER_HOME environment variable set
putenv('COMPOSER_HOME=' . __DIR__ . '/vendor/bin/composer');
// Improve performance when the xdebug extension is enabled
putenv('COMPOSER_DISABLE_XDEBUG_WARN=1');

require_once __DIR__ . DIRECTORY_SEPARATOR . "BrowserComposer.php";
$composer = new BrowserComposer();
$composer->run();
