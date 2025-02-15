<?php

use App\Http\Controllers\Customers\TechnicianLoginsController;
use App\Http\Controllers\Customers\TicketCategoriesController;
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

Route::get("technician_tickets_stats", [TicketsController::class, 'technicianDashboardTicketStats']);
Route::get("technician_tickets_monthly_stats", [TicketsController::class, 'technicianDashboardMonthlyTicketStats']);
Route::get("technician_tickets_today_stats", [TicketsController::class, 'technicianDashboardTodayTicketStats']);


Route::get("technician_tickets_pending_days_stats", [TicketsController::class, "technicianDashboardPendingMorethanDaysStats"]);

Route::get("tickets_unread_notifications", [TicketsController::class, "ticketsUnreadNotifications"]);

Route::post("technician_start_job", [TicketsController::class, "TechnicianStartJob"]);
Route::get("ticket_categories", [TicketsController::class, "TicketCategories"]);
