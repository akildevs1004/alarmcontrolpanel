<?php

use App\Http\Controllers\Customers\TechnicianLoginsController;
use App\Http\Controllers\Customers\TicketResponsesController;
use App\Http\Controllers\Customers\TicketsController;
use App\Models\Customers\TicketResponses;
use Illuminate\Support\Facades\Route;

Route::apiResource('technicians', TechnicianLoginsController::class);

Route::apiResource('tickets', TicketsController::class);

Route::apiResource('tickets_responses', TicketResponsesController::class);
Route::get('download_ticket_atachment/{id}/{file_name}', [TicketsController::class, 'downloadTicketAttachment']);
Route::get('download_ticket_response_atachment/{id}/{file_name}', [TicketResponsesController::class, 'downloadTicketAttachment']);


Route::post('update_ticket_read_status', [TicketResponsesController::class, 'updateTicketReadStatus']);
