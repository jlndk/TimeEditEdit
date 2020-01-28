<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;

Route::view('/', 'index');
Route::get('/{calid}', [CalendarController::class, 'show']);
