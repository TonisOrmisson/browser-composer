<?php

namespace tonisormisson\browsercomposer;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class BrowserComposer
{
    /** @var HtmlOutput */
    public $output;

    /** @var string */
    public $composerPath;

    /** @var bool $installCoposer whether composer needs to be installed or not */
    public $installCoposer = false;

    public function __construct()
    {
        $this->composerPath = __DIR__.'/../';
        $this->installComposer();
        $this->output = new HtmlOutput();
    }

    public function run(){
        $this->output->writeln('$ composer install');

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
            $application->run($input, $this->output);
        } catch (\Exception $ex) {
            $this->output->writeln($ex->getMessage());
        }

        $this->output->writeln("Done.");

    }

    private function installComposer(){
        $composerPhar = $this->composerPath . '/composer.phar';
        if (!file_exists($composerPhar)) {
            $data = file_get_contents('https://getcomposer.org/composer.phar');
            echo  $composerPhar;
            file_put_contents($composerPhar, $data);
            unset($data);
        }
        require_once 'phar://' . str_replace('\\', '/', $this->composerPath) . '/composer.phar/src/bootstrap.php';

        require_once __DIR__.DIRECTORY_SEPARATOR."autoloader.php";

    }

}