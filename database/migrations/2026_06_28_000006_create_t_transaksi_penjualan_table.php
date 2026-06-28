<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_transaksi_penjualan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->increments('id_penjualan');
            $table->unsignedInteger('id_transaksi_gadai');
            $table->unsignedInteger('id_barang');
            $table->unsignedInteger('id_user');
            $table->date('tanggal_jual');
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('biaya_lain', 15, 2)->default(0);
            $table->decimal('laba_rugi', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_transaksi_gadai')
                ->references('id_transaksi_gadai')
                ->on('t_transaksi_gadai')
                ->cascadeOnUpdate();

            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('m_barang')
                ->cascadeOnUpdate();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_transaksi_penjualan');
    }
};
