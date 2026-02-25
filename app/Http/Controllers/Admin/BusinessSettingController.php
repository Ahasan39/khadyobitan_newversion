<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Models\OrderNotificationSetting;
use App\Models\WhatsAppSetting;

class BusinessSettingController extends Controller
{
    public function index()
    {
        return view('backEnd.business_settings.index', [
            'show_new_arrival' => BusinessSetting::getValue('show_new_arrival'),
            'show_top_rated' => BusinessSetting::getValue('show_top_rated'),
            'show_top_selling' => BusinessSetting::getValue('show_top_selling'),
            'show_category' => BusinessSetting::getValue('show_category'),
            'show_brand' => BusinessSetting::getValue('show_brand'),
            'show_all_product' => BusinessSetting::getValue('show_all_product'),
        ]);
    }

    public function update(Request $request)
    {
        BusinessSetting::setValue('show_new_arrival', $request->has('show_new_arrival') ? 1 : 0);
        BusinessSetting::setValue('show_top_rated', $request->has('show_top_rated') ? 1 : 0);
        BusinessSetting::setValue('show_top_selling', $request->has('show_top_selling') ? 1 : 0);
        BusinessSetting::setValue('show_category', $request->has('show_category') ? 1 : 0);
        BusinessSetting::setValue('show_all_product', $request->has('show_all_product') ? 1 : 0);
        BusinessSetting::setValue('show_brand', $request->has('show_brand') ? 1 : 0);

        return back()->with('success', 'Settings updated successfully!');
    }
    
    public function store(Request $request)
    {
    $request->validate([
        'type_index' => 'required|string|max:255',
        'value' => 'nullable|string',
    ]);

    $setting = new BusinessSetting();
    $setting->type = $request->type_index;
    $setting->value = $request->value;
    $setting->save();

    return back()->with('success', 'New business setting added successfully!');
}

     public function whatsAppsstore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        WhatsAppSetting::updateOrCreate(
            ['id' => 1], 
            [
                'name' => $request->name,
                'message' => $request->message
            ]
        );

        return back()->with('success', 'WhatsApp setting updated successfully.');
    }
    
   

public function updateOrderNotification(Request $request)
{
    


    $settings = \App\Models\OrderNotificationSetting::first();
    if (!$settings) {
        $settings = new \App\Models\OrderNotificationSetting();
    }

    $settings->send_to_whatsapp = $request->has('send_to_whatsapp') ? 1 : 0;
    $settings->whatsapp_number = $request->whatsapp_number;
    $settings->send_to_email = $request->has('send_to_email') ? 1 : 0;
    $settings->email_address = $request->email_address;
    $settings->save();

    return back()->with('success', 'Order notification settings updated successfully!');
}



}