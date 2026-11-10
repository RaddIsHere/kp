<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Skema database sesuai ERD pada laporan (Bab III):
 * pelanggan, motor, layanan, pesanan, detail_pesanan, progress.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('motor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->cascadeOnDelete();
            $table->string('plat_nomor', 15)->nullable();
            $table->string('merk', 100);
            $table->string('tipe', 100);
            $table->string('tahun', 4);
            $table->string('warna', 50);
            $table->timestamps();
        });

        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 100);
            $table->decimal('harga', 12, 0);
            $table->timestamps();
        });

        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->cascadeOnDelete();
            $table->foreignId('motor_id')->constrained('motor')->cascadeOnDelete();
            $table->date('tanggal');
            $table->date('estimasi_selesai')->nullable();
            $table->string('status', 50)->default('Menunggu Konfirmasi');
            $table->string('bagian_direpaint', 255)->nullable();
            $table->string('warna_cat', 100)->nullable();
            $table->string('jenis_cat', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('total', 12, 0)->default(0);
            $table->timestamps();
        });

        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanan')->restrictOnDelete();
            $table->decimal('harga', 12, 0);
        });

        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->string('status', 50);
            $table->text('keterangan')->nullable();
            $table->dateTime('tanggal_update');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress');
        Schema::dropIfExists('detail_pesanan');
        Schema::dropIfExists('pesanan');
        Schema::dropIfExists('layanan');
        Schema::dropIfExists('motor');
        Schema::dropIfExists('pelanggan');
    }
};
