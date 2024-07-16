<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
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

    public function UsersCreate(Request $request){
        try{
            $userForm = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,id',
                'password' => 'required|string|min:6',
                'roles_id' => 'required|string',
                'address' => 'required|string',
                'name' => 'required|string',
                'profile' => 'nullable|file|max:10240|mimes:jpeg,png,jpg',
                'description' => 'nullable|string',
            ]);

            if(isset($userForm['password'])){
                $userForm['password'] = Hash::make($userForm['password']);
            }
            

            if($request->hasFile('profile')){
                // save file to storage
                $profile = Storage::url($request->file("profile")->store("public/users"));
                $userForm['profile'] = $profile;
            }

            Models\User::create($userForm);
            
            return back()->with('success', "success create users");

        }catch(ValidationException $e){
            return back()->withErrors($e->validator->errors());
        }
    }

    public function UsersEdit(Request $request, $id){
        try{
            $userForm = $request->validate([
                'name' => 'nullable|string',
                'email' => 'nullable|string|email',
                'password' => 'nullable|string|min:6',
                'roles_id' => 'nullable|string',
                'address' => 'nullable|string',
                'name' => 'nullable|string',
                'profile' => 'nullable|file|max:10240|mimes:jpeg,png,jpg',
                'description' => 'nullable|string',
            ]);
            
            $user = Models\User::find($id);

            if(isset($userForm['password'])){
                $userForm['password'] = Hash::make($userForm['password']);
            }else{
                unset($userForm['password']);
            }

            if($request->hasFile('profile')){
                // delete old files
                if($user->profile && file_exists(storage_path("app/public".str_replace("storage", "", $user->profile)))){
                    unlink(storage_path("app/public".str_replace("storage", "", $user->profile)));
                }
                // save file to storage
                $profile = Storage::url($request->file("profile")->store("public/users"));
                $userForm['profile'] = $profile;
            }

            $user->update($userForm);
            
            return back()->with('success', "success create users");
        }catch(ValidationException $e){
            return back()->withErrors($e->validator->errors());
        }
    }

    public function UsersDelete(Request $request, $id){
        try{
            
            $user = Models\User::find($id);
            if(!$user){
                return back()->withErrors([
                    'error' => 'Users tidak di temukan.',
                ]);
            }
            if(file_exists(storage_path("app/public".str_replace("storage", "", $user->profile)))){
                unlink(storage_path("app/public".str_replace("storage", "", $user->profile)));
            }
            $user->delete();
            return back()->with('success', "success create data");
        }catch(\Exception $e){
            return back()->withErrors($e->getMessage());
        }
    }

}
