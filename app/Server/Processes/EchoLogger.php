<?php

namespace App\Server\Processes;

use App\Server\Entities\Process;

class EchoLogger extends Process
{
    /**
     * Boot any listeners for this process that must be registered after
     * the process is started and the input/output streams are setup.
     *
     * @return self
     */
    public function boot()
    {
        // Log when the process starts
        $this->log(__CLASS__.' started at: '.time());

        // Log all the data that the process outputs
        $this->output()->on('data', function ($chunk) {
            $this->log(__CLASS__.': '.rtrim($chunk, PHP_EOL));
        });

        // Log when the process exits
        $this->on('exit', function ($code, $signal) {
            $this->log(__CLASS__.' exited ('.$code.') at: '.time());

            // Restart the process
           $this->dispatcher()->execute(static::make($this->getCommand()));
        });

        return $this;
    }
}
