<?php

namespace App\Http\Controllers;

use App\Models\AlarmEvents;
use App\Models\Plotting;
use Exception;
use Illuminate\Http\Request;

class PlottingController extends Controller
{
    public function index()
    {
        $customerBuildingPictureId = request("customer_building_picture_id");

        // Retrieve the model
        $model = Plotting::where("customer_building_picture_id", $customerBuildingPictureId)->first();

        if (!$model || !isset($model->plottings)) {
            return $model;
        }
        // Decode the plottings JSON field
        $plottings = $model->plottings;

        // Collect unique sensor IDs to minimize queries
        $sensorIds = collect($plottings)->pluck('sensor_id')->unique();

        // Fetch alarm events for these sensor IDs
        $alarmEvents = AlarmEvents::whereIn('zone', $sensorIds)->where("alarm_status", 1)->get()->keyBy('zone');

        // Map plottings to include alarm events
        $plottings = collect($plottings)->map(function ($plotting) use ($alarmEvents) {
            $plotting['alarm_event'] = $alarmEvents->get($plotting['sensor_id']);
            return $plotting;
        });

        // Encode the plottings back to JSON
        $model->plottings = $plottings;

        return $model;
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
