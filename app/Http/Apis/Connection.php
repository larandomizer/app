<?php

namespace App\Http\Apis;

use App\Server\Commands\CloseConnections;
use App\Server\Commands\DismissNotifications;
use App\Server\Commands\NotifyConnection;
use App\Server\Traits\WebsocketQueue;

class Connection extends Api
{
    use WebsocketQueue;

    public function notify($uuid)
    {
        $this->queue(new NotifyConnection(compact('uuid')));
    }

    public function dismissNotifications($uuid)
    {
        $this->queue(new DismissNotifications(compact('uuid')));
    }

    public function disconnect($uuid)
    {
        $this->queue(new CloseConnections(compact('uuid')));
    }

    public function disconnectAll()
    {
        $this->queue(new CloseConnections());
    }

    public function disconnectType($type)
    {
        $this->queue(new CloseConnections(compact('type')));
    }
}
