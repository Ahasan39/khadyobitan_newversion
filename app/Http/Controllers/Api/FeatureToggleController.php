<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeatureToggle;
use Illuminate\Http\Request;

class FeatureToggleController extends Controller
{
    /**
     * Get all enabled features with settings
     */
    public function getEnabledFeatures()
    {
        try {
            $features = FeatureToggle::getEnabledFeatures();
            
            return response()->json([
                'success' => true,
                'data' => $features
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch features',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific feature if enabled
     */
    public function getFeature($featureKey)
    {
        try {
            $feature = FeatureToggle::where('feature_key', $featureKey)
                ->where('is_enabled', true)
                ->first();
            
            if (!$feature) {
                return response()->json([
                    'success' => false,
                    'message' => 'Feature not found or disabled'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'enabled' => true,
                    'settings' => $feature->settings
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch feature',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
