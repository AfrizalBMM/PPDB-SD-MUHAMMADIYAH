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
        Schema::create('ibu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id')->unique();

            $table->string('nama');
            $table->char('nik',16);
            $table->year('tahun_lahir');
            $table->string('pendidikan');
            $table->string('pekerjaan');
            $table->string('pekerjaan_lainnya')->nullable();
            $table->string('penghasilan');
            $table->string('no_hp',14);

            $table->timestamps();

            $table->foreign('siswa_id')
                ->references('id')->on('siswa')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibu');
    }
};
