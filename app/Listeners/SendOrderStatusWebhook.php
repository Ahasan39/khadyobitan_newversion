<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendOrderStatusWebhook
{
    public function handle(OrderStatusChanged $event)
    {
        try {
            // Map status codes to readable names
            $statusMap = [
                0 => 'pending',
                1 => 'confirmed',
                2 => 'processing',
                3 => 'shipped',
                4 => 'delivered',
                5 => 'cancelled',
                6 => 'returned'
            ];

            $ordersData = [];
            foreach ($event->orders as $order) {
                $orderDetails = [];
                if ($order->orderdetails) {
                    foreach ($order->orderdetails as $detail) {
                        $orderDetails[] = [
                            'product_id' => $detail->product_id,
                            'product_name' => $detail->product_name,
                            'qty' => $detail->qty,
                            'price' => $detail->sale_price,
                            'total' => $detail->sale_price * $detail->qty,
                            'color' => $detail->product_color,
                            'size' => $detail->product_size,
                            'type' => $detail->product_type
                        ];
                    }
                }

                $ordersData[] = [
                    'order_id' => $order->id,
                    'invoice_id' => $order->invoice_id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer_name ?? $order->shipping?->name,
                    'customer_phone' => $order->customer_phone ?? $order->shipping?->phone,
                    'customer_address' => $order->shipping?->address,
                    'customer_area' => $order->shipping?->area,
                    'amount' => $order->amount,
                    'discount' => $order->discount,
                    'shipping_charge' => $order->shipping_charge,
                    'payment_method' => $order->payment?->payment_method,
                    'payment_status' => $order->payment?->payment_status,
                    'order_type' => $order->order_type,
                    'note' => $order->note,
                    'order_details' => $orderDetails,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at
                ];
            }

            $webhookData = [
                'event' => 'order_status_changed',
                'timestamp' => $event->timestamp->toISOString(),
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'unique_event_id' => uniqid() . '-' . time(),
                
                // Status change data
                'status_change' => [
                    'old_status' => [
                        'code' => $event->oldStatus,
                        'name' => $statusMap[$event->oldStatus] ?? 'unknown'
                    ],
                    'new_status' => [
                        'code' => $event->newStatus,
                        'name' => $statusMap[$event->newStatus] ?? 'unknown'
                    ],
                    'updated_by' => [
                        'id' => $event->updatedBy?->id,
                        'name' => $event->updatedBy?->name,
                        'email' => $event->updatedBy?->email,
                        'type' => $event->updatedBy ? 'admin' : 'system'
                    ]
                ],
                
                // Orders data
                'orders' => $ordersData,
                'orders_count' => count($ordersData),
                'total_amount' => array_sum(array_column($ordersData, 'amount')),
                
                // Request data
                'request_data' => [
                    'url' => request()->fullUrl(),
                    'user_agent' => request()->header('User-Agent'),
                    'method' => request()->method(),
                    'referrer' => request()->header('referer')
                ],
                
                // Metadata
                'metadata' => [
                    'app_name' => config('app.name'),
                    'app_url' => config('app.url'),
                    'environment' => config('app.env')
                ]
            ];

            Log::info('OrderStatusChanged event triggered', [
                'orders_count' => count($event->orders),
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
                'updated_by' => $event->updatedBy?->name ?? 'system'
            ]);

            $response = Http::timeout(30)
                ->retry(5, 1000)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Webhook-Source' => config('app.name'),
                    'X-Event-Type' => 'order_status_changed',
                    'X-Request-ID' => uniqid(),
                    'User-Agent' => 'Laravel-Webhook/1.0'
                ])
                ->post('https://omorfarukmail1.app.n8n.cloud/webhook/bb9f49e0-14f5-4bbd-a1bb-b9a0c2c7020d', $webhookData);

            if ($response->successful()) {
                Log::info('Order status webhook sent successfully', [
                    'orders_count' => count($event->orders),
                    'new_status' => $statusMap[$event->newStatus] ?? 'unknown',
                    'response_status' => $response->status(),
                    'timestamp' => now()
                ]);
            } else {
                Log::warning('Order status webhook failed', [
                    'status_code' => $response->status(),
                    'response_body' => $response->body(),
                    'orders_count' => count($event->orders)
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Order status webhook failed', [
                'error' => $e->getMessage(),
                'orders_count' => count($event->orders ?? []),
                'new_status' => $event->newStatus,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
