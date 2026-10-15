import React, { useState } from 'react';

/**
 * =========================================================================
 * [SOAL 10] Komponen Tombol Check-In Kehadiran
 * =========================================================================
 * Deskripsi Kasus:
 * Komponen di bawah digunakan untuk mencatat kehadiran pegawai ke server
 * ketika tombol "Check-In Masuk" diklik. Ketika koneksi internet sedang
 * lambat, pegawai sering mengklik tombol "Check-In Masuk" berkali-kali
 * secara cepat sehingga data kehadiran yang sama tercatat 3 sampai 4 kali
 * di database.
 *
 * Pertanyaan:
 * 1. Apa yang Anda pahami dari alur kode di bawah?
 * 2. Apakah ada yang salah dengan kode tersebut?
 * 3. Bagaimana cara memperbaikinya?
 */
export function TombolCheckIn({ pegawaiId = 1, onCheckIn }) {
    const [lastMessage, setLastMessage] = useState('');

    const handleCheckIn = async () => {
        const res = await onCheckIn(pegawaiId);
        if (res && res.message) {
            setLastMessage(`${res.message} (Total tercatat di server: ${res.total_checkin_count}x)`);
        }
    };

    return (
        <div className="card-component">
            <div className="surat-preview-box">
                <div><strong>ID Pegawai:</strong> #{pegawaiId}</div>
                <div><strong>Nama:</strong> Budi Santoso (Divisi IT)</div>
                <div><strong>Jadwal Shift:</strong> Pagi (08:00 - 17:00 WIB)</div>
            </div>

            <div className="action-row">
                <button
                    type="button"
                    onClick={handleCheckIn}
                    className="btn-success"
                >
                    Check-In Masuk
                </button>
            </div>

            {lastMessage && (
                <div className="status-banner">
                    {lastMessage}
                </div>
            )}
        </div>
    );
}
