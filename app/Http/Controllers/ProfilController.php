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
        $data = [
            'title' => 'Profil',
            'user'  => Auth::user(),
        ];

        return view('user.profil.index', $data);
    }

    public function updateProfil(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'nama'      => 'nullable|string|max:255',
                'email'     => 'nullable|email:dns|max:255|unique:users,email,' . $id,
                'password'  => 'nullable|string|min:8',
                'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $updateData = [];

            if ($request->filled('nama')) {
                $updateData['nama'] = $request->input('nama');
            }

            if ($request->filled('email')) {
                $updateData['email'] = $request->input('email');
            }

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->input('password'));
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
}
