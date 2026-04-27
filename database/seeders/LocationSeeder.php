<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name' => 'Main Headquarters',
                'address' => '123 Industrial Way',
                'city' => 'Chicago',
                'state' => 'IL',
                'zip_code' => '60601',
                'phone' => '+1 (312) 555-0199',
                'email' => 'hq@brickbeam.com',
                'is_primary' => true,
                'hours' => ['Mon-Fri' => '8:00 AM - 6:00 PM', 'Sat' => '10:00 AM - 2:00 PM'],
            ],
            [
                'name' => 'Texas Branch',
                'address' => '456 Tech Ridge',
                'city' => 'Austin',
                'state' => 'TX',
                'zip_code' => '78701',
                'phone' => '+1 (512) 555-0122',
                'email' => 'tx@brickbeam.com',
                'is_primary' => false,
            ],
        ];

        foreach ($locations as $loc) {
            Location::create($loc);
        }
    }
}
