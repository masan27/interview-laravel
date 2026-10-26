<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\PesertaLembur;
use Illuminate\Http\Request;
use App\Models\User;

class AbsensiController extends Controller
{
    /**
     * =========================================================================
     * [SOAL 1] Detail Riwayat Absensi Pegawai
     * =========================================================================
     * Deskripsi Kasus:
     * Kode di bawah digunakan untuk menampilkan detail absensi berdasarkan ID.
     * Di aplikasi Absensi, aturan bisnis menetapkan bahwa setiap pegawai hanya
     * diperbolehkan melihat data absensi yang berasal dari divisinya sendiri (`divisi_id`).
     *
     * Pertanyaan:
     * 1. Apa yang Anda pahami dari alur kode di bawah?
     * 2. Apakah ada yang salah jika ada user yang mengakses ID milik divisi lain?
     * 3. Bagaimana cara memperbaikinya?
     */

    //ambil berdasarkan divisi_id user
    public function soal_1()
    {
        $absensi = Absensi::with('divisi_id')->where('divisi_id', auth()->user()->divisi_id)-> get();
        return response()->json($absensi);
    }

    /**
     * =========================================================================
     * [SOAL 2] Form Pengajuan Izin / Cuti Baru
     * =========================================================================
     * Deskripsi Kasus:
     * Fungsi di bawah digunakan untuk menyimpan pengajuan izin/cuti baru yang
     * dikirim dari form frontend (`tanggal` dan `keterangan`). Masalahnya,
     * terkadang data tersimpan dalam keadaan kosong, dan user yang iseng bahkan
     * bisa mengirim field tambahan `status = "disetujui"` dari luar sehingga
     * pengajuan langsung disetujui tanpa verifikasi atasan.
     *
     * Pertanyaan:
     * 1. Apa yang Anda pahami dari alur kode di bawah?
     * 2. Apakah ada yang salah dengan kode tersebut?
     * 3. Bagaimana cara memperbaikinya?
     *   
     */
    //menambahkan validate agar sesuai, bikin req yg sesuai
    public function soal_2(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
        ]);

        $absensi = Absensi::create($validatedData);

        return response()->json([
            'message' => 'Pengajuan izin berhasil disimpan',
            'data' => $absensi,
        ], 201);
    }

    /**
     * =========================================================================
     * [SOAL 3] Rekap Kehadiran Harian Pegawai
     * =========================================================================
     * Deskripsi Kasus:
     * Kode berikut digunakan untuk mengambil daftar absensi berstatus 'hadir'
     * beserta nama pegawainya. Ketika dijalankan dengan jumlah data absensi
     * yang banyak, fungsi ini menyebabkan performa database menjadi sangat lambat.
     *
     * Pertanyaan:
     * 1. Apa yang Anda pahami dari alur kode di bawah?
     * 2. Apakah ada yang salah dengan kode tersebut?
     * 3. Bagaimana cara memperbaikinya?
     */

    //kasih pagination
    public function soal_3()
    {
        $absensis = Absensi::where('status', 'hadir')->get()->paginate(10);

        $result = [];
        foreach ($absensis as $item) {
            $result[] = [
                'id' => $item->id,
                'tanggal' => $item->tanggal,
                'jam_masuk' => $item->jam_masuk,
                'nama_pegawai' => $item->pegawai->nama ?? '-',
            ];
        }

        return response()->json($result);
    }

    /**
     * =========================================================================
     * [SOAL 4] Menampilkan Daftar Riwayat Absensi di Dashboard
     * =========================================================================
     * Deskripsi Kasus:
     * Endpoint berikut digunakan untuk menampilkan tabel daftar riwayat absensi
     * di halaman dashboard. Seiring bertambahnya data hingga puluhan ribu baris
     * (dan tabel absensi juga menyimpan kolom `foto_selfie_base64` yang berukuran
     * besar), halaman dashboard menjadi sangat lambat dan membebani memori.
     *
     * Pertanyaan:
     * 1. Apa yang Anda pahami dari alur query di bawah?
     * 2. Apakah ada yang salah dengan query tersebut?
     * 3. Bagaimana cara memperbaikinya?
     */
    public function soal_4(Request $request)
    {
        $absensis = Absensi::where('divisi_id', $request->user()->divisi_id)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->paginate(10, ['id', 'tanggal', 'jam_masuk', 'keterangan']);

        return response()->json($absensis);
    }

    /**
     * =========================================================================
     * [SOAL 5] Kebutuhan Data Pegawai dari Service HR
     * =========================================================================
     * Deskripsi Kasus:
     * Anggap `HrServiceController` (`app/Http/Controllers/HrServiceController.php`)
     * adalah layanan yang berada di service / aplikasi terpisah (Service HR).
     * Fungsi `soal_5` di bawah membutuhkan data profil lengkap pegawai (`nip`,
     * `jabatan`, `status_kepegawaian`) dari Service HR tersebut berdasarkan `$id`.
     *
     * Pertanyaan:
     * 1. Jika Service Absensi dan Service HR adalah dua service yang terpisah,
     *    bagaimana cara `soal_5` mengambil data dari `HrServiceController`?
     * 2. Silakan implementasikan pada fungsi di bawah ini.
     */
    public function soal_5($id)
    {
        $profilPegawai = User::findOrFail($id);
        return response()->json($profilPegawai);
    }

    /**
     * =========================================================================
     * [SOAL 6] Menyimpan Jadwal Lembur beserta Daftar Peserta Tim
     * =========================================================================
     * Deskripsi Kasus:
     * Fungsi di bawah bertugas menyimpan data pengajuan lembur baru ke tabel
     * `absensi`, lalu menyimpan daftar anggota tim yang ikut ke tabel
     * `peserta_lembur`. Masalahnya, suatu ketika terjadi error saat menyimpan
     * salah satu peserta di tengah perulangan, tetapi data utama di tabel
     * `absensi` sudah terlanjur tersimpan sehingga datanya tidak sinkron.
     *
     * Pertanyaan:
     * 1. Apa yang Anda pahami dari alur kode di bawah?
     * 2. Apakah ada yang salah dengan kode tersebut?
     * 3. Bagaimana cara memperbaikinya?
     */
    public function soal_6(Request $request)
    {
        $absensi = Absensi::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'divisi_id' => auth()->user()->divisi_id,
        ]);

        if($absensi->hasFile('absensi_id')) {
            foreach ($request->input('peserta_ids', []) as $userId) {
                PesertaLembur::create([
                    'absensi_id' => $absensi->id,
                    'user_id' => $userId,
                ]);
            }
        }
        return response()->json([
            'message' => 'Jadwal lembur tim berhasil dibuat',
            'data' => $absensi,
        ], 201);
    }

    /**
     * =========================================================================
     * [SOAL 7] Unggah Dokumen Bukti Izin Sakit / Dinas
     * =========================================================================
     * Deskripsi Kasus:
     * Fungsi di bawah menerima unggahan file `lampiran` dari pegawai yang
     * mengajukan izin sakit/dinas. Aturan bisnis menetapkan bahwa file `lampiran`
     * wajib diunggah, HANYA boleh berformat PDF atau Word (`pdf`, `doc`, `docx`),
     * dan ukurannya maksimal 2 MB (2048 KB).
     * Saat ini, user masih bisa mengunggah file jenis apa saja (termasuk `.exe`
     * atau `.jpg`) maupun file berukuran sangat besar.
     *
     * Pertanyaan:
     * 1. Apa yang Anda pahami dari alur kode di bawah?
     * 2. Apakah ada yang salah dengan kode tersebut?
     * 3. Bagaimana cara memperbaikinya?
     */
    public function soal_7(Request $request)
    {
        $file = $request->file('lampiran')->validate([
            'lampiran' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);
        $path = $file ? $file->store('lampiran-izin') : null;

        return response()->json([
            'message' => 'File bukti izin berhasil diunggah',
            'path' => $path,
        ], 201);
    }
}
