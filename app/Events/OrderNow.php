<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product; // CHANGE THIS LINE - Use App\Models\Product

class OrderNow
{
    use Dispatchable, SerializesModels;

    public $product;
    public $user;
    public $orderData;
    public $sessionData;

    public function __construct(Product $product, array $orderData, $user = null, array $sessionData = [])
    {
        $this->product = $product;
        $this->orderData = $orderData;
        $this->user = $user;
        $this->sessionData = $sessionData;
    }
}