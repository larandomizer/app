<?php

namespace App\Server\Traits;

use App\Server\Traits\Client;

trait NoProtection
{
    use Client;

    /**
     * Authorize the client connection.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
