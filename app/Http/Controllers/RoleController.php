<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // get all roles
    public function index()
    {
        return response([
            'roles' => Role::orderBy('created_at', 'desc')->with('users')
                ->withCount('users')
                ->get()
        ], 200);
    }

    // get single Role
    public function show($id)
    {
        return response([
            'role' => Role::where('id', $id)->with('users')
                ->withCount('users')->get()
        ], 200);
    }

    // create a Role
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $role = Role::firstOrCreate([
            'name' => $attrs['name']
        ]);

        return response([
            'message' => 'Role created.',
            'role' => $role,
        ], 200);
    }

    // update a Role
    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response([
                'message' => 'Role not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $role->update([
            'name' => $attrs['name']
        ]);

        return response([
            'message' => 'Role updated.',
            'role' => $role
        ], 200);
    }

    //delete Role
    public function destroy($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response([
                'message' => 'Role not found.'
            ], 403);
        }

        $role->delete();

        return response([
            'message' => 'Role deleted.'
        ], 200);
    }
}
