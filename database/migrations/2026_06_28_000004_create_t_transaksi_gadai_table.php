<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_transaksi_gadai', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->increments('id_transaksi_gadai');
            $table->unsignedInteger('id_pelanggan');
            $table->unsignedInteger('id_barang');
            $table->unsignedInteger('id_user');
            $table->string('kode_transaksi', 50)->unique();
            $table->decimal('uang_pinjaman', 15, 2);
            $table->date('tanggal_gadai');
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status', ['aktif', 'ditebus', 'dijual'])->default('aktif');
            $table->date('tanggal_ditebus')->nullable();
            $table->decimal('total_ditebus', 15, 2)->nullable();
            $table->string('qr_code', 255)->nullable();
            $table->integer('jumlah_perpanjangan')->default(0);
            $table->timestamps();

            $table->foreign('id_pelanggan')
                ->references('id_pelanggan')
                ->on('m_pelanggan')
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
        Schema::dropIfExists('t_transaksi_gadai');
    }
};
