<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index()
    {
        $data = [
            'title'  => 'Data Dosen',
            'dosens' => Dosen::all(),
        ];

        return view('admin.dosen.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required',
            'nik'    => 'required|unique:dosens,nik',
            'alamat' => 'nullable',
            'no_hp'  => 'nullable',
        ]);

        $dosen = Dosen::create($request->all());

        if ($dosen->save()) {
            return redirect()->route('data.dosen')->with('success', 'Data berhasil ditambahkan');
        } else {
            return redirect()->route('data.dosen')->with('error', 'Gagal menambahkan data');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'   => 'required',
            'nik'    => 'required|unique:dosens,nik,' . $id,
            'alamat' => 'nullable',
            'no_hp'  => 'nullable',
        ]);

        $dosen = Dosen::findOrFail($id);

        if ($dosen->update($request->all())) {
            return redirect()->route('data.dosen')->with('success', 'Data berhasil diupdate');
        } else {
            return redirect()->route('data.dosen')->with('error', 'Gagal update data');
        }
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);

        if ($dosen->delete()) {
            return redirect()->route('data.dosen')->with('success', 'Berhasil menghapus data');
        } else {
            return redirect()->route('data.dosen')->with('error', 'Gagal menghapus data');
        }
    }
}
