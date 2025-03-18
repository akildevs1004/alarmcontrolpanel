<?php

namespace App\Http\Controllers;

use App\Models\SalesQuotations;
use Illuminate\Http\Request;

class SalesQuotationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = SalesQuotations::with(["ProductService", "BuildingType"])->where("company_id", $request->company_id);



        $model->when($request->filled("date_from"), function ($q) use ($request) {

            $q->where("created_datetime", ">=", $request->date_from . ' 00:00:00');
            $q->where("created_datetime", "<=", $request->date_to . ' 23:59:59');
        });


        $model->when($request->filled("common_search") && $request->common_search != '', function ($q) use ($request) {

            $q->where(function ($q) use ($request) {
                $q->Orwhere('first_name', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('last_name', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('contact_number', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('whatsapp_number', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('email', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('address', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('device_type', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('quotation_id', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('payment_type', 'ILIKE', '%' . $request->common_search . '%');
                $q->Orwhere('amount', 'ILIKE', '%' . $request->common_search . '%');


                $count = (int)$request->common_search;
                $q->Orwhere('sensor_count',   $count);
                $q->Orwhere('building_name', 'ILIKE', '%' . $request->common_search . '%');

                $q->orWhereHas('BuildingType', function ($query) use ($request) {
                    $query->where('name', 'ILIKE', '%' . $request->common_search . '%');
                });
                $q->orWhereHas('ProductService', function ($query) use ($request) {
                    $query->where('name', 'ILIKE', '%' . $request->common_search . '%');
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
        $validate = [
            "company_id" => "required",
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
            "device_product_service_id" => "required",
            "payment_type" => "required",
            "discount" => "nullable",
            "amount" => "required",
            "total_years" => "required",
            "inquiry_id" => "nullable",


        ];
        $data =  $request->validate($validate);







        if ($request->editId) {
            $data["updated_datetime"] = date("Y-m-d H:i:s");
            SalesQuotations::where("id", $request->editId)->update($data);
            return $this->response("Quotation Updated Successfully", null, true);
        } else {
            $maxId = SalesQuotations::where("company_id", $request->company_id)
                ->orderBy("quotation_count", "desc")
                ->value("quotation_count") ?? 0; // Default to 0 if no records exist

            $quotationFormat = sprintf("QTN%d%06d", $request->company_id, $maxId + 1);
            $data["created_datetime"] = date("Y-m-d H:i:s");

            $data["quotation_id"] = $quotationFormat;
            $data["quotation_count"] = $maxId + 1;
            SalesQuotations::create($data);

            return $this->response("Quotation Created Successfully", null, true);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesQuotations  $salesQuotations
     * @return \Illuminate\Http\Response
     */
    public function show(SalesQuotations $salesQuotations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesQuotations  $salesQuotations
     * @return \Illuminate\Http\Response
     */
    public function edit(SalesQuotations $salesQuotations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesQuotations  $salesQuotations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesQuotations $salesQuotations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesQuotations  $salesQuotations
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesQuotations $salesQuotations)
    {
        //
    }
}
