<?php

namespace App\Server;

use App\Server\Contracts\Listener as ListenerInterface;
use App\Server\Contracts\Server as ServerInterface;
use App\Server\Traits\DynamicProperties;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Support\Facades\Queue as QueueManager;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Output\OutputInterface;

class Server implements ServerInterface
{
    use DynamicProperties;

    protected $address = '0.0.0.0';
    protected $connector;
    protected $http;
    protected $listener;
    protected $max_connections;
    protected $output;
    protected $port = 8080;
    protected $queue = 'default';
    protected $server;
    protected $websocket;

    /**
     * Make a new instance of the server.
     *
     * @return self
     */
    public static function make()
    {
        $server = app(static::class)
            ->listener(new Listener(new Connections()));

        return $server->websocket(new WsServer($server->listener()))
            ->http(new HttpServer($server->websocket()))
            ->server(IoServer::factory($server->http(), $server->port(), $server->address()));
    }

    /**
     * Set the bindings for the server.
     *
     * @example bind('0.0.0.0')
     *          bind('0.0.0.0', 8080)
     *          bind(['0.0.0.0', 8080])
     *
     * @param string|array $address to bind to
     * @param int          $port    to listen on
     *
     * @return self
     */
    public function bind($address, $port = null)
    {
        if (is_array($address)) {
            list($address, $port) = $address;
        }

        $this->address($address);
        $this->port($port);

        return $this;
    }

    /**
     * Start the server by running the event loop.
     */
    public function start()
    {
        $this->listener()->start();
        $this->server()->run();
    }

    /**
     * Stop the server by stopping the event loop.
     */
    public function stop()
    {
        $this->listener()->stop();
        $this->server()->loop->stop();
    }

    /**
     * Get or set the password the server accepts for admin commands.
     *
     * @example password() ==> 'opensesame'
     *          password('opensesame') ==> self
     *
     * @param string $password
     *
     * @return string|self
     */
    public function password($password = null)
    {
        return $this->dynamic('password', $password);
    }

    /**
     * Get or set the address the server binds to.
     *
     * @example address() ==> '0.0.0.0'
     *          address('0.0.0.0') ==> self
     *
     * @param string $ip4 address
     *
     * @return string|self
     */
    public function address($ip4 = null)
    {
        return $this->dynamic('address', $ip4);
    }

    /**
     * Get or set the port the server listens on.
     *
     * @example port() ==> 8080
     *          port(8080) ==> self
     *
     * @param int $number for port
     *
     * @return string|self
     */
    public function port($number = null)
    {
        return $this->dynamic('port', $number);
    }

    /**
     * Get or set the bindings for the server.
     *
     * @example bindings() ==> ['0.0.0.0', 8080]
     *          bindings('0.0.0.0') ==> self
     *          bindings('0.0.0.0', 8080) ==> self
     *          bindings(['0.0.0.0', 8080]) ==> self
     *
     * @param string|array $address to bind to
     * @param int          $port    to listen on
     *
     * @return array|self
     */
    public function bindings($address = null, $port = null)
    {
        if ( ! is_null($address)) {
            $this->bind($address, $port);
        }

        if (empty(func_get_args())) {
            return [$this->address, $this->port];
        }

        return $this;
    }

    /**
     * Get or set the queue the server processes.
     *
     * @example queue() ==> 'server'
     *          queue('server') ==> self
     *
     * @param string $name of queue
     *
     * @return string|self
     */
    public function queue($name = null)
    {
        return $this->dynamic('queue', $name);
    }

    /**
     * Set the queue the server processes.
     *
     * @example useQueue() is equivalent to useQueue('default', 'default')
     *          useQueue($connection) to inject an existing connector
     *          useQueue('beanstalkd') to use beanstalkd driver on default queue
     *          useQueue('beanstalkd', 'server') to use beanstalkd driver on server queue
     *
     * @param string|\Illuminate\Contracts\Queue\Queue $connection
     * @param string                                   $name
     *
     * @return self
     */
    public function useQueue($connection = null, $name = null)
    {
        if ( ! $connection instanceof Queue) {
            $connection = QueueManager::connection($connection);
        }

        $this->connector($connection);
        $this->queue($name);

        // @todo the connection and queue need to be passed to the listener
        // @todo the loop needs to have a queue worker added to it

        return $this;
    }

    /**
     * Get or set the maximum number of connections the server allows to connect.
     *
     * @example maxConnections() ==> 100
     *          maxConnections(100) ==> self
     *
     * @param int $number of maximium connections allowed to connect
     *
     * @return int|self
     */
    public function maxConnections($number = null)
    {
        if (is_null($number)) {
            return $this->dynamic('max_connections', $number);
        }

        $this->dynamic('max_connections', $number);
        // @todo max connections needs to be passed to listener

        return $this;
    }

    /**
     * Get or set the queue connector the server uses.
     *
     * @example connector() ==> \Illuminate\Contracts\Queue\Queue
     *          connector($connector) ==> self
     *
     * @param \Illuminate\Contracts\Queue\Queue $instance
     *
     * @return \Illuminate\Contracts\Queue\Queue|self
     */
    public function connector(Queue $instance = null)
    {
        return $this->dynamic('connector', $instance);
    }

    /**
     * Get or set the event listener the server uses.
     *
     * @example listener() ==> \App\Server\Contracts\Listener
     *          listener($listener) ==> self
     *
     * @param \App\Server\Contracts\Listener $instance
     *
     * @return \App\Server\Contracts\Listener|self
     */
    public function listener(ListenerInterface $instance = null)
    {
        return $this->dynamic('listener', $instance);
    }

    /**
     * Get or set the WebSocket instance the server uses.
     *
     * @example websocket() ==> \Ratchet\WebSocket\WsServer
     *          websocket($websocket) ==> self
     *
     * @param \Ratchet\WebSocket\WsServer $instance
     *
     * @return \Ratchet\WebSocket\WsServer|self
     */
    public function websocket(WsServer $instance = null)
    {
        return $this->dynamic('websocket', $instance);
    }

    /**
     * Get or set the HTTP instance the server uses.
     *
     * @example http() ==> \Ratchet\Http\HttpServer
     *          http($http) ==> self
     *
     * @param \Ratchet\Http\HttpServer $instance
     *
     * @return \Ratchet\Http\HttpServer|self
     */
    public function http(HttpServer $instance = null)
    {
        return $this->dynamic('http', $instance);
    }

    /**
     * Get or set the I/O instance the server uses.
     *
     * @example server() ==> \Ratchet\Server\IoServer
     *          server($server) ==> self
     *
     * @param \Ratchet\Server\IoServer $instance
     *
     * @return \Ratchet\Server\IoServer|self
     */
    public function server(IoServer $instance = null)
    {
        if ( ! is_null($instance)) {
            $this->listener()->loop($instance->loop);
        }

        return $this->dynamic('server', $instance);
    }

    /**
     * Get or set the output interface the server pipes output to.
     *
     * @example output() ==> \Symfony\Component\Console\Output\OutputInterface
     *          output($output) ==> self
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $interface
     *
     * @return \Symfony\Component\Console\Output\OutputInterface|self
     */
    public function output(OutputInterface $interface = null)
    {
        if ( ! is_null($interface)) {
            $this->listener()->output($interface);
        }

        return $this->dynamic('output', $interface);
    }
}
