<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Login'];

        return view('auth.login', $data);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'remember' => 'sometimes|boolean'
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Email harus diisi dengan alamat email yang valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->id_role == 1) {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
            } elseif ($user->id_role == 2) {
                return redirect()->route('home')->with('success', 'Login berhasil!');
            }
        }

        return back()->with('error', ' Kredensial yang Anda berikan tidak cocok dengan catatan kami!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
