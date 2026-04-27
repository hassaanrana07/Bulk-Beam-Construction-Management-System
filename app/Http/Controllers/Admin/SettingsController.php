<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SettingsController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'currentSettings' => array_merge(
                GlobalSetting::getByGroup('general'),
                GlobalSetting::getByGroup('theme'),
                GlobalSetting::getByGroup('seo'),
                GlobalSetting::getByGroup('lead'),
                GlobalSetting::getByGroup('company')
            ),
            'systemConfigs' => \App\Models\SystemConfig::query()->pluck('value', 'key')
        ]);
    }

    public function updateConfigs(Request $request)
    {
        $this->authorize('configure system');

        foreach ($request->except('_token') as $key => $value) {
            \App\Models\SystemConfig::set($key, $value);
        }

        return back()->with('success', 'System configurations updated');
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        // Handle logo upload or removal
        if ($request->hasFile('company_logo')) {
            // Delete old logo file if exists
            $oldLogo = GlobalSetting::get('company_logo');
            if ($oldLogo && str_starts_with((string) $oldLogo, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $oldLogo);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('company_logo')->store('logos', 'public');
            GlobalSetting::set('company_logo', '/storage/' . $path, 'string', 'company');
        } elseif ($request->input('remove_logo') === '1' || $request->input('remove_logo') === 'true') {
            // Delete old logo file if exists
            $oldLogo = GlobalSetting::get('company_logo');
            if ($oldLogo && str_starts_with((string) $oldLogo, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $oldLogo);
                Storage::disk('public')->delete($oldPath);
            }
            GlobalSetting::set('company_logo', '', 'string', 'company');
        }

        $textSettings = [
            'site_name' => ['group' => 'general', 'type' => 'string'],
            'contact_email' => ['group' => 'general', 'type' => 'string'],
            'contact_phone' => ['group' => 'general', 'type' => 'string'],
            'company_info' => ['group' => 'company', 'type' => 'string'],
            'meta_description' => ['group' => 'seo', 'type' => 'string'],
            'google_analytics_id' => ['group' => 'seo', 'type' => 'string'],
            'allow_public_toggle' => ['group' => 'theme', 'type' => 'boolean'],
            'header_style' => ['group' => 'theme', 'type' => 'string'],
            'logo_width' => ['group' => 'theme', 'type' => 'integer'],
            'logo_height' => ['group' => 'theme', 'type' => 'integer'],
            'show_company_name' => ['group' => 'theme', 'type' => 'boolean'],
            'login_branding_mode' => ['group' => 'theme', 'type' => 'string'],
            'min_lead_score' => ['group' => 'lead', 'type' => 'integer'],
        ];

        foreach ($textSettings as $key => $meta) {
            if ($request->has($key)) {
                $value = $request->input($key);
                if ($meta['type'] === 'boolean') {
                    $value = $value ? 'true' : 'false';
                }
                GlobalSetting::set($key, $value, $meta['type'], $meta['group']);
            }
        }

        return back()->with('success', 'Global system parameters have been successfully reconfigured.');
    }
}
