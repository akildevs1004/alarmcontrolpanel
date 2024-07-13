<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Requests\Customer\StoreRequest;

use App\Models\Customers\CustomerContacts;
use App\Models\Customers\Customers;
use App\Models\Deivices\DeviceZones;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
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
