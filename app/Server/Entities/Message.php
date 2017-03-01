<?php

namespace App\Server\Entities;

use App\Server\Contracts\Manager;
use App\Server\Contracts\Message as MessageInterface;
use App\Server\Traits\FluentProperties;
use Illuminate\Support\Fluent;

abstract class Message extends Fluent implements MessageInterface
{
    use FluentProperties;

    protected $dispatcher;

    /**
     * Get or set the message dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null)
    {
        return $this->property(__METHOD__, $instance);
    }

    /**
     * Handle the message.
     *
     * @return mixed
     */
    public function handle()
    {
    }
}
