<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged
{
    use Dispatchable, SerializesModels;

    public $orders;
    public $oldStatus;
    public $newStatus;
    public $updatedBy;
    public $timestamp;

    public function __construct($orders, $oldStatus, $newStatus, $updatedBy = null)
    {
        $this->orders = $orders;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->updatedBy = $updatedBy;
        $this->timestamp = now();
    }
}

