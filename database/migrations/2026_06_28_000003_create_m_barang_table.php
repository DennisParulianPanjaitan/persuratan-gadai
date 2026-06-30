<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('m_barang', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->increments('id_barang');
            $table->unsignedInteger('id_jenis_barang');
            $table->string('nama_barang', 100);
            $table->decimal('harga_beli', 15, 2);
            $table->string('kondisi', 30)->nullable();
            $table->decimal('berat', 10, 2)->nullable();
            $table->string('foto_barang', 255)->nullable();
            $table->enum('status_verifikasi', ['pending', 'terverifikasi', 'ditolak'])->default('pending');
            $table->decimal('harga_gadai_sementara', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_jenis_barang')
                ->references('id_jenis_barang')
                ->on('m_jenis_barang')
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_barang');
    }
};
