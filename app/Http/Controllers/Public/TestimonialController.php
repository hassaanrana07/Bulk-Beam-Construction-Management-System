<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Inertia\Inertia;

class TestimonialController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Testimonials', [
            'testimonials' => Testimonial::where('is_published', true)->orderBy('order')->get()
        ]);
    }
}
