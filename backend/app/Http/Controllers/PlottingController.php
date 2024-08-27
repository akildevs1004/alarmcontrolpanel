<?php

namespace App\Http\Controllers;

use App\Models\AlarmEvents;
use App\Models\Customers\CustomerBuildingPictures;
use App\Models\Deivices\DeviceZones;
use App\Models\Plotting;
use Exception;
use Illuminate\Http\Request;

class PlottingController extends Controller
{
    public function index(Request $request)
    {
        $customerBuildingPictureId = request("customer_building_picture_id");

        // Retrieve the model
        $model = Plotting::with(["photos"])->where("customer_building_picture_id", $customerBuildingPictureId)->first();

        if (!$model || !isset($model->plottings)) {
            return $model;
        }
        // Decode the plottings JSON field
        $plottings = $model->plottings;


        $plottings =  $this->updatePlottingWithalarm_event($plottings);

        // // Collect unique sensor IDs to minimize queries
        // // $sensorIds = collect($plottings)->pluck('sensor_id')->unique();


        // // Fetch alarm events for these sensor IDs
        // $alarmEvents = AlarmEvents::where("alarm_status", 1)->get()->keyBy('zone');

        // // Map plottings to include alarm events
        // $plottings = collect($plottings)->map(function ($plotting) use ($alarmEvents) {
        //     $plotting['alarm_event'] = $alarmEvents->get($plotting['sensor_id']);
        //     return $plotting;
        // });

        // Encode the plottings back to JSON


        //  return CustomerBuildingPictures::with("photoPlottings")->where("customer_id", $request->customer_id)->get();

        $model->plottings = $plottings;
        $buildingPhotosPlottings = [];
        //get customer all photos plottings
        if ($request->filled('customer_id'))
            $buildingPhotosPlottings = CustomerBuildingPictures::with("photoPlottings")->where("customer_id", $request->customer_id)->get();
        $model->buildingPhotosPlottings = $buildingPhotosPlottings;
        return $model;
    }

    public function updatePlottingWithalarm_event($plottings)
    {
        $sensorIds = array_column($plottings, 'sensor_id');
        $deviceZones = DeviceZones::with('device')
            ->whereIn('id', array_filter($sensorIds))
            ->get()
            ->keyBy('id');

        foreach ($plottings as &$plot) {
            $plot['alarm_event'] = null;

            if ($plot['sensor_id'] && $plot['sensor_id'] != $plot['device_id']) {
                $deviceZone = $deviceZones->get($plot['sensor_id']);

                if ($deviceZone) {
                    $plot['alarm_event'] = AlarmEvents::where('alarm_status', 1)
                        ->where('serial_number', $deviceZone->device->serial_number)
                        ->where('zone', $deviceZone->zone_code)
                        ->where('area', $deviceZone->area_code)
                        ->get();
                }
            } else if ($plot['sensor_id'] == $plot['device_id']) {
                $plot['alarm_event'] = AlarmEvents::with('device')
                    ->where('alarm_status', 1)
                    ->whereHas('device', function ($q) use ($plot) {
                        $q->where('id', $plot['device_id']);
                    })
                    ->get();
            }
        }

        return $plottings;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_building_picture_id' => 'required|integer',
            'plottings' => 'required|array',
            'plottings.*.sensor_id' => 'required',
            'plottings.*.device_id' => 'required',
            'plottings.*.label' => 'required|string',
            'plottings.*.top' => 'required|string',
            'plottings.*.left' => 'required|string',
        ]);

        try {
            $plotting = Plotting::query();

            $plotting->where("customer_building_picture_id", $data['customer_building_picture_id']);

            $plotting->delete();

            $plotting = $plotting->create($data);

            return response()->json($plotting, 201);
        } catch (Exception $e) {
            // Handle general errors
            return response()->json([
                'error' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
