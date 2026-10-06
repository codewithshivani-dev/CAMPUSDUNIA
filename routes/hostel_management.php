<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HostelManagement\BuildingController;
use App\Http\Controllers\HostelManagement\BlockController;
use App\Http\Controllers\HostelManagement\FloorController;
use App\Http\Controllers\HostelManagement\RoomController;

Route::prefix('hostel-management')->name('hostel.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Building Management
    |--------------------------------------------------------------------------
    */

    Route::get('/buildings', [BuildingController::class, 'index'])
        ->name('buildings.index');

    Route::get('/buildings/create', [BuildingController::class, 'create'])
        ->name('buildings.create');

    Route::post('/buildings', [BuildingController::class, 'store'])
        ->name('buildings.store');


    /*
    |--------------------------------------------------------------------------
    | Block Management
    |--------------------------------------------------------------------------
    */

    Route::get('/blocks', [BlockController::class, 'index'])
        ->name('blocks.index');

    Route::get('/blocks/create', [BlockController::class, 'create'])
        ->name('blocks.create');

    Route::post('/blocks', [BlockController::class, 'store'])
        ->name('blocks.store');


    /*
    |--------------------------------------------------------------------------
    | Floor Management
    |--------------------------------------------------------------------------
    */

    Route::get('/floors', [FloorController::class, 'index'])
        ->name('floors.index');

    Route::get('/floors/create', [FloorController::class, 'create'])
        ->name('floors.create');

    Route::post('/floors', [FloorController::class, 'store'])
        ->name('floors.store');


    /*
    |--------------------------------------------------------------------------
    | Room Management
    |--------------------------------------------------------------------------
    */

    Route::get('/rooms', [RoomController::class, 'index'])
        ->name('rooms.index');

    Route::get('/rooms/create', [RoomController::class, 'create'])
        ->name('rooms.create');

    Route::post('/rooms', [RoomController::class, 'store'])
        ->name('rooms.store');

});