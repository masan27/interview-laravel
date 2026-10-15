import React, { useState } from 'react';

/**
 * =========================================================================
 * [SOAL 9] Komponen Daftar Anggota Shift Harian
 * =========================================================================
 * Deskripsi Kasus:
 * Komponen React di bawah digunakan untuk menampilkan dan menambahkan nama
 * pegawai ke dalam daftar jadwal shift harian (`pegawaiList`).
 *
 * Tugas Anda:
 * Lengkapi fungsi `handleTambah` di bawah ini agar ketika tombol
 * "+ Tambah Pegawai" diklik:
 * 1. Jika input `namaBaru` kosong (atau hanya berisi spasi), proses dihentikan.
 * 2. Menambahkan `namaBaru` ke dalam daftar state `pegawaiList` sehingga tampil di layar.
 * 3. Mengosongkan kembali nilai input `namaBaru` setelah berhasil ditambahkan.
 */
export function DaftarShift() {
    const [pegawaiList, setPegawaiList] = useState([
        'Budi Santoso (Divisi IT)',
        'Siti Aminah (Divisi IT)',
    ]);
    const [namaBaru, setNamaBaru] = useState('');

    const handleTambah = () => {
        // Lengkapi logika penambahan pegawai shift di sini:

    };

    return (
        <div className="card-component">
            <div className="input-row">
                <input
                    type="text"
                    value={namaBaru}
                    onChange={(e) => setNamaBaru(e.target.value)}
                    placeholder="Ketik nama pegawai shift..."
                    className="text-input"
                />
                <button type="button" onClick={handleTambah} className="btn-primary">
                    + Tambah Pegawai
                </button>
            </div>

            <div className="list-label">
                Daftar Pegawai Shift Saat Ini ({pegawaiList.length} orang):
            </div>
            <ul className="recipient-list">
                {pegawaiList.map((nama, index) => (
                    <li key={index} className="recipient-item">
                        <span className="badge-num">{index + 1}</span>
                        <span>{nama}</span>
                    </li>
                ))}
            </ul>
        </div>
    );
}
