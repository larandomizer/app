<?php

namespace App\Http\Apis;

use App\Server\Traits\WebsocketQueue;

class Topic extends Api
{
    use WebsocketQueue;

    public function store()
    {
        $this->queue(new RegisterTopic(request()->only('name')));
    }

    public function destroy($uuid)
    {
        $this->queue(new UnregisterTopic(request()->only('uuid')));
    }
}
