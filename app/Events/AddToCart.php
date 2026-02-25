<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product; // CHANGE THIS LINE - Use App\Models\Product

class AddToCart
{
    use Dispatchable, SerializesModels;

    public $product;
    public $user;
    public $cartData;
    public $sessionData;

    public function __construct(Product $product, array $cartData, $user = null, array $sessionData = [])
    {
        $this->product = $product;
        $this->cartData = $cartData;
        $this->user = $user;
        $this->sessionData = $sessionData;
    }
}