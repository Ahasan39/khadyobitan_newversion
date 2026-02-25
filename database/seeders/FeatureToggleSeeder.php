<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeatureToggle;

class FeatureToggleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            [
                'feature_key' => 'help_button',
                'feature_name' => 'Help Button',
                'is_enabled' => false,
                'settings' => [
                    'text' => 'Need Help?',
                    'phone' => '',
                    'whatsapp' => '',
                    'position' => 'bottom-right',
                    'color' => '#25D366',
                    'icon' => 'fa-headset'
                ]
            ],
            [
                'feature_key' => 'social_media_floating',
                'feature_name' => 'Social Media Floating',
                'is_enabled' => false,
                'settings' => [
                    'position' => 'left',
                    'icons' => [
                        [
                            'platform' => 'facebook',
                            'url' => '',
                            'icon' => 'fab fa-facebook-f',
                            'color' => '#1877F2'
                        ],
                        [
                            'platform' => 'instagram',
                            'url' => '',
                            'icon' => 'fab fa-instagram',
                            'color' => '#E4405F'
                        ],
                        [
                            'platform' => 'twitter',
                            'url' => '',
                            'icon' => 'fab fa-twitter',
                            'color' => '#1DA1F2'
                        ],
                        [
                            'platform' => 'youtube',
                            'url' => '',
                            'icon' => 'fab fa-youtube',
                            'color' => '#FF0000'
                        ]
                    ]
                ]
            ],
            [
                'feature_key' => 'invoice_whatsapp_button',
                'feature_name' => 'Invoice WhatsApp Button',
                'is_enabled' => false,
                'settings' => [
                    'button_text' => 'Contact via WhatsApp',
                    'phone_number' => '',
                    'message_template' => 'Hello, I have a question about my order #{invoice_id}. Customer: {customer_name}, Total: {total_amount}',
                    'button_color' => '#25D366',
                    'button_position' => 'bottom'
                ]
            ],
            [
                'feature_key' => 'installment_message',
                'feature_name' => 'Installment Message',
                'is_enabled' => false,
                'settings' => [
                    'message_text' => 'Pay in easy installments! Contact us for more details.',
                    'display_location' => 'both',
                    'background_color' => '#FFF3CD',
                    'text_color' => '#856404',
                    'border_style' => 'solid',
                    'border_color' => '#FFEAA7'
                ]
            ]
        ];

        foreach ($features as $feature) {
            FeatureToggle::updateOrCreate(
                ['feature_key' => $feature['feature_key']],
                $feature
            );
        }
    }
}
