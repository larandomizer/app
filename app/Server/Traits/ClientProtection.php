<?php

namespace App\Server\Traits;

use App\Server\Traits\Client;

trait ClientProtection
{
    use Client;

    /**
     * Authorize the client connection.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->client()->isAdmin()
            || $this->client()->uuid() === $this->uuid;
    }
}
