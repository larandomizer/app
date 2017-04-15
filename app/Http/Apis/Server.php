<?php

namespace App\Http\Apis;

use ArtisanSDK\Server\Commands\StopServer;
use ArtisanSDK\Server\Traits\WebsocketQueue;

/**
 * An example API for interacting with the realtime server.
 */
class Server extends Api
{
    use WebsocketQueue;

    /**
     * Restart the realtime server by stopping it and allowing
     * Supervisor to start it back up again.
     *
     * @example DELETE /api/server
     */
    public function restart()
    {
        $this->queue(new StopServer());
    }
}
