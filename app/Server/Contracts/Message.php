<?php

namespace App\Server\Contracts;

use Illuminate\Contracts\Support\Jsonable;

interface Message extends Jsonable
{
    /**
     * Get or set the message dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null);
}
