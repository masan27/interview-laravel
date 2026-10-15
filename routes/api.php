<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaygroundInspectorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Live Coding Assessment Aplikasi Absensi (Level Low – Mid)
|--------------------------------------------------------------------------
| Seluruh route di bawah ini otomatis terautentikasi sebagai User #1
| (Budi Santoso - divisi_id: 1 / Divisi IT) melalui DemoAuthMiddleware.
*/

Route::get('/me', [AuthController::class, 'me']);

// Route untuk Soal 1 s/d Soal 7 di AbsensiController
Route::get('/absensi/soal-1/{id}', [AbsensiController::class, 'soal_1']); // [SOAL 1]
Route::post('/absensi/soal-2', [AbsensiController::class, 'soal_2']); // [SOAL 2]
Route::get('/absensi/soal-3', [AbsensiController::class, 'soal_3']); // [SOAL 3]
Route::get('/absensi/soal-4', [AbsensiController::class, 'soal_4']); // [SOAL 4]
Route::get('/absensi/soal-5/{id}', [AbsensiController::class, 'soal_5']); // [SOAL 5]
Route::post('/absensi/soal-6', [AbsensiController::class, 'soal_6']); // [SOAL 6]
Route::post('/absensi/soal-7', [AbsensiController::class, 'soal_7']); // [SOAL 7]

/**
 * =========================================================================
 * [SOAL 8] Endpoint Pengiriman Kode OTP Absensi
 * =========================================================================
 * Deskripsi Kasus:
 * Endpoint pengiriman OTP di bawah ini menggunakan gateway WhatsApp/SMS berbayar.
 * Saat sistem dirilis, terjadi serangan bot yang menembak endpoint tersebut
 * ratusan kali dalam satu menit sehingga membebani server dan menghabiskan kuota.
 *
 * Pertanyaan:
 * 1. Apa yang Anda pahami dari kode route di bawah?
 * 2. Apakah ada yang salah dengan route tersebut?
 * 3. Bagaimana cara memperbaikinya?
 */
Route::post('/request-otp', [AuthController::class, 'sendOtp']);

// -------------------------------------------------------------------------
// Endpoint Internal untuk Dashboard Penguji Interaktif (Jangan Diubah)
// -------------------------------------------------------------------------
Route::prefix('playground')->group(function () {
    Route::get('/inspect-soal-3', [PlaygroundInspectorController::class, 'inspectSoal3']);
    Route::get('/inspect-soal-5', [PlaygroundInspectorController::class, 'inspectSoal5']);
    Route::post('/inspect-soal-6', [PlaygroundInspectorController::class, 'inspectSoal6']);
    Route::post('/inspect-soal-7', [PlaygroundInspectorController::class, 'inspectSoal7']);
    Route::post('/check-in', [PlaygroundInspectorController::class, 'simulateCheckIn']);
    Route::post('/reset-checkin-counter', [PlaygroundInspectorController::class, 'resetCheckInCounter']);
    Route::post('/reset-db', [PlaygroundInspectorController::class, 'resetDatabase']);
});
