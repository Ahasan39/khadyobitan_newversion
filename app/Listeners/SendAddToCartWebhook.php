<?php

namespace App\Listeners;

use App\Events\AddToCart;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendAddToCartWebhook
{
    public function handle(AddToCart $event)
    {
        try {
            $webhookData = [
                'event' => 'add_to_cart',
                'timestamp' => now()->toISOString(),
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'unique_action_id' => uniqid() . '-' . time(),
                
                // User data
                'user' => [
                    'id' => $event->user?->id,
                    'email' => $event->user?->email,
                    'is_authenticated' => !is_null($event->user)
                ],
                
                // Product data
                'product' => [
                    'id' => $event->product->id,
                    'name' => $event->product->name,
                    'slug' => $event->product->slug,
                    'sku' => $event->product->sku,
                    'new_price' => $event->product->new_price,
                    'old_price' => $event->product->old_price,
                    'category_id' => $event->product->category_id,
                ],
                
                // Cart specific data
                'cart_data' => [
                    'quantity' => $event->cartData['qty'],
                    'color' => $event->cartData['product_color'] ?? null,
                    'size' => $event->cartData['product_size'] ?? null,
                    'total_price' => $event->product->new_price * $event->cartData['qty']
                ],
                
                // Session and request data
                'session_data' => $event->sessionData,
                'request_data' => [
                    'url' => request()->fullUrl(),
                    'referrer' => request()->header('referer'),
                    'user_agent' => request()->header('User-Agent'),
                    'method' => request()->method(),
                    'utm_params' => [
                        'utm_source' => request()->get('utm_source'),
                        'utm_medium' => request()->get('utm_medium'),
                        'utm_campaign' => request()->get('utm_campaign'),
                        'utm_term' => request()->get('utm_term'),
                        'utm_content' => request()->get('utm_content')
                    ]
                ]
            ];

            Http::timeout(10)
                ->retry(3, 100)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Webhook-Source' => config('app.name'),
                    'X-Event-Type' => 'add_to_cart',
                    'X-Request-ID' => uniqid()
                ])
                ->post('https://omorfarukmail1.app.n8n.cloud/webhook/bb9f49e0-14f5-4bbd-a1bb-b9a0c2c7020d', $webhookData);
           
            Log::info('Add to cart webhook sent successfully', [
                'product_id' => $event->product->id,
                'user_id' => $event->user?->id,
                'quantity' => $event->cartData['qty'],
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            Log::error('Add to cart webhook failed', [
                'error' => $e->getMessage(),
                'product_id' => $event->product->id,
                'user_id' => $event->user?->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}