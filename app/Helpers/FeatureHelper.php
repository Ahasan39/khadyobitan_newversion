<?php

use App\Models\FeatureToggle;

if (!function_exists('isFeatureEnabled')) {
    /**
     * Check if a feature is enabled
     *
     * @param string $featureKey
     * @return bool
     */
    function isFeatureEnabled($featureKey)
    {
        try {
            return FeatureToggle::isEnabled($featureKey);
        } catch (\Exception $e) {
            \Log::error('Feature check error: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('getFeatureSettings')) {
    /**
     * Get feature settings if enabled
     *
     * @param string $featureKey
     * @return array|null
     */
    function getFeatureSettings($featureKey)
    {
        try {
            return FeatureToggle::getSettings($featureKey);
        } catch (\Exception $e) {
            \Log::error('Feature settings error: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getAllEnabledFeatures')) {
    /**
     * Get all enabled features
     *
     * @return \Illuminate\Support\Collection
     */
    function getAllEnabledFeatures()
    {
        try {
            return FeatureToggle::getEnabledFeatures();
        } catch (\Exception $e) {
            \Log::error('Get enabled features error: ' . $e->getMessage());
            return collect([]);
        }
    }
}

if (!function_exists('renderHelpButton')) {
    /**
     * Render help button HTML if enabled
     *
     * @return string
     */
    function renderHelpButton()
    {
        if (!isFeatureEnabled('help_button')) {
            return '';
        }

        $settings = getFeatureSettings('help_button');
        if (!$settings) {
            return '';
        }

        $position = $settings['position'] ?? 'bottom-right';
        $text = $settings['text'] ?? 'Need Help?';
        $color = $settings['color'] ?? '#25D366';
        $icon = $settings['icon'] ?? 'fa-headset';
        $phone = $settings['phone'] ?? '';
        $whatsapp = $settings['whatsapp'] ?? '';

        $positionStyles = [
            'bottom-right' => 'bottom: 20px; right: 20px;',
            'bottom-left' => 'bottom: 20px; left: 20px;',
            'top-right' => 'top: 20px; right: 20px;',
            'top-left' => 'top: 20px; left: 20px;',
        ];

        $style = $positionStyles[$position] ?? $positionStyles['bottom-right'];

        $html = '<div class="help-button-widget" style="position: fixed; ' . $style . ' z-index: 9999;">';
        $html .= '<div style="background: ' . $color . '; color: white; padding: 15px 25px; border-radius: 50px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); cursor: pointer; font-weight: 600;">';
        $html .= '<i class="fas ' . $icon . ' me-2"></i>' . $text;
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}

if (!function_exists('renderSocialMediaFloating')) {
    /**
     * Render social media floating icons if enabled
     *
     * @return string
     */
    function renderSocialMediaFloating()
    {
        if (!isFeatureEnabled('social_media_floating')) {
            return '';
        }

        $settings = getFeatureSettings('social_media_floating');
        if (!$settings || empty($settings['icons'])) {
            return '';
        }

        $position = $settings['position'] ?? 'left';
        $positionStyle = $position === 'left' ? 'left: 20px;' : 'right: 20px;';

        $html = '<div class="social-media-floating" style="position: fixed; top: 50%; transform: translateY(-50%); ' . $positionStyle . ' z-index: 9998; display: flex; flex-direction: column; gap: 10px;">';

        foreach ($settings['icons'] as $icon) {
            if (empty($icon['url'])) {
                continue;
            }

            $html .= '<a href="' . $icon['url'] . '" target="_blank" rel="noopener noreferrer" style="width: 50px; height: 50px; background: ' . ($icon['color'] ?? '#1877F2') . '; color: white; display: flex; align-items: center; justify-content: center; border-radius: 50%; text-decoration: none; box-shadow: 0 2px 10px rgba(0,0,0,0.2); transition: transform 0.3s;" onmouseover="this.style.transform=\'scale(1.1)\'" onmouseout="this.style.transform=\'scale(1)\'">';
            $html .= '<i class="' . ($icon['icon'] ?? 'fab fa-facebook-f') . '" style="font-size: 1.5rem;"></i>';
            $html .= '</a>';
        }

        $html .= '</div>';

        return $html;
    }
}

if (!function_exists('renderInstallmentMessage')) {
    /**
     * Render installment message if enabled
     *
     * @param string $location 'product' or 'checkout'
     * @return string
     */
    function renderInstallmentMessage($location = 'product')
    {
        if (!isFeatureEnabled('installment_message')) {
            return '';
        }

        $settings = getFeatureSettings('installment_message');
        if (!$settings) {
            return '';
        }

        $displayLocation = $settings['display_location'] ?? 'both';
        
        // Check if should display in this location
        if ($displayLocation !== 'both' && $displayLocation !== $location) {
            return '';
        }

        $message = $settings['message_text'] ?? 'Pay in easy installments! Contact us for more details.';
        $bgColor = $settings['background_color'] ?? '#FFF3CD';
        $textColor = $settings['text_color'] ?? '#856404';
        $borderStyle = $settings['border_style'] ?? 'solid';
        $borderColor = $settings['border_color'] ?? '#FFEAA7';

        $html = '<div class="installment-message" style="padding: 15px 20px; border-radius: 8px; background: ' . $bgColor . '; color: ' . $textColor . '; border: 2px ' . $borderStyle . ' ' . $borderColor . '; text-align: center; font-weight: 500; margin: 15px 0;">';
        $html .= '<i class="fas fa-credit-card me-2"></i>' . $message;
        $html .= '</div>';

        return $html;
    }
}

if (!function_exists('getWhatsAppInvoiceLink')) {
    /**
     * Generate WhatsApp link for invoice
     *
     * @param string $invoiceId
     * @param string $customerName
     * @param string $totalAmount
     * @return string|null
     */
    function getWhatsAppInvoiceLink($invoiceId, $customerName, $totalAmount)
    {
        if (!isFeatureEnabled('invoice_whatsapp_button')) {
            return null;
        }

        $settings = getFeatureSettings('invoice_whatsapp_button');
        if (!$settings || empty($settings['phone_number'])) {
            return null;
        }

        $phoneNumber = preg_replace('/[^0-9+]/', '', $settings['phone_number']);
        $messageTemplate = $settings['message_template'] ?? 'Hello, I have a question about my order #{invoice_id}.';

        $message = str_replace(
            ['{invoice_id}', '{customer_name}', '{total_amount}'],
            [$invoiceId, $customerName, $totalAmount],
            $messageTemplate
        );

        $encodedMessage = urlencode($message);

        return "https://wa.me/{$phoneNumber}?text={$encodedMessage}";
    }
}

if (!function_exists('renderInvoiceWhatsAppButton')) {
    /**
     * Render WhatsApp button for invoice
     *
     * @param string $invoiceId
     * @param string $customerName
     * @param string $totalAmount
     * @param string $position 'top' or 'bottom'
     * @return string
     */
    function renderInvoiceWhatsAppButton($invoiceId, $customerName, $totalAmount, $position = 'bottom')
    {
        if (!isFeatureEnabled('invoice_whatsapp_button')) {
            return '';
        }

        $settings = getFeatureSettings('invoice_whatsapp_button');
        if (!$settings) {
            return '';
        }

        $buttonPosition = $settings['button_position'] ?? 'bottom';
        
        // Check if should display in this position
        if ($buttonPosition !== 'both' && $buttonPosition !== $position) {
            return '';
        }

        $link = getWhatsAppInvoiceLink($invoiceId, $customerName, $totalAmount);
        if (!$link) {
            return '';
        }

        $buttonText = $settings['button_text'] ?? 'Contact via WhatsApp';
        $buttonColor = $settings['button_color'] ?? '#25D366';

        $html = '<div class="invoice-whatsapp-button" style="margin: 15px 0; text-align: center;">';
        $html .= '<a href="' . $link . '" target="_blank" class="btn btn-lg" style="background: ' . $buttonColor . '; color: white; text-decoration: none; border: none; padding: 12px 30px; border-radius: 8px; display: inline-block; font-weight: 600;">';
        $html .= '<i class="fab fa-whatsapp me-2"></i>' . $buttonText;
        $html .= '</a>';
        $html .= '</div>';

        return $html;
    }
}
