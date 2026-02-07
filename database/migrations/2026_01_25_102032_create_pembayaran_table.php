<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_siswa_id');
            $table->date('tanggal_bayar');
            $table->integer('nominal_bayar');
            $table->enum('metode', ['cash','transfer'])->default('cash');
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('admin_id');
            $table->timestamps();

            $table->foreign('tagihan_siswa_id')
                ->references('id')->on('tagihan_siswa')
                ->onDelete('cascade');

            $table->foreign('admin_id')
                ->references('id')->on('users')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
