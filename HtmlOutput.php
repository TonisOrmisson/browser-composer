<?php
namespace tonisormisson\browsercomposer;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;

class HtmlOutput extends \Symfony\Component\Console\Output\Output
{

    public function __construct($verbosity = self::VERBOSITY_NORMAL, $decorated = false, OutputFormatterInterface $formatter = null)
    {
        parent::__construct($verbosity, $decorated, $formatter);

        // tell php to automatically flush after every output
        //$this->disableOb();
    }

    protected function disableOb()
    {
        // Turn off output buffering
        ini_set('output_buffering', 'off');
        // Turn off PHP output compression
        //ini_set('zlib.output_compression', false);
        // Implicitly flush the buffer(s)
        ini_set('implicit_flush', true);
        ob_implicit_flush(true);
        // Clear, and turn off output buffering
        while (ob_get_level() > 0) {
            // Get the curent level
            $level = ob_get_level();
            // End the buffering
            ob_end_clean();
            // If the current level has not changed, abort
            if (ob_get_level() == $level) {
                            break;
            }
        }
        // Disable apache output buffering/compression
        if (function_exists('apache_setenv')) {
            apache_setenv('no-gzip', '1');
            apache_setenv('dont-vary', '1');
        }
    }

    protected function h($text)
    {
        return htmlspecialchars($text);
    }

    public function writeln($messages, $options = 0)
    {
        $this->write($messages, true, $options);
    }

    public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL)
    {
        $this->doWrite($messages, $newline);
    }

    protected function doWrite($message, $newline)
    {

        //echo $this->h($message);
        echo $message;

        if ($newline) {
            echo "\n";
        }
        if (ob_get_length()) {
            ob_flush();
            flush();
        }
    }

}