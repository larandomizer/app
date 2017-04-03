<?php

namespace App\Http\Apis;

use App\Giveaway\Commands\AddPrize;
use App\Giveaway\Commands\PickRandomWinner;
use App\Giveaway\Commands\ResetPrizes;
use App\Server\Traits\WebsocketQueue;

class Prize extends Api
{
    use WebsocketQueue;

    public function add()
    {
        $this->queue(new AddPrize(request()->only('name', 'sponsor')));
    }

    public function reset()
    {
        $this->queue(new ResetPrizes());
    }

    public function giveaway()
    {
        $this->queue(new PickRandomWinner());
    }
}
