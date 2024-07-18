<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->date('tgl_absen');
            $table->time('jam_masuk');
            $table->string('koor_masuk');
            $table->time('jam_keluar')->nullable();
            $table->string('koor_keluar')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
