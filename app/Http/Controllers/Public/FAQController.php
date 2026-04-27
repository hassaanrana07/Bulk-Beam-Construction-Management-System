<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\Page;
use Inertia\Inertia;

class FAQController extends Controller
{
    public function index()
    {
        $page = Page::where('slug', 'faqs')->first();
        if ($page) {
            $page->load(['contentSections' => fn($q) => $q->where('is_active', true)->orderBy('order')]);
            $page->setRelation('sections', $page->contentSections);
        }

        return Inertia::render('Public/FAQ', [
            'page' => $page,
            'faqs' => FAQ::where('is_published', true)
                ->where('is_public_visible', true)
                ->orderBy('order')
                ->get()
        ]);
    }
}
