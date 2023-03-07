<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::whereHas("roles", function($query) {
            $query->whereNotIn("name", ["admin","visitor"]);
        })->where('name', 'ILIKE', "%{$request->name}%")->latest();


        if(isset($request->page)){
            $data = $data->paginate(6);
            $data->appends($request->only($request->keys()));
        }else{
            $data = $data->get();
        }

        return response()->json([
            'status'    => 200,
            'data'      => $data
        ]);
    }

    public function show($id)
    {
        $data = User::with('roles')->where('id', $id)->first();

        return response()->json([
            'status'    => 200,
            'data'      => $data
        ]);
    }
}
