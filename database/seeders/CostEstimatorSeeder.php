<?php

namespace Database\Seeders;

use App\Models\CostEstimatorRule;
use Illuminate\Database\Seeder;

class CostEstimatorSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'name' => 'Premium Residential Build',
                'category' => 'Residential',
                'base_rate_per_sqft' => 250,
                'options' => [
                    'smart_home' => ['label' => 'Smart Home Automation', 'type' => 'percentage', 'value' => 15],
                    'solar' => ['label' => 'Solar Energy System', 'type' => 'fixed', 'value' => 45000],
                    'landscaping' => ['label' => 'Luxury Landscaping', 'type' => 'fixed', 'value' => 75000],
                ]
            ],
            [
                'name' => 'Industrial Warehouse',
                'category' => 'Infrastructure',
                'base_rate_per_sqft' => 120,
                'options' => [
                    'hvac' => ['label' => 'Climate Control (Industrial)', 'type' => 'percentage', 'value' => 25],
                    'loading_docks' => ['label' => 'Expanded Loading Docks', 'type' => 'fixed', 'value' => 120000],
                ]
            ],
            [
                'name' => 'Class-A Office Space',
                'category' => 'Commercial',
                'base_rate_per_sqft' => 180,
                'options' => [
                    'glass_facade' => ['label' => 'Full Glass Curtain Wall', 'type' => 'percentage', 'value' => 30],
                    'green_roof' => ['label' => 'Sustainable Green Roof', 'type' => 'fixed', 'value' => 55000],
                ]
            ],
        ];

        foreach ($rules as $rule) {
            CostEstimatorRule::create($rule);
        }
    }
}
