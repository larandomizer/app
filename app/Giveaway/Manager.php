<?php

namespace App\Giveaway;

use App\Giveaway\Entities\Prizes;
use App\Giveaway\Messages\UpdatePrizes;
use ArtisanSDK\Server\Contracts\Connection;
use ArtisanSDK\Server\Manager as BaseManager;
use ArtisanSDK\Server\Promises\AsyncExample;
use ArtisanSDK\Server\Timers\AutoRestartServer;
use ArtisanSDK\Server\Timers\CurrentUptime;
use Carbon\Carbon;

class Manager extends BaseManager
{
    /**
     * Collection of prizes.
     *
     * @var \App\Giveaway\Entities\Prizes
     */
    protected $prizes;

    /**
     * Setup the initial state of the manager when starting.
     *
     * @return self
     */
    public function boot()
    {
        // Make sure default bootloader runs
        parent::boot();

        // Initialize collections
        $this->prizes(new Prizes());

        // Register all the timers
        $this->add(new CurrentUptime(Carbon::now()));
        $this->add(new AutoRestartServer());

        // Register all the listeners
        $this->listener(new Listener());

        // Example Async Promise
        // $promise = AsyncExample::make()
        //     ->then(AsyncExample::class)
        //     ->then(AsyncExample::class);
        // $this->promise($promise, 'Example');

        return $this;
    }

    /**
     * Called when a new connection is opened.
     *
     * @param \ArtisanSDK\Server\Contracts\Connection $connection being opened
     *
     * @return self
     */
    public function open(Connection $connection)
    {
        // Setup the connection using the default methods
        parent::open($connection);

        // Send a list of prizes to new connection
        $this->send(new UpdatePrizes($this->prizes()), $connection);

        return $this;
    }

    /**
     * Get or set the prizes available on the server.
     *
     * @example prizes() ==> \App\Giveaway\Entities\Prizes
     *          prizes($prizes) ==> self
     *
     * @param \App\Giveaway\Entities\Prizes $prizes
     *
     * @return \App\Giveaway\Entities\Prizes|self
     */
    public function prizes(Prizes $prizes = null)
    {
        return $this->property(__FUNCTION__, $prizes);
    }
}
