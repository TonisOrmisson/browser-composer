<?php

namespace tonisormisson\browsercomposer;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class BrowserComposer
{
    /** @var HtmlOutput */
    public $output;
    public function __construct()
    {
        $this->installComposer();
        $this->output = new HtmlOutput();
        $this->output->writeln('Run: composer update');
    }

    public function run(){

        try {
            $params = array(
                'command' => 'install',
                //'--no-dev' => true,
                '--optimize-autoloader' => true,
                //'--no-suggest' => true,
                '--no-interaction' => true,
                //'--no-progress' => true
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
        $composerPhar = __DIR__ . '/composer.phar';
        if (!file_exists($composerPhar)) {
            $data = file_get_contents('https://getcomposer.org/composer.phar');
            file_put_contents($composerPhar, $data);
            unset($data);
        }
        require_once __DIR__.DIRECTORY_SEPARATOR."autoloader.php";

    }

}