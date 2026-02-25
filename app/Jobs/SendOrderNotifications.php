<?php

namespace App\Jobs;
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\OrderNotificationSetting;
use App\Models\UpdatePermission;
use App\Models\GeneralSetting;
use Mail;

class SendOrderNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;
    public $requestData;
    public $tries = 3;
    public $timeout = 60;

    public function __construct($orderId, $requestData)
    {
        $this->orderId = $orderId;
        $this->requestData = $requestData;
    }

    public function handle()
    {
        $permission = UpdatePermission::where('key', 'order_notification')
            ->where('status', 1)
            ->first();
        
        if (!$permission) {
            return;
        }

        $notificationSettings = OrderNotificationSetting::get();
        if (count($notificationSettings) == 0) {
            return;
        }

        // Reload order from database
        $order = Order::find($this->orderId);
        if (!$order) {
            \Log::error('Order not found: ' . $this->orderId);
            return;
        }

        $orderMessage = $this->prepareOrderMessage($order);

        foreach ($notificationSettings as $setting) {
            if ($setting->whatsapp_number) {
                $this->sendWhatsAppNotification($setting->whatsapp_number, $orderMessage);
            }

            if ($setting->email_address) {
                $this->sendEmailNotification($setting->email_address, $order, $orderMessage);
            }
        }
    }

    private function prepareOrderMessage($order)
    {
        $site_setting = GeneralSetting::where('status', 1)->first();
        
        $message = "New Order Received!\n\n";
        $message .= "Invoice ID: {$order->invoice_id}\n";
        $message .= "Customer: {$this->requestData['name']}\n";
        $message .= "Phone: {$this->requestData['phone']}\n";
        $message .= "Address: {$this->requestData['address']}\n";
        $message .= "Total: {$order->amount} BDT\n";
        $message .= "Payment: {$this->requestData['payment_method']}\n\n";
        
        $message .= "Items:\n";
        foreach ($order->orderdetails as $detail) {
            $message .= "- {$detail->product_name} (x{$detail->qty}) - {$detail->sale_price} BDT\n";
        }
        
        return $message;
    }

    private function sendWhatsAppNotification($number, $message)
    {
        try {
            $instanceId = env('WHATSAPP_INSTANCE_ID');
            $token = env('WHATSAPP_TOKEN');
            $url = "https://api.ultramsg.com/{$instanceId}/messages/chat";

            $data = [
                'token' => $token,
                'to' => $number,
                'body' => $message,
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            curl_close($ch);

            \Log::info('WhatsApp Sent for Order: ' . $this->orderId);
        } catch (\Exception $e) {
            \Log::error('WhatsApp Error: ' . $e->getMessage());
        }
    }

    private function sendEmailNotification($email, $order, $message)
    {
        try {
            $site_setting = GeneralSetting::where('status', 1)->first();
            
            Mail::send([], [], function ($mail_message) use ($email, $order, $message, $site_setting) {
                $mail_message->to($email)
                    ->subject("New Order - #{$order->invoice_id}")
                    ->html(nl2br($message));
                
                if ($site_setting && $site_setting->email) {
                    $mail_message->from($site_setting->email, $site_setting->name);
                }
            });
            
            \Log::info('Email Sent for Order: ' . $this->orderId);
        } catch (\Exception $e) {
            \Log::error('Email Error: ' . $e->getMessage());
        }
    }

    public function failed(\Exception $exception)
    {
        \Log::error('Notification Job Failed for Order ' . $this->orderId . ': ' . $exception->getMessage());
    }
}