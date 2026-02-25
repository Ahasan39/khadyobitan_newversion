<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class ProductViewed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;
    public $user;
    public $sessionData;

    public function __construct(Product $product, $user = null, array $sessionData = [])
    {
        $this->product = $product;
        $this->user = $user;
        $this->sessionData = $sessionData;
    }
}
