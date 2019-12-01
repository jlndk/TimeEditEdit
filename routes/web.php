<?php

use App\Http\Controllers\FrontPageController;
use App\Http\Controllers\CalendarController;

Route::get('/', [FrontPageController::class, 'index']);
Route::get('/{calid}', [CalendarController::class, 'show']);