<?php

namespace App\Http\Apis;

use ArtisanSDK\Server\Traits\WebsocketQueue;

/**
 * An example API for managing PUB/SUB topics on the realtime server.
 */
class Topic extends Api
{
    use WebsocketQueue;

    /**
     * Create a new topic.
     *
     * @example POST /api/topic
     */
    public function store()
    {
        $this->queue(new RegisterTopic(request()->only('name')));
    }

    /**
     * Delete an existing topic.
     *
     * @example DELETE /api/topic/{uuid}
     *
     * @param UUID $uuid of topic
     */
    public function destroy($uuid)
    {
        $this->queue(new UnregisterTopic($uuid));
    }
}
