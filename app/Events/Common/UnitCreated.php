<?php

namespace App\Events\Common;

use App\Abstracts\Event;
use Modules\Inventory\Models\Unit;

class UnitCreated extends Event
{
    public $unit;

    /**
     * Create a new event instance.
     *
     * @param $unit
     */
    public function __construct(Unit $unit)
    {
        $this->unit = $unit;
    }
}
