<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\UserRequest;
use App\Role;
use App\User;
use Request;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'hasRole:Root']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param UserRequest $request
     * @return Response
     */
    public function index(UserRequest $request)
    {
        $users = User::with('roles')->orderBy('name')->get();

        return view('users/index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param UserRequest $request
     * @return Response
     */
    public function create(UserRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     *
     * @return Response
     */
    public function store(UserRequest $request)
    {
        User::create(Request::all());

        return redirect('users');
    }

    /**
     * Display the specified resource.
     *
     * @param UserRequest $request
     * @param  int $id
     * @return Response
     */
    public function show(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserRequest $request
     * @param  int $id
     * @return Response
     */
    public function edit(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     *
     * @param $id
     *
     * @return Response
     * @internal param int $id
     */
    public function update($id, UserRequest $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        $user->roles()->sync($request->input('userRoles') ?? []);

        return redirect('users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param UserRequest $request
     *
     * @return Response
     * @internal param int $id
     */
    public function destroy($id, UserRequest $request)
    {
        User::findOrFail($id)->delete();

        return redirect('users');
    }

}
