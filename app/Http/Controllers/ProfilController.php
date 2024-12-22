<?php

namespace App\Http\Controllers;

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
        $user = Auth::user();

        $data = [
            'title'  => 'Profil',
            'user'   => $user,
        ];

        return view('user.profil.index', $data);
    }

    public function updateProfil(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate(
            [
                'nama'      => 'nullable|string|max:255',
                'alamat'    => 'nullable|string|max:255',
                'no_hp'     => 'nullable|string|',
                'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            ],

            [
                'foto_user.max'   => 'Ukuran foto tidak boleh lebih dari 5MB.',
                'foto_user.image' => 'File yang diunggah harus berupa gambar.',
                'foto_user.mimes' => 'Format file harus salah satu dari: jpeg, png, jpg, atau gif.',
            ]
        );

        try {
            $updateData = [];

            if ($request->filled('nama')) {
                $updateData['nama'] = $request->input('nama');

                if ($user->dosen) {
                    $user->dosen->update(['nama' => $request->input('nama')]);
                }
            }

            if ($request->filled('alamat')) {
                $updateData['alamat'] = $request->input('alamat');
            }

            if ($request->filled('no_hp')) {
                $updateData['no_hp'] = $request->input('no_hp');
            }

            if ($request->hasFile('foto_user')) {
                if ($user->foto_user && $user->foto_user != 'user-1.jpg') {
                    Storage::delete('uploads/' . $user->foto_user);
                }
                $file = $request->file('foto_user');
                $filename = 'profile_picture' . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/'), $filename);
                $updateData['foto_user'] = $filename;
            }

            if (!empty($updateData)) {
                $user->update($updateData);
            }

            return redirect()->route('profil', ['id' => $user->id])->with('success', 'Profil berhasil diubah.');
        } catch (\Exception $e) {
            Log::error('Gagal mengubah profil: ' . $e->getMessage());

            return redirect()->route('profil', ['id' => $user->id])->with('error', 'Gagal mengubah profil.');
        }
    }

    public function ubahPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'password'  => 'required',
            'pass_baru' => 'required|min:8|confirmed',
        ], [
            'password.required'   => 'Password sekarang harus diisi.',
            'pass_baru.required'  => 'Password baru harus diisi.',
            'pass_baru.min'       => 'Password baru minimal harus 8 karakter.',
            'pass_baru.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password sekarang tidak sesuai.']);
        }

        $user->password = Hash::make($request->input('pass_baru'));
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}
