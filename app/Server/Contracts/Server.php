<?php

namespace App\Server\Contracts;

use Illuminate\Contracts\Queue\Queue;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\Console\Output\OutputInterface;

interface Server
{
    /**
     * Make a new instance of the server.
     *
     * @return self
     */
    public static function make();

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
    public function bind($address, $port = null);

    /**
     * Start the server by running the event loop.
     */
    public function start();

    /**
     * Stop the server by stopping the event loop.
     */
    public function stop();

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
    public function password($password = null);

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
    public function address($ip4 = null);

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
    public function port($number = null);

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
    public function bindings($address = null, $port = null);

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
    public function connector(Queue $instance = null);

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
    public function queue($name = null);

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
    public function useQueue($connection = null, $name = null);

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
    public function maxConnections($number = null);

    /**
     * Get or set the output interface the server pipes output to.
     *
     * @example logger() ==> \Symfony\Component\Console\Output\OutputInterface
     *          logger($instance) ==> self
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $interface
     *
     * @return \Symfony\Component\Console\Output\OutputInterface|self
     */
    public function logger(OutputInterface $instance = null);

    /**
     * Get or set the message broker the server uses.
     *
     * @example broker() ==> \App\Server\Contracts\Broker
     *          broker($instance) ==> self
     *
     * @param \App\Server\Contracts\Broker $instance
     *
     * @return \App\Server\Contracts\Broker|self
     */
    public function broker(Broker $instance = null);

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
    public function websocket(WsServer $instance = null);

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
    public function http(HttpServer $instance = null);

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
    public function socket(IoServer $instance = null);
}
