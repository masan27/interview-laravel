<?php

namespace App\Providers;

use App\Http\Controllers\HrServiceController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mencegah deadlock pada single-threaded `php artisan serve` ketika
        // kandidat menggunakan Http::get() ke URL lokal apa pun yang mengandung angka ID
        Http::fake(function ($request) {
            if (preg_match('#/(\d+)(?:\?.*)?$#', $request->url(), $matches)) {
                $id = (int) $matches[1];
                try {
                    $response = app(HrServiceController::class)->getPegawai($id);
                    return Http::response($response->getData(true), $response->getStatusCode());
                } catch (\Throwable $e) {
                    return Http::response(['message' => 'Pegawai tidak ditemukan'], 404);
                }
            }

            return null;
        });
    }
}
