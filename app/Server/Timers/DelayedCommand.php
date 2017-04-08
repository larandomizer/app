<?php

namespace App\Server\Timers;

use App\Server\Contracts\ShouldAutoStart;
use App\Server\Entities\Timer;

class DelayedCommand extends Timer implements ShouldAutoStart
{
}
