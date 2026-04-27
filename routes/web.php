<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ServiceController;
use App\Http\Controllers\Public\PortfolioController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\PageController;
// use App\Http\Controllers\Public\CostEstimatorController; // Removed - Cost Analysis feature removed
use App\Http\Controllers\Public\FAQController as PublicFAQController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'show'])->defaults('slug', 'about')->name('about');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/portfolio/{portfolio:slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/insights', [BlogController::class, 'index'])->name('insights');
Route::get('/insights/{post:slug}', [BlogController::class, 'show'])->name('insights.show');
Route::get('/faqs', [PublicFAQController::class, 'index'])->name('faqs');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

use App\Http\Controllers\Public\NewsletterController;
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Project Estimator (Public)
use App\Http\Controllers\Public\ProjectEstimateController as PublicEstimateController;
Route::get('/estimate', [PublicEstimateController::class, 'index'])->name('estimate.index');
Route::get('/estimate/fetch/{portfolio}', [PublicEstimateController::class, 'fetch'])->name('estimate.fetch');
Route::post('/estimate/calculate', [PublicEstimateController::class, 'calculate'])->name('estimate.calculate');
Route::post('/estimate/save', [PublicEstimateController::class, 'store'])->name('estimate.save');

use App\Http\Controllers\Public\LeadController as PublicLeadController;
use App\Http\Controllers\Public\InquiryController;
Route::post('/contact/submit', [InquiryController::class, 'store'])->name('contact.submit');


// Route::get('/estimate', [CostEstimatorController::class, 'index'])->name('estimate.index');
// Route::post('/estimate', [CostEstimatorController::class, 'calculate'])->name('estimate.calculate');
// Route::post('/estimate/live', [CostEstimatorController::class, 'calculateLive'])->name('estimate.live');

// Dynamic Pages (Catch-all)
Route::redirect('/dashboard', '/admin/dashboard');
Route::get('/p/{page:slug}', [PageController::class, 'show'])->name('pages.show');

// Admin Group
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Include Admin Routes
    require base_path('routes/admin.php');
});

require __DIR__ . '/auth.php';
