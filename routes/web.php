<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/{type}/{y}/{m}/{d}', [EventController::class, 'index']);
