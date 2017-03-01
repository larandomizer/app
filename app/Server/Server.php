<?php

namespace App\Server;

use App\Server\Contracts\Broker as BrokerInterface;
use App\Server\Contracts\Server as ServerInterface;
use App\Server\Entities\Connections;
use App\Server\Traits\FluentProperties;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Support\Facades\Queue as QueueManager;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Output\OutputInterface;

class Server implements ServerInterface
{
    use FluentProperties;

    protected $address = '0.0.0.0';
    protected $broker;
    protected $connector;
    protected $http;
    protected $output;
    protected $port = 8080;
    protected $queue = 'default';
    protected $socket;
    protected $websocket;

    /**
     * Make a new instance of the server.
     *
     * @return self
     */
    public static function make()
    {
        $server = app(static::class)
            ->broker(new Broker(new Manager(new Connections())));

        return $server->websocket(new WsServer($server->broker()))
            ->http(new HttpServer($server->websocket()))
            ->socket(IoServer::factory($server->http(), $server->port(), $server->address()));
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
        $this->broker()->manager()->start();
    }

    /**
     * Stop the server by stopping the event loop.
     */
    public function stop()
    {
        $this->broker()->manager()->stop();
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
        $this->broker()->manager()->password($password);

        return $this;
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
        return $this->property(__METHOD__, $ip4);
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
        return $this->property(__METHOD__, $number);
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
     * Get or set the queue connector the server uses.
     *
     * @example connector() ==> \Illuminate\Contracts\Queue\Queue
     *          connector($instance) ==> self
     *
     * @param \Illuminate\Contracts\Queue\Queue $instance
     *
     * @return \Illuminate\Contracts\Queue\Queue|self
     */
    public function connector(Queue $instance = null)
    {
        $this->broker()->manager()->connector($instance);

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
        $this->broker()->manager()->queue($name);

        return $this;
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
        $this->broker()->maxConnections($number);

        return $this;
    }

    /**
     * Get or set the logger interface the server pipes output to.
     *
     * @example logger() ==> \Symfony\Component\Console\Output\OutputInterface
     *          logger($interface) ==> self
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $interface
     *
     * @return \Symfony\Component\Console\Output\OutputInterface|self
     */
    public function logger(OutputInterface $interface = null)
    {
        $this->broker()->logger($interface);

        return $this;
    }

    /**
     * Get or set the event broker the server uses.
     *
     * @example broker() ==> \App\Server\Contracts\Broker
     *          broker($instance) ==> self
     *
     * @param \App\Server\Contracts\Broker $instance
     *
     * @return \App\Server\Contracts\Broker|self
     */
    public function broker(BrokerInterface $instance = null)
    {
        if ( ! is_null($instance)) {
            $instance->manager()->broker($instance);
        }

        return $this->property(__METHOD__, $instance);
    }

    /**
     * Get or set the WebSocket instance the server uses.
     *
     * @example websocket() ==> \Ratchet\WebSocket\WsServer
     *          websocket($instance) ==> self
     *
     * @param \Ratchet\WebSocket\WsServer $instance
     *
     * @return \Ratchet\WebSocket\WsServer|self
     */
    public function websocket(WsServer $instance = null)
    {
        return $this->property(__METHOD__, $instance);
    }

    /**
     * Get or set the HTTP instance the server uses.
     *
     * @example http() ==> \Ratchet\Http\HttpServer
     *          http($instance) ==> self
     *
     * @param \Ratchet\Http\HttpServer $instance
     *
     * @return \Ratchet\Http\HttpServer|self
     */
    public function http(HttpServer $instance = null)
    {
        return $this->property(__METHOD__, $instance);
    }

    /**
     * Get or set the I/O instance the server uses.
     *
     * @example socket() ==> \Ratchet\Server\IoServer
     *          socket($instance) ==> self
     *
     * @param \Ratchet\Server\IoServer $instance
     *
     * @return \Ratchet\Server\IoServer|self
     */
    public function socket(IoServer $instance = null)
    {
        if ( ! is_null($instance)) {
            $this->broker()->manager()->loop($instance->loop);
        }

        return $this->property(__METHOD__, $instance);
    }
}
