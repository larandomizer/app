<?php

namespace App\Http\Apis;

use ArtisanSDK\Server\Commands\CloseConnections;
use ArtisanSDK\Server\Commands\DismissNotifications;
use ArtisanSDK\Server\Commands\NotifyConnection;
use ArtisanSDK\Server\Traits\WebsocketQueue;

/**
 * An example API for interacting with realtime connections.
 */
class Connection extends Api
{
    use WebsocketQueue;

    /**
     * Send a notification to the connection.
     *
     * @example POST /api/connection/{uuid}/notification?sender=<uuid>
     *
     * @param UUID $uuid of connection
     */
    public function notify($uuid)
    {
        $receiver = $uuid;
        $sender = request()->get('sender');

        $this->queue(new NotifyConnection(compact('sender', 'receiver')));
    }

    /**
     * Dismiss all notification for the connection.
     *
     * @example DELETE /api/connection/{uuid}/notification
     *
     * @param UUID $uuid of connection
     */
    public function dismissNotifications($uuid)
    {
        $this->queue(new DismissNotifications(compact('uuid')));
    }

    /**
     * Disconnect the connection.
     *
     * @example DELETE /api/connection/{uuid}
     *
     * @param UUID $uuid of connection
     */
    public function disconnect($uuid)
    {
        $this->queue(new CloseConnections(compact('uuid')));
    }

    /**
     * Disconnect all connections.
     *
     * @example DELETE /api/connection
     *
     * @param UUID $uuid of connection
     */
    public function disconnectAll()
    {
        $this->queue(new CloseConnections());
    }

    /**
     * Disconnect all connections of the type.
     *
     * @example DELETE /api/connection/{type}
     *
     * @param string $type of connection
     */
    public function disconnectType($type)
    {
        $this->queue(new CloseConnections(compact('type')));
    }
}
