<?php

namespace App\Http\Apis;

use App\Server\Commands\AddPrize;
use App\Server\Commands\PickRandomWinner;
use App\Server\Commands\ResetPrizes;
use App\Server\Traits\WebsocketQueue;

class Prize extends Api
{
    use WebsocketQueue;

    public function add()
    {
        $this->queue(new AddPrize(request()->all()));
    }

    public function reset()
    {
        $this->queue(new ResetPrizes(request()->all()));
    }

    public function giveaway()
    {
        $this->queue(new PickRandomWinner(request()->all()));
    }
}
