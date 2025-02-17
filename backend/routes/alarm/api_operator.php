<?php


use App\Http\Controllers;
use App\Http\Controllers\AlarmLogsController;
use App\Http\Controllers\AlarmSensorTypesController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AnnouncementsCategoriesController;

use App\Http\Controllers\Customers\Alarm\AlarmNotificationController;
use App\Http\Controllers\Customers\Alarm\DeviceArmedLogsController;
use App\Http\Controllers\Customers\AlarmDashboard;
use App\Http\Controllers\Customers\AlarmDashboardController;
use App\Http\Controllers\Customers\AlarmDeviceTemperatureLogsController;
use App\Http\Controllers\Customers\Api\ApiAlarmDeviceSensorLogsController;
use App\Http\Controllers\Customers\Api\ApiAlarmDeviceTemperatureLogsController;
use App\Http\Controllers\Customers\CustomerAlarmEventsController;
use App\Http\Controllers\Customers\CustomerBuildingPhotosController;
use App\Http\Controllers\Customers\CustomerBuildingPicturesController;
use App\Http\Controllers\Customers\CustomerCamerasController;
use App\Http\Controllers\Customers\CustomerContactsController;
use App\Http\Controllers\Customers\CustomerPaymentsController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\Customers\Reports\AlarmReportsController;
use App\Http\Controllers\Customers\SecurityLoginController;
use App\Http\Controllers\DeviceNotificationsManagersController;
use App\Http\Controllers\DeviceZonesController;
use App\Http\Controllers\DeviceZoneTypesController;
use App\Http\Controllers\PlottingController;
use App\Http\Controllers\RolePermissionsController;
use App\Models\AlarmLogs;
use App\Models\Customers\Customers;
use App\Models\Customers\SecurityLogin;
use App\Models\DeviceArmedLogs;
use App\Models\MapKey;
use Illuminate\Support\Facades\Route;






//security dashboard
Route::get('security_device_alarm_burglary_stats', [AlarmDashboardController::class, 'alarmEventBurglaryStatistics']);
Route::get('security_device_alarm_medical_stats', [AlarmDashboardController::class, 'alarmEventMedicalStatistics']);
Route::get('security_device_alarm_water_stats', [AlarmDashboardController::class, 'alarmEventWaterStatistics']);
Route::get('security_device_alarm_temperature_stats', [AlarmDashboardController::class, 'alarmEventTemperatureStatistics']);
Route::get('security_device_alarm_fire_stats', [AlarmDashboardController::class, 'alarmEventFireStatistics']);
Route::get('buildingtype_customer_stats', [AlarmDashboardController::class, 'buildingtypeCustomerStats']);
Route::get('customers_accounts_expire_stats', [AlarmDashboardController::class, 'customersAccountsExpireStats']);
