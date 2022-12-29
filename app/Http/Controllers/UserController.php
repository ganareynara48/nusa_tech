<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function register()
    {
        
        $data['title'] = 'Register';
        return view('user/register', $data);

    }

    public function register_action(Request $request)
    {

        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|unique:users',
            'password'              => 'required',
            'password_confirm'      => 'required',
            'role'                  => 'required'
        ]);

        $user = new User([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'created_at' => Carbon::now(), 
            'updated_at' => Carbon::now()
        ]);

        $user->save();

        return redirect()->route('login')->with('success', 'User Created');

    }

    public function login()
    {
        
        $data['title'] = 'Login';
        return view('user/login', $data);

    }

    public function login_action(Request $request)
    {
        $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'password' => 'Password not correct'
        ]);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


}
