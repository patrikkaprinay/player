<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);

        $user->delete();
        return 'User deleted successfully';
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->role = $request->role;

        $user->save();
        return 'User updated successfully';
    }

    public function admins()
    {
        return User::select('username', 'name', 'id', 'email')->where('role', '1')->get();
    }
}
