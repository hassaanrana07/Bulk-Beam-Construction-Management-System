<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Testimonial;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'home')->firstOrFail();
        $page->load([
            'contentSections' => function ($q) {
                $q->where('is_active', true)->orderBy('order');
            }
        ]);
        $page->setRelation('sections', $page->contentSections);

        return Inertia::render('Public/Home', [
            'page' => $page,
            'featured_services' => Service::where('is_featured', true)
                ->where('status', 'published')
                ->where('is_public_visible', true)
                ->where('is_public', true)
                ->get(),
            'projects' => Portfolio::where('status', 'published')
                ->where('is_public_visible', true)
                ->where('is_public', true)
                ->get()->map(fn($p) => [
                    'id' => $p->id,
                    'title' => $p->title,
                    'slug' => $p->slug,
                    'location' => $p->location,
                    'short_description' => $p->short_description,
                    'featured_image' => $p->featured_image,
                    'project_type' => $p->project_type,
                    'is_featured' => $p->is_featured,
                    'budget' => '$' . number_format($p->total_budget / 1000000, 1) . 'M',
                ]),
            'testimonials' => Testimonial::where('is_published', true)
                ->where('is_public_visible', true)
                ->orderBy('order')
                ->get(),
        ]);
    }
}
