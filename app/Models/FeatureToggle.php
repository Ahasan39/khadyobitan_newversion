<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureToggle extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_key',
        'feature_name',
        'is_enabled',
        'settings'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'settings' => 'array'
    ];

    /**
     * Check if a feature is enabled
     */
    public static function isEnabled($featureKey)
    {
        return self::where('feature_key', $featureKey)
            ->where('is_enabled', true)
            ->exists();
    }

    /**
     * Get feature settings
     */
    public static function getSettings($featureKey)
    {
        $feature = self::where('feature_key', $featureKey)
            ->where('is_enabled', true)
            ->first();
        
        return $feature ? $feature->settings : null;
    }

    /**
     * Update feature settings
     */
    public static function updateFeature($featureKey, $data)
    {
        return self::where('feature_key', $featureKey)->update($data);
    }

    /**
     * Get all enabled features
     */
    public static function getEnabledFeatures()
    {
        return self::where('is_enabled', true)
            ->get()
            ->keyBy('feature_key')
            ->map(function($feature) {
                return [
                    'enabled' => true,
                    'settings' => $feature->settings
                ];
            });
    }
}
