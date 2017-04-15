<?php

namespace App\Console;

use App\Giveaway\Manager;
use ArtisanSDK\Server\Console\ServerStart as BaseCommand;
use ArtisanSDK\Server\Server;
use Illuminate\Support\Facades\Queue;

class ServerStart extends BaseCommand
{
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Server::make()
            ->bind($this->option('address'), $this->option('port'))
            ->uses(new Manager())
            ->uses($this->getOutput())
            ->uses(Queue::connection($this->option('connector')), $this->option('queue'))
            ->password($this->option('key'))
            ->maxConnections($this->option('max', 0))
            ->start();
    }
}
