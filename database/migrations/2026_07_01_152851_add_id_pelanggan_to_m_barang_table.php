<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('m_barang', function (Blueprint $table) {
            $table->unsignedInteger('id_pelanggan')->nullable()->after('id_barang');

            $table->foreign('id_pelanggan')
                ->references('id_pelanggan')
                ->on('m_pelanggan')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_barang', function (Blueprint $table) {
            $table->dropForeign(['id_pelanggan']);
            $table->dropColumn('id_pelanggan');
        });
    }
};
