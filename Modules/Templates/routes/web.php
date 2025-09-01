<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Modules\Templates\App\Http\Controllers\Admin\EmailTemplatesController;
use Modules\Templates\App\Http\Controllers\Admin\EmailTriggerController;
use Modules\Templates\App\Http\Controllers\Admin\MediaGalleryController;
use Modules\Templates\App\Http\Controllers\Admin\EmailBuilder\EmailBuilderController;
use Modules\Templates\Livewire\Trigger\EmailTemplateList;

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
Route::group([], function () {
    // Route::resource('templates', TemplatesController::class)->names('templates');
    Route::prefix('admin')->as('admin.')->group(function () {

        Route::get('/email-templates', [EmailTemplatesController::class, 'index'])->name('templates.index');
        //Email Builder Routes
        Route::get('/email-builder', [EmailBuilderController::class, 'index'])->name('email-builder');
        Route::post('/email-builder/save-template', [EmailBuilderController::class, 'saveTemplate'])->name('email-builder.save-template');
        Route::post('/email-builder/update-template', [EmailBuilderController::class, 'updateTemplate'])->name('email-builder.update-template');
        # MEDIA GALLERY ROUTES
        Route::prefix('media-gallery')->as('media.')->group(function () {
            Route::get('/list', [MediaGalleryController::class, 'index'])->name('index');
            Route::get('/create', [MediaGalleryController::class, 'create'])->name('create');
            Route::post('/store', [MediaGalleryController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [MediaGalleryController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [MediaGalleryController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [MediaGalleryController::class, 'destroy'])->name('destroy');
        });

        # EMAIL TRIGGER ROUTES
        Route::prefix('email-trigger')->as('email.trigger.')->group(function () {
            Route::get('/list', [EmailTriggerController::class, 'index'])->name('index');
            Route::get('/edit/{trigger}', [EmailTriggerController::class, 'edit'])->name('edit');
        });        
    });
    
});
