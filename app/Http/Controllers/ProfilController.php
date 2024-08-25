<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Profil',
            'user'  => Auth::user(),
        ];

        return view('user.profil.index', $data);
    }

    public function updateProfil(Request $request, $id)
    {
        // Validasi input dari user
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Mengambil data user berdasarkan ID
        $user = User::findOrFail($id);

        // Update nama dan email
        $user->nama = $request->input('nama');
        $user->email = $request->input('email');

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        // Update foto profil jika ada file baru
        if ($request->hasFile('foto_user')) {
            // Hapus foto lama jika ada
            if ($user->foto_user && $user->foto_user != 'user-1.jpg') {
                Storage::delete('public/assets/images/profile/' . $user->foto_user);
            }

            // Simpan foto baru
            $file = $request->file('foto_user');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/'), $filename);

            // Simpan nama file foto ke dalam database
            $user->foto_user = $filename;
        }

        // Simpan perubahan ke database
        $user->save();

        // Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('profil')->with('success', 'Profil berhasil diubah.');
    }
}
