<?php

use Fieroo\Events\Controllers\CouponController;
use Illuminate\Support\Facades\Route;
use Fieroo\Events\Controllers\EventController;

Route::group(['prefix' => 'admin', 'middleware' => ['web','auth']], function() {
    Route::resource('/events', EventController::class);
    // Route::get('/events/{id}/subscription', [EventController::class, 'subscription']);
    Route::get('/events/{id}/furnishes', [EventController::class, 'indexFurnishings']);
    Route::get('/events/{id}/exhibitor/{exhibitor_id}/recap-furnishings', [EventController::class, 'recapFurnishings']);
    Route::get('/events/{id}/exhibitors', [EventController::class, 'indexExhibitors']);
    //Route::post('/events/furnishes', [EventController::class, 'confirmFurnishings']);
    Route::post('/events/{id}/stands/getSelectList', [EventController::class, 'getStandSelectList']);
    Route::group(['prefix' => 'export'], function() {
        Route::get('/events/exhibitors', [EventController::class, 'exportEventsExhibitors']);
        Route::get('/event/{id}/exhibitors', [EventController::class, 'exportEventExhibitors']);
    });
    Route::resource('/coupons', CouponController::class);
});
