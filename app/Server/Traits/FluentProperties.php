<?php

namespace App\Server\Traits;

trait FluentProperties
{
    /**
     * Dynamically get or set the property's value using fluency.
     *
     * @example property('foo') ==> true
     *          property('foo', true) ==> self
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return mixed|self
     */
    protected function property($property, $value = null)
    {
        $name = explode('::', $property);
        $name = end($name);

        if (is_null($value)) {
            return $this->$name;
        }

        $this->$name = $value;

        return $this;
    }
}
