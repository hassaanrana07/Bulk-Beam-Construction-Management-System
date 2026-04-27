<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        NewsletterSubscription::updateOrCreate(
            ['email' => $request->email],
            [
                'status' => 'active',
                'ip_address' => $request->ip()
            ]
        );

        return Redirect::back()->with('success', 'Operational intelligence subscription confirmed. Welcome to Matrix Core.');
    }
}
