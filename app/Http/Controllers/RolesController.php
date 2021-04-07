<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Request;

class RolesController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'hasRole:Root']);
    }

    public function index()
    {
        $roles = Role::orderBy('name')->get();

        return view('roles/index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(RoleRequest $request)
    {
        Role::create(Request::all());

        return redirect('roles');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('roles.edit', compact('role'));
    }

    public function update($id, RoleRequest $request)
    {
        Role::findOrFail($id)->update($request->all());

        return redirect('roles');
    }

    public function destroy($id, RoleRequest $request)
    {
        Role::findOrFail($id)->delete();

        return redirect('roles');
    }

}
