<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Staff;
use App\Models\Blog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('search');

        if (!$query) {
            return redirect()->back();
        }

        $portfolios = Portfolio::where('title', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'status' => $item->execution_status,
                    'type' => 'Portfolio',
                    'route' => route('admin.portfolios.index', ['search' => $item->title]) // Redirect to list with search for better UX
                ];
            });

        $services = Service::where('title', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'type' => 'Service',
                    'route' => route('admin.services.index', ['search' => $item->title])
                ];
            });

        $staff = Staff::where('name', 'like', "%{$query}%")
            ->orWhere('role', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'type' => 'Staff',
                    'route' => route('admin.staff.index', ['search' => $item->name])
                ];
            });

        $blogs = Blog::where('title', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->title,
                    'type' => 'Blog',
                    'route' => route('admin.blog.index', ['search' => $item->title])
                ];
            });

        $results = $portfolios->concat($services)->concat($staff)->concat($blogs);

        return Inertia::render('Admin/Search/Results', [
            'results' => $results->values()->all(),
            'query' => $query
        ]);
    }
}
