<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\AlarmEvents;
use App\Models\AlarmLogs;
use App\Models\Customers\CustomerAlarmEvents;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CustomerAlarmEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customers\CustomerAlarmEvents  $customerAlarmEvents
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerAlarmEvents $customerAlarmEvents)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customers\CustomerAlarmEvents  $customerAlarmEvents
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerAlarmEvents $customerAlarmEvents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customers\CustomerAlarmEvents  $customerAlarmEvents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerAlarmEvents $customerAlarmEvents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers\CustomerAlarmEvents  $customerAlarmEvents
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerAlarmEvents $customerAlarmEvents)
    {
        //
    }
    public function getAlarmEvents(Request $request)
    {
        // return  $request->validate([
        //     'date_from' => 'required|date|before_or_equal:date_to',
        //     'date_to' => 'required|date|after_or_equal:date_from',
        //     'company_id' => 'required|integer',
        //     'customer_id' => 'required|integer'
        // ]);
        $model = AlarmEvents::with(["device.customer"])->where('company_id', $request->company_id)

            // ->when($request->filled("common_search"), function ($query) use ($request) {
            //     $query->where("customer_id", $request->common_search);
            // })
            ->when($request->filled("customer_id"), fn ($q) => $q->where("customer_id", $request->customer_id))
            ->whereBetween('alarm_start_datetime', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);


        $model->when($request->filled('common_search'), function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->Where('serial_number', 'ILIKE', "%$request->common_search%");
                $q->orWhere('alarm_type', 'ILIKE', "%$request->common_search%");
                $q->orWhere('alarm_category', 'ILIKE', "%$request->common_search%");
                $q->orWhere('zone', 'ILIKE', "%$request->common_search%");
                $q->orWhere('area', 'ILIKE', "%$request->common_search%");



                $q->orWhereHas('device', fn (Builder $query) => $query->where('name', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                $q->orWhereHas('device', fn (Builder $query) => $query->where('device_type', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                $q->orWhereHas('device', fn (Builder $query) => $query->where('location', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                // $q->orWhereHas('device.sensorzones', fn (Builder $query) => $query->where('sensor_name', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                // $q->orWhereHas('device.sensorzones', fn (Builder $query) => $query->where('area_code', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                // $q->orWhereHas('device.sensorzones', fn (Builder $query) => $query->where('zone_code', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
            });
        });

        $model->orderBy("alarm_start_datetime", "asc");
        return $model->orderByDesc('id')->paginate($request->perPage);;
    }
    public function getAlarmLogs(Request $request)
    {
        // return  $request->validate([
        //     'date_from' => 'required|date|before_or_equal:date_to',
        //     'date_to' => 'required|date|after_or_equal:date_from',
        //     'company_id' => 'required|integer',
        //     'customer_id' => 'required|integer'
        // ]);
        $model = AlarmLogs::with(["device.customer"])->where('company_id', $request->company_id)
            ->where('customer_id', $request->customer_id)
            // ->when($request->filled("serial_number"), fn ($q) => $q->when("serial_number", $request->serial_number))
            ->whereBetween('log_time', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59'])
            ->where('alarm_status', 1)
            ->orderBy("log_time", "asc");
        return $model->orderByDesc('id')->paginate($request->perPage);;
    }
}
