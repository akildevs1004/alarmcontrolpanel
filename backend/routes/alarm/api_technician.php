<?php

use App\Http\Controllers\Customers\TechnicianLoginsController;
use App\Http\Controllers\TicketsController;
use Illuminate\Support\Facades\Route;

Route::apiResource('technicians', TechnicianLoginsController::class);

Route::apiResource('tickets', TicketsController::class);
