<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Customers\TicketAttachments;
use App\Models\Customers\Tickets;
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
        $model = Tickets::where("company_id", $request->company_id);


        $model->orderBy("created_datetime", "desc");
        return $model->paginate();
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

        $columns = ['company_id', 'customer_id', 'operator_id', 'subject',  'description'];
        $selected = array_intersect_key($data, array_flip($columns));
        $selected["created_datetime"] = date("Y-m-d H:i:s");
        $model = Tickets::create($selected);




        $insertedId = $model->id;
        $attachments = [];
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
}
