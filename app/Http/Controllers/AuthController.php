<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Mengembalikan informasi user yang sedang aktif pada sesi simulasi.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Digunakan pada [SOAL 8] untuk menguji pembatasan request (Rate Limiting)
     * pada route POST /api/request-otp di file routes/api.php.
     */
    public function sendOtp(Request $request)
    {
        return response()->json([
            'message' => 'Kode OTP berhasil dikirim ke nomor terdaftar.',
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
