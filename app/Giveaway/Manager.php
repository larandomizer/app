<?php

namespace App\Giveaway;

use App\Giveaway\Entities\Prizes;
use App\Giveaway\Messages\UpdatePrizes;
use App\Server\Contracts\Connection;
use App\Server\Manager as BaseManager;
use App\Server\Timers\AutoRestartServer;
use App\Server\Timers\CurrentUptime;
use Carbon\Carbon;

class Manager extends BaseManager
{
    protected $prizes;

    /**
     * Setup the initial state of the manager when starting.
     *
     * @return self
     */
    public function boot()
    {
        parent::boot();

        // Initialize collections
        $this->prizes(new Prizes());

        // Register all the timers
        $this->add(new CurrentUptime(Carbon::now()));
        $this->add(new AutoRestartServer());

        // Register all the listeners
        $this->listener(new Listener());

        return $this;
    }

    /**
     * Called when a new connection is opened.
     *
     * @param \App\Server\Contracts\Connection $connection being opened
     *
     * @return self
     */
    public function open(Connection $connection)
    {
        parent::open($connection);

        return $this->send(new UpdatePrizes($this->prizes()), $connection);
    }

    /**
     * Get or set the prizes available on the server.
     *
     * @example prizes() ==> \App\Server\Entities\Prizes
     *          prizes($prizes) ==> self
     *
     * @param \App\Server\Entities\Prizes $prizes
     *
     * @return \App\Server\Entities\Prizes|self
     */
    public function prizes(Prizes $prizes = null)
    {
        return $this->property(__FUNCTION__, $prizes);
    }
}
