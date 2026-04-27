<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Inertia\Inertia;

class PageController extends Controller
{
    public function show($slug = 'home')
    {
        $page = Page::where('slug', $slug)->where('status', 'published')->firstOrFail();

        $page->load([
            'contentSections' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }
        ]);

        // Map relationship to attribute for frontend compatibility
        $page->setRelation('sections', $page->contentSections);

        $data = [
            'page' => $page
        ];

        if ($slug === 'about') {
            $data['team'] = \App\Models\Staff::where('is_active', true)
                ->where('is_public_visible', true)
                ->orderBy('order')
                ->get();
            $data['certifications'] = \App\Models\Certification::where('is_active', true)
                ->where('is_public_visible', true)
                ->orderBy('order')
                ->get();
        }

        return Inertia::render('Public/Page', $data);
    }
}
