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
        Schema::create('transaksi_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('m_barang')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('total_harga', 10, 2);
            $table->timestamp('tanggal_transaksi')->useCurrent();
            $table->timestamps();    

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualans');
    }
};
