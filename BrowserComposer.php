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

    /** @var boolean */
    public $doInstall = false;

    public function __construct()
    {
        $this->composerPath = __DIR__.'/../';
        $this->output = new HtmlOutput();
    }

    public function run(){
        if($this->doInstall){
            $this->runComposer();
        }else{
            $this->output->writeln('$');
        }
    }

    private function runComposer(){
        $this->installComposer();

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
        $this->output->writeln('$ checking composer');
        $composerPhar = $this->composerPath . '/composer.phar';
        if (!file_exists($composerPhar)) {
            $this->output->writeln('$ installing composer');
            $data = file_get_contents('https://getcomposer.org/composer.phar');
            echo  $composerPhar;
            file_put_contents($composerPhar, $data);
            unset($data);
        } else {
            $this->output->writeln('$ composer exists');
        }
        require_once 'phar://' . str_replace('\\', '/', $this->composerPath) . '/composer.phar/src/bootstrap.php';

        require_once __DIR__.DIRECTORY_SEPARATOR."autoloader.php";

    }

}