<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getNip($nama)
    {
        $dosen = Dosen::where('nama', $nama)->first();

        if ($dosen) {
            return response()->json(['nip' => $dosen->nip]);
        }

        return response()->json(['nip' => null]);
    }
}
