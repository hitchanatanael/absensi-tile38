<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'email'    => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        Auth::login($user);
        if ($user->id_role == 1) {
            return redirect()->route('admin.dashboard')->with('success', 'Login berhasil!');
        } elseif ($user->id_role == 2) {
            return redirect()->route('home')->with('success', 'Login berhasil!');
        }

        return back()->with('error', 'Kredensial yang diberikan tidak cocok dengan catatan kami!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
