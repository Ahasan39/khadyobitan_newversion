<?php

namespace App\Listeners;

use App\Events\ProductViewed;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendProductViewWebhook
{
    public function handle(ProductViewed $event)
    {
        try {
            $webhookData = [
                'event' => 'product_view',
                'timestamp' => now()->toISOString(),
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'unique_view_id' => uniqid() . '-' . time(), // Ensure uniqueness
                
                // User data
                'user' => [
                    'id' => $event->user?->id,
                    'email' => $event->user?->email,
                    'is_authenticated' => !is_null($event->user)
                ],
           
                
                // Complete product data
                'product' => [
                    'id' => $event->product->id,
                    'name' => $event->product->name,
                    'slug' => $event->product->slug,
                    'sku' => $event->product->sku,
                    'description' => $event->product->description,
                    'new_price' => $event->product->new_price,
                    'old_price' => $event->product->old_price,
                    'type' => $event->product->type,
                    'status' => $event->product->status,
                    'stock' => $event->product->stock,
                    'category_id' => $event->product->category_id,
                    'subcategory_id' => $event->product->subcategory_id,
                    'childcategory_id' => $event->product->childcategory_id,
                    'weight' => $event->product->weight,
                    'length' => $event->product->length,
                    'width' => $event->product->width,
                    'height' => $event->product->height,
                    'meta_title' => $event->product->meta_title,
                    'meta_description' => $event->product->meta_description,
                    'created_at' => $event->product->created_at,
                    'updated_at' => $event->product->updated_at
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
                    'X-Event-Type' => 'product_view',
                    'X-Request-ID' => uniqid()
                ])
                ->post('https://omorfarukmail1.app.n8n.cloud/webhook/bb9f49e0-14f5-4bbd-a1bb-b9a0c2c7020d', $webhookData);
           
            
            Log::info('Product view webhook sent successfully', [
                'product_id' => $event->product->id,
                'user_id' => $event->user?->id,
                'timestamp' => now()
            ]);

        } catch (\Exception $e) {
            Log::error('Product view webhook failed', [
                'error' => $e->getMessage(),
                'product_id' => $event->product->id,
                'user_id' => $event->user?->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}