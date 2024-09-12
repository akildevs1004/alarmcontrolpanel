<?php

use App\Http\Controllers\Customers\TechnicianLoginsController;
use Illuminate\Support\Facades\Route;

Route::apiResource('technicians', TechnicianLoginsController::class);
