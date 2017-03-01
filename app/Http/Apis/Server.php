<?php

namespace App\Http\Apis;

use App\Server\Commands\StopServer;
use App\Server\Traits\WebsocketQueue;

class Server extends Api
{
    use WebsocketQueue;

    public function restart()
    {
        $this->queue(new StopServer());
    }
}
