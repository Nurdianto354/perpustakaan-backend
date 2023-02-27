<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers()
    {
        try {
            $users = User::with('roles')->latest()->get();
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal mengambil data users', $e->getMessage());
        }

        return response()->ok(['users' => $users], 'Sukses mengambil data users');
    }

    public function getUser($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal mengambil data user', $e->getMessage());
        }

        return response()->ok(['user' => $user], 'Sukses mengambil data user');
    }
}
