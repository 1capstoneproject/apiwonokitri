<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Models;

class UsersController extends Controller
{
    //

    public function Users(Request $request){
        $users = Models\User::all();
        $roles = Models\Role::all();
        return response()->view('pages.users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
