<?php
namespace tonisormisson\browsercomposer;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

header("Content-type: text/html");

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

// Download composer
$composerPhar = __DIR__ . '/composer.phar';
if (!file_exists($composerPhar)) {
    $data = file_get_contents('https://getcomposer.org/composer.phar');
    file_put_contents($composerPhar, $data);
    unset($data);
}

require_once __DIR__.DIRECTORY_SEPARATOR."autoloader.php";

//exec('rm -r vendor');
// change out of the webroot so that the vendors file is not created in
// a place that will be visible to the intahwebz
//chdir('../');
//
// Composer\Factory::getHomeDir() method
// needs COMPOSER_HOME environment variable set
putenv('COMPOSER_HOME=' . __DIR__ . '/vendor/bin/composer');
// Improve performance when the xdebug extension is enabled
putenv('COMPOSER_DISABLE_XDEBUG_WARN=1');
// call `composer install` command programmatically
$output = new HtmlOutput();

$output->writeln('Run: composer install');

try {
    $params = array(
        'command' => 'install',
        '--no-dev' => true,
        '--optimize-autoloader' => true,
        '--no-suggest' => true,
        '--no-interaction' => true,
        '--no-progress' => true
        //'--verbose' => true
    );
    $input = new ArrayInput($params);
    $application = new Application();
    $application->setAutoExit(false);
    $application->run($input, $output);
} catch (\Exception $ex) {
    $output->writeln($ex->getMessage());
}

$output->writeln("Done.");
