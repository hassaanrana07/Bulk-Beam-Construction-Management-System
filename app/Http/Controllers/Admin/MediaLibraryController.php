<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
class MediaLibraryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Media/Index');
    }
    public function store()
    {
        return back();
    }
}
