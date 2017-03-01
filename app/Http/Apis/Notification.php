<?php

namespace App\Http\Apis;

use App\Server\Commands\NotifyConnection;
use App\Server\Traits\WebsocketQueue;

class Notification extends Api
{
    use WebsocketQueue;

    public function send()
    {
        $this->queue(new NotifyConnection(request()->only('receiver', 'sender')));
    }
}
