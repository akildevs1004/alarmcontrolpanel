<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Requests\Customer\StoreRequest;
use App\Models\AlarmEvents;
use App\Models\Customers\CustomerContacts;
use App\Models\Customers\Customers;
use App\Models\Deivices\DeviceZones;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AlarmDashboardController extends Controller
{

    public function getDeviceArmedStatistics(Request $request)
    {

        $model = Device::where("company_id", $request->company_id);



        $totalCount = $model->count();
        $armedCount = $model->where("armed_status", 1)->count();

        return ["total" => $totalCount, "armed" => $armedCount];
    }
    public function getDeviceLiveStatistics(Request $request)
    {

        $model = Device::where("company_id", $request->company_id);
        $totalCount = $model->count();
        $armedCount = $model->where("status_id", 1)->count();

        return ["total" => $totalCount, "online" => $armedCount];
    }
    public function getCustomerContractStatistics(Request $request)
    {

        $today = now();
        $thirtyDaysFromNow = now()->addDays(30);


        $customers = Customers::where("company_id", $request->company_id);

        $total = $customers->clone()->count();
        $expired = $customers->clone()->where("end_date", "<", $today)
            ->count();
        $expiringIn30Days = $customers->clone()->whereBetween("end_date", [$today, $thirtyDaysFromNow])->count();
        return ["total" => $total, "expired" => $expired, "expiringin30days" => $expiringIn30Days];
    }
    public function alarmResponseStatistics(Request $request)
    {
        $statistics = DB::table('alarm_events')
            ->where('company_id', $request->company_id)
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->whereBetween('alarm_start_datetime', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59'])
            ->selectRaw('
        COALESCE(SUM(CASE WHEN response_minutes < 1 THEN 1 ELSE 0 END), 0) AS less_than_1_minute,
        COALESCE(SUM(CASE WHEN response_minutes >= 1 AND response_minutes < 5 THEN 1 ELSE 0 END), 0) AS between_1_and_5_minutes,
        COALESCE(SUM(CASE WHEN response_minutes >= 5 AND response_minutes < 10 THEN 1 ELSE 0 END), 0) AS between_5_and_10_minutes,
        COALESCE(SUM(CASE WHEN response_minutes >= 10 THEN 1 ELSE 0 END), 0) AS more_than_10_minutes
    ')
            ->first();

        return  $statistics;
    }
    public function alarmStatistics(Request $request)
    {
        $statistics = DB::table('alarm_events')
            ->where('company_id', $request['company_id'])
            ->where('alarm_status', 1)
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereBetween('alarm_start_datetime', [
                    $request['date_from'] . ' 00:00:00',
                    $request['date_to'] . ' 23:59:59'
                ]);
            })
            ->selectRaw('
            COALESCE(SUM(CASE WHEN alarm_type = \'Burglary\' THEN 1 ELSE 0 END), 0) AS burglary,
            COALESCE(SUM(CASE WHEN alarm_type = \'Medical\' THEN 1 ELSE 0 END), 0) AS medical,
            COALESCE(SUM(CASE WHEN alarm_type = \'Temperature\' THEN 1 ELSE 0 END), 0) AS temperature,
            COALESCE(SUM(CASE WHEN alarm_type = \'Water\' THEN 1 ELSE 0 END), 0) AS water,
            COALESCE(SUM(CASE WHEN alarm_type = \'Fire\' THEN 1 ELSE 0 END), 0) AS fire
        ')
            ->first();

        return response()->json($statistics);
    }
    public function alarmEventStatistics(Request $request)
    {
        $events = AlarmEvents::where('company_id', $request['company_id'])
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->whereBetween('alarm_start_datetime', [
                $request['date_from'] . ' 00:00:00',
                $request['date_to'] . ' 23:59:59'
            ])->get();

        return $events;
    }
    public function getDeviceSensorStatistics(Request $request)
    {

        // Fetch distinct device types
        $deviceTypes = Device::where('company_id', $request->company_id)

            ->distinct()
            ->pluck('device_type');

        // Fetch sensor names
        $sensorNames = DeviceZones::whereHas('device', function ($query) use ($request) {
            $query->where('company_id', $request->company_id);
        })->pluck('sensor_name');

        // Merge the two collections and get distinct values
        $mergedCollection = $deviceTypes->merge($sensorNames);

        // Convert to array if needed
        $distinctValues = $mergedCollection->toArray();
        $item_counts = array();


        foreach ($distinctValues as $item) {
            if (array_key_exists($item, $item_counts)) {
                $item_counts[$item]++;
            } else {
                $item_counts[$item] = 1;
            }
        }

        return $item_counts;
    }
}
