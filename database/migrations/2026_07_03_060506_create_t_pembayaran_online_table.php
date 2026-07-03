<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_pembayaran_online', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->increments('id_pembayaran');
            $table->unsignedInteger('id_transaksi_gadai');
            $table->enum('jenis_pembayaran', ['tebus', 'perpanjangan']);
            $table->integer('jumlah_bulan')->nullable(); // Only for perpanjangan
            $table->decimal('nominal_bayar', 15, 2);
            $table->string('bukti_pembayaran', 255);
            $table->enum('status', ['menunggu_konfirmasi', 'disetujui', 'ditolak'])->default('menunggu_konfirmasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('id_transaksi_gadai')
                ->references('id_transaksi_gadai')
                ->on('t_transaksi_gadai')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_pembayaran_online');
    }
};
