<?php

use Illuminate\Support\Facades\Route;
use Larabuild\Pagebuilder\Http\Controllers\PageBuilderController;
use Larabuild\Pagebuilder\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$page = Route::controller(PageController::class);
$pBuilder = Route::controller(PageBuilderController::class);

if (! empty(config('pagebuilder.url_prefix'))) {
    $page = $page->prefix(config('pagebuilder.url_prefix'));
    $pBuilder = $pBuilder->prefix(config('pagebuilder.url_prefix'));
}

if (! empty(config('pagebuilder.route_middleware'))) {
    $page = $page->middleware(config('pagebuilder.route_middleware'));
    $pBuilder = $pBuilder->middleware(config('pagebuilder.route_middleware'));
}

$page->group(function () {
    Route::get('pages', 'index')->name('pagebuilder');
    Route::get('pages/{page}/edit', 'edit')->name('page.edit');
    Route::post('pages', 'store')->name('page.store');
    Route::put('pages/{page}', 'update')->name('page.update');
    Route::get('pages/create', 'create')->name('page.create');
    Route::delete('pages/delete/{id}', 'destroy')->name('page.delete');
    Route::post('status/update', 'statusUpdate')->name('page.status-update');
});

$pBuilder->group(function () {
    Route::get('header/build', 'header')->name('pagebuilder.header');
    Route::get('frontend-reset/{slug}', 'frontendReset')->name('pagebuilder.frontend.reset');

    Route::get('footer/build', 'footer')->name('pagebuilder.footer');
    Route::get('pages/{id}/build', 'build')->name('pagebuilder.build');
    Route::post('pages/{id}/store', 'storeComponentData');
    Route::post('get-section-settings', 'getSettings');
    Route::post('set-section-settings', 'setSectionSettings');
    Route::post('set-page-settings', 'setPageSettings');
    Route::post('get-section-html', 'getPageSectionHtml');
});

Route::get('pages/{id}/iframe', [PageBuilderController::class, 'iframe'])->name('pagebuilder.iframe');

Route::fallback(function () {
    $path = trim(request()->path(), '/');

    // Don't let the pagebuilder fallback intercept API/admin or known backend routes
    // or our items assignment route. If the path matches any of these prefixes
    // we return a 404 so Laravel can handle it appropriately (or show a normal 404).
    $skipPrefixes = ['admin', 'api', 'items', 'item', 'parents', 'students', 'teacher', 'storage', '_debugbar'];
    foreach ($skipPrefixes as $prefix) {
        if ($path === $prefix || str_starts_with($path, $prefix.'/') || str_starts_with($path, $prefix)) {
            abort(404);
        }
    }

    $builder = new PageBuilderController();

    return $builder->renderPage(request()->path());
})->where('/', '.*')->name('pagebuilder.page');

require __DIR__.'/optionbuilder.php';
