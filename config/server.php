<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Manager Class
    |--------------------------------------------------------------------------
    |
    | The server runs as a singleton instance with with the server being the
    | main event loop manager, the broker being the connection handler, and
    | the manager being the kernel of the application. Sometimes you have
    | to extend the manager and this config lets you customize that.
    |
    */

    'manager' => App\Giveaway\Manager::class,

    /*
    |--------------------------------------------------------------------------
    | Server Address Bindings
    |--------------------------------------------------------------------------
    |
    | The address and port that the server will bind to for client connections.
    |
    */

    'address' => env('SERVER_ADDRESS', '0.0.0.0'),
    'port'    => 8080,

    /*
    |--------------------------------------------------------------------------
    | Message Queue Connector
    |--------------------------------------------------------------------------
    |
    | The message queue that the server will be responsible for processing.
    | Defaults to the default queue on the default connection for the
    | default queue driver. Connection must exist in queue.php.
    |
    */

    'connector' => env('SERVER_QUEUE_DRIVER', env('QUEUE_DRIVER', 'beanstalkd')),
    'queue'     => env('SERVER_QUEUE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Admin Command Password
    |--------------------------------------------------------------------------
    |
    | The secret key that the server will use to authenticate connections that
    | can execute remote admin commands like restarting the server.
    |
    */

    'password' => env('SERVER_KEY', 'password'),

    /*
    |--------------------------------------------------------------------------
    | Max Connections Allowed
    |--------------------------------------------------------------------------
    |
    | The maximum number of connections the server will accept. Do not specify
    | a value or set to zero to allow for unlimited connections.
    |
    */

    'max_connections' => env('SERVER_MAX_CONNECTIONS'),

    /*
    |--------------------------------------------------------------------------
    | Message Namespaces
    |--------------------------------------------------------------------------
    |
    | The class namespaces that a message can resolve to. Remember to keep the
    | first order in the order you want the paths to be resolved as the first
    | namespace that has a corresponding class will be resolved.
    |
    */

    'namespaces' => [
        'App\\Giveaway\\Messages\\',
        'ArtisanSDK\\Server\\Messages\\',
    ],
];
