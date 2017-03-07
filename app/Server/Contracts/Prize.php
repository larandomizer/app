<?php

namespace App\Server\Contracts;

interface Prize
{
    /**
     * Get or set the UUID of the prize.
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
     * Get or set the name of the prize.
     *
     * @example name() ==> string
     *          name($name) ==> self
     *
     * @param string $name
     *
     * @return string|self
     */
    public function name($name = null);

    /**
     * Get or set the sponsor of the prize.
     *
     * @example sponsor() ==> string
     *          sponsor($sponsor) ==> self
     *
     * @param string $sponsor
     *
     * @return string|self
     */
    public function sponsor($sponsor = null);

    /**
     * Get or set the winner of the prize.
     *
     * @example winner() ==> string
     *          winner($uuid) ==> self
     *
     * @param string $winner
     *
     * @return string|self
     */
    public function winner($uuid = null);

    /**
     * Get or set the awarded status of the prize.
     *
     * @example awarded() ==> bool
     *          awarded($state) ==> self
     *
     * @param bool $state
     *
     * @return bool|self
     */
    public function awarded($state = null);
}
