<?php

namespace App\Http\Controllers;

use App\Models\RolePermissions;
use Illuminate\Http\Request;

class RolePermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RolePermissions  $rolePermissions
     * @return \Illuminate\Http\Response
     */
    public function show(RolePermissions $rolePermissions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RolePermissions  $rolePermissions
     * @return \Illuminate\Http\Response
     */
    public function edit(RolePermissions $rolePermissions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RolePermissions  $rolePermissions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RolePermissions $rolePermissions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RolePermissions  $rolePermissions
     * @return \Illuminate\Http\Response
     */
    public function destroy(RolePermissions $rolePermissions)
    {
        //
    }

    public function roleUpdatePermissions(Request $request)
    {
        if ($request->role_id > 0 && $request->filled("permission_pages")) {
            RolePermissions::where('role_id', $request->role_id)->delete();


            $permissions = array_map(function ($page) use ($request) {
                return [
                    'role_id' => $request->role_id,
                    'page_name' => $page,
                ];
            }, $request->permission_pages);


            RolePermissions::insert($permissions);
        }

        return    $this->response('Role Permissions are successfully Updated', null, true);
    }

    public function getRolePermissions(Request $request)
    {

        return RolePermissions::where("role_id", $request->role_id)->pluck('page_name');
    }
}
