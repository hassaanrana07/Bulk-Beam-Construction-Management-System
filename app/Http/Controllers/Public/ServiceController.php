<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $page = \App\Models\Page::where('slug', 'services')->where('status', 'published')->first();

        if ($page) {
            $page->load([
                'contentSections' => function ($query) {
                    $query->where('is_active', true)->orderBy('order');
                }
            ]);
            $page->setRelation('sections', $page->contentSections);
        }

        return Inertia::render('Public/Services/Index', [
            'page' => $page,
            'services' => Service::where('status', 'published')
                ->where('is_public_visible', true)
                ->where('is_public', true)
                ->latest()
                ->get(),
            'estimation_rules' => \App\Models\CostEstimatorRule::where('is_active', true)->orderBy('order')->get()
        ]);
    }

    public function show(Service $service)
    {
        if (!$service->is_public_visible || $service->status !== 'published') {
            abort(404);
        }

        return Inertia::render('Public/Services/Show', [
            'service' => $service,
            'related_services' => Service::where('id', '!=', $service->id)
                ->where('is_public_visible', true)
                ->where('status', 'published')
                ->take(3)
                ->get()
        ]);
    }
}
