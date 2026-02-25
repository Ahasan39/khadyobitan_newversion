<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SmsGateway;
use App\Models\GeneralSetting;

class SendOrderSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;
    public $invoiceId;
    public $amount;
    public $phone;
    public $name;
    public $tries = 3;
    public $timeout = 60;

    public function __construct($orderId, $invoiceId, $amount, $phone, $name)
    {
        $this->orderId = $orderId;
        $this->invoiceId = $invoiceId;
        $this->amount = $amount;
        $this->phone = $phone;
        $this->name = $name;
    }

    public function handle()
    {
        $site_setting = GeneralSetting::where('status', 1)->first();
        $sms_gateway = SmsGateway::where(['status' => 1, 'order' => '1'])->first();
        
        if ($sms_gateway) {
            $url = "$sms_gateway->url";
            $data = [
                "api_key" => "$sms_gateway->api_key",
                "number" => $this->phone,
                "type" => 'text',
                "senderid" => "$sms_gateway->serderid",
                "message" => "Dear {$this->name}!\r\nYour order ({$this->invoiceId}) has been successfully placed. Total Bill {$this->amount}\r\nThank you for using {$site_setting->name}"
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            curl_close($ch);
            
            \Log::info('SMS Sent for Order: ' . $this->invoiceId);
        }
    }

    public function failed(\Exception $exception)
    {
        \Log::error('SMS Job Failed for Order ' . $this->invoiceId . ': ' . $exception->getMessage());
    }
}
