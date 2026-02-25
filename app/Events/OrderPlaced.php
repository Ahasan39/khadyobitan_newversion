<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced
{
    use Dispatchable, SerializesModels;

    public $orderData;
    public $cartItems;
    public $user;
    public $sessionData;

    public function __construct(array $orderData, array $cartItems, $user = null, array $sessionData = [])
    {
        $this->orderData = $orderData;
        $this->cartItems = $cartItems;
        $this->user = $user;
        $this->sessionData = $sessionData;
    }
}