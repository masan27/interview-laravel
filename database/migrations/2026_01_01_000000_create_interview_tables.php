<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->unsignedBigInteger('divisi_id');
            $table->string('nama_divisi');
            $table->string('jabatan')->default('Staff IT');
            $table->timestamps();
        });

        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->string('jam_masuk')->nullable();
            $table->string('keterangan')->nullable();
            $table->longText('foto_selfie_base64')->nullable();
            $table->string('status')->default('menunggu');
            $table->unsignedBigInteger('divisi_id')->default(1);
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->timestamps();

            $table->foreign('pegawai_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('peserta_lembur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('absensi_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('absensi_id')->references('id')->on('absensi')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_lembur');
        Schema::dropIfExists('absensi');
        Schema::dropIfExists('users');
    }
};
