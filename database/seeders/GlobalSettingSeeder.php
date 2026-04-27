<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use Illuminate\Database\Seeder;

class GlobalSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'Brick & Beam', 'type' => 'string', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Premium Residential and Commercial Construction', 'type' => 'string', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'info@brickbeam.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => '+1 (555) 123-4567', 'type' => 'string', 'group' => 'general'],

            // Theme
            ['key' => 'default_theme', 'value' => 'dark', 'type' => 'string', 'group' => 'theme'],
            ['key' => 'allow_public_toggle', 'value' => 'true', 'type' => 'boolean', 'group' => 'theme'],
            ['key' => 'primary_color', 'value' => '#ff6b00', 'type' => 'string', 'group' => 'theme'],

            // SEO
            ['key' => 'meta_title_suffix', 'value' => '| Brick & Beam Construction', 'type' => 'string', 'group' => 'seo'],
            ['key' => 'google_analytics_id', 'value' => '', 'type' => 'string', 'group' => 'seo'],

            // Lead Quality
            ['key' => 'min_lead_score_to_notify', 'value' => '50', 'type' => 'integer', 'group' => 'leads'],
        ];

        foreach ($settings as $setting) {
            GlobalSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
