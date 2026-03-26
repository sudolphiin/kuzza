<?php

use App\InfixModuleManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

if (moduleStatusCheck('Saas')) {
    Route::group(['middleware' => ['subdomain'], 'domain' => '{subdomain}.' . config('app.short_url')], function ($routes) {
        require 'tenant.php';
    });

    Route::group(['middleware' => ['subdomain'], 'domain' => '{subdomain}'], function ($routes) {
        require 'tenant.php';
    });
}

Route::group(['middleware' => ['subdomain']], function ($routes) {
    require 'tenant.php';
});

Route::get('migrate', function () {
    if (Auth::check() && Auth::id() == 1) {
        Artisan::call('migrate', ['--force' => true]);
        Brian2694\Toastr\Facades\Toastr::success('Migration run successfully');

        return redirect()->to(url('/admin-dashboard'));
    }
    abort(404);
});

Route::post('editor/upload-file', 'UploadFileController@upload_image');
// Route::get('hide-routes',[HomeController::class,'hideRoute']);

Route::middleware(['subdomain'])->get('/', 'LandingController@index')->name('/');
Route::middleware(['subdomain', 'throttle:10,1'])->post('landing/get-started', 'LandingController@submitGetStarted')->name('landing.get-started');

// Item assignment routes (legacy helper + redirect into new admin flow)
Route::middleware(['auth'])->group(function () {
    Route::get('items', 'ItemAssignmentController@items')->name('items.list');
    Route::get('parents/search', 'ItemAssignmentController@searchParents')->name('parents.search');

    // When clicking from the admin dashboard menu, send admins to the new Assign Items UI.
    Route::get('items/assign', function () {
        return redirect()->route('assign-items-to-student');
    })->name('items.assign');

    // Existing JSON/API endpoint for direct item-to-parent assignment remains available on POST.
    Route::post('items/assign', 'ItemAssignmentController@assign')->name('items.assign.store');

    Route::get('parents/{parent}/assigned-items', 'ItemAssignmentController@assignedItems')->name('parents.assigned.items');
    // Parent-facing demo shop & checkout (URI adjusted to avoid Apache serving the physical /parents folder)
    Route::get('parent-items/shop', 'ItemAssignmentController@shop')->name('parents.items.shop');
    Route::get('parent-items/checkout', 'ItemAssignmentController@checkoutPage')->name('parents.items.checkoutPage');
    Route::post('parent-items/checkout', 'ItemAssignmentController@checkout')->name('parents.items.checkout');
    Route::post('parent-items/process-checkout', 'ItemAssignmentController@processCheckout')->name('parents.items.processCheckout');
});
