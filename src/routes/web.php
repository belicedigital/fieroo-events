<?php
use Illuminate\Support\Facades\Route;
use Fieroo\Events\Controllers\EventController;

Route::group(['prefix' => 'admin', 'middleware' => ['web','auth']], function() {
    Route::resource('/events', EventController::class);
    // Route::get('/events/{id}/subscription', [EventController::class, 'subscription']);
    Route::get('/events/{id}/furnishes', [EventController::class, 'indexFurnishings']);
    Route::get('/events/{id}/exhibitor/{exhibitor_id}/recap-furnishings', [EventController::class, 'recapFurnishings']);
    Route::get('/events/{id}/exhibitors', [EventController::class, 'indexExhibitors']);
    //Route::post('/events/furnishes', [EventController::class, 'confirmFurnishings']);
});