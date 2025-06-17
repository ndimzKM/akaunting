<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Events\Common\UnitCreated;
use App\Events\Common\UnitCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use Modules\Inventory\Models\Unit;

class CreateUnit extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Unit
    {
        event(new UnitCreating($this->request));

        $payload = $this->request->all();
        $payload['values'] = json_encode($payload['values']);

        \DB::transaction(function () use ($payload) {
            $this->model = Unit::create($payload);
        });

        event(new UnitCreated($this->model, $this->request));

        return $this->model;
    }
}
