<?php

namespace App\Http\Controllers\Customers\Alarm;

use App\Http\Controllers\Controller;
use App\Models\DeviceArmedLogs;
use Illuminate\Http\Request;

class DeviceArmedLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $model = $this->filter($request);


        return $model->paginate($request->perPage ?? 10);;
    }

    public function filter($request)
    {
        $model = DeviceArmedLogs::with(["device"])->where("company_id", $request->company_id);


        $model->when($request->filled("customer_id"), function ($query) use ($request) {
            $query->whereHas("device", function ($q) use ($request) {
                $q->where("customer_id", $request->customer_id);
            });
        });


        $model->when($request->filled("common_search"), function ($q) use ($request) {
            $q->whereHas(
                "device",
                function ($qq) use ($request) {
                    $qq->where("name", "ILIKE", "%$request->common_search%");
                }
            );
        });
        if ($request->filled("date_from")) {
            $model->whereBetween('armed_datetime', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);
        }
        return  $model->orderBy("armed_datetime", "DESC");
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
     * @param  \App\Models\DeviceArmedLogs  $deviceArmedLogs
     * @return \Illuminate\Http\Response
     */
    public function show(DeviceArmedLogs $deviceArmedLogs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeviceArmedLogs  $deviceArmedLogs
     * @return \Illuminate\Http\Response
     */
    public function edit(DeviceArmedLogs $deviceArmedLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeviceArmedLogs  $deviceArmedLogs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeviceArmedLogs $deviceArmedLogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeviceArmedLogs  $deviceArmedLogs
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeviceArmedLogs $deviceArmedLogs)
    {
        //
    }
}
