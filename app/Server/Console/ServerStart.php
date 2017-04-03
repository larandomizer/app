<?php

namespace App\Server\Console;

use App\Giveaway\Manager;
use App\Server\Server;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class ServerStart extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'server:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a server that allows for remote client connections.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->makeSignature();

        parent::__construct();
    }

    /**
     * Make the signature.
     */
    protected function makeSignature()
    {
        $options = [];
        $options[] = '--A|address='.config('server.address').' : The address that the server will bind to for client connections';
        $options[] = '--P|port='.config('server.port').' : The port that the server will listen on for client connections';
        $options[] = '--Q|queue='.config('server.queue').' : The message queue that the server will be responsible for processing';
        $options[] = '--C|connector='.config('server.connector').' : The queue connection that the server will use for message processing';
        $options[] = '--M|max='.config('server.max_connections').' : The maximum number of connections the server will accept';
        $options[] = '--K|key='.config('server.password').' : The secret key that the server will use to authenticate admin commands';

        $this->signature = $this->name.PHP_EOL.'{'.implode('}'.PHP_EOL.'{', $options).'}';
    }

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
