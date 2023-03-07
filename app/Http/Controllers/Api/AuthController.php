<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Log;

class AuthController extends Controller
{
    public function _construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|string|email|unique:users',
            'password'  => 'required|string|confirmed|min:6',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'    => 400,
                'message'   => $validator->errors()->toJson()
            ]);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        if(User::count() == 1) {
            $role = Role::find(1);
            $user->assignRole($role->name);
        } else {
            $role = Role::find(2);
            $user->assignRole($role->name);
        }

        return response()->json([
            'status'    => 200,
            'message'   => 'Registrasi berhasil',
            'user'      => $user
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required|string|min:6',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 422);
        }

        if(!$token=auth()->attempt($validator->validated())) {
            return response()->json(['message'=>'Unauthorized', 'status' => 401]);
        }

        return $this->createNewToken($token);
    }

    public function createNewToken($token)
    {
        $ttl_in_minutes = 60*24*100;

        return response()->json([
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'status'        => 200,
            'message'       => 'Berhasil login',
            // 'expires_in'    => auth()->factory()->getTTL()*$ttl_in_minutes,
            'id'            => auth()->user()->id,
            'name'          => auth()->user()->name,
            'email'         => auth()->user()->email,
            'roles'         => auth()->user()->getRoleNames()->first(),
        ]);
    }

    public function profile()
    {
        return response()->json([
            'user' => auth()->user(),
            'roles' => auth()->user()->roles
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message'   => 'Berhasil logout',
            'status'    => 200
        ]);
    }
}
