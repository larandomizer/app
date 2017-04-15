<?php

namespace App\Http\Apis;

use App\Giveaway\Commands\AddPrize;
use App\Giveaway\Commands\PickRandomWinner;
use App\Giveaway\Commands\ResetPrizes;
use ArtisanSDK\Server\Traits\WebsocketQueue;

/**
 * An example API for managing prizes.
 */
class Prize extends Api
{
    use WebsocketQueue;

    /**
     * Add a prize to the prize pool.
     *
     * @example POST /api/prize?name=<string>&sponsor=<string>
     */
    public function add()
    {
        $this->queue(new AddPrize(request()->only('name', 'sponsor')));
    }

    /**
     * Reset all the prizes in the prize pool.
     *
     * @example DELETE /api/prize
     */
    public function reset()
    {
        $this->queue(new ResetPrizes());
    }

    /**
     * Pick a random winner to giveaway the prize.
     *
     * @example GET /api/prize
     */
    public function giveaway()
    {
        $this->queue(new PickRandomWinner());
    }
}
