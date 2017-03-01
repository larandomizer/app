<?php

namespace App\Server\Contracts;

use App\Server\Entities\Commands;
use App\Server\Entities\Connections;
use App\Server\Entities\Topics;
use Exception;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\Queue;
use React\EventLoop\LoopInterface as Loop;

interface Manager
{
    /**
     * Called when the server is started.
     *
     * @return self
     */
    public function start();

    /**
     * Called when the server is stopped.
     *
     * @return self
     */
    public function stop();

    /**
     * Called when a new connection is opened.
     *
     * @param \App\Server\Contracts\Connection $connection being opened
     *
     * @return self
     */
    public function open(Connection $connection);

    /**
     * Send message to one connection.
     *
     * @param \App\Server\Contracts\Message    $message    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     *
     * @return self
     */
    public function send(Message $message, Connection $connection);

    /**
     * Send message to one connection and then close the connection.
     *
     * @param \App\Server\Contracts\Message    $message    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     *
     * @return self
     */
    public function end(Message $message, Connection $connection);

    /**
     * Broadcast message to multiple connections.
     *
     * @param \App\Server\Contracts\Message    $message
     * @param \App\Server\Entities\Connections $connections to send to
     * @param bool                             $silent      output
     *
     * @return self
     */
    public function broadcast(Message $message, Connections $connections);

    /**
     * Called when a new message is received from an open connection.
     *
     * @param \App\Server\Contracts\Message    $message    payload received
     * @param \App\Server\Contracts\Connection $connection sending the message
     *
     * @return self
     */
    public function receive(Message $message, Connection $connection);

    /**
     * Called when an open connection is closed.
     *
     * @param \App\Server\Contracts\Connection $connection to be closed
     *
     * @return self
     */
    public function close(Connection $connection);

    /**
     * Called when an error occurs on the connection.
     *
     * @param \App\Server\Contracts\Connection $connection that errored
     * @param \Exception                       $exception  caught
     *
     * @return self
     */
    public function error(Connection $connection, Exception $exception);

    /**
     * Get or set the connections on the server.
     *
     * @example connections() ==> \App\Server\Entities\Connections
     *          connections($connections) ==> self
     *
     * @param \App\Server\Entities\Connections $connections
     *
     * @return \App\Server\Entities\Connections|self
     */
    public function connections(Connections $connections = null);

    /**
     * Get or set the topics available for subscribing.
     *
     * @example topics() ==> \App\Server\Entities\Topics
     *          topics($topics) ==> self
     *
     * @param \App\Server\Entities\Topics $topics
     *
     * @return \App\Server\Entities\Topics|self
     */
    public function topics(Topics $topics = null);

    /**
     * Register a new topic in the collection of topics.
     *
     * @param \App\Server\Contracts\Topic $topic to register
     *
     * @return self
     */
    public function register(Topic $topic);

    /**
     * Unregister an existing topic from the collection of topics.
     *
     * @param \App\Server\Contracts\Topic $topic to unregister
     *
     * @return self
     */
    public function unregister(Topic $topic);

    /**
     * Subscribe a connection to the topic.
     *
     * @param \App\Server\Contracts\Topic      $topic      to subscribe to
     * @param \App\Server\Contracts\Connection $connection to subscribe to topic
     *
     * @return self
     */
    public function subscribe(Topic $topic, Connection $connection);

    /**
     * Unsubscribe a connection from the topic.
     *
     * @param \App\Server\Contracts\Topic      $topic      to unsubscribe from
     * @param \App\Server\Contracts\Connection $connection to unsubscribe from topic
     *
     * @return self
     */
    public function unsubscribe(Topic $topic, Connection $connection);

    /**
     * Get or set the event loop the server runs on.
     *
     * @example loop() ==> \React\EventLoop\LoopInterface
     *          loop($instance) ==> self
     *
     * @param \React\EventLoop\LoopInterface $instance
     *
     * @return \React\EventLoop\LoopInterface|self
     */
    public function loop(Loop $instance = null);

    /**
     * Get or set the broker that communicates with the server.
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
     * Process a job that has been popped off the queue.
     *
     * @param \Illuminate\Contracts\Queue\Job $job to be processed
     *
     * @return self
     */
    public function work(Job $job);

    /**
     * Get or set the commands available to be ran.
     *
     * @example commands() ==> \App\Server\Entities\Commands
     *          commands($commands) ==> self
     *
     * @param \App\Server\Entities\Commands $commands
     *
     * @return \App\Server\Entities\Commands|self
     */
    public function commands(Commands $commands = null);

    /**
     * Run a command immediately within this tick of the event loop.
     *
     * @param \App\Server\Contracts\Command $command to run
     *
     * @return self
     */
    public function run(Command $command);

    /**
     * Run a command in the next tick of the event loop.
     *
     * @param \App\Server\Contracts\Command $command to run
     *
     * @return self
     */
    public function next(Command $command);

    /**
     * Abort a command before it has a chance to run.
     *
     * @param \App\Server\Contracts\Command $command to abort
     *
     * @return self
     */
    public function abort(Command $command);
}
