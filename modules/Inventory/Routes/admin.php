<?php

use Illuminate\Support\Facades\Route;

/**
 * 'admin' middleware and 'inventory' prefix applied to all routes (including names)
 *
 * @see \App\Providers\Route::register
 */

Route::admin('inventory', function () {
    Route::get('/', 'Main@index')->name('index');
    Route::resource('units', 'UnitController');
});
