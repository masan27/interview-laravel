# Aplikasi Absensi Pegawai — Live Coding Technical Assessment

Selamat datang di sesi **Live Coding Technical Assessment** untuk posisi **Fullstack Developer (Laravel & React)**.

Repository ini berisi aplikasi mini **Aplikasi Absensi Pegawai (Laravel 10 + React + SQLite)** yang sudah berjalan. Terdapat **10 skenario studi kasus** pada sisi **Backend (Laravel)** dan **Frontend (React)** untuk Anda analisis penyebabnya dan perbaiki secara langsung.

---

## 🚀 Cara Menjalankan Aplikasi

Aplikasi ini menggunakan database lokal **SQLite** sehingga tidak memerlukan instalasi database eksternal.

```bash
# 1. Pastikan database SQLite sudah terisi data simulasi awal
php artisan migrate:fresh --seed

# 2. Jalankan server aplikasi
php artisan serve
```

Buka browser di **[http://127.0.0.1:8000](http://127.0.0.1:8000)** untuk membuka **Dashboard Pengujian Interaktif**.

> **Catatan tentang Komponen React (Soal 9 & Soal 10):**
> Setiap kali Anda menyimpan perubahan pada file `.jsx` di folder `resources/js/components/`, Anda cukup **Refresh halaman browser** (`Cmd+R` / `F5`) dan sistem akan otomatis meng-compile ulang komponen React tersebut.

---

## 📂 Daftar Studi Kasus Teknis (10 Soal Praktik)

### 1. Detail Riwayat Absensi Pegawai
*   **File Target**: `app/Http/Controllers/AbsensiController.php` → method `soal_1($id)`
*   **Deskripsi Kasus**: Kode ini digunakan untuk menampilkan detail absensi berdasarkan ID. Di aplikasi Absensi, aturan bisnis menetapkan bahwa setiap pegawai hanya diperbolehkan melihat data absensi yang berasal dari divisinya sendiri (`divisi_id`). Menurut Anda, apakah kode tersebut aman jika ada user yang mencoba mengakses ID absensi milik divisi lain? Analisis letak kesalahannya dan perbaiki kodenya.

### 2. Form Pengajuan Izin / Cuti Baru
*   **File Target**: `app/Http/Controllers/AbsensiController.php` → method `soal_2(Request $request)`
*   **Deskripsi Kasus**: Fungsi ini digunakan untuk menyimpan pengajuan izin/cuti baru yang dikirim dari form frontend (`tanggal` dan `keterangan`). Masalahnya, terkadang data tersimpan dalam keadaan kosong, dan user yang iseng bahkan bisa mengirim field tambahan `status = "disetujui"` dari luar sehingga pengajuan langsung berstatus disetujui tanpa verifikasi atasan. Analisis mengapa hal ini bisa terjadi dan perbaiki kodenya.

### 3. Rekap Kehadiran Harian Pegawai
*   **File Target**: `app/Http/Controllers/AbsensiController.php` → method `soal_3()`
*   **Deskripsi Kasus**: Kode ini digunakan untuk mengambil daftar absensi berstatus `'hadir'` beserta nama pegawainya. Ketika dijalankan dengan jumlah data absensi yang banyak, fungsi ini menyebabkan performa database menjadi sangat lambat. Analisis mengapa hal tersebut terjadi dan bagaimana cara memperbaikinya.

### 4. Menampilkan Daftar Riwayat Absensi di Dashboard
*   **File Target**: `app/Http/Controllers/AbsensiController.php` → method `soal_4(Request $request)`
*   **Deskripsi Kasus**: Endpoint ini digunakan untuk menampilkan tabel daftar riwayat absensi di halaman dashboard. Seiring bertambahnya data hingga puluhan ribu baris (dan tabel absensi juga menyimpan kolom `foto_selfie_base64` yang berukuran besar), halaman dashboard menjadi sangat lambat dan membebani memori. Analisis apa yang kurang tepat dari query tersebut dan bagaimana cara memperbaikinya.

### 5. Kebutuhan Data Pegawai dari Service HR
*   **File Target**: `app/Http/Controllers/AbsensiController.php` → method `soal_5($id)`
*   **Service Terpisah**: `app/Http/Controllers/HrServiceController.php`
*   **Deskripsi Kasus**: Anggap `HrServiceController` adalah layanan yang berada di service / aplikasi terpisah (Service HR). Fungsi `soal_5($id)` di `AbsensiController` membutuhkan data profil lengkap pegawai (`nip`, `jabatan`, `status_kepegawaian`) dari Service HR tersebut. Jika keduanya merupakan service yang terpisah, bagaimana cara `soal_5($id)` mendapatkan data dari `HrServiceController`? Silakan implementasikan pada fungsi tersebut.

### 6. Menyimpan Jadwal Lembur beserta Daftar Peserta Tim
*   **File Target**: `app/Http/Controllers/AbsensiController.php` → method `soal_6(Request $request)`
*   **Deskripsi Kasus**: Fungsi ini bertugas menyimpan data pengajuan lembur baru ke tabel `absensi`, lalu menyimpan daftar anggota tim yang ikut ke tabel `peserta_lembur`. Masalahnya, suatu ketika terjadi error saat menyimpan salah satu peserta di tengah perulangan, tetapi data utama di tabel `absensi` sudah terlanjur tersimpan sehingga datanya tidak sinkron. Mengapa ini terjadi dan bagaimana cara memperbaikinya?

### 7. Unggah Dokumen Bukti Izin Sakit / Dinas
*   **File Target**: `app/Http/Controllers/AbsensiController.php` → method `soal_7(Request $request)`
*   **Deskripsi Kasus**: Fungsi ini menerima unggahan file `lampiran` dari pegawai yang mengajukan izin sakit/dinas. Aturan bisnis menetapkan bahwa file `lampiran` wajib diunggah, hanya boleh berformat **PDF atau Word (`pdf`, `doc`, `docx`)**, dan ukurannya maksimal **2 MB (2048 KB)**. Saat ini, user masih bisa mengunggah file jenis apa saja (termasuk `.exe` atau `.jpg`) maupun file berukuran sangat besar. Analisis letak kekurangannya dan perbaiki kodenya.

### 8. Endpoint Pengiriman Kode OTP Absensi
*   **File Target**: `routes/api.php` → `Route::post('/request-otp', ...)`
*   **Deskripsi Kasus**: Endpoint pengiriman OTP ini terhubung ke layanan WhatsApp/SMS berbayar. Saat aplikasi berjalan, ada pihak yang menembak endpoint tersebut ratusan kali dalam satu menit sehingga membebani server dan menghabiskan kuota pesan. Bagaimana cara mengamankan endpoint ini di Laravel?

### 9. Komponen Daftar Anggota Shift Harian (React)
*   **File Target**: `resources/js/components/DaftarShift.jsx`
*   **Deskripsi Kasus**: Komponen React ini digunakan untuk menampilkan dan menambahkan nama pegawai ke dalam daftar jadwal shift harian (`pegawaiList`). Saat ini fungsi `handleTambah` masih kosong. Lengkapi fungsi `handleTambah` agar ketika tombol **+ Tambah Pegawai** diklik, nama pegawai baru ditambahkan ke daftar di layar dan kolom input dikosongkan kembali.

### 10. Komponen Tombol Check-In Kehadiran (React)
*   **File Target**: `resources/js/components/TombolCheckIn.jsx`
*   **Deskripsi Kasus**: Komponen ini digunakan untuk mencatat kehadiran pegawai ke server ketika tombol **Check-In Masuk** diklik. Ketika koneksi internet sedang lambat, pegawai sering mengklik tombol **Check-In Masuk** berkali-kali secara cepat, sehingga data kehadiran yang sama tercatat 3 sampai 4 kali di server. Bagaimana cara memperbaiki komponen React ini?

---

## 💬 Bagian 2: Pertanyaan Pengalaman & Cara Kerja (5 Soal Lisan)
1. **Kolaborasi Git dalam Tim**: Bagaimana alur kerja Git yang biasa Anda gunakan sehari-hari dan apa yang Anda lakukan saat mengalami *merge conflict*?
2. **Langkah Melacak & Memperbaiki Bug (Debugging)**: Jika tombol "Check-In" tiba-tiba gagal di user, langkah apa saja yang pertama kali Anda periksa untuk mencari penyebabnya?
3. **Pengalaman Mengerjakan Fitur yang Menantang**: Ceritakan salah satu fitur paling menantang yang pernah Anda buat dan bagaimana cara menyelesaikannya.
4. **Penanganan Bug Mendesak & Pengujian**: Bagaimana cara Anda memastikan perbaikan bug mendadak sudah benar dan tidak merusak fitur lain?
5. **Kebiasaan Menulis Kode yang Rapi (Clean Code Dasar)**: Kebiasaan apa yang Anda terapkan agar kode Laravel atau React yang Anda tulis mudah dibaca oleh rekan satu tim?
