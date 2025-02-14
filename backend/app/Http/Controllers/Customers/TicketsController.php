<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customers\TicketAttachments;
use App\Models\Customers\Tickets;
use DateTime;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = Tickets::with("customer")->where("company_id", $request->company_id);




        $model->when($request->filled("common_search"), function ($query) use ($request) {

            $query->where(function ($q) use ($request) {
                $q->where("id", "ILIKE", "%$request->common_search%")
                    ->orWhere("subject", "ILIKE", "%$request->common_search%")
                    ->orWhereHas("customer", function ($qqq) use ($request) {
                        $qqq->where("building_name", "ILIKE", "%$request->common_search%");
                    });
            });
        });

        $model->when($request->filled("filterRequestfrom"), function ($query) use ($request) {

            if ($request->filterRequestfrom == 'security') {
                $query->where("security_id", "!=", null);
            }
            if ($request->filterRequestfrom == 'customer') {
                $query->where("customer_id", "!=", null);
            }
        });

        $model->when($request->filled("date_from"), function ($q) use ($request) {
            $q->whereBetween("created_datetime", [$request->date_from . " 00:00:00", $request->date_to . " 23:59:00"]);
        });

        $model->when($request->filled("customer_id"), function ($q) use ($request) {

            $q->where("customer_id", $request->customer_id);
        });
        $model->when($request->filled("security_id"), function ($q) use ($request) {

            $q->where("security_id", $request->security_id);
        });
        $model->when($request->filled("status"), function ($q) use ($request) {

            $q->where("status", $request->status);
        });
        $model->when($request->filled("filterWord"), function ($q) use ($request) {
            if ($request->filterWord == 'activeStats') {
                $q->where("status", 1);
            }
            if ($request->filterWord == 'todayStats') {
                $q->whereBetween("created_datetime", [date("Y-m-d 00:00:00"), date("Y-m-d 23:59:59")]);
            }
            if ($request->filterWord == 'monthlyStats') {

                $monthYear = date("Y-m");
                $start_datetime = $monthYear . '-01 00:00:00';
                $end_datetime = (new DateTime($monthYear . '-01'))->modify('last day of this month')->format('Y-m-d 23:59:59');

                $q->whereBetween("created_datetime", [$start_datetime, $end_datetime]);
            }
            if ($request->filterWord == '10daysPending') {


                $q->where("created_datetime", "<", now()->subDays(10));
            }
            if ($request->filterWord == '30daysPending') {


                $q->where("created_datetime", "<", now()->subDays(30));
            }
        });




        $model->orderBy("created_datetime", "desc");
        return $model->paginate($request->per_page ?? 10);
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
        $request->validate(["subject" => "required", "description" => "required"]);


        $data = $request->all();

        $columns = ['company_id', 'customer_id', 'security_id', 'subject',  'description'];
        $selected = array_intersect_key($data, array_flip($columns));
        $selected["created_datetime"] = date("Y-m-d H:i:s");
        //$selected["is_read"] = false;
        $selected["is_technician_read"] = false;
        $selected["is_customer_read"] = true;
        $selected["is_security_read"] = true;


        $model = Tickets::create($selected);




        $insertedId = $model->id;
        $attachments = [];
        if ($request->items)
            foreach ($request->items as $item) {

                $file = $item["file"];
                $title = $item["title"];
                $ext = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $ext;
                $file->move(public_path('tickets/attachments/' . $insertedId . "/"), $fileName);


                $attachments[] = [
                    "title" => $title,
                    "attachment" => $fileName,
                    "file_type" => $ext,
                    "ticket_id" => $insertedId,
                ];
            }

        TicketAttachments::insert($attachments);

        return $this->response("New Ticket is created successfully", $model, true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tickets  $tickets
     * @return \Illuminate\Http\Response
     */
    public function show(Tickets $tickets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tickets  $tickets
     * @return \Illuminate\Http\Response
     */
    public function edit(Tickets $tickets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tickets  $tickets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tickets $tickets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tickets  $tickets
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tickets $tickets)
    {
        //
    }

    public function downloadTicketAttachment(Request $request, $ticket_id, $file_name)
    {

        $filePath = public_path("tickets/attachments/" . $ticket_id) .  '/' . $file_name;


        if (file_exists($filePath)) {

            return response()->download($filePath, $file_name);
        } else {

            abort(404);
        }
    }

    public function technicianDashboardTicketStats(Request $request)
    {

        $model = Tickets::where("company_id", $request->company_id)->where("status", 1);

        $total = $model->clone()->count();
        $security_count = $model->clone()->whereNotNull("security_id")->count();
        $customer_count = $model->clone()->whereNotNull("customer_id")->count();
        return ["total" => $total, "customer_count" => $customer_count, "security_count" => $security_count];
    }

    public function technicianDashboardMonthlyTicketStats(Request $request)
    {

        $monthYear = date("Y-m");
        $start_datetime = $monthYear . '-01 00:00:00';
        $end_datetime = (new DateTime($monthYear . '-01'))->modify('last day of this month')->format('Y-m-d 23:59:59');
        $model = Tickets::where("company_id", $request->company_id)->whereBetween("created_datetime", [$start_datetime, $end_datetime]);
        $pending_count = (clone $model)->where("status", 1)->count();
        $resolved_count = (clone $model)->where("status", 0)->count();
        return ["total" => $pending_count + $resolved_count, "pending_count" => $pending_count, "resolved_count" => $resolved_count];
    }
    public function technicianDashboardTodayTicketStats(Request $request)
    {

        $monthYear = date("Y-m-d");
        $start_datetime = $monthYear . ' 00:00:00';
        $end_datetime = $monthYear . ' 23:59:59';
        $model = Tickets::where("company_id", $request->company_id)->whereBetween("created_datetime", [$start_datetime, $end_datetime]);
        $pending_count = (clone $model)->where("status", 1)->count();
        $resolved_count = (clone $model)->where("status", 0)->count();
        return ["total" => $pending_count + $resolved_count, "pending_count" => $pending_count, "resolved_count" => $resolved_count];
    }
    public function technicianDashboardPendingMorethanDaysStats(Request $request)
    {

        $pendingDays = 10;
        if ($request->filled('days')) $pendingDays = $request->days;
        $model = Tickets::where("company_id", $request->company_id)
            ->where("status", 1)
            ->where("created_datetime", "<", now()->subDays($pendingDays));

        $total = $model->clone()->count();
        $security_count = $model->clone()->whereNotNull("security_id")->count();
        $customer_count = $model->clone()->whereNotNull("customer_id")->count();
        return ["total" => $total, "customer_count" => $customer_count, "security_count" => $security_count];
    }
    public function ticketsUnreadNotifications(Request $request)
    {
        $model = Tickets::where("company_id", $request->company_id);

        if ($request->filled("technician_id")) {

            $model->where("is_technician_read", false)->orderBy("created_datetime", "desc");

            return $model->get();
        }
        if ($request->filled("customer_id")) {

            $model->where("is_customer_read", false)->where("customer_id", $request->customer_id);
            $model->orderBy("created_datetime", "desc");

            return $model->get();
        }

        if ($request->filled("security_id")) {

            $model->where("is_security_read", false)->where("security_id", $request->security_id);
            $model->orderBy("created_datetime", "desc");

            return $model->get();
        }



        return [];
    }
}
