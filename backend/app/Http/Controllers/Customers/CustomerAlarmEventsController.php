<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\AlarmEvents;
use App\Models\AlarmLogs;
use App\Models\Customers\CustomerAlarmEvents;
use App\Models\Customers\CustomerAlarmNotes;
use App\Models\Customers\CustomerContacts;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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


        $data = $request->validate([
            'primary_pin_number' => 'nullable',
            'seconday_pin_number' => 'nullable',
            'company_id' => 'required',
            'customer_id' => 'required',
            'event_id' => 'required',
            'notes' => 'required',
            'title' => 'required',



        ]);

        if (empty($request->input('primary_pin_number')) && empty($request->input('seconday_pin_number'))) {
            return [
                "status" => false,
                "errors" => ['primary_pin_number' => ['Either Primary or Secondary Pin is  required.']],
            ];
        }
        if ($request->event_id > 0) {
            $alarmModel = AlarmEvents::where("id", $request->event_id);
            $alarm_start_datetime = $alarmModel->first()->alarm_start_datetime;
            $data = [];
            $primaryCount = CustomerContacts::where("customer_id", $request->input('customer_id'))
                ->where("alarm_stop_pin", $request->input('primary_pin_number'))
                ->count();

            $secondaryCount = CustomerContacts::where("customer_id", $request->input('customer_id'))->where("alarm_stop_pin", $request->input('seconday_pin_number'))->count();


            if ($primaryCount == 0 && $secondaryCount == 0) {
                return [
                    "status" => false,
                    "errors" => ['primary_pin_number' => ['PIN number is not matched']],
                ];
            }

            if ($primaryCount) {
                $data["pin_verified_by"] = "primary";
            } else if ($secondaryCount) {
                $data["pin_verified_by"] = "secondary";
            }

            $data["alarm_status"] = 0;
            $data["alarm_end_manually"] = 1;
            $data["alarm_end_datetime"] = date("Y-m-d H:i:s");

            $datetime1 = new DateTime($alarm_start_datetime);
            $datetime2 = new DateTime(date("Y-m-d H:i:s"));

            $interval = $datetime1->diff($datetime2);

            $data["response_minutes"] = $interval->i + ($interval->h * 60) + ($interval->days * 1440);


            $alarmModel->update($data);
            //-----------------------------------------------------------------------------------------
            $data = [];

            // i represents the minutes part of the interval


            $data['company_id'] =  $request->company_id;
            $data['customer_id'] = $request->customer_id;
            $data['alarm_id'] = $request->event_id;
            $data['title'] = $request->title;
            $data['notes'] = $request->notes;
            $data['created_datetime'] = date("Y-m-d H:i:s");
            $record = CustomerAlarmNotes::create($data);






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
            'title' => 'nullable',
            'notes' => 'required',
            'title' => 'nullable',

            'call_status' => 'required',
            'response' => 'required',
            'event_status' => 'required',
            'security_id' => 'required',


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
            $data['call_status'] = $request->call_status;
            $data['response'] = $request->response;
            $data['event_status'] = $request->event_status;
            $data['security_id'] = $request->security_id;
            $data['contact_id'] = $request->contact_id;
            $data['created_datetime'] = date("Y-m-d H:i:s");

            if ($request->filled("notes_id")) {

                $record = CustomerAlarmNotes::where("id", $request->notes_id)->update($data);
            } else {

                if ($request->event_status != "Closed") {
                    $record = CustomerAlarmNotes::create($data);
                } else if ($request->event_status == "Closed") {
                    if (empty($request->input('primary_pin_number')) && empty($request->input('seconday_pin_number'))) {
                        return [
                            "status" => false,
                            "errors" => ['primary_pin_number' => ['  Primary  Pin is  required.']],
                        ];
                    }
                    if ($request->alarm_id > 0) {
                        $alarmModel = AlarmEvents::where("id", $request->alarm_id);
                        $alarm_start_datetime = $alarmModel->first()->alarm_start_datetime;

                        $primaryCount = CustomerContacts::where("customer_id", $request->input('customer_id'))
                            ->where("alarm_stop_pin", $request->input('primary_pin_number'))
                            ->count();

                        $secondaryCount = CustomerContacts::where("customer_id", $request->input('customer_id'))->where("alarm_stop_pin", $request->input('seconday_pin_number'))->count();


                        if ($primaryCount == 0 && $secondaryCount == 0) {
                            return [
                                "status" => false,
                                "errors" => ['primary_pin_number' => ['PIN number is not matched']],
                            ];
                        }
                        $data2 = [];
                        if ($primaryCount) {
                            $data2["pin_verified_by"] = "primary";
                        } else if ($secondaryCount) {
                            $data2["pin_verified_by"] = "secondary";
                        }

                        $data2["alarm_status"] = 0;
                        $data2["alarm_end_manually"] = 1;
                        $data2["alarm_end_datetime"] = date("Y-m-d H:i:s");

                        $datetime1 = new DateTime($alarm_start_datetime);
                        $datetime2 = new DateTime(date("Y-m-d H:i:s"));

                        $interval = $datetime1->diff($datetime2);

                        $data2["response_minutes"] = $interval->i + ($interval->h * 60) + ($interval->days * 1440);


                        $alarmModel->update($data2);
                        //-----------------------------------------------------------------------------------------


                        $record = CustomerAlarmNotes::create($data);






                        return $this->response('Alarm stopped Successfully', null, true);
                    } else {
                        return $this->response('Alarm Details are not available', null, false);
                    }
                }
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
        $model = CustomerAlarmNotes::with(["security", "contact", "alarm"])->where("company_id", $request->company_id)
            ->where("alarm_id", $request->alarm_id);
        $model->when($request->filled("contact_id"), function ($query) use ($request) {
            $query->where("contact_id", $request->contact_id);
        });
        $model->orderBy("created_datetime", "DESC");
        return $model->paginate($request->perPage ?? 10);;
    }
    public function getAlarmNotificationsList(Request $request)
    {
        try {
            $jsonFilePath = 'alarm-sensors/' . $request->company_id . '_live_events.json';
            $fileContent = Storage::read($jsonFilePath);
            return json_decode($fileContent, true);
        } catch (\Exception $e) {

            return [];
        }

        $model = AlarmEvents::with([
            "device.customer.primary_contact",
            "device.customer.secondary_contact",
            "notes"
        ])->where('company_id', $request->company_id)->where('alarm_status', 1);

        //$model->orderBy(request('sortBy') ?? "alarm_start_datetime", request('sortDesc') ? "desc" : "asc");

        $model->orderBy("alarm_start_datetime", "DESC");
        return $model->get();
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
            "notes",
            "category",
            "device.customer.buildingtype"
        ])->where('company_id', $request->company_id)

            // ->when($request->filled("common_search"), function ($query) use ($request) {
            //     $query->where("customer_id", $request->common_search);
            // })
            ->when($request->filled("alarm_status"), fn($q) => $q->where("alarm_status", $request->alarm_status))
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
            ->when($request->filled("customer_id"), fn($q) => $q->where("customer_id", $request->customer_id));
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
                        $quqery->orWhereHas('device.customer', fn(Builder $query) => $query->where('building_name', 'ILIKE', "$request->common_search%")
                            ->orWhere('area', 'ILIKE', "$request->common_search%")
                            ->orWhere('city', 'ILIKE', "$request->common_search%")

                            ->where('company_id', $request->company_id));
                    }

                );

                $q->orWhereHas('device', fn(Builder $query) => $query->where('name', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                $q->orWhereHas('device', fn(Builder $query) => $query->where('device_type', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
                $q->orWhereHas('device', fn(Builder $query) => $query->where('location', 'ILIKE', "$request->common_search%")->where('company_id', $request->company_id));
            });
        });
        $model->orderBy(request('sortBy') ?? "alarm_start_datetime", request('sortDesc') ? "desc" : "asc");

        //$model->orderBy("alarm_start_datetime", "asc");
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
