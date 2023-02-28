<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Register user
    public function register(Request $request)
    {
        // dd(Role::select('id')->where('name', 'user')->pluck('id'));
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
            'role_id' => Role::select('id')->where('name', 'guest')->pluck('id')->first()
        ]);

        if ($user->id == 1) {
            $user->update([
                'role_id' => 4,
            ]);
        }

        //return user & token in response
        return response([
            'user' => User::where('id', $user->id)->with('role')->first(),
            'token' => $user->createToken('secret')->plainTextToken
        ], 200);
    }

    // login user
    public function login(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // attempt login
        if (!Auth::attempt($attrs)) {
            return response([
                'message' => 'Invalid credentials.',
                'error' => $attrs
            ], 403);
        }

        //return user & token in response
        return response([
            'user' => User::where('id', auth()->user()->id)->with('role')->first(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ], 200);
    }

    // logout user
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout success.'
        ], 200);
    }

    // get current user details
    public function user()
    {
        return response([
            'user' => User::where('id', auth()->user()->id)->with('role:id,name')->first()
        ], 200);
    }

    // update current user
    public function update(Request $request)
    {


        $attrs = request()->validate([
            'name' => 'required|string',
            'image' => 'nullable|image',
        ]);

        if ($attrs['image'] != '') {
            $attrs['image'] = request()->file('image')->store('profiles');
        }

        auth()->user()->update([
            'name' => $attrs['name'],
            'image' => $attrs['image']
        ]);

        return response([
            'message' => 'User updated.',
            'user' => auth()->user()
        ], 200);
    }

    // create an attachment
    // public function updateProfileImage(Request $request)
    // {

    //     $attributes = request()->validate([
    //         'image' => 'required|image',
    //     ]);

    //     $attributes['image'] = request()->file('image')->store('profiles');

    //     auth()->user()->update([
    //         'image' => $attributes['image']
    //     ]);


    //     return response([
    //         'message' => 'profile uploaded.'
    //     ], 200);
    // }
}
