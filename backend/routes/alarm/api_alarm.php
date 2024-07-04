<?php

use App\Http\Controllers\Alarm\Api\ApiAlarmControlController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AnnouncementsCategoriesController;
use App\Http\Controllers\Customers\CustomersController;
use App\Models\Customers\Customers;
use Illuminate\Support\Facades\Route;

// announcement
//Route::apiResource('announcement', AnnouncementController::class);
// Route::get('announcement_list', [AnnouncementController::class, 'annoucement_list']);
// Route::get('announcement/search/{key}', [AnnouncementController::class, 'search']);
//Route::get('alarm_device_status', [ApiAlarmControlController::class, 'LogDeviceStatus']);
//Route::get('announcement/employee/{id}', [AnnouncementController::class, 'getAnnouncement']);


Route::apiResource('customers', CustomersController::class);
Route::get('building_types', [CustomersController::class, 'buildingTypes']);
Route::get('address_types', [CustomersController::class, 'addressTypes']);

Route::post('customers_primary_contact_update', [CustomersController::class, 'updatePrimaryContacts']);
Route::post('customers_secondary_contact_update', [CustomersController::class, 'updateSecondaryContacts']);
Route::post('customers_building_contact_update', [CustomersController::class, 'updatebuildingContacts']);
Route::post('customer-update', [CustomersController::class, 'updateCustomer']);
Route::post('customers_contact_update', [CustomersController::class, 'updateCustomerContact']);
