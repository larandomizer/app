<?php

namespace App\Server\Traits;

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
