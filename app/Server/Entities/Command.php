<?php

namespace App\Server\Entities;

use App\Server\Contracts\Command as CommandInterface;
use App\Server\Contracts\Manager;
use App\Server\Traits\FluentProperties;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Fluent;

abstract class Command extends Fluent implements CommandInterface, ShouldQueue
{
    use FluentProperties, Queueable;

    protected $dispatcher;

    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->delayed(0);
    }

    /**
     * Get or set the command dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Get or set the delay in milliseconds for the command to be executed.
     *
     * @param int $delay in ms
     *
     * @return int|self
     */
    public function delayed($delay = null)
    {
        return $this->property('delay', $delay);
    }
}
