<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customers\CustomerPayments;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = CustomerPayments::with("customer")->where('company_id', $request->company_id)
            ->when(
                $request->filled("customer_id"),
                fn($q) => $q->where("customer_id", $request->customer_id)
            )->when(
                $request->filled("filter_status"),
                fn($q) => $q->where("status", $request->filter_status)
            )->when(
                $request->filled("filter_mode_of_payment"),
                fn($q) => $q->where("mode_of_payment", $request->filter_mode_of_payment)
            )->when(
                $request->filled("filter_customer_id"),
                fn($q) => $q->where("customer_id", $request->filter_customer_id)
            )->when(
                $request->filled("common_search"),
                fn($q) => $q->where("invoice_number", "ILIKE", "%" . $request->common_search . "%")
            )





            ->whereBetween(
                'created_at',
                [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']
            );


        $model->when($request->filled('common_search'), function ($q) use ($request) {
            $q->where(function ($q) use ($request) {
                $q->Where('invoice_number', 'ILIKE', "%$request->common_search%");
                $q->orWhere('amount', 'ILIKE', "%$request->common_search%");
                $q->orWhere('received_date', 'ILIKE', "%$request->common_search%");
                $q->orWhere('mode_of_payment', 'ILIKE', "%$request->common_search%");
                $q->orWhere('status', 'ILIKE', "%$request->common_search%");
            });
        });

        $model->orderBy("created_at", "DESC");
        return $model->orderByDesc('id')->paginate($request->perPage ?? 10);;
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

        $rules = [
            'company_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'invoice_number' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'payment_id' => 'nullable', // Fixed typo from 'paymant_id'
            'received_date' => 'nullable',
            'mode_of_payment' => 'nullable',
            'invoice_date' => 'nullable',




        ];

        // If received_date is present, make it required instead of nullable
        if ($request->status == 'Received') {
            $rules['received_date'] = 'required';
            $rules['mode_of_payment'] = 'required';
        }

        $request->validate($rules);
        try {


            $data = $request->all();


            unset($data['editId']);



            if ($request->filled("editId")) {

                $record = CustomerPayments::where("id", $request->editId)->update($data);
            } else {
                $record = CustomerPayments::create($data);
            }

            if ($record) {
                return $this->response('Payment Details are Created.', $record, true);
            } else {
                return $this->response('Payment Details Not Created', null, false);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customers\CustomerPayments  $customerPayments
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerPayments $customerPayments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customers\CustomerPayments  $customerPayments
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerPayments $customerPayments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customers\CustomerPayments  $customerPayments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerPayments $customerPayments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers\CustomerPayments  $customerPayments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $modelEvent = CustomerPayments::where("id", $request->id);

        if ($modelEvent)
            $modelEvent->delete();

        return $this->response('Payment Deleted successfully', null, true);
    }

    public function CustomerProductInvoiceSubmition(Request $request)
    {


        $request->validate(
            [
                'company_id' => 'required|integer',
                'customer_id' => 'required|integer',
                'device_service_id' => 'required|integer',
                'end_date' => 'required',
                'start_date' => 'required',
                'payment_type' => 'required',
                'product_discount_price' => 'required|numeric',
                'product_final_price' => 'required|numeric|gt:0',
                'product_price' => 'required|numeric|gt:0',


            ]
        );

        $invoiceFormat = "INV000001";
        if ($request->payment_type == 'Yearly') {

            $date1 = Carbon::parse($request->start_date); // Start date
            $date2 = Carbon::parse($request->end_date); // End date

            $diffYears = $date1->diff($date2)->y;  // Difference in years
            $diffMonths = $date1->diff($date2)->m; // Remaining months

            $totalInvoiceCount = 1;
            if ($diffYears > 0 &&  $diffMonths > 1)
                $totalInvoiceCount = $diffYears + 1;


            $maxId = CustomerPayments::query()->orderBy("id", "desc")->value("id");
            //$invoiceFormat = sprintf("INV%06d", $maxId);
            $invoiceFormat = sprintf("INV%d%06d", $request->company_id, $maxId + 1);
            for ($i = 1; $i <= $totalInvoiceCount; $i++) {


                $date = Carbon::parse($request->start_date); // Current date
                $invoice_date = $date->addDays(365 * ($i - 1))->format('Y-m-d'); // Add 365 days

                $data = [
                    "company_id" => $request->company_id,
                    "customer_id" => $request->customer_id,
                    "invoice_number" => $invoiceFormat,
                    "amount" => $request->product_final_price,
                    "status" => "Pending",
                    "invoice_date" => $invoice_date,

                ];

                CustomerPayments::create($data);
            }


            return  $this->response("Invoices created sucsessfully.", null, true);
        }


        return  $this->response("Error Occured", null, false);
    }
}
