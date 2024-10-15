<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Requests\Customer\StoreRequest;
use App\Models\AlarmEvents;
use App\Models\Customers\CustomerContacts;
use App\Models\Customers\Customers;
use App\Models\CustomersBuildingTypes;
use App\Models\Deivices\DeviceZones;
use App\Models\Device;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use function Aws\filter;

class AlarmDashboardController extends Controller
{

    public function dashboardStatisctsCustomers(Request $request)
    {
        $totalCustomers = Customers::where("company_id", $request->company_id)->count();
        $deviceCounts = Device::selectRaw("             
        COUNT(CASE WHEN armed_status = 1 THEN 1 END) as armedCount,
        COUNT(CASE WHEN armed_status = 0 THEN 1 END) as disarmCount,
        COUNT(CASE WHEN status_id = 1 THEN 1 END) as onlineCount,
        COUNT(CASE WHEN status_id = 2 THEN 1 END) as offlineCount
    ")->first();

        $alarmCounts = AlarmEvents::selectRaw("             
        COUNT(CASE WHEN alarm_status = 1 THEN 1  END) as openCount,
        COUNT(CASE WHEN alarm_status = 0 THEN 1   END) as closedCount 
        
    ")
            //->whereDate("alarm_start_datetime", $request->date)
            ->first();


        return  [
            "customersCount" => $totalCustomers,
            "armedCount" => $deviceCounts->armedcount,
            "disarmCount" => $deviceCounts->disarmcount,
            "onlineCount" => $deviceCounts->onlinecount,
            "offlineCount" => $deviceCounts->offlinecount,


            "openCount" => $alarmCounts->opencount,
            "closedCount" => $alarmCounts->closedcount,
        ];
    }
    public function dashboardStatisctsHourlyRange(Request $request)
    {
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        for ($i = 0; $i < 24; $i++) {

            $j = $i;

            $j = $i <= 9 ? "0" . $i : $i;


            $counts = AlarmEvents::where("company_id", $request->company_id)->selectRaw("             
                COUNT(CASE WHEN alarm_type = 'SOS' THEN 1   END) as sosCount,
                COUNT(CASE WHEN alarm_category = 1 THEN 1  END) as crititalCount,
                COUNT(CASE WHEN alarm_category = 2 THEN 1   END) as mediumCount,
                COUNT(CASE WHEN alarm_category = 3 THEN 1   END) as lowCount
            ")->where('alarm_start_datetime', '>=', $date_from . ' ' . $j . ':00:00')
                ->where('alarm_start_datetime', '<=', $date_to  . ' ' . $j . ':59:59')

                ->first();
            $finalarray[] = [
                "hour" => $i,
                "sosCount" => $counts->soscount,
                "highCount" => $counts->crititalcount,
                "mediumCount" => $counts->mediumcount,
                "lowCount" => $counts->lowcount,
            ];
        }

        return $finalarray;
    }
    public function dashboardStatisctsDateRange(Request $request)
    {
        $finalarray = [];
        $dateStrings = [];
        if ($request->has("date_from") && $request->has("date_to")) {
            // Usage example:
            $startDate = new DateTime($request->date_from);
            $endDate = new DateTime($request->date_to);

            $dateStrings = $this->createDateRangeArray($startDate, $endDate);
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $dateStrings[] = date('Y-m-d', strtotime(date('Y-m-d') . '-' . $i . ' days'));
            }
        }
        foreach ($dateStrings as $key => $date) {

            $counts = AlarmEvents::where("company_id", $request->company_id)->selectRaw("             
            COUNT(CASE WHEN alarm_type = 'SOS' THEN 1   END) as sosCount,
            COUNT(CASE WHEN alarm_category = 1 THEN 1  END) as crititalCount,
            COUNT(CASE WHEN alarm_category = 2 THEN 1   END) as mediumCount,
            COUNT(CASE WHEN alarm_category = 3 THEN 1   END) as lowCount
        ")
                ->whereDate("alarm_start_datetime", $date)
                ->first();
            $finalarray[] = [
                "date" => $date,
                "sosCount" => $counts->soscount,
                "highCount" => $counts->crititalcount,
                "mediumCount" => $counts->mediumcount,
                "lowCount" => $counts->lowcount,
            ];
        }


        return  $finalarray;
    }
    function createDateRangeArray($startDate, $endDate)
    {
        $dateStrings = [];
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            $dateStrings[] = $currentDate->format('Y-m-d'); // Change the format as needed
            $currentDate->modify('+1 day');
        }

        return $dateStrings;
    }
    public function getDeviceArmedStatistics(Request $request)
    {

        $model = Device::where("company_id", $request->company_id)->where("device_id", "!=", "Manual");



        $totalCount = $model->count();
        $armedCount = $model->where("armed_status", 1)->count();

        return ["total" => $totalCount, "armed" => $armedCount];
    }
    public function getDeviceLiveStatisticsGroupBy(Request $request)
    {
        $statuses = [
            'Water' => ['online' => 0, 'offline' => 0],
            'Burglary' => ['online' => 0, 'offline' => 0],
            'Fire' => ['online' => 0, 'offline' => 0],
            'Medical' => ['online' => 0, 'offline' => 0],
            'Temperature' => ['online' => 0, 'offline' => 0],

        ];

        foreach ($statuses as $type => $status) {
            $model = Device::with(['sensorzones'])
                ->where('company_id', $request->company_id)->where("device_id", "!=", "Manual")
                ->where(function ($query) use ($type) {
                    $query->where('device_type', 'ILIKE', $type)
                        ->orWhereHas('sensorzones', function ($query) use ($type) {
                            $query->where('sensor_name', 'ILIKE', $type);
                        });
                });


            $model->when($request->filled("filter_customers_list"), function ($model) use ($request) {
                $model->whereIn('customer_id', $request->filter_customers_list);
            });
            $statuses[$type]['online'] = $model->clone()->where('status_id', 1)->count();
            $statuses[$type]['offline'] = $model->clone()->where('status_id', 2)->count();
            $statuses[$type]['percentage'] = ($statuses[$type]['online'] + $statuses[$type]['offline'] > 0) ?  round($statuses[$type]['online'] * 100 / ($statuses[$type]['online'] + $statuses[$type]['offline'])) : 0;
        }

        return [
            'Burglary' => ["online" => $statuses['Burglary']['online'], "offline" => $statuses['Burglary']['offline'],  "percentage" => $statuses['Burglary']['percentage']],
            'Water' => ["online" => $statuses['Water']['online'],  "offline" => $statuses['Water']['offline'],  "percentage" => $statuses['Water']['percentage']],
            'Fire' => ["online" => $statuses['Fire']['online'],  "offline" => $statuses['Fire']['offline'],  "offline" => $statuses['Fire']['offline'],  "percentage" => $statuses['Fire']['percentage']],
            'Medical' => ["online" => $statuses['Medical']['online'],  "offline" => $statuses['Medical']['offline'],  "percentage" => $statuses['Medical']['percentage']],
            'Temperature' => ["online" => $statuses['Temperature']['online'],  "offline" => $statuses['Temperature']['offline'],  "percentage" => $statuses['Temperature']['percentage']],

        ];
    }
    public function getDeviceLiveStatistics(Request $request)
    {

        $model = Device::where("company_id", $request->company_id)->where("device_id", "!=", "Manual");
        $totalCount = $model->count();
        $online = $model->where("status_id", 1)->count();

        return ["total" => $totalCount, "online" => $online];
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
        COALESCE(SUM(CASE WHEN response_minutes < 1 THEN 1   END), 0) AS less_than_1_minute,
        COALESCE(SUM(CASE WHEN response_minutes >= 1 AND response_minutes < 5 THEN 1   END), 0) AS between_1_and_5_minutes,
        COALESCE(SUM(CASE WHEN response_minutes >= 5 AND response_minutes < 10 THEN 1   END), 0) AS between_5_and_10_minutes,
        COALESCE(SUM(CASE WHEN response_minutes >= 10 THEN 1   END), 0) AS more_than_10_minutes
    ')
            ->first();

        return  $statistics;
    }
    public function alarmCustomerStatistics(Request $request)
    {
        $statistics = AlarmEvents::with('customer')
            ->select('customer_id', DB::raw('count(*) as total_alarms')) // Add aggregate function
            ->where('company_id', $request->company_id)
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->whereBetween('alarm_start_datetime', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ])
            ->groupBy('customer_id')
            ->get();
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
            COALESCE(SUM(CASE WHEN alarm_type = \'Burglary\' THEN 1  END), 0) AS burglary,
            COALESCE(SUM(CASE WHEN alarm_type = \'Medical\' THEN 1   END), 0) AS medical,
            COALESCE(SUM(CASE WHEN alarm_type = \'Temperature\' THEN 1   END), 0) AS temperature,
            COALESCE(SUM(CASE WHEN alarm_type = \'Water\' THEN 1 END), 0) AS water,
            COALESCE(SUM(CASE WHEN alarm_type = \'Fire\' THEN 1 END), 0) AS fire,
            COALESCE(SUM(CASE WHEN alarm_type = \'SOS\' THEN 1   END), 0) AS sos,
            COALESCE(SUM(CASE WHEN alarm_category = \'1\' THEN 1   END), 0) AS critical 
            


        ')
            ->first();

        return response()->json($statistics);
    }
    public function alarmEventOperatorStatistics(Request $request)
    {
        $statistics = DB::table('alarm_events')
            ->where('company_id', $request['company_id'])
            ->when($request->filled("filter_customers_list") && $request->filter_customers_list != '', function ($query) use ($request) {
                $query->whereIn('customer_id', $request->filter_customers_list);
            })
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })



            ->selectRaw('
            COALESCE(SUM(CASE WHEN alarm_type = \'Intruder\' AND alarm_status = 1 AND customer_id IN (' . implode(',', $request->filter_customers_list) . ') THEN 1   END), 0) AS intruder,
            
            COALESCE(SUM(CASE WHEN alarm_type = \'SOS\' AND alarm_status = 1 AND customer_id IN (' . implode(',', $request->filter_customers_list) . ') THEN 1  END), 0) AS sos,
            COALESCE(SUM(CASE WHEN alarm_type = \'Medical\' AND alarm_status = 1 AND customer_id IN (' . implode(',', $request->filter_customers_list) . ') THEN 1  END), 0) AS medical,
            COALESCE(SUM(CASE WHEN alarm_type = \'ac_off\' AND alarm_status = 1 AND customer_id IN (' . implode(',', $request->filter_customers_list) . ') THEN 1  END), 0) AS ac_off,
            COALESCE(SUM(CASE WHEN alarm_category = \'1\' AND alarm_status = 1 AND customer_id IN (' . implode(',', $request->filter_customers_list) . ') THEN 1   END), 0) AS critical,
           
              (SELECT COUNT(*) FROM devices WHERE status_id = 2 AND customer_id IN (' . implode(',', $request->filter_customers_list) . ')) AS offline,

              
             (SELECT COUNT(*) FROM devices WHERE armed_status = 1 and status_id = 1 AND customer_id IN (' . implode(',', $request->filter_customers_list) . ')) AS armed,
              (SELECT COUNT(*) FROM devices WHERE armed_status = 0 and status_id = 1  AND customer_id IN (' . implode(',', $request->filter_customers_list) . ')) AS disarm 
        ')

            ->first();

        return response()->json($statistics);
    }
    public function alarmEventTemperatureStatistics(Request $request)
    {
        $events = AlarmEvents::with(["category"])->where('company_id', $request['company_id'])
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->where('alarm_type', "Temperature");
        $events->when($request->filled("filter_customers_list"), function ($model) use ($request) {
            $model->whereIn('customer_id', $request->filter_customers_list);
        });
        return ["active" => $events->clone()->where("alarm_status", 1)->count(), "closed" => $events->clone()->where("alarm_status", 0)->count()];
    }
    public function alarmEventWaterStatistics(Request $request)
    {
        $events = AlarmEvents::with(["category"])->where('company_id', $request['company_id'])
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->where('alarm_type', "Water");
        $events->when($request->filled("filter_customers_list"), function ($model) use ($request) {
            $model->whereIn('customer_id', $request->filter_customers_list);
        });

        return ["active" => $events->clone()->where("alarm_status", 1)->count(), "closed" => $events->clone()->where("alarm_status", 0)->count()];
    }
    public function alarmEventFireStatistics(Request $request)
    {
        $events = AlarmEvents::with(["category"])->where('company_id', $request['company_id'])
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->where('alarm_type', "Fire");
        $events->when($request->filled("filter_customers_list"), function ($query) use ($request) {
            $query->whereIn('customer_id', $request->filter_customers_list);
        });
        return ["active" => $events->clone()->where("alarm_status", 1)->count(), "closed" => $events->clone()->where("alarm_status", 0)->count()];
    }
    public function buildingtypeCustomerStats(Request $request)
    {

        $BuildingTypes = CustomersBuildingTypes::all();

        $groupCount = Customers::where('company_id', $request->company_id)
            ->select('building_type_id', DB::raw('COUNT(id) as total_count'))
            ->groupBy('building_type_id')
            ->pluck('total_count', 'building_type_id'); // Pluck total_count with building_type_id as the key

        $groupResults = [];

        foreach ($BuildingTypes as $buildingType) {
            $groupResults[] = ["name" => $buildingType->name, "count" => $groupCount->get($buildingType->id, 0)];
        }

        return $groupResults;
    }

    public function customersAccountsExpireStats(Request $request)
    {

        $businessTypes = CustomersBuildingTypes::all();

        $groupCount = Customers::where('company_id', $request->company_id)
            ->select('building_type_id', DB::raw('COUNT(id) as total_count'))
            ->groupBy('building_type_id')
            ->pluck('total_count', 'building_type_id'); // Pluck total_count with building_type_id as the key

        $groupResults = [];

        foreach ($businessTypes as $buildingType) {
            $groupResults[] = ["name" => $buildingType->name, "count" => $groupCount->get($buildingType->id, 0)];
        }

        return $groupResults;
    }

    public function alarmEventMedicalStatistics(Request $request)
    {
        $events = AlarmEvents::with(["category"])->where('company_id', $request['company_id'])
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->where('alarm_type', "Medical");

        $events->when($request->filled("filter_customers_list"), function ($model) use ($request) {
            $model->whereIn('customer_id', $request->filter_customers_list);
        });

        return ["active" => $events->clone()->where("alarm_status", 1)->count(), "closed" => $events->clone()->where("alarm_status", 0)->count()];
    }

    public function alarmEventBurglaryStatistics(Request $request)
    {
        $events = AlarmEvents::with(["category"])->where('company_id', $request['company_id'])
            ->when($request->filled('customer_id'), function ($query) use ($request) {
                $query->where('customer_id', $request->customer_id);
            })
            ->where('alarm_status', 1)
            ->where('alarm_type', "Intruder")
            ->when($request->filled("filter_customers_list"), function ($query) use ($request) {
                $query->whereIn('customer_id', $request->filter_customers_list);
            })
            ->get()->groupBy("alarm_category");

        return $events;
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


        $deviceTypes = Device::where('company_id', $request->company_id)

            ->distinct()
            ->pluck('device_type');


        $sensorNames = DeviceZones::whereHas('device', function ($query) use ($request) {
            $query->where('company_id', $request->company_id);
        })->pluck('sensor_name');


        $mergedCollection = $deviceTypes->merge($sensorNames);


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
