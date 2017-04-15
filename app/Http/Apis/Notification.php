<?php

namespace App\Http\Apis;

use ArtisanSDK\Server\Commands\NotifyConnection;
use ArtisanSDK\Server\Traits\WebsocketQueue;

/**
 * An example API for sending notifications to realtime connections.
 */
class Notification extends Api
{
    use WebsocketQueue;

    /**
     * Send a notification to the connection.
     *
     * @example POST /api/notification?receiver=<string>&sender=<string>
     */
    public function send()
    {
        $this->queue(new NotifyConnection(request()->only('receiver', 'sender')));
    }
}
