<?php

namespace App\Giveaway\Entities;

use App\Giveaway\Contracts\Prize as PrizeInterface;
use ArtisanSDK\Server\Traits\FluentProperties;
use ArtisanSDK\Server\Traits\JsonHelpers;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

class Prize implements PrizeInterface, Arrayable, Jsonable, JsonSerializable
{
    use FluentProperties, JsonHelpers;

    protected $awarded = false;
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
        return $this->property(__FUNCTION__, $uuid);
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
        return $this->property(__FUNCTION__, $name);
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
        return $this->property(__FUNCTION__, $sponsor);
    }

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
    public function winner($uuid = null)
    {
        return $this->property(__FUNCTION__, $uuid);
    }

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
    public function awarded($state = null)
    {
        return $this->property(__FUNCTION__, $state);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'awarded' => $this->awarded(),
            'name'    => $this->name(),
            'sponsor' => $this->sponsor(),
            'uuid'    => $this->uuid(),
            'winner'  => $this->winner() ? $this->winner() : null,
        ], function ($value) {
            return ((is_array($value) || is_string($value)) && ! empty($value)) || ! is_null($value);
        });
    }
}
