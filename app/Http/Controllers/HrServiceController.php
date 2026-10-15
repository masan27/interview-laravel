<?php

namespace App\Http\Controllers;

use App\Models\User;

/**
 * =========================================================================
 * SERVICE HR / KEPEGAWAIAN (Anggap Berada di Service / Server Terpisah)
 * =========================================================================
 */
class HrServiceController extends Controller
{
    public function getPegawai($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'id' => $user->id,
            'nip' => '19950412202203100'.$user->id,
            'nama' => $user->nama,
            'email' => $user->email,
            'jabatan' => $user->jabatan,
            'divisi_id' => $user->divisi_id,
            'nama_divisi' => $user->nama_divisi,
            'status_kepegawaian' => 'Pegawai Tetap',
        ]);
    }
}
