<?php

namespace App\Http\Apis;

use App\Server\Commands\DismissNotification;
use App\Server\Commands\NotifyConnection;
use App\Server\Traits\WebsocketQueue;

class Notification extends Api
{
    use WebsocketQueue;

    public function dismiss($uuid)
    {
        $this->queue(new DismissNotification(compact('uuid')));
    }

    public function send()
    {
        $this->queue(new NotifyConnection(request()->only('uuid')));
    }
}
