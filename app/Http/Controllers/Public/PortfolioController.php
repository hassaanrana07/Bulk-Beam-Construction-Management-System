<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index()
    {
        $page = \App\Models\Page::where('slug', 'portfolio')->where('status', 'published')->first();

        if ($page) {
            $page->load([
                'contentSections' => function ($query) {
                    $query->where('is_active', true)->orderBy('order');
                }
            ]);
            $page->setRelation('sections', $page->contentSections);
        }

        return Inertia::render('Public/Portfolio/Index', [
            'page' => $page,
            'projects' => Portfolio::where('status', 'published')
                ->where('is_public_visible', true)
                ->where('is_public', true)
                ->latest()
                ->get()
                ->map(function ($project) {
                    return [
                        'id' => $project->id,
                        'title' => $project->title,
                        'slug' => $project->slug,
                        'short_description' => $project->short_description,
                        'location' => $project->location,
                        'featured_image' => $project->featured_image,
                        'project_type' => $project->project_type,
                    ];
                })
        ]);
    }

    public function show(Portfolio $portfolio)
    {
        if (!$portfolio->is_public_visible || $portfolio->status !== 'published') {
            abort(404);
        }

        $project = [
            'id' => $portfolio->id,
            'title' => $portfolio->title,
            'slug' => $portfolio->slug,
            'description' => $portfolio->description,
            'short_description' => $portfolio->short_description,
            'location' => $portfolio->location,
            'project_type' => $portfolio->project_type,
            'start_date' => $portfolio->start_date,
            'completion_date' => $portfolio->completion_date,
            'execution_status' => $portfolio->execution_status,
            'featured_image' => $portfolio->featured_image,
            'gallery' => $portfolio->gallery,
            // Case Study fields
            'case_study_category' => $portfolio->case_study_category,
            'case_study_scope' => $portfolio->case_study_scope,
            'case_study_sector' => $portfolio->case_study_sector,
            'cs_phase_1' => $portfolio->cs_phase_1,
            'cs_phase_2' => $portfolio->cs_phase_2,
            'cs_phase_3' => $portfolio->cs_phase_3,
            'cs_phase_4' => $portfolio->cs_phase_4,
            'cs_phase_5' => $portfolio->cs_phase_5,
            'cs_duration_weeks' => $portfolio->cs_duration_weeks,
            'cs_team' => $portfolio->cs_team,
            'cs_total_value' => $portfolio->cs_total_value,
            // Structure Analysis
            'structural_features' => $portfolio->structural_features,
            'base_structure' => $portfolio->base_structure,
            'foundation_type' => $portfolio->foundation_type,
            'total_floors' => $portfolio->total_floors,
            'floor_composition' => $portfolio->floor_composition,
            'capabilities' => $portfolio->capabilities,
            'functional_features' => $portfolio->functional_features,
            'technology_used' => $portfolio->technology_used,
            'construction_technology' => $portfolio->construction_technology,
            'tools_used' => $portfolio->tools_used,
            'framework_type' => $portfolio->framework_type,
        ];

        return Inertia::render('Public/Portfolio/Show', [
            'project' => $project
        ]);
    }
}
