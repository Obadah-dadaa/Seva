<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->is_admin
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return view('customer.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'phone'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['phone' => $data['phone'], 'password' => $data['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home')->with('seva_toast', 'success:تم تسجيل الدخول بنجاح');
        }

        return back()
            ->withErrors(['phone' => 'رقم الهاتف أو كلمة المرور غير صحيحة.'])
            ->onlyInput('phone');
    }

    public function showRegister()
    {
        if (Auth::check() && !Auth::user()->is_admin) {
            return redirect()->route('home');
        }

        return view('customer.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'max:40', 'unique:users,phone'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'phone'    => $data['phone'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('seva_toast', 'success:تم إنشاء حسابك بنجاح');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('seva_toast', 'info:تم تسجيل الخروج بنجاح');
    }
}
