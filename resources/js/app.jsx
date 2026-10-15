import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import { DaftarShift } from './components/DaftarShift';
import { TombolCheckIn } from './components/TombolCheckIn';

function App() {
    const [activeTab, setActiveTab] = useState('soal1');
    const [user, setUser] = useState(null);
    const [output, setOutput] = useState(null);
    const [loading, setLoading] = useState(false);
    const [checkinCount, setCheckinCount] = useState(0);

    useEffect(() => {
        fetch('/api/me')
            .then((r) => r.json())
            .then(setUser)
            .catch(() => {});
    }, []);

    const switchTab = (tab) => {
        setActiveTab(tab);
        setOutput(null);
    };

    // Soal 1 Tester
    const testSoal1 = async (id) => {
        setLoading(true);
        try {
            const res = await fetch(`/api/absensi/soal-1/${id}`, {
                headers: { Accept: 'application/json' },
            });
            const data = await res.json();
            setOutput({
                status: res.status,
                passed: id === 1 ? res.status === 200 : res.status === 403,
                summary:
                    id === 1
                        ? res.status === 200
                            ? '✅ Berhasil membuka data absensi milik divisi sendiri (200 OK).'
                            : `❌ Data absensi divisi sendiri gagal dibuka (HTTP ${res.status}).`
                        : res.status === 403
                          ? '✅ Aman! Akses ke data absensi divisi lain ditolak dengan HTTP 403.'
                          : `❌ BOCOR! Data absensi milik divisi lain masih bisa diakses (HTTP ${res.status}).`,
                payload: data,
            });
        } finally {
            setLoading(false);
        }
    };

    // Soal 2 Tester
    const testSoal2 = async (mode) => {
        setLoading(true);
        try {
            const body =
                mode === 'empty'
                    ? { tanggal: '', keterangan: '' }
                    : {
                          tanggal: '2026-07-10',
                          keterangan: 'Izin Keperluan Keluarga',
                          status: 'disetujui',
                      };

            const res = await fetch('/api/absensi/soal-2', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify(body),
            });
            const data = await res.json();

            if (mode === 'empty') {
                setOutput({
                    status: res.status,
                    passed: res.status === 422,
                    summary:
                        res.status === 422
                            ? '✅ Aman! Input kosong berhasil ditolak (HTTP 422).'
                            : `❌ BUG! Input kosong masih lolos dan tersimpan ke database (HTTP ${res.status}).`,
                    payload: data,
                });
            } else {
                const savedStatus = data?.data?.status;
                const passed = res.status === 201 && savedStatus === 'menunggu';
                setOutput({
                    status: res.status,
                    passed,
                    summary: passed
                        ? '✅ Aman! Meskipun user mengirim status="disetujui", sistem tetap menyimpannya sebagai "menunggu".'
                        : `❌ BUG! User berhasil memanipulasi status pengajuan menjadi "${savedStatus}".`,
                    payload: data,
                });
            }
        } finally {
            setLoading(false);
        }
    };

    // Soal 3 Tester
    const testSoal3 = async () => {
        setLoading(true);
        try {
            const res = await fetch('/api/playground/inspect-soal-3');
            const data = await res.json();
            setOutput({
                status: res.status,
                passed: data.is_optimal,
                summary: data.is_optimal
                    ? `✅ Optimal! Hanya ${data.query_count} query database yang dijalankan untuk memuat ${data.total_items} data absensi.`
                    : `❌ Lambat! Sebanyak ${data.query_count} query database dijalankan untuk memuat ${data.total_items} data absensi.`,
                payload: data,
            });
        } finally {
            setLoading(false);
        }
    };

    // Soal 4 Tester
    const testSoal4 = async () => {
        setLoading(true);
        try {
            const res = await fetch('/api/absensi/soal-4');
            const data = await res.json();
            const isPaginated =
                data &&
                typeof data === 'object' &&
                Array.isArray(data.data) &&
                typeof data.current_page === 'number';
            const firstItem = isPaginated ? data.data[0] : Array.isArray(data) ? data[0] : null;
            const hasHeavyPhoto =
                firstItem && Object.prototype.hasOwnProperty.call(firstItem, 'foto_selfie_base64');
            const passed = isPaginated && !hasHeavyPhoto;

            setOutput({
                status: res.status,
                passed,
                summary: passed
                    ? '✅ Optimal! Data sudah dimuat bertahap per halaman dan tidak menyertakan kolom foto_selfie_base64 yang berat.'
                    : '❌ Belum Optimal!',
                payload: data,
            });
        } finally {
            setLoading(false);
        }
    };

    // Soal 5 Tester
    const testSoal5 = async () => {
        setLoading(true);
        try {
            const res = await fetch('/api/playground/inspect-soal-5');
            const data = await res.json();
            setOutput({
                status: res.status,
                passed: data.passed,
                summary: data.passed
                    ? '✅ Berhasil! Data profil pegawai dari Service HR berhasil diambil dan dikembalikan.'
                    : '❌ Belum Berhasil! Fungsi soal_5() belum mengembalikan data profil pegawai dari URL Service HR.',
                payload: data,
            });
        } finally {
            setLoading(false);
        }
    };

    // Soal 6 Tester
    const testSoal6 = async () => {
        setLoading(true);
        try {
            const res = await fetch('/api/playground/inspect-soal-6', {
                method: 'POST',
                headers: { Accept: 'application/json' },
            });
            const data = await res.json();
            setOutput({
                status: res.status,
                passed: data.is_consistent,
                summary: data.is_consistent
                    ? '✅ Konsisten! Saat peserta ke-3 gagal disimpan, data lembur utama ikut dibatalkan.'
                    : `❌ Data Tidak Sinkron! Peserta ke-3 gagal, tetapi jumlah data absensi tetap bertambah dari ${data.before_absensi_count} menjadi ${data.after_absensi_count}.`,
                payload: data,
            });
        } finally {
            setLoading(false);
        }
    };

    // Soal 7 Tester
    const testSoal7 = async (scenario) => {
        setLoading(true);
        try {
            const res = await fetch('/api/playground/inspect-soal-7', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ scenario }),
            });
            const data = await res.json();
            const shouldReject = scenario === 'invalid_ext' || scenario === 'too_large';
            const passed = shouldReject ? res.status === 422 : res.status === 201;

            let summary = '';
            if (scenario === 'invalid_ext') {
                summary = passed
                    ? '✅ Aman! File script_berbahaya.exe ditolak oleh validasi (HTTP 422).'
                    : `❌ BAHAYA! File script_berbahaya.exe masih berhasil diunggah (HTTP ${res.status}).`;
            } else if (scenario === 'too_large') {
                summary = passed
                    ? '✅ Aman! File PDF berukuran 3 MB (> 2 MB) ditolak oleh validasi (HTTP 422).'
                    : `❌ BUG! File PDF 3 MB (> 2 MB) masih lolos diunggah (HTTP ${res.status}).`;
            } else if (scenario === 'valid_word') {
                summary = passed
                    ? '✅ Berhasil! File Word (.docx) ukuran 400 KB diterima dengan baik (HTTP 201).'
                    : `❌ Gagal! File Word (.docx) yang valid justru ditolak (HTTP ${res.status}).`;
            } else {
                summary = passed
                    ? '✅ Berhasil! File PDF (.pdf) ukuran 400 KB diterima dengan baik (HTTP 201).'
                    : `❌ Gagal! File PDF (.pdf) yang valid justru ditolak (HTTP ${res.status}).`;
            }

            setOutput({
                status: res.status,
                passed,
                summary,
                payload: data,
            });
        } finally {
            setLoading(false);
        }
    };

    // Soal 8 Tester
    const testSoal8 = async () => {
        setLoading(true);
        try {
            const results = [];
            for (let i = 1; i <= 5; i++) {
                const r = await fetch('/api/request-otp', {
                    method: 'POST',
                    headers: { Accept: 'application/json' },
                });
                results.push({ attempt: i, status: r.status });
            }
            const blocked = results.some((r) => r.status === 429);
            setOutput({
                status: blocked ? 429 : 200,
                passed: blocked,
                summary: blocked
                    ? '✅ Aman! Pengiriman OTP beruntun yang berlebihan berhasil ditolak (HTTP 429).'
                    : '❌ Belum Aman! Kelima request OTP beruntun lolos semuanya (200 OK) tanpa pembatasan.',
                payload: results,
            });
        } finally {
            setLoading(false);
        }
    };

    // Soal 10 Handler
    const handleCheckInApi = async (pegawaiId) => {
        const res = await fetch('/api/playground/check-in', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
            },
            body: JSON.stringify({ pegawai_id: pegawaiId }),
        });
        const data = await res.json();
        setCheckinCount(data.total_checkin_count);
        return data;
    };

    const resetCheckInCounter = async () => {
        await fetch('/api/playground/reset-checkin-counter', { method: 'POST' });
        setCheckinCount(0);
    };

    const resetDatabase = async () => {
        setLoading(true);
        try {
            const res = await fetch('/api/playground/reset-db', { method: 'POST' });
            const data = await res.json();
            setCheckinCount(0);
            setOutput({
                status: 200,
                passed: true,
                summary: '🔄 ' + data.message,
                payload: data,
            });
        } finally {
            setLoading(false);
        }
    };

    const tabs = [
        { id: 'soal1', label: 'Soal 1: Detail Absensi (BE)', file: 'app/Http/Controllers/AbsensiController.php → soal_1()' },
        { id: 'soal2', label: 'Soal 2: Pengajuan Izin/Cuti (BE)', file: 'app/Http/Controllers/AbsensiController.php → soal_2()' },
        { id: 'soal3', label: 'Soal 3: Rekap Kehadiran (BE)', file: 'app/Http/Controllers/AbsensiController.php → soal_3()' },
        { id: 'soal4', label: 'Soal 4: Riwayat Dashboard (BE)', file: 'app/Http/Controllers/AbsensiController.php → soal_4()' },
        { id: 'soal5', label: 'Soal 5: Integrasi Service HR (BE)', file: 'app/Http/Controllers/AbsensiController.php → soal_5()' },
        { id: 'soal6', label: 'Soal 6: Jadwal Lembur Tim (BE)', file: 'app/Http/Controllers/AbsensiController.php → soal_6()' },
        { id: 'soal7', label: 'Soal 7: Upload Bukti Izin (BE)', file: 'app/Http/Controllers/AbsensiController.php → soal_7()' },
        { id: 'soal8', label: 'Soal 8: Endpoint OTP (BE)', file: 'routes/api.php → POST /request-otp' },
        { id: 'soal9', label: 'Soal 9: Daftar Shift (FE React)', file: 'resources/js/components/DaftarShift.jsx' },
        { id: 'soal10', label: 'Soal 10: Tombol Check-In (FE React)', file: 'resources/js/components/TombolCheckIn.jsx' },
        { id: 'lisan', label: 'Bagian 2: Pengalaman (5 Soal)', file: 'README.md' },
    ];

    const currentTabObj = tabs.find((t) => t.id === activeTab);

    return (
        <div className="layout">
            <header className="topbar">
                <div>
                    <h1>Aplikasi Absensi Pegawai — Live Coding Assessment</h1>
                    <p className="subtitle">
                        Analisis masalah pada kode sumber di editor Anda, perbaiki, lalu uji hasilnya melalui tombol di bawah ini.
                    </p>
                </div>
                <div className="topbar-right">
                    {user && (
                        <div className="user-pill">
                            👤 Login Simulasi: <strong>{user.nama}</strong> (<code>divisi_id: {user.divisi_id}</code>)
                        </div>
                    )}
                    <button onClick={resetDatabase} className="btn-outline" disabled={loading}>
                        🔄 Reset Database & Cache
                    </button>
                </div>
            </header>

            <div className="main-grid">
                <aside className="sidebar">
                    <div className="sidebar-title">DAFTAR STUDI KASUS (10 SOAL)</div>
                    {tabs.map((t) => (
                        <button
                            key={t.id}
                            onClick={() => switchTab(t.id)}
                            className={`tab-btn ${activeTab === t.id ? 'active' : ''}`}
                        >
                            {t.label}
                        </button>
                    ))}
                </aside>

                <main className="content-panel">
                    <div className="file-target-bar">
                        <span>📂 File Target:</span>
                        <code>{currentTabObj?.file}</code>
                    </div>

                    {activeTab === 'soal1' && (
                        <section className="question-section">
                            <h2>Soal 1 — Detail Riwayat Absensi Pegawai (<code>soal_1</code>)</h2>
                            <p>
                                User yang sedang login berasal dari <strong>Divisi 1 (IT)</strong>. Aturan bisnis menetapkan bahwa pegawai
                                hanya diperbolehkan melihat data absensi yang berasal dari divisinya sendiri.
                            </p>
                            <div className="btn-group">
                                <button onClick={() => testSoal1(1)} className="btn-primary" disabled={loading}>
                                    Buka Absensi #1 (Milik Divisi 1)
                                </button>
                                <button onClick={() => testSoal1(2)} className="btn-danger" disabled={loading}>
                                    Coba Buka Absensi #2 (Milik Divisi 2)
                                </button>
                            </div>
                        </section>
                    )}

                    {activeTab === 'soal2' && (
                        <section className="question-section">
                            <h2>Soal 2 — Form Pengajuan Izin / Cuti Baru (<code>soal_2</code>)</h2>
                            <p>
                                Fungsi ini menyimpan pengajuan izin/cuti baru dari form frontend. Saat ini, form kosong masih bisa
                                tersimpan dan user dapat mengirim field tambahan <code>status = "disetujui"</code> dari luar.
                            </p>
                            <div className="btn-group">
                                <button onClick={() => testSoal2('empty')} className="btn-danger" disabled={loading}>
                                    1. Uji Kirim Form Kosong
                                </button>
                                <button onClick={() => testSoal2('inject')} className="btn-primary" disabled={loading}>
                                    2. Uji Kirim status="disetujui" dari Client
                                </button>
                            </div>
                        </section>
                    )}

                    {activeTab === 'soal3' && (
                        <section className="question-section">
                            <h2>Soal 3 — Rekap Kehadiran Harian Pegawai (<code>soal_3</code>)</h2>
                            <p>
                                Fungsi <code>soal_3()</code> mengambil data absensi berstatus <code>hadir</code> beserta nama pegawainya.
                                Ketika jumlah datanya banyak, proses ini membuat database menjadi lambat.
                            </p>
                            <div className="btn-group">
                                <button onClick={testSoal3} className="btn-primary" disabled={loading}>
                                    🔍 Jalankan & Cek Performa Query
                                </button>
                            </div>
                        </section>
                    )}

                    {activeTab === 'soal4' && (
                        <section className="question-section">
                            <h2>Soal 4 — Menampilkan Daftar Riwayat Absensi di Dashboard (<code>soal_4</code>)</h2>
                            <p>
                                Fungsi <code>soal_4()</code> digunakan untuk menampilkan tabel daftar riwayat absensi di halaman dashboard.
                                Ketika data absensi mencapai puluhan ribu baris (dan tabel juga memiliki kolom <code>foto_selfie_base64</code> yang besar),
                                halaman dashboard menjadi sangat lambat dan membebani memori.
                            </p>
                            <div className="btn-group">
                                <button onClick={testSoal4} className="btn-primary" disabled={loading}>
                                    📊 Uji Response Endpoint soal_4
                                </button>
                            </div>
                        </section>
                    )}

                    {activeTab === 'soal5' && (
                        <section className="question-section">
                            <h2>Soal 5 — Kebutuhan Data Pegawai dari Service HR (<code>soal_5</code>)</h2>
                            <p>
                                Anggap <code>app/Http/Controllers/HrServiceController.php</code> berada di service / aplikasi terpisah
                                (Service HR). Fungsi <code>soal_5($id)</code> di <code>AbsensiController.php</code> membutuhkan data profil
                                lengkap pegawai dari Service HR tersebut. Bagaimana cara mengambil datanya jika keduanya merupakan service terpisah?
                            </p>
                            <div className="btn-group">
                                <button onClick={testSoal5} className="btn-primary" disabled={loading}>
                                    🌐 Uji Output Fungsi soal_5
                                </button>
                            </div>
                        </section>
                    )}

                    {activeTab === 'soal6' && (
                        <section className="question-section">
                            <h2>Soal 6 — Menyimpan Jadwal Lembur beserta Daftar Peserta Tim (<code>soal_6</code>)</h2>
                            <p>
                                Fungsi <code>soal_6()</code> menyimpan jadwal lembur ke tabel <code>absensi</code>, lalu menyimpan daftar anggota
                                tim ke tabel <code>peserta_lembur</code>. Ketika salah satu peserta gagal disimpan di tengah perulangan, data utama di
                                tabel <code>absensi</code> tetap tersimpan sehingga datanya tidak sinkron.
                            </p>
                            <div className="btn-group">
                                <button onClick={testSoal6} className="btn-primary" disabled={loading}>
                                    🧪 Uji Simulasi Gagal di Tengah Perulangan
                                </button>
                            </div>
                        </section>
                    )}

                    {activeTab === 'soal7' && (
                        <section className="question-section">
                            <h2>Soal 7 — Unggah Dokumen Bukti Izin Sakit / Dinas (<code>soal_7</code>)</h2>
                            <p>
                                Fungsi <code>soal_7()</code> menerima unggahan file <code>lampiran</code>. Aturan bisnis menetapkan bahwa file
                                wajib diunggah, hanya boleh berformat <strong>PDF atau Word (<code>pdf, doc, docx</code>)</strong>, dan ukuran
                                maksimal <strong>2 MB (2048 KB)</strong>.
                            </p>
                            <div className="btn-group">
                                <button onClick={() => testSoal7('invalid_ext')} className="btn-danger" disabled={loading}>
                                    1. Uji Upload File .exe (Harus Ditolak)
                                </button>
                                <button onClick={() => testSoal7('too_large')} className="btn-danger" disabled={loading}>
                                    2. Uji Upload PDF 3 MB (Harus Ditolak)
                                </button>
                                <button onClick={() => testSoal7('valid_pdf')} className="btn-success" disabled={loading}>
                                    3. Uji Upload PDF 400 KB (Harus Berhasil)
                                </button>
                                <button onClick={() => testSoal7('valid_word')} className="btn-primary" disabled={loading}>
                                    4. Uji Upload Word .docx 400 KB (Harus Berhasil)
                                </button>
                            </div>
                        </section>
                    )}

                    {activeTab === 'soal8' && (
                        <section className="question-section">
                            <h2>Soal 8 — Endpoint Pengiriman Kode OTP Absensi</h2>
                            <p>
                                Endpoint <code>POST /api/request-otp</code> di <code>routes/api.php</code> digunakan untuk mengirim OTP WhatsApp/SMS.
                                Saat ini endpoint tersebut bisa ditembak berulang kali tanpa batas dalam waktu singkat.
                            </p>
                            <div className="btn-group">
                                <button onClick={testSoal8} className="btn-danger" disabled={loading}>
                                    🚀 Simulasi Tembak 5x Request OTP Beruntun
                                </button>
                            </div>
                        </section>
                    )}

                    {activeTab === 'soal9' && (
                        <section className="question-section">
                            <h2>Soal 9 — Komponen Daftar Anggota Shift Harian (React)</h2>
                            <p>
                                Komponen di bawah dirender dari <code>resources/js/components/DaftarShift.jsx</code>.
                                Lengkapi fungsi <code>handleTambah</code> pada file tersebut agar saat tombol <strong>+ Tambah Pegawai</strong> diklik,
                                nama pegawai yang diketik masuk ke dalam daftar di bawah dan kolom input kembali kosong.
                                Setelah memperbaiki kodenya di editor, <strong>Refresh halaman browser ini</strong>.
                            </p>
                            <DaftarShift />
                        </section>
                    )}

                    {activeTab === 'soal10' && (
                        <section className="question-section">
                            <h2>Soal 10 — Komponen Tombol Check-In Kehadiran (React)</h2>
                            <p>
                                Komponen di bawah dirender dari <code>resources/js/components/TombolCheckIn.jsx</code>.
                                Coba klik tombol <strong>Check-In Masuk</strong> berkali-kali secara cepat saat koneksi lambat!
                                Setelah memperbaiki kodenya, <strong>Refresh halaman browser ini</strong>.
                            </p>
                            <div className="counter-box">
                                <span>Total Check-In Tercatat di Server: <strong>{checkinCount}x</strong></span>
                                <button onClick={resetCheckInCounter} className="btn-outline-sm">Reset Counter</button>
                            </div>
                            <TombolCheckIn pegawaiId={1} onCheckIn={handleCheckInApi} />
                        </section>
                    )}

                    {activeTab === 'lisan' && (
                        <section className="question-section">
                            <h2>Bagian 2 — 5 Pertanyaan Pengalaman & Cara Kerja (Diskusi Lisan)</h2>
                            <div className="oral-card">
                                <ol className="oral-list">
                                    <li><strong>Kolaborasi Git dalam Tim:</strong> Bagaimana alur kerja Git sehari-hari dan cara mengatasi <em>merge conflict</em>?</li>
                                    <li><strong>Debugging Bug:</strong> Jika tombol "Check-In" tiba-tiba gagal di user, apa saja yang pertama kali Anda periksa?</li>
                                    <li><strong>Fitur Paling Menantang:</strong> Ceritakan salah satu fitur paling menantang yang pernah Anda kerjakan dan cara menyelesaikannya.</li>
                                    <li><strong>Penanganan Bug Mendesak:</strong> Bagaimana memastikan perbaikan bug mendadak sudah benar dan tidak merusak fitur lain?</li>
                                    <li><strong>Clean Code Dasar:</strong> Kebiasaan apa yang Anda terapkan agar kode Laravel/React mudah dibaca rekan setim?</li>
                                </ol>
                            </div>
                        </section>
                    )}

                    {output && (
                        <div className={`result-box ${output.passed ? 'result-pass' : 'result-fail'}`}>
                            <div className="result-header">
                                <strong>{output.summary}</strong>
                                <span className="status-badge">HTTP {output.status}</span>
                            </div>
                            <pre className="json-preview">{JSON.stringify(output.payload, null, 2)}</pre>
                        </div>
                    )}
                </main>
            </div>
        </div>
    );
}

const container = document.getElementById('app');
if (container) {
    createRoot(container).render(<App />);
}
