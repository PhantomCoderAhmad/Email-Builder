<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailTriggerFired
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $triggerEvent;
    public $authUserId;

    public function __construct($triggerEvent, $authUserId)
    {
        $this->triggerEvent = $triggerEvent;
        $this->authUserId = $authUserId;
    }
}
