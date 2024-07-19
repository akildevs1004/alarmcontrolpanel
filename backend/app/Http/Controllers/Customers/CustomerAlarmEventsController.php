<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\AlarmEvents;
use App\Models\AlarmLogs;
use App\Models\Customers\CustomerAlarmEvents;
use App\Models\Customers\CustomerAlarmNotes;
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
    public function destroyEvent(Request $request)
    {
        $modelEvent = AlarmEvents::where("id", $request->id);

        foreach ($modelEvent->get() as $event) {
            $model = CustomerAlarmNotes::where('id', $event->id);
            try {
                if (file_exists(public_path('/customers/notes') . '/' . $model->first()->picture_raw))
                    unlink(public_path('/customers/notes') . '/' . $model->first()->picture_raw);
            } catch (\Exception $e) {
            }
            $model->delete();
        }
        $modelEvent->delete();

        return $this->response('Event Deleted successfully', null, true);
    }
    public function destroyNotes(Request $request)
    {
        $model = CustomerAlarmNotes::where('id', $request->id);


        try {
            if (file_exists(public_path('/customers/notes') . '/' . $model->first()->picture_raw))
                unlink(public_path('/customers/notes') . '/' . $model->first()->picture_raw);
        } catch (\Exception $e) {
        }
        $model->delete();

        return $this->response('Notes Deleted successfully', null, true);
    }
    public function stopEvent(Request $request)
    {
        if ($request->event_id > 0) {
            $data = ["alarm_status" => 0, "alarm_end_manually" => 1, "alarm_end_datetime" => date("Y-m-d H:i:s")];
            AlarmEvents::where("id", $request->event_id)->update($data);
            return $this->response('Alarm stopped Successfully', null, true);
        } else {
            return $this->response('Alarm Details are not available', null, false);
        }
    }
    public function createEventNotes(Request $request)
    {
        $request->validate([

            'company_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'alarm_id' => 'required|integer',
            'title' => 'required',
            'notes' => 'required',

        ]);
        // if ((int)$request->customer_id <= 0 || (int)$request->company_id <= 0) {
        //     return $this->response('Customer Id and Company Ids are not exist', null, false);
        // }
        try {


            $data = []; // $request->all();


            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $ext = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $ext;

                $request->file('attachment')->move(public_path('/customers/notes'), $fileName);
                $data['picture'] = $fileName;
                unset($data['attachment']);
            }


            $data['company_id'] =  $request->company_id;
            $data['customer_id'] = $request->customer_id;
            $data['alarm_id'] = $request->alarm_id;
            $data['title'] = $request->title;
            $data['notes'] = $request->notes;
            $data['created_datetime'] = date("Y-m-d H:i:s");

            if ($request->filled("notes_id")) {

                $record = CustomerAlarmNotes::where("id", $request->notes_id)->update($data);
            } else {
                $record = CustomerAlarmNotes::create($data);
            }

            if ($record) {
                return $this->response('  Notes   Created.', $record, true);
            } else {
                return $this->response('Notes   Not Created', null, false);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getAlarmEventsNotes(Request $request)
    {
        $model = CustomerAlarmNotes::where("company_id", $request->company_id)
            ->where("alarm_id", $request->alarm_id);
        $model->orderBy("created_datetime", "asc");
        return $model->orderByDesc('id')->paginate($request->perPage ?? 10);;
    }
    public function getAlarmNotificationsList(Request $request)
    {
        $model = AlarmEvents::with([
            "device.customer.primary_contact",
            "device.customer.secondary_contact",
            "notes"
        ])->where('company_id', $request->company_id)->where('alarm_status', 1);



        $model->orderBy("alarm_start_datetime", "asc");
        return $model->get();;
    }
    public function getAlarmEvents(Request $request)
    {
        // return  $request->validate([
        //     'date_from' => 'required|date|before_or_equal:date_to',
        //     'date_to' => 'required|date|after_or_equal:date_from',
        //     'company_id' => 'required|integer',
        //     'customer_id' => 'required|integer'
        // ]);
        $model = AlarmEvents::with([
            "device.customer.primary_contact",
            "device.customer.secondary_contact",
            "notes"
        ])->where('company_id', $request->company_id)

            // ->when($request->filled("common_search"), function ($query) use ($request) {
            //     $query->where("customer_id", $request->common_search);
            // })
            ->when($request->filled("alarm_status"), fn ($q) => $q->where("alarm_status", $request->alarm_status))
            ->when($request->filled("filterResponseInMinutes"), function ($query) use ($request) {
                if ((int) $request->filterResponseInMinutes == 0)
                    $query->where("response_minutes", '>', 10);
                else 
                if ((int) $request->filterResponseInMinutes == 1)
                    $query->where("response_minutes", '<=', 1);
                else
                if ((int) $request->filterResponseInMinutes == 5)

                    $query->where("response_minutes", '>=', 1)->where("response_minutes", '<=', 5);

                else
                    if ((int) $request->filterResponseInMinutes == 10)

                    $query->where("response_minutes", '>=', 5)->where("response_minutes", '<=', 10);
            })
            ->when($request->filled("customer_id"), fn ($q) => $q->where("customer_id", $request->customer_id));
        if ($request->filled("date_from")) {
            $model->whereBetween('alarm_start_datetime', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);
        }


        $model->when($request->filled('filterSensorname'), function ($q) use ($request) {

            $q->Where('alarm_type', 'ILIKE', "%$request->filterSensorname%");
        });

        $model->when($request->filled('common_search'), function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->Where('serial_number', 'ILIKE', "%$request->common_search%");
                $q->orWhere('alarm_type', 'ILIKE', "%$request->common_search%");
                $q->orWhere('alarm_category', 'ILIKE', "%$request->common_search%");
                $q->orWhere('zone', 'ILIKE', "%$request->common_search%");
                $q->orWhere('area', 'ILIKE', "%$request->common_search%");

                $q->when(
                    !$request->filled("customer_id"),
                    function ($quqery) use ($request) {
                        $quqery->orWhereHas('device.customer', fn (Builder $query) => $query->where('building_name', 'ILIKE', "$request->common_search%")
                            ->orWhere('area', 'ILIKE', "$request->common_search%")
                            ->orWhere('city', 'ILIKE', "$request->common_search%")

                            ->where('company_id', $request->company_id));
                    }

                );

                $q->orWhereHas('device', fn (Builder $query) => $query->where('name', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                $q->orWhereHas('device', fn (Builder $query) => $query->where('device_type', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                $q->orWhereHas('device', fn (Builder $query) => $query->where('location', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
            });
        });

        $model->orderBy("alarm_start_datetime", "asc");
        return $model->orderByDesc('id')->paginate($request->perPage ?? 10);;
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
