<?php

namespace App\Server\Entities;

use App\Server\Contracts\Connection;
use App\Server\Contracts\Prize as PrizeInterface;
use App\Server\Traits\FluentProperties;
use App\Server\Traits\JsonHelpers;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

class Prize implements PrizeInterface, Arrayable, Jsonable, JsonSerializable
{
    use FluentProperties, JsonHelpers;

    protected $name;
    protected $sponsor;
    protected $uuid;
    protected $winner;

    /**
     * Instantiate the prize with the name and sponsor.
     *
     * @param string $name    of prize
     * @param string $sponsor of prize
     */
    public function __construct($name, $sponsor)
    {
        $this->uuid(Uuid::uuid4()->toString());
        $this->name($name);
        $this->sponsor($sponsor);
    }

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
    public function uuid($uuid = null)
    {
        return $this->property(__METHOD__, $uuid);
    }

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
    public function name($name = null)
    {
        return $this->property(__METHOD__, $name);
    }

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
    public function sponsor($sponsor = null)
    {
        return $this->property(__METHOD__, $sponsor);
    }

    /**
     * Get or set the winner of the prize.
     *
     * @example winner() ==> \App\Server\Contract\Connection
     *          winner($winner) ==> self
     *
     * @param \App\Server\Contracts\Connection $winner
     *
     * @return \App\Server\Contracts\Connection|self
     */
    public function winner(Connection $winner = null)
    {
        return $this->property(__METHOD__, $winner);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'uuid'    => $this->uuid,
            'name'    => $this->name,
            'sponsor' => $this->sponsor,
            'winner'  => $this->winner ? $this->winner->toArray() : null,
        ]);
    }
}
