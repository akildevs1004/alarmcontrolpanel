<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customers\SecurityLogin;
use App\Models\User;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Document\Security;

class SecurityLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = SecurityLogin::with(["user", "customersAssigned"])->where("company_id", $request->company_id);

        $model->when($request->filled("common_search"), function ($q) use ($request) {


            $q->where(function ($qwhere) use ($request) {
                $qwhere->where("first_name", "ILIKE", "%$request->common_search%");
                $qwhere->orWhere("last_name", "ILIKE", "%$request->common_search%");
                $qwhere->orWhere("contact_number", "ILIKE", "%$request->common_search%");


                $qwhere->orWhereHas("user",  fn(Builder $query) => $query->where("email", "ILIKE", "%$request->common_search%"));
            });
        });
        return $model->orderBy('id', 'ASC')->paginate($request->perPage);;
    }
    public function getOperatorLiveStatus(Request $request)
    {

        $model = SecurityLogin::where("company_id", $request->company_id)->orderBy("last_active_datetime", "DESC");

        return $model->get();

        $currentDateTime = now(); // Get the current datetime

        $company = Company::whereId($request->company_id)->first();
        $company_time_zone = $company->utc_time_zone != null ? $company->utc_time_zone : "Asia/Dubai";

        if ($company_time_zone)

            $currentDateTime = new DateTime();
        $currentDateTime->setTimezone(new DateTimeZone($company_time_zone));
        $logtime = $currentDateTime->format('Y-m-d H:i:s');

        $model = SecurityLogin::select('*', DB::raw("TIMESTAMPDIFF(MINUTE, last_active_datetime, '{$logtime}') AS minutes_difference"))
            ->where("company_id", $request->company_id)
            ->orderBy("last_active_datetime", "DESC")
            ->get();

        return $model;
    }
    public function updateLiveStatus(Request $request)
    {

        $company = Company::whereId($request->company_id)->first();
        $company_time_zone = $company->utc_time_zone != null ? $company->utc_time_zone : "Asia/Dubai";

        if ($company_time_zone)

            $log_time = new DateTime();
        $log_time->setTimezone(new DateTimeZone($company_time_zone));


        $model = SecurityLogin::where("id", $request->security_id)->update(["last_active_datetime" => $log_time->format('Y-m-d H:i:s')]);
    }
    public function securityDropdownlist(Request $request)
    {

        $model = SecurityLogin::where("company_id", $request->company_id);
        $model->orderBy("id", "ASC");

        return $model->get();
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
        if ($request->editId) {
            $request->validate([

                'company_id' => 'required|integer',
                'branch_id' => 'nullable',
                'editId' => 'nullable',
                'first_name' => 'required',
                'last_name' => 'required',
                'email_id' => 'required',
                'contact_number' => 'required',


            ]);

            if ($request->password != '' && $request->password != $request->confirm_password) {
                return  ["status" => false, "errors" => ["confirm_password" => ["Password and Confirm Password not matched"]]];
            }
        } else {
            $request->validate([

                'company_id' => 'required|integer',
                'branch_id' => 'nullable',
                'editId' => 'nullable',
                'first_name' => 'required',
                'last_name' => 'required',
                'email_id' => 'required',
                'contact_number' => 'required',

                'password' => 'required',
                'confirm_password' => 'required',
            ]);

            if ($request->password != $request->confirm_password) {
                return  ["status" => false, "errors" => ["confirm_password" => ["Password and Confirm Password not matched"]]];
            }
        }


        $data =  $request->all();




        if (isset($request->attachment) && $request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $ext = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $ext;

            $request->file('attachment')->move(public_path('/security'), $fileName);
            $data['picture'] = $fileName;
        }
        unset($data['editId']);
        unset($data['confirm_password']);
        unset($data['email_id']);
        unset($data['password']);
        unset($data['attachment']);





        if ($request->filled("editId")) {




            $isExist = User::where('email', '=', $request->email_id)->first();
            if ($isExist == null) {

                $user = User::create([
                    "user_type" => "security",
                    'name' => 'null',
                    'email' => $request->email_id,
                    'password' => Hash::make($request->password),
                    'company_id' => $request->company_id,
                    'web_login_access' => 1,


                ]);
                $data['user_id'] = $user->id;
            } else {
                if ($request->user_id == $isExist->id) {


                    if ($request->password == $request->confirm_password) {
                        User::where("id", $request->user_id)->update([
                            'password' => Hash::make($request->password),
                        ]);
                    } else {
                        return  ["status" => false, "errors" => ["confirm_password" => ["Password and Confirm Password not matched"]]];
                    }
                } else
                    return ["status" => false, "errors" => ["email_id" => ["User Email is already Exist"]]];
            }

            if ($request->filled("web_login_access") && $request->filled("user_id"))
                User::where("id", $request->user_id)->update([
                    'web_login_access' =>  $request->web_login_access,
                ]);
            unset($data['web_login_access']);
            $record = SecurityLogin::where("id", $request->editId)->update($data);

            return $this->response('Security account details are updated', $record, true);
        } else {
            $isExist = User::where('email', '=', $request->email_id)->first();
            if ($isExist == null) {

                $user = User::create([
                    "user_type" => "security",
                    'name' => 'null',
                    'email' => $request->email_id,
                    'password' => Hash::make($request->password),
                    'company_id' => $request->company_id,
                    'web_login_access' => 1,


                ]);
                $data['user_id'] = $user->id;

                User::where('id', $user->id)->update(["web_login_access" => 1]);
            } else {
                return ["status" => false, "errors" => ["email_id" => ["User Email is already Exist"]]];
            }
            $record = SecurityLogin::create($data);
        }



        if ($record) {
            return $this->response('Security account is created.', $record, true);
        } else {
            return $this->response('Security account can not create ', null, false);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customers\SecurityLogin  $securityLogin
     * @return \Illuminate\Http\Response
     */
    public function show(SecurityLogin $securityLogin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customers\SecurityLogin  $securityLogin
     * @return \Illuminate\Http\Response
     */
    public function edit(SecurityLogin $securityLogin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customers\SecurityLogin  $securityLogin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SecurityLogin $securityLogin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers\SecurityLogin  $securityLogin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request, $id)
    {
        if ($id > 0) {
            $return = SecurityLogin::where("id", $id)->delete();
            return $this->response('Security account is deleted Successfully', null, true);
        }
    }
}
