<?php

namespace Database\Seeders;

use App\Models\Lead;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $leads = [
            [
                'name' => 'Alexander Sterling',
                'email' => 'alex@sterlingassets.com',
                'phone' => '+1 555-0102',
                'company' => 'Sterling Realty Group',
                'message' => 'Interested in a new commercial development project in downtown Miami.',
                'status' => 'converted',
                'created_at' => now()->subMonths(2),
            ],
            [
                'name' => 'Sophia Chen',
                'email' => 'sophia@chen-interiors.com',
                'phone' => '+1 555-0103',
                'company' => 'Chen Luxury Living',
                'message' => 'Requesting a quote for a high-end residential build.',
                'status' => 'new',
                'created_at' => now()->subDays(5),
            ],
            [
                'name' => 'Marcus Thorne',
                'email' => 'm.thorne@thornlogistics.com',
                'phone' => '+1 555-0104',
                'company' => 'Thorne Global Logistics',
                'message' => 'Looking for a sustainable warehouse solution in New Jersey.',
                'status' => 'contacted',
                'created_at' => now()->subMonths(1),
            ],
            [
                'name' => 'Isabella Rossi',
                'email' => 'isabella@rossidesigns.it',
                'phone' => '+39 02 123 4567',
                'company' => 'Rossi & Co',
                'message' => 'Collaborative opportunity for a European expansion project.',
                'status' => 'new',
                'created_at' => now()->subDays(2),
            ],
            [
                'name' => 'Julian Blackwell',
                'email' => 'jb@blackwellcap.com',
                'phone' => '+1 555-0105',
                'company' => 'Blackwell Capital',
                'message' => 'Evaluating industrial park developers for our next venture.',
                'status' => 'converted',
                'created_at' => now()->subMonths(3),
            ],
        ];

        foreach ($leads as $lead) {
            Lead::create($lead);
        }

        // Add some random leads for the chart
        for ($i = 0; $i < 20; $i++) {
            Lead::create([
                'name' => 'Lead ' . $i,
                'email' => 'lead' . $i . '@example.com',
                'phone' => '555-00' . $i,
                'message' => 'Random inquiry message',
                'status' => collect(['new', 'contacted', 'converted', 'rejected'])->random(),
                'created_at' => now()->subMonths(rand(0, 5))->subDays(rand(1, 28)),
            ]);
        }
    }
}
