<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customers\CustomerPayments;
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
        $model = CustomerPayments::where('company_id', $request->company_id)
            ->when(
                $request->filled("customer_id"),
                fn ($q) => $q->where("customer_id", $request->customer_id)
            )
            ->whereBetween(
                'received_date',
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

        $model->orderBy("received_date", "asc");
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
        $request->validate([

            'company_id' => 'required|integer',
            'customer_id' => 'required|integer',
            'invoice_number' => 'required',
            'amount' => 'required',
            'received_date' => 'required',
            'mode_of_payment' => 'required',
            'status' => 'required',
            'paymant_id' => 'nullable',


        ]);
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
}
