<?php

namespace App\Server\Traits;

trait AdminProtection
{
    use Client;

    /**
     * Authorize the client connection.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->client()->admin();
    }
}
