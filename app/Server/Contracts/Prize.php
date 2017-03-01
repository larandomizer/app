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
     * @example winner() ==> \App\Server\Contract\Connection
     *          winner($winner) ==> self
     *
     * @param \App\Server\Contract\Connection $winner
     *
     * @return \App\Server\Contract\Connection|self
     */
    public function winner(Connection $winner = null);
}
