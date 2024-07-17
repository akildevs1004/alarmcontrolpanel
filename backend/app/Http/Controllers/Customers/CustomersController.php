<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Requests\Customer\StoreRequest;

use App\Models\Customers\CustomerContacts;
use App\Models\Customers\Customers;
use App\Models\Deivices\DeviceZones;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = Customers::with(["devices.sensorzones", "contacts", "primary_contact", "secondary_contact"])->where("company_id", $request->company_id);

        $model->when($request->filled("customer_id"), fn ($q) => $q->where("id", $request->customer_id));


        return $model->orderByDesc('id')->paginate($request->perPage);;
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
    public function store(StoreRequest  $request)
    {

        try {
            $data = $request->validated();

            $isExist = Customers::where('email', '=', $request->email)->first();
            if ($isExist == null) {

                $data["created_date"] = date("Y-m-d");
                //$data["password"] = Hash::make($data["password"]);



                if (isset($request->attachment) && $request->hasFile('attachment')) {
                    $file = $request->file('attachment');
                    $ext = $file->getClientOriginalExtension();
                    $fileName = time() . '.' . $ext;

                    $request->file('attachment')->move(public_path('/customers/building'), $fileName);
                    $data['profile_picture'] = $fileName;
                }

                if ($request->filled("customer_id")) {
                    $record = Customers::where("id", $request->customer_id)->update($data);
                } else {
                    $isExist = User::where('email', '=', $request->email)->first();
                    if ($isExist == null) {

                        User::create([
                            "user_type" => "customer",
                            'name' => 'null',
                            'email' => $data['email'],
                            'password' => Hash::make($data["password"]),
                            'company_id' => $request->company_id,
                        ]);
                    } else {
                        return $this->response('User Email is already Exist', null, false);
                    }
                    unset($data["password"]);
                    $record = Customers::create($data);
                }



                if ($record) {
                    return $this->response('Customer New Account is created.', $record, true);
                } else {
                    return $this->response('Customer New Account can not create ', null, false);
                }
            } else {

                return [
                    "message" => 'Customer Email is already Exist',
                    "errors" => [
                        "email" => [
                            'Customer Email is already Exist'
                        ]
                    ]
                ];
                return $this->response('Customer Email is already Exist', null, false);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function show(Customers $customers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function edit(Customers $customers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest  $request)
    {
    }
    public function updateCustomer(UpdateRequest  $request)
    {
        try {
            $data = $request->validated();
            unset($data["customer_id"]);

            $isExist = Customers::where('id', '=', $request->customer_id)->first();
            if ($isExist) {



                if (isset($request->attachment) && $request->hasFile('attachment')) {
                    $file = $request->file('attachment');
                    $ext = $file->getClientOriginalExtension();
                    $fileName = time() . '.' . $ext;

                    $request->file('attachment')->move(public_path('/customers/building'), $fileName);
                    $data['profile_picture'] = $fileName;
                }

                if ($request->filled("customer_id")) {
                    $record = Customers::where("id", $request->customer_id)->update($data);
                }

                if ($record) {
                    return $this->response('Customer   Account is Updated.', $record, true);
                } else {
                    return $this->response('Customer   Account can not Updated ', null, false);
                }
            } else {

                return [
                    "message" => 'Customer Email is already Exist',
                    "errors" => [
                        "email" => [
                            'Customer Email is already Exist'
                        ]
                    ]
                ];
                return $this->response('Customer Email is already Exist', null, false);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customers $customers)
    {
        //
    }
    public function updatebuildingContacts(Request $request)
    {
        $data1 = $request->all();
        $data["first_name"] = $data1["first_name"];
        $data["last_name"] = $data1["last_name"];
        $data["company_id"] = $data1["company_id"];
        $data["customer_id"] = $data1["customer_id"];
        $data["address_type"] = $data1["address_type"] ?? "building";
        $data["phone1"] = $data1["phone1"];
        $data["phone2"] = $data1["phone2"];
        $data["office_phone"] = $data1["office_phone"];
        $data["email"] = $data1["email"];
        $data["whatsapp"] = $data1["whatsapp"];



        return   $this->updateContact($data, $request, "building");
    }
    public function updateSecondaryContacts(Request $request)
    {
        $data1 = $request->all();
        $data["first_name"] = $data1["first_name"];
        $data["last_name"] = $data1["last_name"];
        $data["company_id"] = $data1["company_id"];
        $data["customer_id"] = $data1["customer_id"];
        $data["address_type"] = $data1["address_type"] ?? "secondary";
        $data["phone1"] = $data1["phone1"];
        $data["phone2"] = $data1["phone2"];
        $data["office_phone"] = $data1["office_phone"];
        $data["email"] = $data1["email"];
        $data["whatsapp"] = $data1["whatsapp"];
        $data["display_order"] = 0;
        return   $this->updateContactPrimary($data, $request, "secondary");
    }

    public function updateDeviceZones(Request $request)
    {
        $zones = $request->sensorzones;
        $device_id = $request->device_id;
        $company_id = $request->company_id;

        if ($device_id > 0) {
            DeviceZones::where("company_id", $company_id)->where("device_id", $device_id)->delete();

            foreach ($zones as $key => $value) {
                if ($value["sensor_name"] != '') {

                    $data = [
                        "sensor_name" => $value["sensor_name"],
                        "wired" =>  $value["wired"],
                        "location" =>  $value["location"],
                        "area_code" =>  $value["area_code"],
                        "zone_code" => $value["zone_code"],
                        "device_id" => $device_id,
                        "company_id" => $company_id,
                        "delay" =>  $value["delay"],
                        "hours24" => $value["hours24"],
                    ];
                    DeviceZones::create($data);
                }
            }
            return $this->response('Zone Details Updated Successfully', null, true);
        } else {
            return $this->response('Zone Details Not updated', null, false);
        }

        return $this->response('Zone Details Updated Successfully', null, true);
    }
    public function customerDeviceTypes(Request $request)
    {
        // Fetch distinct device types
        $deviceTypes = Device::where('company_id', $request->company_id)
            ->where('customer_id', $request->customer_id)
            ->distinct()
            ->pluck('device_type');

        // Fetch sensor names
        $sensorNames = DeviceZones::whereHas('device', function ($query) use ($request) {
            $query->where('company_id', $request->company_id)
                ->where('customer_id', $request->customer_id);
        })->pluck('sensor_name');

        // Merge the two collections and get distinct values
        $mergedCollection = $deviceTypes->merge($sensorNames)->unique();

        // Convert to array if needed
        return  $distinctValues = $mergedCollection->toArray();
    }
    public function updateCustomerSettings(Request $request)
    {

        if ($request->filled("customer_id") && $request->filled("company_id")) {

            $data = [];
            $data["account_status"] = $request->account_status;
            $data["login_status"] = $request->login_status;
            if ($request->filled("password")) {
                $data["password"] = Hash::make($request->password);
            }

            Customers::where("company_id", $request->company_id)->where("id", $request->customer_id)->update($data);
        }

        return $this->response('Customer Setting Updated Successfully', null, true);
    }
    public function updatePrimaryContacts(Request $request)
    {
        $data1 = $request->all();
        $data["first_name"] = $data1["first_name"];
        $data["last_name"] = $data1["last_name"];
        $data["company_id"] = $data1["company_id"];
        $data["customer_id"] = $data1["customer_id"];
        $data["address_type"] = $data1["address_type"] ?? "primary";
        $data["phone1"] = $data1["phone1"];
        $data["phone2"] = $data1["phone2"];
        $data["office_phone"] = $data1["office_phone"];
        $data["email"] = $data1["email"];
        $data["whatsapp"] = $data1["whatsapp"];
        $data["display_order"] = 0;

        return   $this->updateContactPrimary($data, $request, "primary");
    }
    public function updateCustomerContact(Request $request)
    {
        $data1 = $request->all();
        //$data["display_order"] = 1;
        $data["address"] = isset($data1["address"]) ? $data1["address"] : '---';
        $data["first_name"] = isset($data1["first_name"]) ? $data1["first_name"] : '---';
        $data["last_name"] = isset($data1["last_name"]) ? $data1["last_name"] : '---';
        $data["company_id"] = isset($data1["company_id"]) ? $data1["company_id"] : '---';
        $data["customer_id"] = isset($data1["customer_id"]) ? $data1["customer_id"] : '---';
        $data["address_type"] = isset($data1["address_type"]) ? $data1["address_type"] : '---';
        $data["phone1"] = isset($data1["phone1"]) ? $data1["phone1"] : '---';
        $data["phone2"] = isset($data1["phone2"]) ? $data1["phone2"] : '---';
        $data["office_phone"] = isset($data1["office_phone"]) ? $data1["office_phone"] : '---';
        $data["email"] = isset($data1["email"]) ? $data1["email"] : '---';
        $data["whatsapp"] = isset($data1["whatsapp"]) ? $data1["whatsapp"] : '---';
        $data["latitude"] = isset($data1["latitude"]) ? $data1["latitude"] : '---';
        $data["longitude"] = isset($data1["longitude"]) ? $data1["longitude"] : '---';
        $data["working_hours"] = isset($data1["working_hours"]) ? $data1["working_hours"] : '---';
        $data["distance"] = isset($data1["phdistanceone2"]) ? $data1["phondistancee2"] : '---';
        $data["notes"] = isset($data1["notes"]) ? $data1["notes"] : '---';




        $addressTypes = $this->addressTypes($request);


        if (!$request->filled("editId")) {
            $displayOrders = array_column($addressTypes, 'display_order');
            $data["display_order"] = max($displayOrders) + 1;

            // $filtered_data = array_filter($addressTypes, function ($item) use ($request) {
            //     return strtolower($item["name"]) == strtolower($request->address_type);
            // });
            // $singleArray = array();

            // foreach ($filtered_data as $key => $value) {
            //     $singleArray[] = $value;
            // }

            // if ($singleArray && $singleArray[0]) {
            //     $data["display_order"] = $filtered_data[0]["display_order"];
            // } else {

            //     $displayOrders = array_column($addressTypes, 'display_order');
            //     $data["display_order"] = max($displayOrders) + 1;
            // }
        }


        return   $this->updateContact($data, $request, $request->address_type);
    }

    public function updateContactPrimary($data, $request, $type)
    {

        if ((int)$request->customer_id <= 0 || (int)$request->company_id <= 0) {
            return $this->response('Customer Id and Company Ids are not exist', null, false);
        }
        try {
            //$data = $request->all();
            if ($request->filled("editId")) {
                $record = CustomerContacts::where('company_id',   $request->company_id)
                    ->where('customer_id',   $request->customer_id)
                    ->where('id',   $request->editId)->update($data);
                return $this->response('Customer ' . $type . ' Contacts Updated.', $record, true);
            } else {

                $customerPrimaryContact = CustomerContacts::where('company_id',   $request->company_id)
                    ->where('customer_id',   $request->customer_id)
                    ->where('address_type',  $type)
                    ->first();

                if (isset($request->profile_picture) && $request->hasFile('profile_picture')) {
                    $file = $request->file('profile_picture');
                    $ext = $file->getClientOriginalExtension();
                    $fileName = time() . '.' . $ext;

                    $request->file('profile_picture')->move(public_path('/customers/contacts'), $fileName);
                    $data['profile_picture'] = $fileName;
                }

                if ($customerPrimaryContact != null) {

                    $record = CustomerContacts::where('company_id',   $request->company_id)
                        ->where('customer_id',   $request->customer_id)
                        ->where('address_type',   $type)->update($data);



                    if ($record) {
                        return $this->response('Customer ' . $type . ' Contacts Updated.', $record, true);
                    } else {
                        return $this->response('Customer  ' . $type . '  Contacts Not Updated', null, false);
                    }
                } else {


                    $data['company_id'] =  $request->company_id;
                    $data['customer_id'] = $request->customer_id;
                    $data['address_type'] = $type;
                    $record = CustomerContacts::create($data);



                    if ($record) {
                        return $this->response('Customer ' . $type . ' Contacts Created.', $record, true);
                    } else {
                        return $this->response('Customer ' . $type . ' Contacts Not Created', null, false);
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateContact($data, $request, $type)
    {

        if ((int)$request->customer_id <= 0 || (int)$request->company_id <= 0) {
            return $this->response('Customer Id and Company Ids are not exist', null, false);
        }
        try {
            //$data = $request->all();
            if ($request->filled("editId")) {
                $record = CustomerContacts::where('company_id',   $request->company_id)
                    ->where('customer_id',   $request->customer_id)
                    ->where('id',   $request->editId)->update($data);
                return $this->response('Customer ' . $type . ' Contacts Updated.', $record, true);
            } else {

                $data['company_id'] =  $request->company_id;
                $data['customer_id'] = $request->customer_id;
                $data['address_type'] = $type;
                $record = CustomerContacts::create($data);



                if ($record) {
                    return $this->response('Customer ' . $type . ' Contacts Created.', $record, true);
                } else {
                    return $this->response('Customer ' . $type . ' Contacts Not Created', null, false);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function buildingTypes()
    {
        $data = [
            ["name" => "Commercial", "id" => 1],
            ["name" => "Residencial", "id" => 2],
            ["name" => "Semi Commercial", "id" => 3],
            ["name" => "Inventory", "id" => 4],
        ];
        return $data;
    }
    public function getCustomerTemperatureDevices(Request $request)
    {
        $data = Device::with(["sensorzones"])
            ->where('company_id', $request->company_id)
            ->where('customer_id', $request->customer_id)
            ->where(function ($query) {
                $query->where("device_type", "Temperature")
                    ->orWhere(function ($query) {
                        $query->where("device_type", "Control Panel")
                            ->whereHas('sensorzones', function ($query) {
                                $query->where('sensor_name', 'Temperature');
                            });
                    });
            })
            ->get();
        return $data;
    }
    public function deviceModels()
    {
        $data = [
            "OX-866", "OX-886", "OX-966", "OX-900", "XT-CPANEL", "XT-FIRE", "XT-WATER"





        ];
        return $data;
    }
    public function deviceTypes()
    {
        $data = [
            ["name" => "Control Panel", "id" => "Control Panel"],
            ["name" => "Burglary", "id" => "Burglary"],
            ["name" => "Medical", "id" => "Medical"],
            ["name" => "Temperature", "id" => "Temperature"],
            ["name" => "Water", "id" => "Water"],
            ["name" => "Humidity", "id" => "Humidity"],
            ["name" => "Fire", "id" => "Fire"],

            ["name" => "All(Attendance and Access)", "id" => "all"],
            ["name" => "Attendance", "id" => "Attendance"],
            ["name" => "Access Control", "id" => "Access Control"],

        ];
        return $data;
    }

    public function getSensorsList()
    {
        return  [

            "Burglary",
            "Medical",
            "Fire",
            "Water",
            "Temperature",
        ];
    }
    // public function deviceTypes()
    // {
    //     $data = [

    //         ["name" => "Burglary", "id" => "Burglary"],
    //         ["name" => "Medical", "id" => "Medical"],
    //         ["name" => "Temperature", "id" => "Temperature"],
    //         ["name" => "Water", "id" => "Water"],
    //         ["name" => "Humidity", "id" => "Humidity"],

    //         ["name" => "All(Attendance and Access)", "id" => "all"],
    //         ["name" => "Attendance", "id" => "Attendance"],
    //         ["name" => "Access Control", "id" => "Access Control"],

    //     ];
    //     return $data;
    // }
    public function addressTypes(Request $request)
    {
        $data = [
            ["name" => "Police Station", "display_order" => 1],
            ["name" => "Fire/Civil Department", "display_order" => 2],
            ["name" => "Hopsital", "display_order" => 3],
            ["name" => "Building Security", "display_order" => 4],
            ["name" => "Community Security", "display_order" => 5],
        ];


        // Fetch addressTypes from the database
        $addressTypes = CustomerContacts::where("company_id", $request->company_id)->where("display_order", ">", 0)
            ->get(["address_type AS name", "display_order"])
            ->toArray();

        // Merge the predefined data with the addressTypes from the database
        $mergedData = array_merge($data, $addressTypes);

        // Remove duplicates based on the 'name' key
        $mergedData = array_map("unserialize", array_unique(array_map("serialize", $mergedData)));

        // Sort by 'display_order'
        usort($mergedData, function ($a, $b) {
            return $a['display_order'] <=> $b['display_order'];
        });

        return $mergedData;
    }

    public function alarmCategories()
    {
        return  $data = [
            ["name" => "Critical", "id" => 2],
            ["name" => "Normal", "id" => 1],


        ];
    }

    public function customersForMap(Request $request)
    {
        $model = Customers::with(["latest_alarm_event", "alarm_events", "devices.sensorzones", "contacts", "primary_contact", "secondary_contact"])->where("company_id", $request->company_id);

        $model->when($request->filled("customer_id"), fn ($q) => $q->where("id", $request->customer_id));

        return $model->orderByDesc('id')->paginate($request->perPage);
    }
}
