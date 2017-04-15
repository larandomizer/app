<?php

namespace App\Giveaway;

use ArtisanSDK\Server\Entities\Listener as BaseListener;

class Listener extends BaseListener
{
    /**
     * Initialize any registered message handlers upon construction.
     *
     * @return self
     */
    public function boot()
    {
        $this->register(Messages\AddPrize::class, Commands\AddPrize::class);
        $this->register(Messages\PickRandomWinner::class, Commands\PickRandomWinner::class);
        $this->register(Messages\ResetPrizes::class, Commands\ResetPrizes::class);

        return $this;
    }
}
