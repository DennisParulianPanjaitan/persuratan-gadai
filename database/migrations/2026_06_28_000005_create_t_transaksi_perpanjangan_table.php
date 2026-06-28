<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_transaksi_perpanjangan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_0900_ai_ci';

            $table->increments('id_perpanjangan');
            $table->unsignedInteger('id_transaksi_gadai');
            $table->unsignedInteger('id_user');
            $table->date('tanggal_perpanjangan');
            $table->integer('perpanjangan_ke');
            $table->integer('tambahan_bulan');
            $table->date('jatuh_tempo_sebelum');
            $table->date('jatuh_tempo_sesudah');
            $table->decimal('biaya_perpanjangan', 15, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_transaksi_gadai')
                ->references('id_transaksi_gadai')
                ->on('t_transaksi_gadai')
                ->cascadeOnUpdate();

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_transaksi_perpanjangan');
    }
};
