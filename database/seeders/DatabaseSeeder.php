<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user1 = User::create([
            'id' => 1,
            'nama' => 'Budi Santoso (Divisi IT)',
            'email' => 'budi@kantor.id',
            'divisi_id' => 1,
            'nama_divisi' => 'Divisi Teknologi Informasi',
            'jabatan' => 'Software Engineer',
        ]);

        $user2 = User::create([
            'id' => 2,
            'nama' => 'Siti Aminah (Divisi IT)',
            'email' => 'siti@kantor.id',
            'divisi_id' => 1,
            'nama_divisi' => 'Divisi Teknologi Informasi',
            'jabatan' => 'QA Engineer',
        ]);

        $user3 = User::create([
            'id' => 3,
            'nama' => 'Andi Wijaya (Divisi Keuangan)',
            'email' => 'andi@kantor.id',
            'divisi_id' => 2,
            'nama_divisi' => 'Divisi Keuangan & Akuntansi',
            'jabatan' => 'Staff Keuangan',
        ]);

        // Absensi #1: Milik Divisi 1 (IT) - Boleh dilihat oleh User #1
        Absensi::create([
            'id' => 1,
            'tanggal' => '2026-07-01',
            'jam_masuk' => '08:02:15',
            'keterangan' => 'Hadir Tepat Waktu (WFO)',
            'foto_selfie_base64' => str_repeat('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/', 50),
            'status' => 'hadir',
            'divisi_id' => 1,
            'pegawai_id' => $user1->id,
        ]);

        // Absensi #2: Milik Divisi 2 (Keuangan) - TIDAK BOLEH dilihat oleh User #1 (Divisi 1)
        Absensi::create([
            'id' => 2,
            'tanggal' => '2026-07-01',
            'jam_masuk' => '08:10:00',
            'keterangan' => '[PRIVAT DIVISI LAIN] Catatan Kehadiran & Evaluasi Divisi Keuangan',
            'foto_selfie_base64' => str_repeat('data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/', 50),
            'status' => 'hadir',
            'divisi_id' => 2,
            'pegawai_id' => $user3->id,
        ]);

        // Buat 24 data absensi tambahan milik Divisi 1 dengan status 'hadir'
        for ($i = 3; $i <= 26; $i++) {
            Absensi::create([
                'id' => $i,
                'tanggal' => sprintf('2026-07-%02d', ($i % 28) + 1),
                'jam_masuk' => sprintf('07:%02d:10', 30 + ($i % 25)),
                'keterangan' => "Presensi Harian Pegawai Ke-{$i}",
                'foto_selfie_base64' => str_repeat("BASE64_IMAGE_PAYLOAD_BESAR_KE_{$i}_", 60),
                'status' => 'hadir',
                'divisi_id' => 1,
                'pegawai_id' => ($i % 2 === 0) ? $user1->id : $user2->id,
            ]);
        }
    }
};
