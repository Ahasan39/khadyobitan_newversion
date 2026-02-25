<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendOrderPlacedWebhook
{
    public function handle(OrderPlaced $event)
    {
        try {
            // Add more debugging info
            Log::info('OrderPlaced event triggered', [
                'customer_name' => $event->orderData['name'] ?? 'Unknown',
                'cart_items_count' => count($event->cartItems),
                'total_amount' => $event->orderData['total'] ?? 0,
                'timestamp' => now()
            ]);

            $webhookData = [
                'event' => 'order_placed',
                'timestamp' => now()->toISOString(),
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'unique_order_id' => uniqid() . '-' . time(),
                
                // User data
                'user' => [
                    'id' => $event->user?->id,
                    'email' => $event->user?->email ?? null,
                    'name' => $event->user?->name ?? $event->orderData['name'],
                    'phone' => $event->user?->phone ?? $event->orderData['phone'],
                    'is_authenticated' => !is_null($event->user)
                ],
                
                // Order data
                'order_data' => [
                    'name' => $event->orderData['name'],
                    'phone' => $event->orderData['phone'],
                    'address' => $event->orderData['address'],
                    'area' => $event->orderData['area'],
                    'payment_method' => $event->orderData['payment_method'],
                    'subtotal' => (float) $event->orderData['subtotal'],
                    'shipping_cost' => (float) $event->orderData['shipping_cost'],
                    'discount' => (float) $event->orderData['discount'],
                    'total' => (float) $event->orderData['total']
                ],
                
                // Cart items data with better structure
                'cart_items' => array_map(function($item) {
                    return [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'qty' => (int) $item['qty'],
                        'price' => (float) $item['price'],
                        'total' => (float) $item['total'],
                        'size' => $item['size'],
                        'color' => $item['color'],
                        'image' => $item['image'],
                        'slug' => $item['slug']
                    ];
                }, $event->cartItems),
                
                // Session and request data
                'session_data' => $event->sessionData,
                'request_data' => [
                    'url' => request()->fullUrl(),
                    'referrer' => request()->header('referer'),
                    'user_agent' => request()->header('User-Agent'),
                    'method' => request()->method(),
                    'utm_params' => array_filter([
                        'utm_source' => request()->get('utm_source'),
                        'utm_medium' => request()->get('utm_medium'),
                        'utm_campaign' => request()->get('utm_campaign'),
                        'utm_term' => request()->get('utm_term'),
                        'utm_content' => request()->get('utm_content')
                    ])
                ],
                
                // Additional metadata
                'metadata' => [
                    'app_name' => config('app.name'),
                    'app_url' => config('app.url'),
                    'environment' => config('app.env'),
                    'laravel_version' => app()->version(),
                    'php_version' => PHP_VERSION
                ]
            ];

            // Log the data being sent for debugging
            Log::info('Sending webhook data to N8N', [
                'webhook_url' => 'https://omorfarukmail1.app.n8n.cloud/webhook/bb9f49e0-14f5-4bbd-a1bb-b9a0c2c7020d',
                'data_size' => strlen(json_encode($webhookData)),
                'customer' => $event->orderData['name'],
                'total' => $event->orderData['total']
            ]);

            $response = Http::timeout(30) // Increased timeout
                ->retry(5, 1000) // More retries with longer delay
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Webhook-Source' => config('app.name'),
                    'X-Event-Type' => 'order_placed',
                    'X-Request-ID' => uniqid(),
                    'User-Agent' => 'Laravel-Webhook/1.0'
                ])
                ->post('https://omorfarukmail1.app.n8n.cloud/webhook/bb9f49e0-14f5-4bbd-a1bb-b9a0c2c7020d', $webhookData);

            // Log the response for debugging
            Log::info('N8N webhook response', [
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'headers' => $response->headers(),
                'customer' => $event->orderData['name']
            ]);
           
            if ($response->successful()) {
                Log::info('Order placed webhook sent successfully', [
                    'customer_name' => $event->orderData['name'],
                    'phone' => $event->orderData['phone'],
                    'total' => $event->orderData['total'],
                    'response_status' => $response->status(),
                    'timestamp' => now()
                ]);
            } else {
                Log::warning('Webhook sent but received non-success response', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                    'customer_name' => $event->orderData['name']
                ]);
            }

        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::error('HTTP request failed for order placed webhook', [
                'error' => $e->getMessage(),
                'customer_name' => $event->orderData['name'] ?? 'Unknown',
                'phone' => $event->orderData['phone'] ?? 'Unknown',
                'response_code' => $e->getCode(),
                'trace' => $e->getTraceAsString()
            ]);
        } catch (\Exception $e) {
            Log::error('Order placed webhook failed with general exception', [
                'error' => $e->getMessage(),
                'customer_name' => $event->orderData['name'] ?? 'Unknown',
                'phone' => $event->orderData['phone'] ?? 'Unknown',
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}