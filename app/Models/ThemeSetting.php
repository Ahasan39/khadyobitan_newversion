<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $fillable = [
        'header_color',
        'footer_color',
        'text_color',
        'button_color',
        'add_to_cart_color',
        'price_color',
        'single_order_color',
        'checkout_header_color',
        'checkout_order_color',
    ];
}
