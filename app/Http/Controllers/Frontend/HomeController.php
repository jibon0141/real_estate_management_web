<?php

namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function adminDashboard()
    {
        return view('admin.extends.dashboard');
    }

    public function userDashboard(){
        return view('user.extends.dashboard');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'login_info'      => 'required|max:255',
                'password'   => 'required',
            ]);

            $remember = $request->has('remember');

            // Admin login via email + password (user_type must be admin)
            if (Auth::attempt(['email' => $request->login_info, 'password' => $request->password], $remember)) {
                if (Auth::user()->user_type === 'admin') {
                    return redirect('/admin/dashboard')->with('success', 'Welcome To Admin Panel!');
                }
                Auth::logout();
                return redirect()->back()->with('error', 'Invalid credentials for admin login.');
            }

            // User login via user_name + password (user_type must be user)
            if (Auth::attempt(['user_name' => $request->login_info, 'password' => $request->password], $remember)) {
                if (Auth::user()->user_type === 'user') {
                    return redirect('/user/dashboard')->with('success', 'Welcome To User Panel!');
                }
                Auth::logout();
                return redirect()->back()->with('error', 'Invalid credentials for user login.');
            }

            return redirect()->back()->with('error', 'Login Failed');
        }

        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Welcome To Admin Panel.');
        }
        if (Auth::check() && Auth::user()->user_type === 'user') {
            return redirect('/user/dashboard')->with('success', 'Welcome To User Panel.');
        }

        return view('login');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back()->with('success', 'Logged out successfully!');
    }


}
