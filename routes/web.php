<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Meng-compile resources/js/app.jsx beserta komponen Soal 9 & Soal 10
 * secara otomatis menggunakan esbuild setiap kali halaman di-refresh,
 * sehingga kandidat cukup menjalankan `php artisan serve` saja!
 */
Route::get('/playground/bundle.js', function () {
    $esbuild = base_path('node_modules/esbuild/bin/esbuild');
    $entry = base_path('resources/js/app.jsx');
    $outfile = public_path('js/app.bundle.js');

    if (!is_dir(dirname($outfile))) {
        mkdir(dirname($outfile), 0755, true);
    }

    if (file_exists($esbuild)) {
        $cmd = sprintf(
            '%s %s --bundle --outfile=%s --format=iife --loader:.jsx=jsx 2>&1',
            escapeshellarg($esbuild),
            escapeshellarg($entry),
            escapeshellarg($outfile)
        );
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            $err = addslashes(implode("\n", $output));
            $jsError = "document.getElementById('app').innerHTML = '<div style=\"padding:24px;margin:24px;background:#fef2f2;border:2px solid #ef4444;border-radius:8px;font-family:monospace;white-space:pre-wrap;color:#991b1b;\"><strong>⚠️ Syntax Error pada Komponen React:</strong>\\n\\n{$err}</div>';";
            return response($jsError, 200)->header('Content-Type', 'application/javascript');
        }
    }

    return response(file_get_contents($outfile), 200)
        ->header('Content-Type', 'application/javascript')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
});
