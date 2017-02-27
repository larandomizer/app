<?php

namespace App\Server;

use Illuminate\Support\Collection;

class Notifications extends Collection
{
    /**
     * Get the first notification that matches the UUID.
     *
     * @param string $uuid
     *
     * @return \App\Server\Contracts\Notification|null
     */
    public function uuid($uuid)
    {
        return $this->first(function ($notification) use ($uuid) {
            return $notification->uuid() === $uuid;
        });
    }
}
