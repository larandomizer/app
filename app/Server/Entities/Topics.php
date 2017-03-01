<?php

namespace App\Server\Entities;

use App\Server\Traits\UUIDFilter;
use Illuminate\Support\Collection;

class Topics extends Collection
{
    use UUIDFilter;
}
