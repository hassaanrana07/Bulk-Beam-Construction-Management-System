<?php

namespace Database\Seeders;

use App\Models\SystemConfig;
use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder
{
    public function run()
    {
        $configs = [
            'project_types' => json_encode(['Residential', 'Commercial', 'Industrial', 'Institutional']),
            'material_categories' => json_encode(['Cement', 'Steel', 'Bricks', 'Wood', 'Electrical', 'Plumbing']),
            'status_labels' => json_encode([
                'project' => ['Ongoing', 'Completed', 'Delayed', 'On Hold', 'Canceled'],
                'task' => ['Todo', 'In Progress', 'Completed', 'Blocked'],
                'approval' => ['Pending', 'Manager Approved', 'Finance Processed', 'Rejected']
            ]),
            'currency' => 'USD',
            'date_format' => 'Y-m-d',
        ];

        foreach ($configs as $key => $value) {
            SystemConfig::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
