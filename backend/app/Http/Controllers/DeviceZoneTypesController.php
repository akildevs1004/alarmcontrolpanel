<?php

namespace App\Http\Controllers;

use App\Models\Customers\DeviceZoneTypes;
use Illuminate\Http\Request;

class DeviceZoneTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DeviceZoneTypes::orderBy('id', 'asc')->paginate($this->per_page ?? 10);
    }
    public function zonetypesList()
    {
        return DeviceZoneTypes::orderBy('id', 'asc')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->validate(["name" => "required"]);

        // if ($request->company_id) {
        //     $data["company_id"] = $request->company_id;
        // }

        $verifyDuplicate = DeviceZoneTypes::where("name", $request->name);
        if ($verifyDuplicate->count() == 0) {
            $record = DeviceZoneTypes::create($data);

            if ($record) {
                return $this->response('New Zone Type is  successfully added.', $record, true);
            } else {
                return $this->response('New Zone Type cannot add.', null, false);
            }
        } else {
            return $this->response('  Zone Type is already Exist', null, false);
        }
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
     * @param  \App\Models\Customers\DeviceZoneTypes  $DeviceZoneTypes
     * @return \Illuminate\Http\Response
     */
    public function show(DeviceZoneTypes $DeviceZoneTypes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customers\DeviceZoneTypes  $DeviceZoneTypes
     * @return \Illuminate\Http\Response
     */
    public function edit(DeviceZoneTypes $DeviceZoneTypes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customers\DeviceZoneTypes  $DeviceZoneTypes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {


        $request->validate(["name" => "required", "id" => "required"]);



        $verifyDuplicate = DeviceZoneTypes::where("name", "=", $request->name)->where("id", "!=", $request->id);

        if ($verifyDuplicate->count() == 0) {
            $record = DeviceZoneTypes::where("id", $request->id)->update(["name" => $request->name]);

            if ($record) {
                return $this->response('Zone Type successfully updated.', null, true);
            } else {
                return $this->response('Zone Type cannot updated.', null, false);
            }
        } else {
            return $this->response('Zone Type Already Exist', null, false);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers\DeviceZoneTypes  $DeviceZoneTypes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {


        if ($request->filled('id')) {
            DeviceZoneTypes::where("id", $request->id)->delete();
        }

        $this->response("Zone Type name Deleted Successfully", null, true);

        //
    }
}
