<?php

namespace App\Server\Contracts;

use Carbon\Carbon;

interface Notification
{
    /**
     * Get or set the UUID of the notification.
     *
     * @example uuid() ==> string
     *          uuid($uuid) ==> self
     *
     * @param string $uuid
     *
     * @return string|self
     */
    public function uuid($uuid = null);

    /**
     * Get or set the connection that sent the notification.
     *
     * @example sender() ==> string
     *          sender($uuid) ==> self
     *
     * @param string $uuid
     *
     * @return string|self
     */
    public function sender($uuid = null);

    /**
     * Get or set the time that the notification was sent.
     *
     * @example timestamp() ==> \Carbon\Carbon
     *          timestamp($timestamp) ==> self
     *
     * @param \Carbon\Carbon $timestamp
     *
     * @return \Carbon\Carbon|self
     */
    public function timestamp(Carbon $timestamp = null);
}
