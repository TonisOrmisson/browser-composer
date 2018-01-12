<?php

namespace tonisormisson\browsercomposer;

use Composer\Console\Application;
use Composer\Util\Filesystem;
use Symfony\Component\Console\Input\ArrayInput;

class BrowserComposer
{
    /** @var HtmlOutput */
    public $output;

    /** @var string */
    public $composerPath;

    /** @var boolean */
    public $doInstall = false;

    /** @var boolean */
    public $dryRun = false;

    /** @var boolean */
    public $deleteCurrent = false;

    public function __construct()
    {
        $this->composerPath = __DIR__ . '/../';
        $this->installComposer();
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'HtmlOutput.php';
        $this->output = new HtmlOutput();
        putenv('COMPOSER_HOME=' . __DIR__ . '/vendor/bin/composer');
        // Improve performance when the xdebug extension is enabled
        //putenv('COMPOSER_DISABLE_XDEBUG_WARN=1');
    }

    public function run() {
        if ($this->deleteCurrent) {
            $this->deleteCurrentDependencies();
        }

        if ($this->doInstall) {
            $this->runComposer();
        }else {
            $this->output->writeln('$');
        }
    }

    private function deleteCurrentDependencies() {

        try {
            $this->output->writeln('$ rm -rf vendor/*');
            exec('rm -rf ../vendor/*');
        } catch (\Exception $ex) {
            $this->output->writeln($ex->getMessage());
        }
    }

    private function runComposer() {

        $this->output->writeln('$ composer install');

        try {
            $params = array(
                'command' => 'install',
                '--no-dev' => true,
                '--optimize-autoloader' => true,
                '--no-suggest' => true,
                '--no-interaction' => true,
                '--no-progress' => true,
                '--working-dir'=>$this->composerPath,
                '--dry-run'=>$this->dryRun,
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

    private function installComposer() {
        $composerPhar = $this->composerPath . '/composer.phar';
        if (!file_exists($composerPhar)) {
            $data = file_get_contents('https://getcomposer.org/composer.phar');
            echo  $composerPhar;
            file_put_contents($composerPhar, $data);
            unset($data);
        }
        require_once 'phar://' . str_replace('\\', '/', $this->composerPath) . '/composer.phar/src/bootstrap.php';


    }

}