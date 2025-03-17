<?php

namespace App\Http\Controllers;

use App\Models\SalesBusinessSourceTypes;
use App\Models\SalesInquiry;
use Illuminate\Http\Request;

class SalesInquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = SalesInquiry::with(["businessSource", "buildingType"])->where("company_id", $request->company_id);



        $model->when($request->filled("date_from"), function ($q) use ($request) {

            $q->where("created_datetime", ">=", $request->date_from . ' 00:00:00');
            $q->where("created_datetime", "<=", $request->date_to . ' 23:59:59');
        });


        $model->when($request->filled("common_search") && $request->common_search != '', function ($q) use ($request) {



            $q->where(function ($q) use ($request) {
                $q->Orwhere('business_source_info', 'ILIKE', '$request->common_search%');
                $q->Orwhere('first_name', 'ILIKE', '$request->common_search%');
                $q->Orwhere('last_name', 'ILIKE', '$request->common_search%');
                $q->Orwhere('contact_number', 'ILIKE', '$request->common_search%');
                $q->Orwhere('whatsapp_number', 'ILIKE', '$request->common_search%');
                $q->Orwhere('email', 'ILIKE', '$request->common_search%');
                $q->Orwhere('address', 'ILIKE', '$request->common_search%');
                $q->Orwhere('device_type', 'ILIKE', '$request->common_search%');
                $q->Orwhere('sensor_count',   $request->common_search);
                $q->Orwhere('building_name', 'ILIKE', '$request->common_search%');
                $q->Orwhere('receiption_remarks', 'ILIKE', '$request->common_search%');
                $q->Orwhere('customer_request', 'ILIKE', '$request->common_search%');



                $q->orWhereHas('businessSource', function ($query) use ($request) {
                    $query->where('name', 'ILIKE', "%$request->common_search%");
                });

                $q->orWhereHas('buildingType', function ($query) use ($request) {
                    $query->where('name', 'ILIKE', "%$request->common_search%");
                });
            });
        });

        $model->orderBy("created_datetime", "DESC");

        return $model->paginate($request->per_page);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            "company_id" => "required",
            "business_source_id" => "required",
            "business_source_info" => "required",
            "first_name" => "required",
            "last_name" => "required",
            "contact_number" => "required",
            "whatsapp_number" => "nullable",
            "email" => "required",
            "address" => "required",
            "building_name" => "nullable",
            "building_type_id" => "nullable",
            "device_type" => "required",
            "sensor_count" => "required",


            "receiption_remarks" => "nullable",
            "customer_request" => "nullable",
        ];
        $data =  $request->validate($validate);

        $data["created_datetime"] = date("Y-m-d H:i:s");


        if ($request->editId) {
            SalesInquiry::where("id", $request->editId)->update($data);
            return $this->response("Inquiry Updated Successfully", null, true);
        } else {
            SalesInquiry::create($data);
        }


        return $this->response("Inquiry Created Successfully", null, true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesInquiry  $salesInquiry
     * @return \Illuminate\Http\Response
     */
    public function show(SalesInquiry $salesInquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesInquiry  $salesInquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesInquiry $salesInquiry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesInquiry  $salesInquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesInquiry $salesInquiry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesInquiry  $salesInquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesInquiry $salesInquiry)
    {
        //
    }

    public function GetBusinessSourceTypes(Request $request)
    {

        return SalesBusinessSourceTypes::orderBy("name", "asc")->get();
    }
}
