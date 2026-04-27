<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Custom Building',
                'slug' => 'custom-building',
                'short_description' => 'Architectural excellence meets master craftsmanship in our custom home builds.',
                'description' => 'We specialize in luxury residential construction, turning your vision into a structural reality. Our team handles everything from foundation to the final polish.',
                'is_featured' => true,
                'status' => 'published',
                'structural_type' => 'Private Residential',
                'capability_tools' => ['AutoCAD', 'Revit', 'BIM'],
                'capability_features' => ['Energy Efficiency', 'Architectural Integrity', 'Personalized Design'],
                'capability_deliverables' => ['Blueprints', 'Material Selection', 'Precision Building Phase'],
                'operations_description' => 'Defining vision and budget, blueprints and material selection, precision building phase.',
                'operations_timeline' => '24-48 weeks',
                'operations_team' => 'Design & Engineering Elite Unit',
                'featured_image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=2070',
            ],
            [
                'title' => 'Commercial Renovation',
                'slug' => 'commercial-renovation',
                'short_description' => 'Transforming workspaces into high-performance environments.',
                'description' => 'From retail fit-outs to corporate office overhauls, we deliver commercial spaces that inspire productivity and brand confidence.',
                'is_featured' => true,
                'status' => 'published',
                'structural_type' => 'Corporate Infrastructure',
                'capability_tools' => ['Project Management', 'Asset Tracking', 'Steel Frame Logic'],
                'capability_features' => ['Modern Aesthetics', 'Compliance Standards', 'Minimal Downtime'],
                'capability_deliverables' => ['Structural Overhaul', 'Optimization of Architectural Environments', 'Strategic Feasibility Audits'],
                'operations_description' => 'Strategic commander of structural logistics and executive engineering protocols.',
                'operations_timeline' => '12-24 weeks',
                'operations_team' => 'Industrial Construction Lead',
                'featured_image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=2069',
            ],
            [
                'title' => 'Quality Renovation',
                'slug' => 'quality-renovation',
                'short_description' => 'Precision structural overhauls and optimization of existing architectural environments.',
                'description' => 'Our renovation services focus on structural integrity and modern aesthetic upgrades for aging assets.',
                'is_featured' => true,
                'status' => 'published',
                'structural_type' => 'Technical Overhaul',
                'capability_tools' => ['Laser Scanning', 'Structural Analysis', 'Asset Audit'],
                'capability_features' => ['Preservation of Heritage', 'Integration of Modern Systems', 'Structural Strengthening'],
                'capability_deliverables' => ['Renovation Roadmap', 'Material Inventory', 'Implementation Analysis'],
                'operations_description' => 'High-performance execution of structural renewals.',
                'operations_timeline' => '8-16 weeks',
                'operations_team' => 'Renovation Specialist Squad',
                'featured_image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=2071',
            ],
            [
                'title' => 'Structural Design',
                'slug' => 'structural-design',
                'short_description' => 'Strategic feasibility audits and project management for enterprise clients.',
                'description' => 'Engineering precision at scale for high-rise development and public infrastructure.',
                'is_featured' => true,
                'status' => 'published',
                'structural_type' => 'Engineering Matrix',
                'capability_tools' => ['Structural FEA', 'Civil 3D', 'Infrastructure Pro'],
                'capability_features' => ['Seismic Optimization', 'Load Bearing Analysis', 'Precision Blueprints'],
                'capability_deliverables' => ['Technical Specifications', 'Engineering Blueprints', 'Structural Audit'],
                'operations_description' => 'Precision structural analysis and engineering for high-rise development.',
                'operations_timeline' => '4-12 weeks',
                'operations_team' => 'Principal Engineering Unit',
                'featured_image' => 'https://images.unsplash.com/photo-1504917595217-d4dc5f566fab?q=80&w=2070',
            ],
        ];

        foreach ($services as $service) {
            $existing = Service::withTrashed()->where('slug', $service['slug'])->first();
            if ($existing) {
                if ($existing->trashed()) $existing->restore();
                $existing->update($service);
            } else {
                Service::create($service);
            }
        }
    }
}
