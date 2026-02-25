<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TrackingController extends Controller
{
    public function productView(Request $request)
    {
        $data = $request->all();

        // Your n8n webhook URL
        $n8nWebhookUrl = "https://n8n.webleez.top/webhook/12345";

        // Send data to n8n
        Http::post($n8nWebhookUrl, [
            'event' => 'product_view',
            'product_id' => $data['product_id'],
            'product_name' => $data['product_name'],
            'user_id' => auth()->id() ?? null,
            'visited_at' => now()->toDateTimeString(),
        ]);

        return response()->json(['status' => 'ok']);
    }
}
