<?php

namespace App\Http\Middleware;

use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'roles' => $request->user()->getRoleNames(),
                    'permissions' => $request->user()->getAllPermissions()->pluck('name'),
                ] : null,
            ],
            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'estimate' => $request->session()->get('estimate'),
            ],
            'settings' => array_merge(
                GlobalSetting::getByGroup('general'),
                GlobalSetting::getByGroup('theme'),
                GlobalSetting::getByGroup('seo'),
                GlobalSetting::getByGroup('lead'),
                GlobalSetting::getByGroup('company')
            ),
            'leadership' => \App\Models\Staff::where('is_leadership', true)->where('is_active', true)->orderBy('order')->get(),
            'faqs' => \App\Models\FAQ::where('is_published', true)->orderBy('order')->get(),
            'testimonials' => \App\Models\Testimonial::where('is_published', true)->orderBy('order')->get(),
        ];

    }
}
