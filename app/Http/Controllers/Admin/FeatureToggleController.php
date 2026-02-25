<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureToggle;
use Illuminate\Http\Request;
use Toastr;
use Exception;

class FeatureToggleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:setting-list|setting-edit', ['only' => ['index']]);
        $this->middleware('permission:setting-create', ['only' => ['create','store']]);
        $this->middleware('permission:setting-edit', ['only' => ['edit','update','toggle']]);
        $this->middleware('permission:setting-delete', ['only' => ['destroy']]);
    }

    /**
     * Display all feature toggles
     */
    public function index()
    {
        try {
            $features = FeatureToggle::orderBy('id', 'ASC')->get();
            return view('backEnd.feature_toggles.index', compact('features'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch feature toggles: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Show create form for new feature
     */
    public function create()
    {
        try {
            return view('backEnd.feature_toggles.create');
        } catch (Exception $e) {
            Toastr::error('Failed to load create form: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Store new feature toggle
     */
    public function store(Request $request)
    {
        try {
            // Log incoming request for debugging
            \Log::info('Feature Toggle Store Request', [
                'feature_key' => $request->feature_key,
                'feature_name' => $request->feature_name,
                'feature_type' => $request->feature_type,
                'is_enabled' => $request->has('is_enabled'),
                'all_data' => $request->all()
            ]);

            // Validate basic fields
            $validated = $request->validate([
                'feature_key' => 'required|string|max:100|unique:feature_toggles,feature_key',
                'feature_name' => 'required|string|max:255',
                'feature_type' => 'required|string|in:help_button,social_media_floating,invoice_whatsapp_button,installment_message,custom'
            ]);

            \Log::info('Validation passed', $validated);

            // Validate feature-specific settings
            if ($request->feature_type !== 'custom' && $request->has('feature_type')) {
                $this->validateFeature($request, $request->feature_type);
                \Log::info('Feature-specific validation passed');
            }

            // Prepare settings data
            $settings = $request->feature_type !== 'custom' 
                ? $this->prepareSettings($request, $request->feature_type)
                : ($request->settings ?? []);

            \Log::info('Settings prepared', ['settings' => $settings]);

            // Create feature
            $feature = FeatureToggle::create([
                'feature_key' => $request->feature_key,
                'feature_name' => $request->feature_name,
                'is_enabled' => $request->has('is_enabled') ? 1 : 0,
                'settings' => $settings
            ]);

            \Log::info('Feature created successfully', ['id' => $feature->id]);

            Toastr::success('Feature created successfully', 'Success');
            return redirect()->route('feature.toggles.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Feature Toggle Validation Error', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            Toastr::error('Validation failed: ' . implode(', ', array_map(function($errors) {
                return implode(', ', $errors);
            }, $e->errors())), 'Error');
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            \Log::error('Feature Toggle Create Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            Toastr::error('Failed to create feature: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show edit form for specific feature
     */
    public function edit($id)
    {
        try {
            $feature = FeatureToggle::findOrFail($id);
            return view('backEnd.feature_toggles.edit', compact('feature'));
        } catch (Exception $e) {
            Toastr::error('Failed to fetch feature: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Update feature settings
     */
    public function update(Request $request, $id)
    {
        try {
            $feature = FeatureToggle::findOrFail($id);
            
            // Validate based on feature type
            $this->validateFeature($request, $feature->feature_key);
            
            // Prepare settings data
            $settings = $this->prepareSettings($request, $feature->feature_key);
            
            // Update feature
            $feature->update([
                'is_enabled' => $request->has('is_enabled') ? 1 : 0,
                'settings' => $settings
            ]);
            
            Toastr::success('Feature updated successfully', 'Success');
            return redirect()->route('feature.toggles.index');
            
        } catch (Exception $e) {
            \Log::error('Feature Toggle Update Error: ' . $e->getMessage());
            Toastr::error('Failed to update feature: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Quick toggle feature on/off
     */
    public function toggle(Request $request)
    {
        try {
            $feature = FeatureToggle::findOrFail($request->feature_id);
            $feature->is_enabled = !$feature->is_enabled;
            $feature->save();
            
            $status = $feature->is_enabled ? 'enabled' : 'disabled';
            Toastr::success("Feature {$status} successfully", 'Success');
            return redirect()->back();
            
        } catch (Exception $e) {
            Toastr::error('Failed to toggle feature: '.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Delete feature toggle
     */
    public function destroy($id)
    {
        try {
            $feature = FeatureToggle::findOrFail($id);
            $featureName = $feature->feature_name;
            
            $feature->delete();
            
            Toastr::success("Feature '{$featureName}' deleted successfully", 'Success');
            return redirect()->route('feature.toggles.index');
            
        } catch (Exception $e) {
            \Log::error('Feature Toggle Delete Error: ' . $e->getMessage());
            Toastr::error('Failed to delete feature: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Validate feature based on type
     */
    private function validateFeature(Request $request, $featureKey)
    {
        switch ($featureKey) {
            case 'help_button':
                $request->validate([
                    'text' => 'nullable|string|max:100',
                    'phone' => 'nullable|string|max:20',
                    'whatsapp' => 'nullable|string|max:20',
                    'position' => 'nullable|in:bottom-right,bottom-left,top-right,top-left',
                    'color' => 'nullable|string|max:7',
                ]);
                break;
                
            case 'social_media_floating':
                $request->validate([
                    'position' => 'nullable|in:left,right',
                    'icons' => 'nullable|array',
                    'icons.*.platform' => 'nullable|string',
                    'icons.*.url' => 'nullable|url',
                    'icons.*.icon' => 'nullable|string',
                    'icons.*.color' => 'nullable|string',
                ]);
                break;
                
            case 'invoice_whatsapp_button':
                $request->validate([
                    'button_text' => 'nullable|string|max:100',
                    'phone_number' => 'nullable|string|max:20',
                    'message_template' => 'nullable|string',
                    'button_color' => 'nullable|string|max:7',
                    'button_position' => 'nullable|in:top,bottom,both',
                ]);
                break;
                
            case 'installment_message':
                $request->validate([
                    'message_text' => 'nullable|string',
                    'display_location' => 'nullable|in:product,checkout,both',
                    'background_color' => 'nullable|string|max:7',
                    'text_color' => 'nullable|string|max:7',
                    'border_style' => 'nullable|in:solid,dashed,none',
                ]);
                break;
        }
    }

    /**
     * Prepare settings array based on feature type
     */
    private function prepareSettings(Request $request, $featureKey)
    {
        switch ($featureKey) {
            case 'help_button':
                return [
                    'text' => $request->text ?? 'Need Help?',
                    'phone' => $request->phone ?? '',
                    'whatsapp' => $request->whatsapp ?? '',
                    'position' => $request->position ?? 'bottom-right',
                    'color' => $request->color ?? '#25D366',
                    'icon' => $request->icon ?? 'fa-headset'
                ];
                
            case 'social_media_floating':
                return [
                    'position' => $request->position ?? 'left',
                    'icons' => $request->icons ?? []
                ];
                
            case 'invoice_whatsapp_button':
                return [
                    'button_text' => $request->button_text ?? 'Contact via WhatsApp',
                    'phone_number' => $request->phone_number ?? '',
                    'message_template' => $request->message_template ?? 'Hello, I have a question about my order #{invoice_id}.',
                    'button_color' => $request->button_color ?? '#25D366',
                    'button_position' => $request->button_position ?? 'bottom'
                ];
                
            case 'installment_message':
                return [
                    'message_text' => $request->message_text ?? 'Pay in easy installments! Contact us for more details.',
                    'display_location' => $request->display_location ?? 'both',
                    'background_color' => $request->background_color ?? '#FFF3CD',
                    'text_color' => $request->text_color ?? '#856404',
                    'border_style' => $request->border_style ?? 'solid',
                    'border_color' => $request->border_color ?? '#FFEAA7'
                ];
                
            default:
                return [];
        }
    }
}
