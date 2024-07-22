<?php

namespace App\Events;

use App\Models\Fixture;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class FixtureUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fixture;

    public function __construct(Fixture $fixture)
    {
        $this->fixture = $fixture;
    }
}
