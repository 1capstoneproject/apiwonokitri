<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models;

class AuthController extends Controller
{
    
    public function Login(){
        return response()->view('pages.auth.login');
    }

    public function PostLogin(Request $request){
        try{
            $credentials = $request->validate([
                'email' => 'required|string|email|exists:users,email',
                'password' => 'required|string|min:6'
            ]);

            $users = Models\User::where('email', $request->only('email'))->first();
            
            if(!$users){
                return back()->withInput()->withErrors([
                    'email' => 'Email not provided'
                ]);
            }

            if(!in_array($users->roles->id, [1, 2])){
                return back()->withErrors([
                    'email' => "Email not provided"
                ]);
            }

            if(auth()->attempt($credentials)){
                $request->session()->regenerate();
                $request->session()->put('user', $users);

                return redirect()->intended("/");
            }
            
            return back()->withInput()->withErrors(['password' => 'Password is invalid.']);
        
        }catch(ValidationException $e){
            return back()->withInput()->withErrors($e->validator->errors());
        }
    }

    public function PostLogout(Request $request){
        try{
            // logout from sanctum laravel 11
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/auth/login');
        }catch(\Exception $e){
            return back()->withInput()->withErrors([ 'error' => $e->getMessage()]);
        }
    }
}
