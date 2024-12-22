<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'title'  => 'Akun User',
            'dosens' => Dosen::all(),
            'users'  => User::where('id_role', '!=', 1)->get(),
        ];

        return view('admin.users.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_role'  => 'required|in:1,2',
            'nama'     => 'required|exists:dosens,nama',
            'nip'      => 'required|exists:dosens,nip',
            'email'    => 'required|email:dns',
            'password' => 'required|min:8',
        ]);

        $user = new User();
        $user->id_role = $request->id_role;
        $user->nama = $request->nama;
        $user->nip = $request->nip;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        if ($user->save()) {
            return redirect()->route('akun.user')->with('success', 'Data user berhasil ditambah');
        } else {
            return redirect()->route('akun.user')->with('error', 'Gagal menambahkan data user');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_role' => 'required|in:1,2',
            'nama'    => 'required|exists:dosens,nama',
            'email'   => 'required|email:dns',
        ]);

        $user = User::findOrFail($id);
        $user->id_role = $request->id_role;
        $user->nama = $request->nama;
        $user->email = $request->email;

        if ($user->update()) {
            return redirect()->route('akun.user')->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->route('akun.user')->with('error', 'Gagal update data');
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->delete()) {
            return redirect()->route('akun.user')->with('success', 'Data berhasil dihapus');
        } else {
            return redirect()->route('akun.user')->with('error', 'Gagal menghapus data');
        }
    }
}
