<?php

namespace App\Http\Controllers;

use App\Models\Plotting;
use Exception;
use Illuminate\Http\Request;

class PlottingController extends Controller
{
    public function index()
    {
        return Plotting::where("customer_building_picture_id", request("customer_building_picture_id"))->first();
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
            $plotting = Plotting::updateOrCreate(
                ['customer_building_picture_id' => $data['customer_building_picture_id']],
                ['plottings' => $data['plottings']]
            );

            return response()->json($plotting, 201);
        } catch (Exception $e) {
            // Handle general errors
            return response()->json([
                'error' => 'An unexpected error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
