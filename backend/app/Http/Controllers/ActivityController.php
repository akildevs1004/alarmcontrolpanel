<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        return $this->filters($request)->orderByDesc("id")->paginate($request->per_page ?? 10);
    }

    public function show(Request $request, $user_id)
    {
        return $this->filters($request)->where("user_id", $user_id)->orderByDesc("id")->first();
    }

    public function activitiesByUser(Request $request, $user_id)
    {
        return $this->filters($request)->where("user_id", $user_id)->orderByDesc("id")->get();
    }

    public function filters($request)
    {
        $model = Activity::query();
        $model->when($request->filled("company_id") && $request->company_id > 0, fn($q) => $q->where("company_id", $request->company_id));
        $model->when($request->filled("user_id"), fn($q) => $q->where("user_id", $request->user_id));


        $model->when($request->filled("branch_id"), function ($q) use ($request) {
            $q->whereHas("user.employee", fn($q) => $q->where("branch_id", $request->branch_id));
        });

        $model->when($request->filled("department_id") && $request->department_id > 0, function ($q) use ($request) {
            $q->whereHas("user.employee", fn($q) => $q->where("department_id", $request->department_id));
        });

        $model->when($request->filled("filter_user_type") && $request->filter_user_type != '', function ($q) use ($request) {
            $q->where("model_type",  $request->filter_user_type);
        });

        $model->when($request->filled("filter_text") && $request->filter_text != '', function ($q) use ($request) {

            if ($request->filter_text == 'Company') {
                $query->Where('user_type', 'ILIKE', "%$request->filter_text%");
            }

            $q->where(function ($q) use ($request) {
                $q->Orwhere('model_type', 'ILIKE', '$request->filter_text%');

                $q->orWhereHas('user', function ($query) use ($request) {
                    $query->where('first_name', 'ILIKE', "%$request->filter_text%");
                    $query->orWhere('last_name', 'ILIKE', "%$request->filter_text%");
                });
                $q->orWhereHas('user', function ($query) use ($request) {


                    $query->Where('user_type', 'ILIKE', "%$request->filter_text%");
                });
                $q->orWhereHas('user.security', function ($query) use ($request) {
                    $query->where('first_name', 'ILIKE', "%$request->filter_text%");
                    $query->orWhere('last_name', 'ILIKE', "%$request->filter_text%");
                });
                $q->orWhereHas('user.technician', function ($query) use ($request) {
                    $query->where('first_name', 'ILIKE', "%$request->filter_text%");
                    $query->orWhere('last_name', 'ILIKE', "%$request->filter_text%");
                });
                $q->orWhereHas('user.customer', function ($query) use ($request) {
                    $query->Where('first_name', 'ILIKE', "%$request->filter_text%");
                    $query->orWhere('last_name', 'ILIKE', "%$request->filter_text%");
                });
            });
        });

        $model->when($request->filled("action"), fn($q) => $q->where("action", $request->action));
        $model->when($request->filled("type"), fn($q) => $q->where("type", $request->type));


        $model->when($request->filled("from_date"), fn($q) => $q->where("created_at", ">=", $request->from_date . ' 00:00:00'));
        $model->when($request->filled("to_date"), fn($q) => $q->where("created_at", "<=", $request->to_date . ' 23:59:59'));
        $model->when($request->filled("user_type"), fn($q) => $q->where("model_type",  $request->user_type));
        $model->with(["company", 'user.role', 'user.employee', "user.security", "user.customer"]);
        return $model;
    }

    public function store(Request $request)
    {
        try {
            $record = Activity::create($request->all());

            if ($record) {
                return $this->response('Activity Successfully created.', $record, true);
            } else {
                return $this->response('Activity cannot create.', null, false);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
