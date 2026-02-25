<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderNotificationSetting extends Model
{
    protected $tabl='order_notification_settings';
   protected $fillable = [
    'send_to_whatsapp',
    'whatsapp_number',
    'send_to_email',
    'email_address'
];

}
