<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // get all users
    public function index()
    {
        return response([
            'users' => User::orderBy('created_at', 'desc')->with('role')
                ->get()
        ], 200);
    }

    // get single user
    public function show($id)
    {
        return response([
            'user' => User::where('id', $id)->with('role')
                ->get()
        ], 200);
    }

    // create a user
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        //create user
        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => bcrypt($attrs['password']),
        ]);

        return response([
            'message' => 'user created.',
            'user' => $user,
        ], 200);
    }

    // update a user
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'message' => 'Mail not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
            // 'email' => 'required|email|unique:users,email',
            // // 'password' => 'required|min:6|confirmed',
            // 'role_id' => 'required|integer'
        ]);

        $user->update([
            'name' => $attrs['name'],
            // 'email' => $attrs['email'],
            // 'password' => bcrypt($attrs['password']),
            // 'role_id' => $attrs['role_id'],
        ]);

        // $user->tokens()->delete();

        return response([
            'message' => 'user updated.',
            'user' => $user
        ], 200);
    }

    //delete user
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'message' => 'user not found.'
            ], 403);
        }

        $user->delete();

        return response([
            'message' => 'user deleted.'
        ], 200);
    }

    // change password
    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'message' => 'user not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($attrs['password']),
        ]);

        $user->tokens()->delete();

        return response([
            'message' => 'Mail updated.',
            'user' => $user
        ], 200);
    }

    // change role
    public function changeRole(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response([
                'message' => 'user not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'role_id' => 'required|integer',
        ]);

        $user->update([
            'role_id' => $attrs['role_id'],
        ]);


        return response([
            'message' => 'user role updated.',
            'user' => $user
        ], 200);
    }
}
