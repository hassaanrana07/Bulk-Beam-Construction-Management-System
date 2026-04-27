<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\GlobalSearchController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/system-metrics', [DashboardController::class, 'systemMetrics'])->name('system-metrics');
Route::get('/search', [GlobalSearchController::class, 'search'])->name('search');

Route::resource('pages', PageController::class);
Route::resource('services', ServiceController::class);

// Portfolio Special Routes (Must be above resource to avoid being caught by {portfolio} wildcard)
Route::match(['get', 'post'], '/portfolios/bulk-pdf', [PortfolioController::class, 'bulkPdf'])->name('portfolios.bulk-pdf');
Route::get('/portfolios/{portfolio}/pdf', [PortfolioController::class, 'generatePdf'])->name('portfolios.pdf');
Route::resource('portfolios', PortfolioController::class);

Route::resource('staff', StaffController::class);
Route::resource('leads', LeadController::class)->only(['index', 'show', 'update', 'destroy']);
Route::resource('inquiries', InquiryController::class)->only(['index', 'update', 'destroy']);
Route::resource('estimates', \App\Http\Controllers\Admin\ProjectEstimateController::class);

Route::resource('blog', BlogController::class);
Route::resource('blog-categories', BlogCategoryController::class);
Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class);
Route::resource('faqs', \App\Http\Controllers\Admin\FAQController::class);

Route::get('/media', [MediaLibraryController::class, 'index'])->name('media.index');
Route::post('/media/upload', [MediaLibraryController::class, 'store'])->name('media.upload');

// Finance Modules
Route::get('/finance', [App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('finance.index');
Route::get('/revenue', [App\Http\Controllers\Admin\RevenueController::class, 'index'])->name('revenue.index');
Route::get('/finance/report', [App\Http\Controllers\Admin\FinanceController::class, 'report'])->name('finance.report');
Route::post('/finance/report', [App\Http\Controllers\Admin\FinanceController::class, 'report']);


Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
Route::resource('tasks', \App\Http\Controllers\Admin\TaskController::class);
Route::resource('milestones', \App\Http\Controllers\Admin\MilestoneController::class);
// Route::post('/attendances/clock-in', [\App\Http\Controllers\Admin\AttendanceController::class, 'clockIn'])->name('attendances.clock-in');
// Route::post('/attendances/clock-out', [\App\Http\Controllers\Admin\AttendanceController::class, 'clockOut'])->name('attendances.clock-out');
// Route::resource('attendances', \App\Http\Controllers\Admin\AttendanceController::class);
Route::resource('purchase-requests', \App\Http\Controllers\Admin\PurchaseRequestController::class);
Route::resource('certifications', \App\Http\Controllers\Admin\CertificationController::class);
Route::resource('announcements', \App\Http\Controllers\Admin\AnnouncementController::class);
Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
Route::get('/audit-logs/export/csv', [\App\Http\Controllers\Admin\AuditLogController::class, 'exportCsv'])->name('audit-logs.export-csv');

Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class);
Route::resource('expenses', \App\Http\Controllers\Admin\ExpenseController::class);
Route::get('/expenses/export/csv', [\App\Http\Controllers\Admin\ExpenseController::class, 'exportCsv'])->name('expenses.export-csv');
Route::get('/expenses/export/pdf', [\App\Http\Controllers\Admin\ExpenseController::class, 'exportPdf'])->name('expenses.export-pdf');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
Route::post('/settings/configs', [SettingsController::class, 'updateConfigs'])->name('settings.configs');

// // Estimate Settings Module
// Route::get('/estimate-settings', [\App\Http\Controllers\Admin\EstimateSettingsController::class, 'index'])->name('estimate-settings.index');
// Route::post('/estimate-settings', [\App\Http\Controllers\Admin\EstimateSettingsController::class, 'store'])->name('estimate-settings.store');
// Route::put('/estimate-settings/{estimateRule}', [\App\Http\Controllers\Admin\EstimateSettingsController::class, 'update'])->name('estimate-settings.update');
// Route::delete('/estimate-settings/{estimateRule}', [\App\Http\Controllers\Admin\EstimateSettingsController::class, 'destroy'])->name('estimate-settings.destroy');
