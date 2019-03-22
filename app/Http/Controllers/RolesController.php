<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\RoleRequest;
use App\Role;
use Request;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'hasRole:Root']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $roles = Role::orderBy('name')->get();

        return view('roles/index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     *
     * @return Response
     */
    public function store(RoleRequest $request)
    {
        Role::create(Request::all());

        return redirect('roles');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        return $role;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     *
     * @param $id
     *
     * @return Response
     * @internal param int $id
     */
    public function update($id, RoleRequest $request)
    {
        Role::findOrFail($id)->update($request->all());

        return redirect('roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param RoleRequest $request
     *
     * @return Response
     * @internal param int $id
     */
    public function destroy($id, RoleRequest $request)
    {
        Role::findOrFail($id)->delete();

        return redirect('roles');
    }

}
