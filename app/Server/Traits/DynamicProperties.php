<?php

namespace App\Server\Traits;

trait DynamicProperties
{
    /**
     * Dynamically get or set the property's value.
     *
     * @example dynamic('foo') ==> true
     *          dynamic('foo', true) ==> self
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return mixed|self
     */
    protected function dynamic($property, $value = null)
    {
        if (is_null($value)) {
            return $this->$property;
        }

        $this->$property = $value;

        return $this;
    }
}
