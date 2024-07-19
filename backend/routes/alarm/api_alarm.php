<?php


use App\Http\Controllers;

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AnnouncementsCategoriesController;
use App\Http\Controllers\Customers\AlarmDashboard;
use App\Http\Controllers\Customers\AlarmDashboardController;
use App\Http\Controllers\Customers\AlarmDeviceTemperatureLogsController;
use App\Http\Controllers\Customers\Api\ApiAlarmDeviceTemperatureLogsController;
use App\Http\Controllers\Customers\CustomerAlarmEventsController;
use App\Http\Controllers\Customers\CustomerBuildingPicturesController;
use App\Http\Controllers\Customers\CustomerContactsController;
use App\Http\Controllers\Customers\CustomerPaymentsController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\DeviceNotificationsManagersController;
use App\Http\Controllers\PlottingController;
use App\Models\Customers\Customers;
use App\Models\MapKey;
use Illuminate\Support\Facades\Route;





Route::apiResource('customers', CustomersController::class);
Route::get('customers-for-map', [CustomersController::class, "customersForMap"]);
Route::get('customers-all', [CustomersController::class, "customersAll"]);

//customer

Route::post('customers_primary_contact_update', [CustomersController::class, 'updatePrimaryContacts']);
Route::post('customers_secondary_contact_update', [CustomersController::class, 'updateSecondaryContacts']);
Route::post('customers_building_contact_update', [CustomersController::class, 'updatebuildingContacts']);
Route::post('customer-update', [CustomersController::class, 'updateCustomer']);
Route::post('customers_contact_update', [CustomersController::class, 'updateCustomerContact']);
Route::apiResource('customers_building_picture', CustomerBuildingPicturesController::class);
Route::apiResource('customer_contact', CustomerContactsController::class);
Route::post('update_customer_settings', [CustomersController::class, 'updateCustomerSettings']);
Route::get('customer_device_types', [CustomersController::class, 'customerDeviceTypes']);
Route::get('customers_list', [CustomersController::class, 'getCustomersList']);



//Devices
Route::get('building_types', [CustomersController::class, 'buildingTypes']);
Route::get('address_types', [CustomersController::class, 'addressTypes']);
Route::get('device_models', [CustomersController::class, 'deviceModels']);
Route::get('device_types', [CustomersController::class, 'deviceTypes']);
Route::post('device_zones_update', [CustomersController::class, 'updateDeviceZones']);
Route::get('customer_temperature_devices', [CustomersController::class, 'getCustomerTemperatureDevices']);

Route::get('sensor_types', [CustomersController::class, 'getSensorsList']);
//dashboard
Route::get('device_armed_stats', [AlarmDashboardController::class, 'getDeviceArmedStatistics']);
Route::get('device_live_stats', [AlarmDashboardController::class, 'getDeviceLiveStatistics']);
Route::get('customer_contract_stats', [AlarmDashboardController::class, 'getCustomerContractStatistics']);
Route::get('device_sensors_stats', [AlarmDashboardController::class, 'getDeviceSensorStatistics']);

Route::get('customer_profile_completion_percentage', [CustomersController::class, "customerProfileCompletionPercentage"]);
Route::get('alarm_response_statistics', [AlarmDashboardController::class, "alarmResponseStatistics"]);


//api alarm logs 
Route::get('api_temperature_logs',  [ApiAlarmDeviceTemperatureLogsController::class, 'ApiTemperatureLogs']);
Route::post('api_temperature_logs', [ApiAlarmDeviceTemperatureLogsController::class, 'ApiTemperatureLogs']);
Route::get('api_alarm_logs',  [ApiAlarmDeviceTemperatureLogsController::class, 'AlarmLogs']);
Route::post('api_alarm_logs', [ApiAlarmDeviceTemperatureLogsController::class, 'AlarmLogs']);
Route::get('update_logs', [ApiAlarmDeviceTemperatureLogsController::class, 'updateAlarmResponseTime']);
//alarm logs
Route::apiResource('alarmevents', CustomerAlarmEventsController::class);

Route::get('alarm_dashboard_get_temparature_latest', [AlarmDeviceTemperatureLogsController::class, 'getDeviceLatestTemperature']);

Route::get('alarm_dashboard_get_hourly_data', [AlarmDeviceTemperatureLogsController::class, 'getDeviceTodayHourlyTemperature']);
Route::get('alarm_logs_data_month_data', [AlarmDeviceTemperatureLogsController::class, 'getAlarmLogsMonthwise']);

Route::get('get_alarm_logs', [CustomerAlarmEventsController::class, 'getAlarmLogs']);
Route::get('get_alarm_events', [CustomerAlarmEventsController::class, 'getAlarmEvents']);

Route::get('get_alarm_notification_display', [CustomerAlarmEventsController::class, 'getAlarmNotificationsList']);




Route::get('get_alarm_events_notes', [CustomerAlarmEventsController::class, 'getAlarmEventsNotes']);

Route::post('customer_add_event_notes', [CustomerAlarmEventsController::class, "createEventNotes"]);
Route::delete('delete-notes', [CustomerAlarmEventsController::class, "destroyNotes"]);
Route::delete('delete-event', [CustomerAlarmEventsController::class, "destroyEvent"]);
Route::post('update-device-alarm-event-status-off', [CustomerAlarmEventsController::class, "stopEvent"]);


Route::apiResource('automation', DeviceNotificationsManagersController::class);
Route::delete('delete-automation', [DeviceNotificationsManagersController::class, "destroy"]);

Route::apiResource('customer_payments', CustomerPaymentsController::class);
Route::delete('delete-payment', [CustomerPaymentsController::class, "destroy"]);


Route::get('plotting', [PlottingController::class, "index"]);
Route::post('plotting', [PlottingController::class, "store"]);



Route::get('get-map-key', function () {
    return MapKey::where("isActive", 1)->value("key");
});
