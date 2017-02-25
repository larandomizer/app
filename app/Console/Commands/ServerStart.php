<?php

namespace App\Console\Commands;

use App\Server\Server;
use Illuminate\Console\Command;

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
     *
     * @return void
     */
    public function __construct()
    {
        $this->makeSignature();

        parent::__construct();
    }

    /**
     * Make the signature.
     */
    protected function makeSignature() {
        
        $options = [];
        $options[] = '--A|address=' . env('SERVER_ADDRESS', '0.0.0.0') . ' : The address that the server will bind to for client connections';
        $options[] = '--P|port=' . env('SERVER_PORT', 8080) . ' : The port that the server will listen on for client connections';
        $options[] = '--M|max=' . env('SERVER_MAX_CONNECTIONS') . ' : The maximum number of connections the server will accept';
        $options[] = '--Q|queue=' . env('SERVER_QUEUE', 'default') . ' : The message queue that the server will be responsible for processing';
        $options[] = '--C|connection=' . env('SERVER_QUEUE_DRIVER', env('QUEUE_DRIVER', 'beanstalkd')) . ' : The queue connection that the server will use for message processing';
        
        $this->signature = $this->name . PHP_EOL . '{' . implode('}'.PHP_EOL.'{', $options) .'}';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Server::bind($this->option('address'), $this->option('port'))
            ->useQueue($this->option('connection'), $this->option('queue'))
            ->maxConnections($this->option('max'))
            ->output($this->getOutput())
            ->start();
    }
}
